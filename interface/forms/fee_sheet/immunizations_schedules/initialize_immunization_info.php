<?php
    require_once("$srcdir/patient.inc");
//    $pid=$_SESSION['pid'];
    $patient_data=getPatientData($pid,"DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
    $DOB=$patient_data['DOB_YMD'];
    $ageInfo= getPatientAgeYMD($DOB);
    $ageInMonths=$ageInfo['age_in_months'];
?>
<script>
    var base_dir=webroot+"/interface/patient_file/summary/immunizations_schedules";
    var ajax_code_info=webroot+"/library/ajax/code_info.php";
    var ajax_info=base_dir+"/ajax/schedule_info.php";
    var ajax_billing=webroot+"/interface/forms/fee_sheet/review/fee_sheet_ajax.php";
    var ageInMonths=<?php echo $ageInMonths; ?>;
</script>

<script type="text/javascript" src="<?php echo $web_root;?>/interface/patient_file/summary/immunizations_schedules/js/immunizations_view_model.js"></script>
<script type="text/javascript" src="<?php echo $web_root;?>/interface/forms/fee_sheet/immunizations_schedules/js/immunizations_setup.js"></script>

<link rel="stylesheet" href="<?php echo $web_root;?>/interface/forms/fee_sheet/immunizations_schedules/immunizations_schedules.css" type="text/css">

<?php
    $immunization_dir="$fileroot/interface/patient_file/summary/immunizations_schedules";
    include_once("$immunization_dir/views/schedules_main.php");
    include_once("$immunization_dir/views/schedules_codes.php");
?>