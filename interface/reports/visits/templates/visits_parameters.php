<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/html" id="visits-parameters">
    <select data-bind="options: period_options,optionsText: 'description', value: period_size"></select>
    <table>
        <tbody>
            <tr>
                <td>
                    <div>Clinics&nbsp;<span data-bind="visible:(!clinics_details())">Summary Only</span></div>
                    <input type="checkbox" data-bind="checked:clinics_details"/>
                    <?php echo xlt("Display Details") ?>
                    <div data-bind="foreach: clinics, visible:clinics_details">
                        <div>
                            <input type="checkbox" data-bind="checked: selected"/>
                            <span data-bind="text: name"></span>
                        </div>
                    </div>
                </td>
                <td>
                    <div>Providers&nbsp;<span data-bind="visible:(!providers_details())">Summary Only</span></div>
                    <input type="checkbox" data-bind="checked:providers_details"/>
                    <?php echo xlt("Display Details") ?>
                    <div data-bind="foreach: providers, visible:providers_details">
                        <div>
                            <input type="checkbox" data-bind="checked: selected"/>
                            <span data-bind="text: lname"></span>,<span data-bind="text: fname"></span>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <button data-bind="click: search_visits">Search</button>
</script>
