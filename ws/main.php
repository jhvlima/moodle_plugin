<?php

//include("commands_pg.php"); // commands for postgresql instances
include("commands_mysql.php"); // commands for mysql instances

if($_POST){
  
  if($_POST['action'] == "anexos"){

  $SQL_info = unserialize($_POST['data']);
  $data = result_query($SQL_info);
  //header('Content-type: application/json');
  echo $data;

  }else if($_POST['action'] == "upload"){

  $SQL_info = unserialize($_POST['info']);
  $grades = unserialize($_POST['grades']);
  $data = atualizaQuestoes($SQL_info,$grades);
  //header('Content-type: application/json');
  echo $data;

  }
}
?>
