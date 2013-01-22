<script type="text/html" id="report_parameters">
    <span><?php echo xlt("Reporting:")?></span>
    <select data-bind="options:facility_options, selected: reporting_facility, optionsText: 'name'"></select>
    <span><?php echo xlt("Event:")?></span>
    <select data-bind="options:facility_options, selected: event_facility, optionsText: 'name'"></select>
</script>