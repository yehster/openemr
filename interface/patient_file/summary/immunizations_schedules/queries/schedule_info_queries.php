<?php


function get_schedules($ageInMonths,$frequency=null)
{
    $sqlSchedules="SELECT id,description FROM immunizations_schedules ";
    if(empty($frequency))
    {
        $sqlSchedules.= "WHERE ISNULL(frequency) ";
        $sqlSchedules.=" ORDER BY ABS(age-?) ASC";
        $parameters=array($ageInMonths);
    }
    else
    {
        $sqlSchedules.= "WHERE age<=? AND age_max >=?";
        $parameters=array($ageInMonths,$ageInMonths);
        
    }
    $retval=array();
    $results=sqlStatement($sqlSchedules,$parameters);
    while($row=sqlFetchArray($results)){
        array_push($retval,$row);
    }
    return $retval;
           
}

function get_codes($schedule_id)
{
    $sqlScheduleCodes="SELECT isc.* FROM immunizations_schedules_codes as isc, immunizations_schedules_options as iso WHERE isc.id=iso.code_id and iso.schedule_id=? ORDER BY iso.seq ASC";
    $retval=array();
    $results=sqlStatement($sqlScheduleCodes,array($schedule_id));
    error_log($schedule_id);
    while($row=sqlFetchArray($results)){
        array_push($retval,$row);
    }
    return $retval;
    
}
?>
