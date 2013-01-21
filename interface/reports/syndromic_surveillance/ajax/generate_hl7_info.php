<?php
/**
* ajax to create the hl7 message from provided data
*
* Copyright (C) 2013 Kevin Yeh <kevin.y@integralemr.com>
*
* LICENSE: This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 3
* of the License, or (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
*
* @package OpenEMR
* @author Kevin Yeh <kevin.y@integralemr.com>
* @link http://www.open-emr.org
*/

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../../globals.php");
require_once("$srcdir/hl7/HL7_SS_ADT.php");
require_once("$srcdir/hl7/hl7_classes.php");
function pos_code_to_hl7($pos)
{
    $hl7_mapping=array(
        '23'=>"261QE0002X^Emergency Care",
        "261QM2500X^Medical Specialty",
        '11'=>"261QP2300X^Primary Care",
        '20'=>"261QU0200X^Urgent Care");
    // 11 (office) to Primary care.  May need to update this mapping for your own facility.  
    // These are the only four options allowed per: http://phinvads.cdc.gov/vads/ViewValueSet.action?id=5BA6F22F-4316-E211-989D-001A4BE7FA90
    // Lookup for other NUCC may be needed.  Alternately this is something that ought to be added to the existing facilities table, since there 
    // does not seem to be 1 to 1 mapping between POS codes and Facility Types.
    
    return $hl7_mapping[$pos]."^"."HCPTNUCC";
    
}
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

$hl7_ft=pos_code_to_hl7($evt_fac->{'pos_code'});
$obx_facility_type=$ss_message->obx->getRepeat(2);
$obx_facility_type->setField(1,2);
$obx_facility_type->setField(2,'CWE');
$obx_facility_type->setField(3,'SS003','FACILITY / VISIT TYPE','PHINQUESTION');
$obx_facility_type->setField(5,$hl7_ft);
$obx_facility_type->setField(11,"F"); // Observation is Final
//
if(isset($encounter->{'reason'})&&strlen($encounter->{'reason'}>0))
{
    $obx_chief_complaint=$ss_message->obx->getRepeat(3);
    $obx_chief_complaint->setField(1,3);
    $obx_chief_complaint->setField(2,TX);
    $obx_chief_complaint->setField(3,'8661-1','CHIEF COMPLAINT:FIND:PT:PATIENT:NOM:REPORTED','LN');
    $obx_chief_complaint->setField(5,$encounter->{'reason'});
    $obx_chief_complaint->setField(11,"F"); // Observation is Final
    
}
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
