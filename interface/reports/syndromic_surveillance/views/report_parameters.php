<?php
/**
* knockout template to choose information for HL7 message
*
* Copyright (C) 2013 Kevin Yeh <kevin.y@integralemr.com>
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
* @author Kevin Yeh <kevin.y@integralemr.com>
* @link http://www.open-emr.org
*/
?>

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