<?php

//function result_query($dominioMoodle,$tokenMoodle){
function result_query($MySQL){

	//$sql = "SELECT mdl_course_modules.course, mdl_context.instanceid, mdl_files.userid, mdl_files.contextid, mdl_files.itemid, mdl_files.filename, mdl_grade_grades.rawgrademin, mdl_grade_grades.rawgrademax, mdl_grade_grades.id AS id_grade_grades, mdl_course_modules.idnumber, IFNULL((SELECT mdl_grade_grades_professor.finalGRADE FROM mdl_grade_grades AS mdl_grade_grades_professor, mdl_user WHERE mdl_grade_grades_professor.ID = mdl_grade_grades.id AND mdl_grade_grades.usermodified  = mdl_user.id AND mdl_user.username <> '".$MySQL['usuario']."'),-1) AS notaProfessor, mdl_course.shortname AS course_name, '' AS resposta FROM mdl_files, mdl_context, mdl_course_modules, mdl_grade_items, mdl_grade_grades, mdl_course WHERE mdl_files.contextid = mdl_context.id AND mdl_files.component = 'assignsubmission_file' AND mdl_context.instanceid = mdl_course_modules.id AND mdl_files.userid = mdl_grade_grades.userid AND mdl_course_modules.instance = mdl_grade_items.iteminstance AND mdl_grade_items.id = mdl_grade_grades.itemid AND mdl_course_modules.course = mdl_course.id AND filesize > 0 UNION ALL SELECT mdl_course.id AS course, mdl_course_modules.id AS instanceid, mdl_grade_grades.userid, 0 AS contextid, 0 AS itemid, '' AS filename, mdl_grade_grades.rawgrademin, mdl_grade_grades.rawgrademax, mdl_grade_grades.id AS id_grade_grades, mdl_course_modules.idnumber AS idws, IFNULL((SELECT mdl_grade_grades_professor.finalGRADE FROM mdl_grade_grades AS mdl_grade_grades_professor, mdl_user WHERE mdl_grade_grades_professor.ID = mdl_grade_grades.id AND mdl_grade_grades.usermodified  = mdl_user.id AND mdl_user.username <> '".$MySQL['usuario']."'),-1) AS notaProfessor, mdl_course.shortname AS course_name, mdl_assignsubmission_onlinetext.onlinetext AS resposta FROM mdl_course, mdl_assign, mdl_assign_submission, mdl_assignsubmission_onlinetext, mdl_course_modules, mdl_grade_items, mdl_grade_grades WHERE mdl_course.id = mdl_assign.course AND mdl_assign.id = mdl_assign_submission.assignment AND mdl_assign_submission.id = mdl_assignsubmission_onlinetext.submission AND mdl_assign.id = mdl_assignsubmission_onlinetext.assignment AND mdl_assign.id = mdl_course_modules.instance AND mdl_assign.id = mdl_grade_items.iteminstance AND mdl_grade_items.id = mdl_grade_grades.itemid AND mdl_assign_submission.userid = mdl_grade_grades.userid AND mdl_course_modules.course = mdl_assign.course ORDER BY 1,2,3";
	$sql = "SELECT mdl_course.id AS course,     mdl_course_modules.id AS instanceid,     mdl_grade_grades.userid,     IFNULL(files_user.contextid, 0) AS contextid,     IFNULL(files_user.itemid, 0) AS itemid,     IFNULL(files_user.filename, '') AS filename,     mdl_grade_grades.rawgrademin,     mdl_grade_grades.rawgrademax,     mdl_grade_grades.id AS id_grade_grades,     mdl_course_modules.idnumber AS idnumber,     IFNULL(       (SELECT mdl_grade_grades_professor.finalGRADE       FROM mdl_grade_grades AS mdl_grade_grades_professor, mdl_user       WHERE mdl_grade_grades_professor.ID = mdl_grade_grades.id        AND mdl_grade_grades.usermodified = mdl_user.id        AND mdl_user.username <> 'wsmoodle'),-1) AS notaProfessor,     mdl_course.shortname AS course_name,     mdl_assignsubmission_onlinetext.onlinetext AS resposta,     mdl_course.shortname AS course_name,     mdl_assignsubmission_onlinetext.onlinetext AS resposta,     mdl_assign_submission.id AS id_submissao    FROM mdl_course    INNER JOIN mdl_course_modules ON mdl_course_modules.module = 1    AND mdl_course_modules.course = mdl_course.id    INNER JOIN mdl_assign ON mdl_course.id = mdl_assign.course    AND mdl_course_modules.instance = mdl_assign.id    INNER JOIN mdl_assign_submission ON mdl_assign.id = mdl_assign_submission.assignment    LEFT JOIN mdl_assignsubmission_onlinetext ON mdl_assign_submission.id = mdl_assignsubmission_onlinetext.submission    LEFT JOIN mdl_modules ON mdl_modules.id = mdl_course_modules.module    INNER JOIN mdl_grade_items ON mdl_assign.id = mdl_grade_items.iteminstance    AND mdl_modules.name = mdl_grade_items.itemmodule    INNER JOIN mdl_grade_grades ON mdl_grade_items.id = mdl_grade_grades.itemid    AND mdl_assign_submission.userid = mdl_grade_grades.userid    LEFT JOIN    (SELECT mdl_files.userid,     mdl_files.contextid,     mdl_files.itemid,     mdl_files.filename,     mdl_context.instanceid    FROM mdl_context    INNER JOIN mdl_files ON mdl_files.contextid = mdl_context.id    AND mdl_files.component = 'assignsubmission_file'    AND mdl_files.filesize > 0) AS files_user ON files_user.userid = mdl_grade_grades.userid    AND files_user.instanceid = mdl_course_modules.id    WHERE not(mdl_course_modules.idnumber IS NULL     OR mdl_course_modules.idnumber = '')    ORDER BY 1,2,3";
//$sql = "SELECT mdl_course.id AS course,     mdl_course_modules.id AS instanceid,     mdl_grade_grades.userid,     IFNULL(files_user.contextid, 0) AS contextid,     IFNULL(files_user.itemid, 0) AS itemid,     IFNULL(files_user.filename, '') AS filename,     mdl_grade_grades.rawgrademin,     mdl_grade_grades.rawgrademax,     mdl_grade_grades.id AS id_grade_grades,     mdl_course_modules.idnumber AS idnumber,     IFNULL(       (SELECT mdl_grade_grades_professor.finalGRADE       FROM mdl_grade_grades AS mdl_grade_grades_professor, mdl_user       WHERE mdl_grade_grades_professor.ID = mdl_grade_grades.id        AND mdl_grade_grades.usermodified = mdl_user.id        AND mdl_user.username <> 'soap'),-1) AS notaProfessor,     mdl_course.shortname AS course_name,     mdl_assignsubmission_onlinetext.onlinetext AS resposta,     mdl_course.shortname AS course_name,     mdl_assignsubmission_onlinetext.onlinetext AS resposta,     mdl_assign_submission.id AS id_submissao    FROM mdl_course    INNER JOIN mdl_course_modules ON mdl_course_modules.module = 1    AND mdl_course_modules.course = mdl_course.id    INNER JOIN mdl_assign ON mdl_course.id = mdl_assign.course    AND mdl_course_modules.instance = mdl_assign.id    INNER JOIN mdl_assign_submission ON mdl_assign.id = mdl_assign_submission.assignment    LEFT JOIN mdl_assignsubmission_onlinetext ON mdl_assign_submission.id = mdl_assignsubmission_onlinetext.submission    LEFT JOIN mdl_modules ON mdl_modules.id = mdl_course_modules.module    INNER JOIN mdl_grade_items ON mdl_assign.id = mdl_grade_items.iteminstance    AND mdl_modules.name = mdl_grade_items.itemmodule    INNER JOIN mdl_grade_grades ON mdl_grade_items.id = mdl_grade_grades.itemid    AND mdl_assign_submission.userid = mdl_grade_grades.userid    LEFT JOIN    (SELECT mdl_files.userid,     mdl_files.contextid,     mdl_files.itemid,     mdl_files.filename,     mdl_context.instanceid    FROM mdl_context    INNER JOIN mdl_files ON mdl_files.contextid = mdl_context.id    AND mdl_files.component = 'assignsubmission_file'    AND mdl_files.filesize > 0) AS files_user ON files_user.userid = mdl_grade_grades.userid    AND files_user.instanceid = mdl_course_modules.id    WHERE not(mdl_course_modules.idnumber IS NULL     OR mdl_course_modules.idnumber = '')    ORDER BY 1,2,3";
	$sqlFeed = "SELECT * FROM mdl_assignfeedback_comments";
	$sqlNames = "SELECT mdl_user.id, mdl_user.firstname, mdl_user.lastname FROM mdl_user";

	$MySQLi = new MySQLi($MySQL['servidor'], $MySQL['usuario'], $MySQL['senha'], $MySQL['banco']);

	if (mysqli_connect_errno())
		trigger_error(mysqli_connect_error(), E_USER_ERROR);

	$MySQLi->set_charset("utf8");
	$result_data = $MySQLi->query($sql) OR trigger_error($MySQLi->error, E_USER_ERROR);
	$result_names =  $MySQLi->query($sqlNames) OR trigger_error($MySQLi->error, E_USER_ERROR);
	$result_feedbacks = $MySQLi->query($sqlFeed) OR trigger_error($MySQLi->error, E_USER_ERROR);



	if($result_data){
		while ($anexo = $result_data->fetch_object()){
			$anexos[] = $anexo;
		}
		$result_data->close();
	}

	if($result_names){
		while ($name = $result_names->fetch_object()){
			$names[] = $name;
		}
		$result_names->close();
	}

	if($result_feedbacks){
		while ($feedback = $result_feedbacks->fetch_object()){
			$feedbacks[] = $feedback;
		}

		$result_feedbacks->close();
	}



	header('Content-type: application/json');
	$MySQLi->close();

	return json_encode(array('anexos' => $anexos,'users' => $names,'feedbacks' => $feedbacks));
}

