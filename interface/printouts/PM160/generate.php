<?php
/**
-Demographics for patient
-Next appointment date
-Parent information
-Diagnoses associated with visit
-Snellen results (from form)
-Hearing screening results (from my layout based form)
-Hemoglobin result
-If we sent a urine sample (I'll make a layout based form)
-TB result
-Height
-Weight (in pounds and ounces)
-BMI (eventually in percentile, but maybe start with the raw number)
-Eventually immunizations given at that visit
-Tobacco questions (I'll need to make this a form too)
*/

    require_once("../../globals.php");
    require_once("$srcdir/patient.inc");
    require_once("$srcdir/options.inc.php");    
    require_once("load_data.php");
    $pid=$_SESSION['pid'];
    
    $DOM = new DOMDocument("1.0","utf-8");
    $divResults=$DOM->createElement("div"," ");
    $divResults->setAttribute("id","res_display");
    
    $divTable=$DOM->createElement("table");
    $divResults->appendChild($divTable);
    $divTBODY=$DOM->createElement("tbody");
    
    $divTable->appendChild($divTBODY);
    
    appointment_info($DOM,$divTBODY,$pid);
    stature_info($DOM,$divTBODY,$pid);
    
    $forms=array("Snellen Eye Exam","Hearing Screening","Hemoglobin Result","Tuberculin Skin Test (TST)");
    $patient_data  = getPatientData($pid, "*, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
    $employer_data = getEmployerData($pid);
?>
    <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
    <link rel="stylesheet" href="pm160.css" type="text/css">
    <script type="text/javascript" src="../../../library/js/jquery-2.0.2.min.js"></script>
<body class="body_top">
    <button id="reload">Reload</button><br><br>
        
    <ul id="dem_list">
        <?php display_layout_tabs("DEM",$patient_data,$employer_data); ?>
    </ul>
    <div id="dem_data">
        <?php display_layout_tabs_data("DEM",$patient_data,$employer_data); ?>
    </div>
    <div id="dem_display"></div>
    <?php echo $DOM->saveXML($divResults); ?>
    
    <script type="text/javascript" src="format_demographics.js"></script>
</body>

