<?php
function result_query($PGSQL){
	/*
	 * Use the WS User (moodle assigned) as a authenticated user capable to evaluate
	 * students. The requisition selects all tasks and 
	 *
	 * This requires MDL_WS-USER == DB_MDL_USER
	 */
	$sql = "SELECT mdl_course.id AS course,mdl_course_modules.id AS instanceid, mdl_grade_grades.userid, 
		COALESCE(files_user.contextid, 0) AS contextid, 
		COALESCE(files_user.itemid, 0) AS itemid,
		COALESCE(files_user.filename, '') AS filename, mdl_grade_grades.rawgrademin, mdl_grade_grades.rawgrademax, mdl_grade_grades.id AS id_grade_grades,mdl_course_modules.idnumber AS  idnumber, 
		COALESCE((SELECT mdl_grade_grades_professor.finalGRADE FROM mdl_grade_grades AS mdl_grade_grades_professor, mdl_user WHERE mdl_grade_grades_professor.ID = mdl_grade_grades.id AND mdl_grade_grades.usermodified = mdl_user.id AND mdl_user.username <> '".pg_escape_string($PGSQL['usuario'])."'),-1) AS notaProfessor, mdl_course.shortname AS course_name, mdl_assignsubmission_onlinetext.onlinetext AS resposta, mdl_course.shortname AS course_name, mdl_assignsubmission_onlinetext.onlinetext AS resposta FROM mdl_course 
		INNER JOIN mdl_course_modules ON mdl_course_modules.course = mdl_course.id 
		INNER JOIN mdl_assign ON mdl_course.id = mdl_assign.course AND mdl_course_modules.instance = mdl_assign.id 
		INNER JOIN mdl_assign_submission ON mdl_assign.id = mdl_assign_submission.assignment 
		LEFT JOIN mdl_assignsubmission_onlinetext ON mdl_assign_submission.id = mdl_assignsubmission_onlinetext.submission 
		LEFT JOIN mdl_modules ON mdl_modules.id = mdl_course_modules.module INNER JOIN mdl_grade_items ON mdl_assign.id = mdl_grade_items.iteminstance AND mdl_modules.name = mdl_grade_items.itemmodule 
		INNER JOIN mdl_grade_grades ON mdl_grade_items.id = mdl_grade_grades.itemid AND mdl_assign_submission.userid = mdl_grade_grades.userid 
		LEFT JOIN (SELECT mdl_files.userid, mdl_files.contextid, mdl_files.itemid, mdl_files.filename,mdl_context.instanceid FROM mdl_context 
		INNER JOIN mdl_files ON mdl_files.contextid = mdl_context.id AND mdl_files.component = 'assignsubmission_file' AND mdl_files.filesize > 0) AS files_user ON files_user.userid = mdl_grade_grades.userid AND files_user.instanceid = mdl_course_modules.id WHERE not(mdl_course_modules.idnumber IS NULL OR mdl_course_modules.idnumber = '') ORDER BY 1, 2, 3"; // add idnumber == filter


	// select feedbacks comments
	$sqlFeed = "SELECT * FROM mdl_assignfeedback_comments";

	//select user names 
	$sqlNames = "SELECT mdl_user.id, mdl_user.firstname, mdl_user.lastname FROM mdl_user";

	//connect to DB
	$pgconn = pg_connect("host=".$PGSQL['servidor']." dbname=".$PGSQL['banco']." user=".$PGSQL['usuario']." password=".$PGSQL['senha']) or die("connection failed");

	//query main request on DB
	$result_data = pg_query($pgconn, $sql) or die("Erro na pesquisa");

	//query name request on DB
	$result_names = pg_query($pgconn, $sqlNames) or die("Erro na pesquisa");

	//query feeedbacks request on DB
	$result_feedbacks = pg_query($pgconn, $sqlFeed) or die("Erro na pesquisa");
	
	//mount task package containing objects by id
	$count = 0;
	if($result_data){
		// Cycle through results
		while ($anexo = pg_fetch_object($result_data,$count)){
			$anexos[] = $anexo;
			$count++;
		}
		// Free result set
		pg_free_result($result_data);
	}
	// fetch user list // Disable on name protection instances
	$count = 0;
	if($result_names){
		while ($name = pg_fetch_object($result_names,$count)){
			$names[] = $name;
		$count++;
		}
		// Free result set
		pg_free_result($result_names);
	}

	// fetch feedback list
	$count = 0;
	if($result_feedbacks){
		while ($feedback = pg_fetch_object($result_feedbacks,$count)){
			$feedbacks[] = $feedback;
		$count++;
		}
		// Free result set
		pg_free_result($result_feedbacks);
	}


	header('Content-type: application/json');

	pg_close($pgconn);

	// Return a JSON format containing the objects
	return json_encode(array('anexos' => $anexos,'users' => $names,'feedbacks' => $feedbacks, 'sql' => $sql));
}

