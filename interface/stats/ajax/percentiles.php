<?php
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../globals.php");
require_once("../calculations.php");
require_once("../cdc_growth_stats.php");
session_write_close();

if(isset($_REQUEST['dob']))
{
    $dob=$_REQUEST['dob'];
}

if(isset($_REQUEST['pid']))
{
    $pid=$_REQUEST['pid'];
}

if(isset($_REQUEST['date']))
{
    $date=$_REQUEST['date'];
}

if(isset($_REQUEST['stat_choice']))
{
    $stat_choice=$_REQUEST['stat_choice'];
}

if(isset($_REQUEST['stat_value']))
{
    $stat_value=$_REQUEST['stat_value'];
}

if(isset($_REQUEST['sex']))
{
    $sex=$_REQUEST['sex'];
}
$retval=array();
$age_in_months=getPatientAgeYMD($dob,$date)['age_in_months'];
$retval['pct']=number_format(cdc_age_percentile($stat_value,$age_in_months,$sex,$stat_choice),1);
if($stat_choice==='bmi')
{
    $retval['status']=bmi_pct_to_status($retval['pct']);
}

echo json_encode($retval);

?>
