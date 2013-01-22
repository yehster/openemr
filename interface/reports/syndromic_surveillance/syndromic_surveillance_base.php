<?php
require_once("../../globals.php");
require_once("$srcdir/patient.inc");
require_once("../../../custom/code_types.inc.php");

require_once("queries/reportable_code.php");
require_once("queries/syndromic_queries.php");
require_once("views/search_parameters.php");
require_once("views/report_parameters.php");
?>
<html>
    <header>
        <Title><?php echo xlt("Syndromic Surveillance");?></Title>
    </header>
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<style type="text/css">@import url(<?php echo $web_root;?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $web_root;?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $web_root;?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $web_root;?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $web_root;?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $web_root;?>/library/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo $web_root;?>/library/js/knockout/knockout-2.2.0.js"></script>
<script type="text/javascript" src="viewmodel/syndromic_surveillance_vm.js"></script>

<script>
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>
var mypcc=1;
var view_model=new ss_view_model();
var reportable_codes=<?php echo json_encode(get_reportable_codes()); ?>;
var facilities=<?php echo json_encode(get_facilities()); ?>;
view_model.searchParameters.diag_options=ko.observableArray(reportable_codes);
view_model.searchParameters.diags=ko.observableArray(reportable_codes);
view_model.reportParameters.facility_options=ko.observableArray(facilities);
</script>

<body class="body_top">
    <div data-bind="template: {name: 'search_parameters', data: searchParameters}"></div>
    <div data-bind="template: {name: 'report_parameters', data: reportParameters}"></div>
</body>
<script>
 ko.applyBindings(view_model);
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>
</html>