<?php
function stature_info($pid,&$patient_info)
{
    $sqlVitals = "SELECT height,weight,BMI,DATE_FORMAT(date,'%m-%d-%Y') as date_string FROM form_vitals WHERE pid=? ORDER BY date desc LIMIT 1";
    $vitals_data = sqlQuery($sqlVitals,array($pid));
    if($vitals_data!==false)
    {
        $patient_info['height']=$vitals_data['height'];
        $pounds_int=floor($vitals_data['weight']);
        $ounces_int=floor(($vitals_data['weight']-$pounds_int)*16);
        $patient_info['weight']=$vitals_data['weight'];
        $patient_info['weight_lbs']=$pounds_int;
        $patient_info['weight_ozs']=$ounces_int;
        $patient_info['stature_date']=$vitals_data['date_string'];

    }
}
?>
