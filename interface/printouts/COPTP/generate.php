<?php

    require_once("../../globals.php");
    require_once("../utilities/get_form_data.php");    
    require_once("../utilities/stature_data.php");
    require_once("../utilities/process_checkboxes.php");
    require_once("../utilities/load_problems.php");
    require_once("../java_util.php");
    require_once("../viewer.php");
    require_once("$srcdir/patient.inc");
    require_once("../directory_definitions.php");
    require_once("load_data.php");
?>
<script src="<?php echo $web_root;?>/library/js/jquery-2.0.3.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="COPTP.css" type="text/css">


<?php
    function ris($field,$default='')
    {
        return (isset($_REQUEST[$field]) && !($_REQUEST[$field]==='')) ?  $_REQUEST[$field] : $default;
    }
    
    if(isset($_REQUEST['education_request']))
    {
        $education_request=$_REQUEST['education_request'];
    }
    else
    {
        $education_request="none";
    }
    $coptp_primary=ris('coptp_primary','none');
    set_checkbox($patient_info,"coptp-primary",$coptp_primary);
    
    $coptp_secondary=ris('coptp_secondary','none');
    set_checkbox($patient_info,"coptp-secondary",$coptp_secondary);

    $problems=load_problems($pid,"medical_problem");
    ?>
<div class="info">
<?php    echo problems_table($problems);    ?>
</div>
<?php    
    require_once("choices.php");
    process_education_request($patient_info,$education_request);
    $files_dir=$include_root."/printouts/COPTP/datafiles/";
    $source_file=$files_dir."COPTP.pdf";
    $layout_file=$files_dir."COPTP-layout.xml";
    
    $patient_info['today']=date("m-d-Y");    
    $patient_info['signature-date']=$patient_info['today'];    
    
    $patient_data  = getPatientData($pid, "CONCAT(fname,' ',lname) as pname,city,sex,language, postal_code as zip, street as address, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD, DATE_FORMAT(DOB,'%m-%d-%Y') as DOB_MDY");    
    foreach($patient_data as $key=>$value)
    {
        echo $key.":".$value;
        $patient_info[$key]=$value;
    }    
    
    set_checkbox($patient_info,"sex",$patient_data['sex']);
    
    
    $patient_info['to-learn']=ris("to_learn");
    process_language_choice($patient_info,$patient_data['language']);
    
    $patient_info["Physician"]="X";

    $patient_info['provider-name']="James L. Kay, D.O., FAAP";
    $patient_info['provider-address']="27800 Medical Center Road, Suite 300";
    $patient_info['provider-city-zip']="Mission Viejo, CA 92691";
    $patient_info['provider-id']="1558646620";
    
    $patient_info['provider-phone']="949-364-2229";
    $patient_info['provider-fax']="949-364-1104";

    $patient_info['contact-phone']="949-364-2229";
    $patient_info['contact-name']="Ana Gutierrez";

    
     $sqlVitals = "SELECT height,weight,BMI,DATE_FORMAT(date,'%m-%d-%Y') as vitals_date FROM form_vitals WHERE pid=? ORDER BY date desc LIMIT 1";
     $vitals_data = sqlQuery($sqlVitals,array($pid));
     if($vitals_data!==false)
     {
         if($vitals_data['weight']!=0) $patient_info['weight']=$vitals_data['weight'];
         if($vitals_data['height']!=0) $patient_info['height']=$vitals_data['height'];
         if($vitals_data['BMI']!=0) $patient_info['bmi']=$vitals_data['BMI'];
         $patient_info['vitals-date']=$vitals_data['vitals_date'];
     }
     
    $target_dir=PDF_OUTPUT_DIR;
    $target_file="COPTP_".uniqid().".pdf";    
    stamp_pdf($source_file,$target_dir.$target_file,$layout_file,$patient_info);
    echo "<br>";
    echo embed_pdf($target_file);
?>
</div>
<script src="choices.js" type="text/javascript"></script>