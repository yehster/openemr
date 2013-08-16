<?php

    require_once("../../globals.php");
    require_once("../utilities/get_form_data.php");    
    require_once("../utilities/process_checkboxes.php");
    require_once("../utilities/load_problems.php");
    require_once("../java_util.php");
    require_once("../viewer.php");
    require_once("$srcdir/patient.inc");
    require_once("../directory_definitions.php");
    require_once("load_data.php");
    
    
?>



<?php
    $patient_info=array();
    
    $patient_data  = getPatientData($pid, "fname,lname,mname,city,language, postal_code as zip, street as address, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD, DATE_FORMAT(DOB,'%m/%d/%Y') as DOB_MDY");    
    foreach($patient_data as $key=>$value)
    {
        $patient_info[$key]=$value;
    }

    $ageYMD=getPatientAgeYMD($patient_data['DOB_YMD']);
    $age_in_months = $ageYMD['age_in_months'];
    
    $patient_info['today']=date("m/d/Y");        

    $files_dir=$include_root."/printouts/SchoolEntry/datafiles/";
    $source_file=$files_dir."CaliforniaSchoolEntry.pdf";
    $layout_file=$files_dir."CaliforniaSchoolEntry-layout.xml";

    
    $forms=array("Hemoglobin Result","Lead","Snellen Eye Exam","Hearing Screening","Tuberculosis Risk");
    $field_map=array($forms[0]=>array("gm/dl"=>"hgb"),$forms[1]=>array("mcg/dL"=>"lead")
            ,$forms[3]=>array('Left_Ear'=>'left_ear','Right_Ear'=>'right_ear')
        );
    $dates_map=array($forms[0]=>"hgb-date",$forms[1]=>"lead-date",$forms[2]=>"vision",$forms[3]=>"hearing-date");
    $forms_data=find_forms($pid,$forms);
    for($idx=0;$idx<count($forms);$idx++)
    {
        if(isset($forms_data[$forms[$idx]]))
        {
            $form_data=$forms_data[$forms[$idx]];
            if(isset($dates_map[$forms[$idx]]))
            {
                $date_data=explode("-",substr($form_data['date'],0,10));
                $patient_info[$dates_map[$forms[$idx]]."_month"]=$date_data[1];
                $patient_info[$dates_map[$forms[$idx]]."_day"]=$date_data[2];
                $patient_info[$dates_map[$forms[$idx]]."_year"]=$date_data[0];
            }
            $values=get_form_data($form_data['form_id'],$form_data['formdir']);
            if($idx===4) {process_tb_data($patient_info,$values);}
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
    if(isset($patient_info['hgb']))
    {
        if((($age_in_months<12) && ($patient_info['hgb']<11)) || (($age_in_months>=12) && ($patient_info['hgb']<11.5)))
        {
            echo "<b>Anemia: hgb ".$patient_info['hgb']."gm/dl<b><br>";
        }
    }
    $hearing_notes_left="";
    $hearing_notes_right="";
    if(isset($patient_info['left_ear']))
    {
        if($patient_info['left_ear']!=='PASSED')
        {
            $hearing_notes_left="L:".$patient_info['left_ear'];
        }
    }

    if(isset($patient_info['right_ear']))
    {
        if($patient_info['right_ear']!=='PASSED')
        {
            $hearing_notes_right="R:".$patient_info['right_ear'];
        }
    }
    if(($hearing_notes_left!="")||($hearing_notes_right!=""))
    {
        echo "<b>Hearing:".$hearing_notes_left."&nbsp;".$hearing_notes_right."<b><br>";
        $patient_info['hearing_notes_left']=$hearing_notes_left;
        $patient_info['hearing_notes_right']=$hearing_notes_right;
    }
    process_physical($patient_info,$pid);
    
    $patient_info['urine']="Not Applicable";
    $patient_info['office-address-1']="Santiago Pediatrics";
    $patient_info['office-address-2']="27800 Medical Center Road, Suite 300";
    $patient_info['office-address-3']="Mission Viejo, CA 92691";
    
    $patient_info['immunizations']="See Attached";    
    
    $target_dir=PDF_OUTPUT_DIR;
    $target_file="SchoolEntry_".uniqid().".pdf";    
    
    stamp_pdf($source_file,$target_dir.$target_file,$layout_file,$patient_info);
    echo "<br>";
    echo embed_pdf($target_file);
     
?>
