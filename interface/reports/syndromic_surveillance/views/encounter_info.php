<?php
/**
* knockout template to for display of encounter specifc details in SS report
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

<script type="text/html" id="encounter_info">
    <div>
        <div class="label">Chief Complaint</div>
        <textarea class="chief_complaint" data-bind="value: reason"></textarea>
    </div>
    <table>
        <thead>
            <tr>
                <th>Diagnosis</th>
                <th>Code</th>
            </tr>
        </thead>
        <tbody data-bind="{foreach: diagnoses}">
            <tr>
                <td data-bind="text:description"></td>
                <td data-bind="text:code"></td>
                <td><select data-bind="value:diagnosis_type, options: ['A','W','F']"></select></td>
            </tr>
        </tbody>
    </table>
</script>