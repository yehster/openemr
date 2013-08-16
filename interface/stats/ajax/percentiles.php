<?php
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../globals.php");
require_once("../growth_stats.php");
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

if(isset($_REQUEST['bmi']))
{
    $bmi=$_REQUEST['bmi'];
}

if(isset($_REQUEST['height']))
{
    $height=$_REQUEST['height'];
}

if(isset($_REQUEST['weight']))
{
    $weight=$_REQUEST['weight'];
}

if(isset($_REQUEST['head']))
{
    $head=$_REQUEST['head'];
}
if(isset($_REQUEST['sex']))
{
    $sex=$_REQUEST['sex'];
}
$retval=array();
$age_in_months=getPatientAgeYMD($dob,$date)['age_in_months'];
$retval['bmi']=number_format(cdc_age_percentile($bmi,$age_in_months,$sex,'bmi'),1);

if($age_in_months<24)
{
    $lookup_data=get_who_stats($age_in_months,$sex,$weight,$height,$head);
    $retval['BMI_pct']="Undefined";
    $retval['BMI_status']="Undefined";
}
else if($age_in_months>=23.5)
{
    $lookup_data=get_cdc_stats($age_in_months,$sex,$weight,$height,$bmi);
}
foreach($lookup_data as $key=>$value)
{
    if(is_numeric($value))
    {
        $retval[$key]=number_format($value,1);        
    }
    else
    {
        $retval[$key]=$value;
    }
}
echo json_encode($retval);

?>
