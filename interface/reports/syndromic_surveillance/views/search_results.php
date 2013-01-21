<?php
/**
* knockout template to display the events found in an SS query
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
<script type="text/html" id="search_results">    
    <table class="results" data-bind="visible:encounter_info().length>0">
        <thead>
            <tr>
                <th>PID</th>
                <th>ENC</th>
                <th>List ID</th>
                <th>Diagnosis</th>
            </tr>
        </thead>
        <tbody data-bind="foreach: $data.encounter_info">
            <tr data-bind="event: {click: choose_event}">
                <td data-bind="text:pid"></td>
                <td data-bind="text:encounter"></td>
                <td data-bind="text:list_id"></td>
                <td data-bind="text:diagnosis_code"></td>
            </tr>
        </tbody>
    </table>
</script>

