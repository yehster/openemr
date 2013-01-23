<?php
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../../globals.php");
require_once("../queries/syndromic_classes.php");
require_once("../queries/syndromic_queries.php");
require_once("../../../../library/patient.inc");

if(isset($_REQUEST['pid']))
{
    $pid=$_REQUEST['pid'];
}
if(isset($_REQUEST['list_id']))
{
    $list_id=$_REQUEST['list_id'];
}
if(isset($_REQUEST['encounter']))
{
    $encounter=$_REQUEST['encounter'];
}
$retval=array();
$retval['encounter']=get_encounter_info($encounter);
$retval['patient']=get_patient_info($pid,$retval['encounter']['date']);
echo json_encode($retval);
?>
