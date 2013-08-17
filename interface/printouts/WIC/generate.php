<?php

    require_once("../../globals.php");
    require_once("../utilities/get_form_data.php");    
    require_once("../utilities/stature_data.php");
    require_once("../java_util.php");
    require_once("../viewer.php");
    require_once("$srcdir/patient.inc");
    require_once("../directory_definitions.php");
    
?>
<form action="generate.php" method="post">
    <?php require_once("../utilities/provider_select.php");?>
    <input type='submit'/>
</form>
<?php
    require_once($include_root."/stats/birth_weight/birth_weight_queries.php");
    $files_dir=$include_root."/printouts/WIC/datafiles/";
    $source_file=$files_dir."WICReferralForm.pdf";
    $layout_file=$files_dir."WICReferralForm-layout.xml";

    $target_dir=PDF_OUTPUT_DIR;
    $target_file="WIC_".uniqid().".pdf";

    $patient_info=array();
    
    $patient_info['today']=date("m-d-Y");    
    
    $birthweight=get_birth_weight($pid);
    $patient_info['birth_lbs']=$birthweight['pounds_int'];
    $patient_info['birth_ozs']=$birthweight['ounces'];
    
    $patient_data  = getPatientData($pid, "fname,lname,sex, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD, DATE_FORMAT(DOB,'%m-%d-%Y') as DOB_MDY");    
    foreach($patient_data as $key=>$value)
    {
        echo $key.":".$value;
        $patient_info[$key]=$value;
    }
    
    stature_info($pid,$patient_info,$patient_data['DOB_YMD'],$patient_data['sex']);

    $forms=array("Hemoglobin Result","Lead");
    $field_map=array($forms[0]=>array("gm/dl"=>"hgb"),$forms[1]=>array("mcg/dL"=>"lead"));
    $dates_map=array($forms[0]=>"hgb-date");
    $forms_data=find_forms($pid,$forms);
    for($idx=0;$idx<count($forms);$idx++)
    {
        if(isset($forms_data[$forms[$idx]]))
        {
            $form_data=$forms_data[$forms[$idx]];
            if(isset($dates_map[$forms[$idx]]))
            {
                $date_data=explode("-",substr($form_data['date'],0,10));
                $patient_info[$dates_map[$forms[$idx]]]=$date_data[1]."-".$date_data[2]."-".$date_data[0];
            }
            $values=get_form_data($form_data['form_id'],$form_data['formdir']);
            for($valIdx=0;$valIdx<count($values);$valIdx++)
            {
                $form_map=$field_map[$forms[$idx]];
                if(isset($form_map[$values[$valIdx]['name']]))
                {
                    error_log($form_map[$values[$valIdx]['name']].":".$values[$valIdx]['value']);
                    $patient_info[$form_map[$values[$valIdx]['name']]]=$values[$valIdx]['value'];
                }
            }               
        }
        else
        {
        }
        
    }
    $patient_info['office-address-1']="Santiago Pediatrics";
    $patient_info['office-address-2']="27800 Medical Center Road, Suite 300";
    $patient_info['office-address-3']="Mission Viejo, CA 92691";
    
    $patient_info['phone']="Phone: 949-364-2229; Fax 949-364-1104";
    
    $patient_info['provider']=$provider_name;
    stamp_pdf($source_file,$target_dir.$target_file,$layout_file,$patient_info);
    echo "<br>";
    echo embed_pdf($target_file);
     
?>
