<?php
require_once("../../globals.php");
require_once("$srcdir/patient.inc");
require_once("../../../custom/code_types.inc.php");

require_once("queries/reportable_code.php");
require_once("queries/syndromic_queries.php");

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
for(idx=0;idx<reportable_codes.length;idx++)
{
    view_model.searchParameters.diag_options.push(reportable_codes[idx]);
    view_model.searchParameters.diags.push(reportable_codes[idx]);
}
view_model.reportParameters.facility_options=ko.observableArray(facilities);
</script>

<body class="body_top">
    <select data-bind="options:searchParameters.diag_options, selectedOptions: searchParameters.diags, optionsText: 'code'" size="3" multiple="true"></select>
    <span><?php echo xlt("From:");?></span>
    <input type='text' name='form_from_date' id="form_from_date"
    size='10' 
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' 
    title='yyyy-mm-dd'>
    <img src='../../pic/show_calendar.gif' align='absbottom' 
    width='24' height='22' id='img_from_date' border='0' 
    alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>

    <span><?php echo xlt("To:");?></span>
    <input type='text' name='form_to_date' id="form_to_date" 
    size='10' 
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' 
    title='yyyy-mm-dd'>
    <img src='../../pic/show_calendar.gif' align='absbottom' 
    width='24' height='22' id='img_to_date' border='0' 
    alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>
    <input type="button" value="<?php echo xla('Search') ?>" data-bind="event:{click: search_reportable}"/>

    <span><?php echo xlt("Reporting:")?></span>
    <select data-bind="options:reportParameters.facility_options, selected: reportParameters.reporting_facility, optionsText: 'name'"></select>
    <span><?php echo xlt("Event:")?></span>
    <select data-bind="options:reportParameters.facility_options, selected: reportParameters.event_facility, optionsText: 'name'"></select>
</body>
<script>
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
 ko.applyBindings(view_model);
</script>
</html>