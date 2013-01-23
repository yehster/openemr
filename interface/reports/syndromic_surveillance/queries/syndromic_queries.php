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
    if($from!="")
    {
        $queryParams[]=$from;
    }
    if($to!="")
    {
        $queryParams[]=$to;
    }
    $sqlSelect=" SELECT l.id, l.title,l.diagnosis, l.pid, e.encounter, e.date".
               " FROM lists as l ".
               " LEFT JOIN issue_encounter as i on l.id=i.list_id ".
               " LEFT JOIN form_encounter as e on e.encounter=i.encounter ".
               " WHERE l.diagnosis in (".$diagInClause.")".
               " AND l.type='medical_problem' ".
               (($from!="") ? " AND (e.date >= ? OR ISNULL(e.date))" : "").
               (($to!="")   ? " AND (e.date <= ? OR ISNULL(e.date))" : "").               
               " ORDER BY e.date desc".
               " LIMIT ".$start.",".$page_size;
    $res=sqlStatement($sqlSelect,$queryParams);
    error_log($sqlSelect);
    $retval=array();
    foreach($res->GetArray() as $row)
    {
        $retval[]=new event($row['id'],$row['encounter'],$row['date'],$row['pid'],$row['title'],$row['diagnosis']);
    }
    return $retval;
}

function get_patient_info($pid,$date)
{
    $sqlSelect = " SELECT fname, lname, pubpid, DOB, sex, deceased_date"
               . " FROM patient_data "
              . " WHERE pid=?";
    $res=sqlStatement($sqlSelect,array($pid));
    $retval=$res->fetchRow();
    $retval['age']=getPatientAge($retval['DOB'],$date); //
    return $retval;
}
function get_encounter_info($encounter)
{
    $sqlSelect = " SELECT reason, facility_id,date"
               . " FROM form_encounter "
              . " WHERE encounter=?";
    $res=sqlStatement($sqlSelect,array($encounter));
    $retval=$res->fetchRow();
    
    $diags=array();
    $selectDiagnoses = " SELECT code_text as description, c.code "
                       ." FROM issue_encounter as i,lists as l,codes as c"
                       ." WHERE l.type='medical_problem' AND c.code_type=2"
                       ." AND substr(l.diagnosis,6)=c.code"
                       ." AND i.encounter=? and i.list_id=l.id";
    $resDiag=sqlStatement($selectDiagnoses,array($encounter));
    foreach($resDiag->GetArray() as $diag)
    {
        array_push($diags,$diag);
    }
    $retval['diagnoses']=$diags;
    return $retval;
    
}
?>
