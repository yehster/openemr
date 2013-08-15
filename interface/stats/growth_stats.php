<?php
require_once($include_root."/stats/calculations.php");
require_once($include_root."/stats/who_growth_stats.php");
require_once($include_root."/stats/cdc_growth_stats.php");

function get_cdc_stats($age,$sex,$weight,$height,$bmi)
{
    $retval=array();
    $retval['BMI_pct']=cdc_age_percentile($bmi,$age,$sex,'bmi');
    $retval['BMI_status']=bmi_pct_to_status($retval['BMI_pct']);
    $retval['weight_height_pct']=cdc_weight_height($weight,$height,$sex);
    $retval['weight_pct']=cdc_age_percentile($weight,$age,$sex,'weight');    
    $retval['height_pct']=cdc_age_percentile($height,$age,$sex,'height');
    return $retval;
}

function get_who_stats($age,$sex,$weight,$height,$head)
{
    $retval=array();
    $retval['head_pct']=who_age_percentile($head,$age,$sex,'head');
    $retval['weight_height_pct']=who_weight_height($weight,$height,$sex);
    $retval['weight_pct']=who_age_percentile($weight,$age,$sex,'weight');    
    $retval['height_pct']=who_age_percentile($height,$age,$sex,'height');
    return $retval;
}

?>
