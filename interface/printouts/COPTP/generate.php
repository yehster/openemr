<?php

    require_once("../../globals.php");
    require_once("../utilities/get_form_data.php");    
    require_once("../utilities/stature_data.php");
    require_once("../utilities/process_checkboxes.php");
    require_once("../java_util.php");
    require_once("../viewer.php");
    require_once("$srcdir/patient.inc");
    require_once("../directory_definitions.php");

    $files_dir=$include_root."/printouts/COPTP/datafiles/";
    $source_file=$files_dir."COPTP.pdf";
    $layout_file=$files_dir."COPTP-layout.xml";
    
    $patient_info['today']=date("m-d-Y");    
    
    $patient_data  = getPatientData($pid, "CONCAT(fname,' ',lname) as pname,city,sex,language, postal_code as zip, street as address, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD, DATE_FORMAT(DOB,'%m-%d-%Y') as DOB_MDY");    
    foreach($patient_data as $key=>$value)
    {
        echo $key.":".$value;
        $patient_info[$key]=$value;
    }    
    
    set_checkbox($patient_info,"sex",$patient_data['sex']);
    
    
    $patient_info["Physician"]="X";

    $patient_info['provider-name']="James L. Kay, D.O., FAAP";
    $patient_info['provider-address']="27800 Medical Center Road, Suite 300";
    $patient_info['provider-city-zip']="Mission Viejo, CA 92691";
    
    $patient_info['provider-phone']="949-364-2229";
    $patient_info['provider-fax']="949-364-1104";
    
    $target_dir=PDF_OUTPUT_DIR;
    $target_file="COPTP_".uniqid().".pdf";    
    stamp_pdf($source_file,$target_dir.$target_file,$layout_file,$patient_info);
    echo "<br>";
    echo embed_pdf($target_file);
?>
