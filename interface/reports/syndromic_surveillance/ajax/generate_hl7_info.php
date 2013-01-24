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
$creation_time=new DateTime();
define('HL7_DATE_FORMAT',"Ymdhms");
$creation_time_formatted=$creation_time->format(HL7_DATE_FORMAT);

// Handle MSH
$message_id="ID".$creation_time_formatted;
$ss_message=new HL7_SS_ADT($data->{'type'},$message_id);
$msh=$ss_message->msh;
$rep_fac=$data->{'reporting_facility'};
$msh->setField(4,$rep_fac->{'name'},$rep_fac->{'npi'},'NPI');
$msh->setField(7,$creation_time_formatted);
//Done with MSH

//Handle EVN
$evn=$ss_message->evn;
$evn->setField(2,$creation_time_formatted);
$evt_fac=$data->{'event_facility'};
$evn->setField(7,$evt_fac->{'name'},$evt_fac->{'npi'},'NPI');;
//Done with EVN

//Handle PID
$patient=$data->{'patient'};
$pid=$ss_message->pid;
$patID=$pid->getField(3);
$patID->setComponent(1,$patient->{'pubpid'});
$patID->setComponent(5,"MR");

$pid->setField(8,substr($patient->{'sex'},0,1));
//Done with PID

$encounter=$data->{'encounter'};
//Handle PV1
$pv1=$ss_message->pv1;
// encounter ID-19
$vn=$pv1->getField(19);
$vn->setComponent(1,$encounter->{'encounter_id'});
$vn->setComponent(5,'VN');
// Admit Date -44
$pv1->setField(44,$data->{'admit_date_time'});
// Discharge Date --45
if(isset($data->{'discharge_date_time'}))
{
$pv1->setField(45,$data->{'discharge_date_time'});
    
}

//Done with PV1

// OBX Segments
// Handle Age First
$obx_age=$ss_message->obx->getRepeat(1);
$obx_age->setField(1,1);
$obx_age->setField(2,'NM'); // Numeric Field
$obx_age->setField(3,"21612-7","AGE TIME PATIENT REPORTED","LN"); // LOINC format age
$in_months=strpos($patient->{'age'},'month');
if($in_months===false)
{
    $numeric_age=$patient->{'age'};
    $unit_code='a';
    $unit_concept_name='year [time]';
    $unit_system='UCUM';  
}
else
{
    $numeric_age=substr($patient->{'age'},0,$in_months-1);
    $unit_code='mo';
    $unit_concept_name='month [time]';
    $unit_system='UCUM';  
    
}
$obx_age->setField(5,$numeric_age);
$obx_age->setField(6,$unit_code,$unit_concept_name,$unit_system);
$obx_age->setField(11,"F"); // Result Status (Final)
// done with age
$obx_facility_type=$ss_message->obx->getRepeat(2);
$obx_facility_type->setField(1,2);
$obx_facility_type->setField(2,'CWE');
$obx_facility_type->setField(3,'SS003','FACILITY / VISIT TYPE','PHINQUESTION');
$obx_facility_type->setField(5,"251V00000X","Urgent Care","HCPTNUCC");
$obx_facility_type->setField(11,"F");
//

// build the DG1 segments
$diagnoses=$encounter->{'diagnoses'};
$dg_repeat=1;
foreach($diagnoses as $diag)
{
    $seg=$ss_message->dg1->getRepeat($dg_repeat);
    $seg->setField(1,$seg->getRepeatIdx());
    $seg->setField(3,str_replace(".","",$diag->{'code'})
                                ,$diag->{'description'}
                                ,"I9CDX");  // I9CDX is official code type designation to be used for ICD9
    $seg->setField(6,$diag->{'diagnosis_type'});
    $dg_repeat++;
}
// done building the DG1 segments

$ss_message->applyData();
$retval['hl7']=$ss_message->toString();
echo json_encode($retval);
?>
