<?php

function get_reportable_codes()
{
   $sqlSelectReportableCodes = "select id, code, code_type,code_text from codes".
                               " where reportable=1 ORDER BY code";
   $res = sqlStatement($sqlSelectReportableCodes,array());
   $retval=array();
   foreach($res->GetArray() as $code)
   {
       array_push($retval,new reportable_code($code['id'],$code['code'],$code['code_type'],$code['code_text']));
   }
   return $retval;
}

function get_facilities()
{
    $sqlSelectFacilities = "select name, facility_npi from facility";
    $res=sqlStatement($sqlSelectFacilities,array());
    $retval=array();
    foreach($res->GetArray() as $fac)
    {
        array_push($retval,new facility($fac['name'],$fac['facility_npi']));
    }
    return $retval;
}
?>
