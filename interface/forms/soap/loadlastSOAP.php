<?php
/*
  Loads the soap data from a patient's most recent visit, for the purpose of data re-use
*/
require_once("../../globals.php");

$pid = intval($_REQUEST['pid']);

$result = sqlQuery("select subjective,objective,assessment,plan from form_soap where pid=$pid order by date desc");

if ($result === false)
  echo json_encode(array("Error"=>true));
else
  echo json_encode($result);
?>