function atualizaQuestoes($sql,$questoesJson)
{
	$input = json_decode($questoesJson);
	for ($i = 0; $i < count($input); $i++)
	{
		atualizaQuestao($sql,$input[$i]->id_grade_grades, $input[$i]->nota, 0, $input[$i]->feedback);
	}
}

function atualizaQuestao ($MySQL,$id, $nota, $professor, $feedback)
{
	$MySQLi = new MySQLi($MySQL['servidor'], $MySQL['usuario'], $MySQL['senha'], $MySQL['banco']);

	if ($nota >= 0)
	{
		if(is_null($feedback))
		{
			$query = "UPDATE mdl_grade_grades SET RAWGRADE = '".$MySQLi->real_escape_string($nota)."', finalgrade = '".$MySQLi->real_escape_string($nota)."', timemodified = unix_timestamp(now()), usermodified = (select id from mdl_user WHERE username = '".$MySQLi->real_escape_string($MySQL['usuario'])."') WHERE ID = '".$MySQLi->real_escape_string($id)."' AND (usermodified not in (SELECT mdl_role_assignments.userid FROM mdl_role_assignments, mdl_context, mdl_course, mdl_grade_items WHERE mdl_context.contextlevel = 50 AND mdl_role_assignments.contextid = mdl_context.id AND mdl_context.instanceid = mdl_course.id AND mdl_course.id = mdl_grade_items.courseid AND mdl_role_assignments.roleid in (3,4) AND mdl_grade_items.id = mdl_grade_grades.itemid) OR rawgrade IS NULL)";

		}
		else
		{
			$query = "UPDATE mdl_grade_grades SET RAWGRADE = '".$MySQLi->real_escape_string($nota)."', finalgrade = '".$MySQLi->real_escape_string($nota)."', feedback = '".$MySQLi->real_escape_string($feedback)."',
				feedbackformat = 1 , timemodified = unix_timestamp(now()), usermodified = (SELECT id FROM mdl_user where username = '".$MySQLi->real_escape_string($MySQL['usuario'])."') WHERE ID = '".$MySQLi->real_escape_string($id)."' AND (usermodified not in (SELECT mdl_role_assignments.userid FROM mdl_role_assignments, mdl_context, mdl_course, mdl_grade_items WHERE mdl_context.contextlevel = 50 AND mdl_role_assignments.contextid = mdl_context.id AND mdl_context.instanceid = mdl_course.id AND mdl_course.id = mdl_grade_items.courseid AND mdl_role_assignments.roleid in (3,4) AND mdl_grade_items.id = mdl_grade_grades.itemid) 
				OR rawgrade is null)";
		}


	}
	else
	{
		if(!is_null($feedback))
		{
			$query = "UPDATE mdl_grade_grades SET feedback = '".$MySQLi->real_escape_string($feedback)."', feedbackformat = 1, timemodified = unix_timestamp(now()),
			usermodified = (select id from mdl_user where username = '".$MySQLi->real_escape_string($MySQL['usuario'])."') WHERE ID = '".$MySQLi->real_escape_string($id)."' AND (usermodified not in (SELECT mdl_role_assignments.userid FROM mdl_role_assignments, mdl_context, mdl_course, mdl_grade_items WHERE mdl_context.contextlevel = 50 AND mdl_role_assignments.contextid = mdl_context.id AND mdl_context.instanceid = mdl_course.id  AND mdl_course.id = mdl_grade_items.courseid AND mdl_role_assignments.roleid in (3,4) AND mdl_grade_items.id = mdl_grade_grades.itemid)  OR rawgrade is null)";
		}

	}

	if( $MySQLi->query($query) )
	{

		$sql = "SELECT mdl_assign_grades.id
		FROM mdl_grade_grades, mdl_grade_items, mdl_assign_grades
		where mdl_grade_grades.id = '".$MySQLi->real_escape_string($id)."'
		and mdl_grade_grades.itemid = mdl_grade_items.id
		and mdl_grade_items.iteminstance = mdl_assign_grades.assignment
		and mdl_grade_grades.userid = mdl_assign_grades.userid
		and mdl_grade_items.itemmodule = 'assign'";

		$resultado = $MySQLi->query($sql);
		$Count = $resultado->fetch_row();

		$resultado->close();

		$id_assign_grades = $Count[0];


		if($id_assign_grades > 0)
		{
			if ($nota >= 0) 
			{
				$query = "UPDATE mdl_assign_grades SET timemodified = unix_timestamp(now())
				,grader = (select id from mdl_user where username = '".$MySQLi->real_escape_string($MySQL['usuario'])."')
				,grade = '".$MySQLi->real_escape_string($nota)."'
				WHERE ID = ".$id_assign_grades;
			} 
			else
			{
				$query = "UPDATE mdl_assign_grades SET timemodified = unix_timestamp(now()
				,grader = (select id from mdl_user where username = '".$MySQLi->real_escape_string($MySQL['usuario'])."')
				WHERE ID = ".$id_assign_grades;
			}

			$MySQLi->query($query);

			// Altera comentário
			$query = "UPDATE mdl_assignfeedback_comments
			SET commenttext = '".$MySQLi->real_escape_string($feedback)."'
			WHERE grade = ".$id_assign_grades;

			$MySQLi->query($query);

		}
	}
	else
	{
			if ($nota >= 0)
			{
				$query = "INSERT INTO mdl_assign_grades (assignment, userid, timecreated, timemodified, grader, grade, attemptnumber)
				VALUES (
						(SELECT mdl_grade_items.iteminstance
						FROM  mdl_grade_grades, mdl_grade_items
						where mdl_grade_grades.id = '".$MySQLi->real_escape_string($id)."'
						and mdl_grade_grades.itemid = mdl_grade_items.id
						and mdl_grade_items.itemmodule = 'assign'),
						(SELECT userid FROM mdl_grade_grades WHERE ID = '".$MySQLi->real_escape_string($id)."'),
						unix_timestamp(now()),
						unix_timestamp(now()),
						(select id from mdl_user where username = '".$MySQLi->real_escape_string($MySQL['usuario'])."'),
						'".$MySQLi->real_escape_string($nota)."',
						0)";
			}
			else
			{
				$query = "INSERT INTO mdl_assign_grades (assignment, userid, timecreated, timemodified, grader, attemptnumber)
				VALUES (
						(SELECT mdl_grade_items.iteminstance
						FROM  mdl_grade_grades, mdl_grade_items
						where mdl_grade_grades.id = '".$MySQLi->real_escape_string($id)."'
						and mdl_grade_grades.itemid = mdl_grade_items.id
						and mdl_grade_items.itemmodule = 'assign'),
						(SELECT userid FROM mdl_grade_grades WHERE ID = '".$MySQLi->real_escape_string($id)."'),
						unix_timestamp(now()),
						unix_timestamp(now()),
						(select id from mdl_user where username = '".$MySQLi->real_escape_string($MySQL['usuario'])."'),
						0)";
			}
	
			
			$MySQLi->query($query);

			$sql = "SELECT mdl_assign_grades.id
			FROM mdl_grade_grades, mdl_grade_items, mdl_assign_grades
			where mdl_grade_grades.id = '".$MySQLi->real_escape_string($id)."'
			and mdl_grade_grades.itemid = mdl_grade_items.id
			and mdl_grade_items.iteminstance = mdl_assign_grades.assignment
			and mdl_grade_grades.userid = mdl_assign_grades.userid
			and mdl_grade_items.itemmodule = 'assign'";

			$resultado = $MySQLi->query($sql);
			$Count = $resultado->fetch_row();
			$resultado->close();
			$id_assign_grades = $Count[0];

			$query = "INSERT INTO mdl_assignfeedback_comments (commenttext, assignment, grade, commentformat)
			VALUES (
					'".$MySQLi->real_escape_string($feedback)."',
					(SELECT mdl_grade_items.iteminstance
					FROM  mdl_grade_grades, mdl_grade_items
					where mdl_grade_grades.id = '".$MySQLi->real_escape_string($id)."'
					and mdl_grade_grades.itemid = mdl_grade_items.id
					and mdl_grade_items.itemmodule = 'assign'),".$id_assign_grades.",1)";

			$MySQLi->query($query);

	}

}

?>