/*
 * Description. Receive precess results writing grades and feedbacks 
 */
function atualizaQuestoes($sql, $questoesJson){
	// decode information in array format and iterativelly modifies the grade objects
	$input = json_decode($questoesJson);

	for ($i = 0; $i < count($input); $i++){
		// update questions individually
		atualizaQuestao($sql,$input[$i]->id_grade_grades, $input[$i]->nota, 0, $input[$i]->feedback);
	}
}

/*
 * Description. Receive individually tasks' informations and commit evaluations
 */
function atualizaQuestao ($PGSQL,$id, $nota, $professor, $feedback){
	$pgconn = pg_connect("host=".$PGSQL['servidor']." dbname=".$PGSQL['banco']." user=".$PGSQL['usuario']." password=".$PGSQL['senha']) or die("connection failed");

	// Combinations between GRADE and FEEDBACK objects
	// UPDATE GRADE OR UPDATE FEEDBACK OR UPDATE GRADE AND FEEDBACK
	if ($nota >= 0){
		if(is_null($feedback)){
			$query = "UPDATE mdl_grade_grades SET RAWGRADE = '".pg_escape_string($nota)."',finalgrade = '".pg_escape_string($nota)."',timemodified =  (SELECT extract(epoch FROM now())),usermodified = (SELECT id FROM mdl_user WHERE username = '".pg_escape_string($PGSQL['usuario'])."') WHERE ID = '".pg_escape_string($id)."' AND (usermodified NOT IN (SELECT mdl_role_assignments.userid FROM mdl_role_assignments, mdl_context, mdl_course, mdl_grade_items WHERE mdl_context.contextlevel = 50				AND mdl_role_assignments.contextid = mdl_context.id AND mdl_context.instanceid = mdl_course.id AND mdl_course.id = mdl_grade_items.courseid AND mdl_role_assignments.roleid in (3,4) AND mdl_grade_items.id = mdl_grade_grades.itemid) OR rawgrade IS NULL)";
		}else{
			$query = "UPDATE mdl_grade_grades SET RAWGRADE = '".pg_escape_string($nota)."' ,finalgrade = '".pg_escape_string($nota)."' ,feedback = '".pg_escape_string($feedback)."', feedbackformat = 1, timemodified =  (SELECT extract(epoch FROM now())), usermodified = (SELECT id FROM mdl_user WHERE username = '".pg_escape_string($PGSQL['usuario'])."') WHERE ID = '".pg_escape_string($id)."' AND (usermodified NOT IN (SELECT mdl_role_assignments.userid FROM mdl_role_assignments, mdl_context, mdl_course, mdl_grade_items WHERE mdl_context.contextlevel = 50 AND mdl_role_assignments.contextid = mdl_context.id AND mdl_context.instanceid = mdl_course.id AND mdl_course.id = mdl_grade_items.courseid AND mdl_role_assignments.roleid IN (3,4) AND mdl_grade_items.id = mdl_grade_grades.itemid) OR rawgrade IS NULL)";
		}


	}else{
		if(!is_null($feedback)){
		 	$query = "UPDATE mdl_grade_grades SET feedback = '".pg_escape_string($feedback)."', feedbackformat = 1, timemodified =  (SELECT extract(epoch FROM now())), usermodified = (SELECT id FROM mdl_user WHERE username = '".pg_escape_string($PGSQL['usuario'])."') WHERE ID = '".pg_escape_string($id)."' AND (usermodified NOT IN (SELECT mdl_role_assignments.userid FROM mdl_role_assignments, mdl_context, mdl_course, mdl_grade_items WHERE mdl_context.contextlevel = 50 AND mdl_role_assignments.contextid = mdl_context.id AND mdl_context.instanceid = mdl_course.id AND mdl_course.id = mdl_grade_items.courseid AND mdl_role_assignments.roleid in (3,4) AND mdl_grade_items.id = mdl_grade_grades.itemid) OR rawgrade IS NULL)";
		}
	}

	if(pg_query($pgconn, $query)){
		// list the grades assigned
		$sql = "SELECT mdl_assign_grades.id FROM mdl_grade_grades, mdl_grade_items, mdl_assign_grades where mdl_grade_grades.id = '".pg_escape_string($id)."' AND mdl_grade_grades.itemid = mdl_grade_items.id AND mdl_grade_items.iteminstance = mdl_assign_grades.assignment AND mdl_grade_grades.userid = mdl_assign_grades.userid AND mdl_grade_items.itemmodule = 'assign'";
	
		$resultado = pg_query($pgconn, $sql);		

		$i = 0;
		if($resultado){
			while ($anexo = pg_fetch_row($resultado,$i)){
				$count[] = $anexo;
				$i++;
			}
			pg_free_result($resultado);
		}

		pg_free_result($resultado);

		$id_assign_grades = $count[0][0];

		// Assign the grade objects for the student tasks
		if($id_assign_grades){
			if ($nota >= 0){
				$query = "UPDATE mdl_assign_grades SET timemodified =  (SELECt extract(epoch FROM now())), grader = (SELECT id FROM mdl_user WHERE username = '".pg_escape_string($PGSQL['usuario'])."'), grade = '".pg_escape_string($nota)."'	WHERE ID = ".$id_assign_grades;
			}else{
				$query = "UPDATE mdl_assign_grades SET timemodified = (SELECT extract(epoch FROM now()))
				,grader = (SELECT id FROM mdl_user WHERE username = '".pg_escape_string($PGSQL['usuario'])."') WHERE ID = ".$id_assign_grades;
			}
			
			pg_query($pgconn, $query) or die("Erro na pesquisa");

	
			$query = "UPDATE mdl_assignfeedback_comments SET commenttext = '".pg_escape_string($feedback)."'WHERE grade = ".$id_assign_grades;

			pg_query($pgconn, $query) or die("Erro no update!!!\n");

		}else{
			// This insert grade objects for the student tasks
			if ($nota >= 0){
				$query = "INSERT INTO mdl_assign_grades (assignment, userid, timecreated, timemodified, grader, grade, attemptnumber) VALUES ( (SELECT mdl_grade_items.iteminstance FROM  mdl_grade_grades, mdl_grade_items where mdl_grade_grades.id = '".pg_escape_string($id)."' AND mdl_grade_grades.itemid = mdl_grade_items.id AND mdl_grade_items.itemmodule = 'assign'), (SELECT userid FROM mdl_grade_grades WHERE ID = '".pg_escape_string($id)."'),(SELECT extract(epoch FROM now())), (SELECT extract(epoch FROM now())), (SELECT id FROM mdl_user WHERE username = '".pg_escape_string($PGSQL['usuario'])."'), '".pg_escape_string($nota)."', 0)";
			}else{
				$query = "INSERT INTO mdl_assign_grades (assignment, userid, timecreated, timemodified, grader, attemptnumber) VALUES (	(SELECT mdl_grade_items.iteminstance FROM  mdl_grade_grades, mdl_grade_items where mdl_grade_grades.id = '".pg_escape_string($id)."' AND mdl_grade_grades.itemid = mdl_grade_items.id AND mdl_grade_items.itemmodule = 'assign'), (SELECT userid FROM mdl_grade_grades WHERE ID = '".pg_escape_string($id)."'), (SELECT extract(epoch FROM now())), (SELECT extract(epoch FROM now())), (SELECT id FROM mdl_user WHERE username = '".pg_escape_string($PGSQL['usuario'])."'), 0)";
			}

			// RUN update or insert assignments
			pg_query($pgconn, $query) or die("Erro no insert!\n");


			// Search for feedback objects for the student grades items
			$sql = "SELECT mdl_assign_grades.id	FROM mdl_grade_grades, mdl_grade_items, mdl_assign_grades WHERE mdl_grade_grades.id = '".pg_escape_string($id)."' and mdl_grade_grades.itemid = mdl_grade_items.id 	AND mdl_grade_items.iteminstance = mdl_assign_grades.assignment AND mdl_grade_grades.userid = mdl_assign_grades.userid AND mdl_grade_items.itemmodule = 'assign'";

		
			$resultado = pg_query($pgconn, $sql) or die("Erro na pesquisa");
			
			$i=0;
			while ($row=pg_fetch_row($resultado,$i)){
  				for($j=0; $j < count($row); $j++){
  					$count[]= $row[$j];
				}
  			}

			pg_free_result($resultado);
			$id_assign_grades = $count[0];			

			// Insert a new feedback object for the student grades items
			$query = "INSERT INTO mdl_assignfeedback_comments (commenttext, assignment, grade, commentformat) VALUES ('".pg_escape_string($feedback)."', (SELECT mdl_grade_items.iteminstance FROM  mdl_grade_grades, mdl_grade_items where mdl_grade_grades.id = '".pg_escape_string($id)."' AND mdl_grade_grades.itemid = mdl_grade_items.id AND mdl_grade_items.itemmodule = 'assign'),".$id_assign_grades.",1)";

			$resultado = pg_query($pgconn, $query) or die("Erro na pesquisa");

		}
	}
}
?>