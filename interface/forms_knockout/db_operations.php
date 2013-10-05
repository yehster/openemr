<?php
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");

define("KNOCKOUT_NAME","KNOCKOUT_NAME");
define("KNOCKOUT_TABLE","KNOCKOUT_TABLE");
define("KNOCKOUT_DIR","KNOCKOUT_DIR");

define('COL_DOC_UUID',"document_uuid");


define("FRM_INF_SICK","Infant Sick Visit");
define('TBL_INF_SICK',"form_infant_sick_visit");
define("DIR_INF_SICK","infant_sick_visit");

define("FRM_CIRCUMCISION","Circumcision");
define("TBL_CIRCUMCISION","form_circumcision");
define("DIR_CIRCUMCISION","circumcision");

$knockout_forms=array();

function define_knockout_metadata($name,$table,$dir)
{
    $new_form=array();
    $new_form[KNOCKOUT_NAME]=$name;
    $new_form[KNOCKOUT_TABLE]=$table;
    $new_form[KNOCKOUT_DIR]=$dir;
    $GLOBALS['knockout_forms'][$name]=$new_form;
}

define_knockout_metadata(FRM_INF_SICK,TBL_INF_SICK,DIR_INF_SICK);

define_knockout_metadata(FRM_CIRCUMCISION,TBL_CIRCUMCISION,DIR_CIRCUMCISION);

function new_knockout_form($formname,$encounter,$user,$group,$pid)
{
    $form_info=$GLOBALS['knockout_forms'][$formname];
    
    $retval=array();
    $retval['uuid']=uuid_create();
    $GLOBALS['adodb']['db']->StartTrans();

    $sqlCreateForm  = " INSERT INTO " .$form_info[KNOCKOUT_TABLE]
                   . " (date , authorized , activity , pid , user , groupname , ".COL_DOC_UUID. ")"
                   . " values (NOW(), 0 , 1, ? , ? , ? , ?);";
    $createParams=array($pid,$user,$group,$retval['uuid']);
    $newid = sqlInsert($sqlCreateForm,$createParams);
    $retval['id'] = $newid;
    addForm($encounter, $form_info[KNOCKOUT_NAME], $newid, $form_info[KNOCKOUT_DIR], $pid, $group);    
    $GLOBALS['adodb']['db']->CompleteTrans();
    return $retval;
}

function load_knockout_form($formname,$id)
{
    
    $form_info=$GLOBALS['knockout_forms'][$formname];
    $retval=array();
    $retval['id']=$id;
    $sqlRetrieveData = " SELECT ".COL_DOC_UUID. " FROM ". $form_info[KNOCKOUT_TABLE]
                     . " WHERE id=?";
    $res=sqlQuery($sqlRetrieveData,array($id));
    if($res!==false)
    {
        $retval['uuid']=$res[COL_DOC_UUID];
    }
    return $retval;
}
?>
