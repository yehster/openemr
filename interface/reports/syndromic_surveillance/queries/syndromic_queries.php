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

function find_events($from,$to,$diags,$start=0,$page_size=100)
{
    $queryParams=array();
    if($from!="")
    {
        $queryParams[]=$from;
    }
    if($to!="")
    {
        $queryParams[]=$to;
    }
    $diagInClause="";
    $first=true;
    foreach($diags as $diag)
    {
        $queryParams[]=$diag->dbKey();
        if(!$first)
        {
            $diagInClause.=",";
        }
        $diagInClause.="?";
        $first=false;
    }
    $sqlSelect=" SELECT l.id, l.title,l.diagnosis, l.pid, e.encounter, e.date".
               " FROM lists as l ".
               " LEFT JOIN issue_encounter as i on l.id=i.list_id ".
               " LEFT JOIN form_encounter as e on e.encounter=i.encounter ".
               (($from!="") ? " AND e.date >= ?" : "").
               (($to!="")   ? " AND e.date <= ?" : "").
               " WHERE l.diagnosis in (".$diagInClause.")".
               " AND l.type='medical_problem' ".
               " ORDER BY e.date desc".
               " LIMIT ".$start.",".$page_size;
    $res=sqlStatement($sqlSelect,$queryParams);
    foreach($res->GetArray() as $row)
    {
        error_log($row['encounter']);
    }
    
}
?>
