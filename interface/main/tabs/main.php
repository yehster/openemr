<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$fake_register_globals=false;
$sanitize_all_escapes=true;

/* Include our required headers */
require_once('../../globals.php');
require_once($webserver_root."/interface/includes/include_utils.php");


?>
<!DOCTYPE html>
<title>OpenEMR Tabs</title>
<script type="text/javascript">
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

//  Include this variable for backward compatibility 
var loadedFrameCount = 0;
var tab_mode=true;
function allFramesLoaded() {
// Stub function for backward compatibility with frame race condition mechanism
 return true;
}
var webroot_url="<?php echo $web_root; ?>";
</script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" type="text/css" href="css/tabs.css"/>
<link rel="stylesheet" type="text/css" href="css/menu.css"/>

<?php include_js_library("knockout/knockout-3.4.0.js"); ?>
<?php include_js_library("jquery-2.2.0.min.js"); ?>

<script type="text/javascript" src="js/custom_bindings.js"></script>


<script type="text/javascript" src="js/patient_data_view_model.js"></script>
<script type="text/javascript" src="js/tabs_view_model.js"></script>
<script type="text/javascript" src="js/application_view_model.js"></script>
<script type="text/javascript" src="js/frame_proxies.js"></script>
<script type="text/javascript" src="js/dialog_utils.js"></script>

<link rel='stylesheet' href='<?php echo $web_root; ?>/library/fonts/typicons/typicons.min.css' />

<?php require_once("templates/tabs_template.php"); ?>
<?php require_once("templates/menu_template.php"); ?>
<?php require_once("templates/patient_data_template.php"); ?>
<?php require_once("menu/menu_json.php"); ?>
<div id="mainBox">
    <div id="dialogDiv"></div>
    <div id="menu" class="body_top" data-bind="template: {name: 'menu-template', data: application_data} "> </div>
    <div id="patientData" class="body_title" data-bind="template: {name: 'patient-data-template', data: application_data} "></div>
    <div class="body_title" data-bind="template: {name: 'tabs-controls', data: application_data} "> </div>

    <div class="mainFrames">
        <div id="framesDisplay" data-bind="template: {name: 'tabs-frames', data: application_data}"> </div>
    </div>
</div>
<script>
    $("#dialogDiv").hide();
    ko.applyBindings(app_view_model);

</script>