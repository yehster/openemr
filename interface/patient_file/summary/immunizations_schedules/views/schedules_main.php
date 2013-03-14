<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/html" id="schedules-main">
    <div>
        <select name="schedule_choice" data-bind="options: schedules, value: scheduleChoice,optionsText:'description'"></select>
        <span id="scheduleCodes" data-bind="template: {name: 'schedules-codes', data: scheduleCodes}"></span>
    </div>
</script>