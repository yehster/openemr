<?php


define("TBL_WHO_WEIGHT_LENGTH_MALE",'who_weight_length_male');
define("TBL_WHO_WEIGHT_LENGTH_FEMALE",'who_weight_length_female');

define('COL_LENGTH','length');
define('COL_AGE','age');
function who_weight_height($weight,$height,$sex)
{
    $sex=strtolower($sex);
    if($sex==='male')
    {
        $table=TBL_WHO_WEIGHT_LENGTH_MALE;
    }
    else if($sex==='female')
    {
        $table=TBL_WHO_WEIGHT_LENGTH_FEMALE;        
    }
    else 
    {
            return 0;    
    }

    $difference="(?-".COL_LENGTH.")";
    $delta="ABS".$difference;
    $parameters=array();
    $sql_get_lms="SELECT ".COL_LENGTH.",L,M,S,".$delta." as delta";         array_push($parameters,$height);
    $sql_get_lms.=" FROM ".$table;
    $sql_get_lms.=" WHERE -0.25<=".$difference." AND ".$difference."<0.25 ";      array_push($parameters,$height,$height);
    $sql_get_lms.=" ORDER BY ".$delta." ASC LIMIT 1"; array_push($parameters,$height);
    
    $lms=sqlQuery($sql_get_lms,$parameters); 
    if($lms===false) // If we can't lookup the proper parameters to use, return null
    {
        return 0;
    }

    $z=x_to_z_lms($weight,$lms['L'],$lms['M'],$lms['S']);   
    return 100*(cdf($z));    
}

$GLOBALS['who_data_tables']=array(
    'weight'=>'who_weight_age',
    'height'=>'who_length_age',
    'head'=>'who_head_age'
    );
function who_age_percentile($x,$age,$sex,$stat)
{
    if(isset($GLOBALS['who_data_tables'][$stat]))
    {
        $table=$GLOBALS['who_data_tables'][$stat];
    }
    else
    {
        error_log("Could not determine WHO stats table for:".$stat);
        return "Unknown stat";
    }
    
    $sex=strtolower($sex);
    if(($sex==='male')||($sex==='female'))
    {
        $table.="_".$sex;
    }

    $difference="(?-".COL_AGE.")";
    $delta="ABS".$difference;
    $parameters=array();
    $sql_get_lms="SELECT ".COL_AGE.",L,M,S,".$delta." as delta";         array_push($parameters,$age);
    $sql_get_lms.=" FROM ".$table;
    $sql_get_lms.=" WHERE -0.5<=".$difference." AND ".$difference."<0.5 ";      array_push($parameters,$age,$age);
    $sql_get_lms.=" ORDER BY ".$delta." ASC LIMIT 1"; array_push($parameters,$age);
    
    $lms=sqlQuery($sql_get_lms,$parameters); 
    error_log($stat.":".$x.":".$lms['L'].":".$lms['M'].":".$lms['S']);
    if($lms===false) // If we can't lookup the proper parameters to use, return null
    {
        error_log("No LMS value!:".$age);
        return 0;
    }
  
    $z=x_to_z_lms($x,$lms['L'],$lms['M'],$lms['S']);   
    return 100*(cdf($z));    
    
}
?>

