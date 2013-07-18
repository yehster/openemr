<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/html" id="schedules-codes">
    <table>
        <tbody data-bind="foreach: $data">
            <tr>
                <td data-bind="text:description, event: {click: choose_imm_entry}"></td>
            </tr>
        </tbody>
    </table>
</script>
