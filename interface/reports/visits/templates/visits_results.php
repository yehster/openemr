<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/html" id="visits-results">
    <h1>Results</h1>
    <!-- ko if: report_type()=="Summary" -->
        <table>
            <thead>
                <tr data-bind="foreach: headers">
                    <th data-bind="text: $data"></th>
                </tr>
            </thead>
            <tbody data-bind="foreach: data_rows">
                <tr data-bind="foreach: $data">
                    <td data-bind="text: $data">
                    </td>

                </tr>
            </tbody>
        </table>
    <!-- /ko -->
    <!-- ko if: report_type()=="Providers" -->
        <table>
            <thead>
                <tr data-bind="foreach: headers">
                    <th data-bind="text: $data"></th>
                </tr>
            </thead>
            <tbody data-bind="foreach: data_rows">
                <tr data-bind="foreach: $data">
                    <td data-bind="text: $data==null ? 0 : $data"></td>

                </tr>
            </tbody>
        </table>
    <!-- /ko -->
</script>
