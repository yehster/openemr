<?php
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../../globals.php");
if(isset($_REQUEST['data']))
{
    $data=json_decode($_REQUEST['data']);
}
else
{
    header("HTTP/1.0 403 Forbidden");
    echo "Need Encounter Data";
    return false;    
}
$retval=array();
$retval['hl7']="Hello this is the HL7 message";
echo json_encode($retval);
?>
