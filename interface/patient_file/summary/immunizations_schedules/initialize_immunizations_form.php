<?php
/**
 * Basic PHP setup for the immunization schedules
 * 
 * Copyright (C) 2013 Kevin Yeh <kevin.y@integralemr.com> and Medical Information Integration, LLC <www.mi-squared.com>
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
 * @author  Kevin Yeh <kevin.y@integralemr.com>
 * @link    http://www.open-emr.org
 */
?>
<?php
    require_once("$srcdir/patient.inc");
//    $pid=$_SESSION['pid'];
    $patient_data=getPatientData($pid,"DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
    $DOB=$patient_data['DOB_YMD'];
    $ageInfo= getPatientAgeYMD($DOB);
    $ageInMonths=$ageInfo['age_in_months'];
?>
<script>
    var webroot="<?php echo $web_root;?>";
    var pid=<?php echo $pid;?>;
    var enc=<?php echo $encounter;?>;
    var base_dir=webroot+"/interface/patient_file/summary/immunizations_schedules";
    var ajax_code_info=webroot+"/library/ajax/code_info.php";
    var ajax_info=base_dir+"/ajax/schedule_info.php";
    var ajax_billing=webroot+"/interface/forms/fee_sheet/review/fee_sheet_ajax.php";
    var ageInMonths=<?php echo $ageInMonths; ?>;
</script>

<script type="text/javascript" src="<?php echo $web_root;?>/library/js/knockout/knockout-2.2.1.js"></script>
<script type="text/javascript" src="<?php echo $web_root;?>/interface/patient_file/summary/immunizations_schedules/js/form_setup.js"></script>
<script type="text/javascript" src="<?php echo $web_root;?>/interface/patient_file/summary/immunizations_schedules/js/immunizations_view_model.js"></script>


<link rel="stylesheet" href="<?php echo $web_root;?>/interface/patient_file/summary/immunizations_schedules/immunizations_schedules.css" type="text/css">

<?php
    include_once("views/schedules_main.php");
    include_once("views/schedules_codes.php");
    include_once("views/billing_info.php");
?>