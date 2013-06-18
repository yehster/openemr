<?php

    require_once("../../globals.php");
    require_once("../java_util.php");
    require_once("../viewer.php");
    require_once("$srcdir/patient.inc");
    require_once("../directory_definitions.php");

    $files_dir=$include_root."/printouts/WIC/datafiles/";
    $source_file=$files_dir."WICReferralForm.pdf";
    $layout_file=$files_dir."WICReferralForm-layout.xml";

    $target_dir=PDF_OUTPUT_DIR;
    $target_file="WIC_".uniqid().".pdf";

    $patient_info=array();
    
    $patient_info['today']=date("m-d-Y");    
    
    
    $patient_data  = getPatientData($pid, "fname,lname, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");    
    foreach($patient_data as $key=>$value)
    {
        echo $key.":".$value;
        $patient_info[$key]=$value;
    }
    
    stamp_pdf($source_file,$target_dir.$target_file,$layout_file,$patient_info);

    echo "<br>";
    echo embed_pdf($target_file);
     
?>
