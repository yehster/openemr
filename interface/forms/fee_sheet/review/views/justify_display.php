<?php
/**
 * knockoutjs template for rendering the interface for justifying procedures
 * 
 * Copyright (C) 2013 Kevin Yeh <kevin.y@integralemr.com> and OEMR <www.oemr.org>
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
<script type="text/html" id="justify-display">
    <div data-bind="visible: $data.show">
        <div class="duplicate_warning" data-bind="visible: $data.duplicates().length>0">
            <span data-bind="event:{click: toggle_warning_details}" class="problem_warning" title="<?php echo xla('Click for more details') ?>"><?php echo xlt("Warning, patient has ambiguous codes in the problems list!") ?></span>
            <div data-bind="visible: show_warning_details">
                <span data-bind="foreach: $data.duplicates">
                    <div data-bind="text: description() +':'+ code_type() + '|' + code()"></div>
                </span>
            </div>
        </div>
        <span>
            <span data-bind="visible: diag_code_types.length>0">
                <input value= type="text" data-bind="value: search_query, valueUpdate: 'afterkeydown', event:{focus:search_focus, blur: search_blur}, hasfocus: search_has_focus"/>
                <span class="search_results" data-bind="visible: (search_results().length>0) && search_show">
                    <table>
                        <tbody data-bind="foreach: $data.search_results">
                            <tr>
                                <td data-bind="text:description, event:{click: function(data,event){return choose_search_diag(data,event,$parent)}}"></td>
                                <td data-bind="text:code, attr:{title:code_type}"></td>
                            </tr>
                        </tbody>
                    </table>
                </span> 
                <select data-bind="value: searchType, options: diag_code_types, optionsText: 'key'"></select>
            </span>
            <input type="checkbox" data-bind="checked:create_problems" title="<?php echo xla('Automatically create new problems for diagnoses.')?>"/>
            <span><?php echo xlt("Create Problems")?></span>
        </span>
        <table>
            <thead>
                <tr>
                    <th class='sort' data-bind="event: {click: sort_justify}" title="<?php echo xla('Click to sort') ?>">#</th>
                </tr>
            </thead>
            <tbody data-bind="foreach: $data.diagnosis_options">
                <tr data-bind="attr:{class: source, encounter_issue: encounter_issue}">
                    <td class="priority" data-bind="text: priority()!=99999 ? priority() : ''"/></td>
                    <td class="checkbox"><input type="checkbox" data-bind="checked: selected, event:{click: function(data,event){return check_justify(data,event,$parent);}}" /></td>
                    <td class="info" data-bind="text: code, attr:{title:code_type}"></td>
                    <td class="info" data-bind="text: description"></td>
                </tr>
            </tbody>
        </table>
        <div>
            <input type="button" data-bind="click: update_justify" value="<?php echo xlt("Update");?>"/>
            <input class="cancel_dialog" type="button" data-bind="click: cancel_justify" value="<?php echo xla("Cancel");?>"/>
        </div>
    </div>
</script>