<?php
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../../globals.php");
require_once("$srcdir/hl7/HL7_SS_ADT.php");
require_once("$srcdir/hl7/hl7_classes.php");

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
$ss_message=new HL7_SS_ADT($data->{'type'},"TestID12345");
$encounter=$data->{'encounter'};
$diagnoses=$encounter->{'diagnoses'};
$dg_repeat=1;
foreach($diagnoses as $diag)
{
    $seg=$ss_message->dg1->getRepeat($dg_repeat);
    $seg->setField(3,str_replace(".","",$diag->{'code'})
                                ,$diag->{'description'}
                                ,"I9CDX");  // I9CDX is official code type designation to be used for ICD9
    $seg->setField(1,$seg->getRepeatIdx());
    $seg->setField(6,$diag->{'diagnosis_type'});
    $dg_repeat++;
}
$ss_message->applyData();
$retval['hl7']=$ss_message->toString();
echo json_encode($retval);
?>
