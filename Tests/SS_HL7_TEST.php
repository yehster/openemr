<?php
require_once("../library/hl7/hl7_classes.php");
require_once("../library/hl7/HL7_SS_ADT.php");

$message=new HL7_SS_ADT("A01");
echo $message->toString();
?>
