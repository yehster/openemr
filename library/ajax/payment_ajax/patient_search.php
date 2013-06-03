<?php

function payment_patient_search($patient_code,$mode)
{
    $search_fields=array("ExternalID"=>"pd.pubpid","PID"=>"pd.pid","PolicyNumber"=>"id.policy_number");
    $name_fields=array("lname","fname","mname");
    $query_columns="distinct(pd.pid) as id,pd.pubpid,pd.fname,pd.lname,pd.mname,pd.DOB, id.policy_number";
    $query_parameters=array();
    $query_base="SELECT ".$query_columns ." FROM patient_data as pd ";
    $query_base.="LEFT JOIN insurance_data as id ON id.pid=pd.pid and id.type='primary' ";
    $query_base.=" WHERE ";
    
    if(isset($search_fields[$mode]))
    {
        $query_string=$query_base." ".$search_fields[$mode]." LIKE ? ";
        array_push($query_parameters,$patient_code."%");
        $query_string.=" ORDER BY lname LIMIT 20";
        error_log($query_string);
        $res = sqlStatement($query_string,$query_parameters);
    }
    if(($mode=="Name") || (sqlNumRows($res)==0))
    {
        $fields=preg_split("/[\s,]+/",$patient_code,-1,PREG_SPLIT_NO_EMPTY);
        $query_string=$query_base;
        $query_parameters=array();
        $query_string.=" 1=1 ";
        for($idx=0;$idx<count($fields);$idx++)
        {

            $query_string.=" AND " .$name_fields[$idx]." LIKE ? ";
            array_push($query_parameters,$fields[$idx]."%");
            error_log($fields[$idx]);
        }
        $query_string.=" ORDER BY lname LIMIT 20";
        error_log($query_string);
        $res=sqlStatement($query_string,$query_parameters);
    }
    return $res;
}
?>
