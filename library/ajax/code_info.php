<?php
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../interface/globals.php");
include_once("$srcdir/jsonwrapper/jsonwrapper.php");
$retval=array();

require_once("$srcdir/../custom/code_types.inc.php");
require_once("queries/code_queries.php");
if(isset($_REQUEST['pid']))
{
    $pid=$_REQUEST['pid'];
}

if(isset($_REQUEST['lookup_description']))
{
    $retval['code_description']=lookup_code_descriptions($_REQUEST['lookup_description']);
}
if(isset($_REQUEST['lookup_procedure_code']))
{
   $procedure_info=array();
   $procedure_info['description']=lookup_code_descriptions($_REQUEST['lookup_procedure_code']);
   $procedure_info['code']=$_REQUEST['lookup_procedure_code'];
   $code_split=explode(":",$procedure_info['code']);
   $procedure_info['price']=lookup_procedure_fee($pid,$code_split[1],$code_split[0]);
   $retval['procedure_info']=$procedure_info;
   
}
if(isset($_REQUEST['lookup_justify_codes']))
{
   $descriptions=explode(";",lookup_code_descriptions($_REQUEST['lookup_justify_codes']));
   $codes=explode(";",$_REQUEST['lookup_justify_codes']);
   $justify_codes=array();
   for($idx=0;$idx<count($codes);$idx++)
   {
       $justify_info=array();
       $justify_info['code']=$codes[$idx];
       $justify_info['description']=$descriptions[$idx];
       array_push($justify_codes,$justify_info);
   }
   $retval['justify_info']=$justify_codes;
}

echo json_encode($retval);
return;
?>
