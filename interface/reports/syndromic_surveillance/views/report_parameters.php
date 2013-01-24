<script type="text/html" id="report_parameters">
    <select data-bind="options:type_options, value: type"></select>
    <span><?php echo xlt("Reporting:")?></span>
    <select data-bind="options:facility_options, value: reporting_facility, optionsText: 'name'"></select>
    <span><?php echo xlt("Event:")?></span>
    <select data-bind="options:facility_options, value: event_facility, optionsText: 'name'"></select>
    <span>Encounter#</span>
    <span data-bind="text: encounter.encounter_id()"></span>
    <div>
        <span class="label">Admit Date/Time</span>
        <input type="text" data-bind="value: admit_date_time" title="format: YYYYMMDDHHMMss"></input>
        <span class="label">Discharge Date/Time</span>
        <input type="text" data-bind="value: discharge_date_time" title="format: YYYYMMDDHHMMss"></input>
    </div>
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
    <input type="button" value="<?php echo xla('Generate HL7'); ?>" data-bind="event:{click: generate_hl7}"/>
</script>