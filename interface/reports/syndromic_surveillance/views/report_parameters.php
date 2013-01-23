<script type="text/html" id="report_parameters">
    <span><?php echo xlt("Reporting:")?></span>
    <select data-bind="options:facility_options, selected: reporting_facility, optionsText: 'name'"></select>
    <span><?php echo xlt("Event:")?></span>
    <select data-bind="options:facility_options, selected: event_facility, optionsText: 'name'"></select>
    <div data-bind="with: patient">
        <span data-bind="text: fname"></span>
        <span data-bind="text: lname"></span>
        <span>Sex:</span>
        <span data-bind="text: sex"></span>
        <div>
            <span class="label">DOB:</span>
            <span data-bind="text: DOB"></span>
            <span class="label">External ID:</span>
            <span data-bind="text: pubpid"></span>
            <span class="label">Age:</span>            
            <span data-bind="text: age"></span>

        </div>
    </div>
    <div data-bind="template: {name: 'encounter_info', data: $data.encounter}"></div>
</script>