<?php
$ignoreAuth=true;
require_once("../../interface/globals.php");
require_once("../../interface/stats/calculations.php");
require_once("../../interface/stats/cdc_growth_stats.php");


    echo "<br> pct:".cdc_age_percentile(16.575,24,"Male","bmi");

    echo "<br> pct:".cdc_age_percentile(14.52,24,"Male","bmi");

    echo "<br> pct:".cdc_age_percentile(14.73,24,"Male","bmi");

    echo "<br> pct:".cdc_age_percentile(15.09,24,"Male","bmi");

    echo "<br> pct:".cdc_age_percentile(15.74,24,"Male","bmi"); 

    echo "<br> pct:".cdc_age_percentile(15.74,24.249,"Male","bmi");       
    
    echo "<br> weight-heightpct:".cdc_weight_height(9.76,77.1,"Male");

    echo "<br> weight-heightpct:".cdc_weight_height(11.314,86,"Male");    
?>
