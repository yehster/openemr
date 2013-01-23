<script type="text/html" id="search_parameters">    
    <select data-bind="options:diag_options, selectedOptions: diags, optionsText: 'code'" size="3" multiple="true"></select>
    <span><?php echo xlt("From:");?></span>
    <input type='text' name='form_from_date' id="form_from_date"
    size='10' 
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' 
    title='yyyy-mm-dd'>
    <img src='../../pic/show_calendar.gif' align='absbottom' 
    width='24' height='22' id='img_from_date' border='0' 
    alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>

    <span><?php echo xlt("To:");?></span>
    <input type='text' name='form_to_date' id="form_to_date" 
    size='10' 
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' 
    title='yyyy-mm-dd'>
    <img src='../../pic/show_calendar.gif' align='absbottom' 
    width='24' height='22' id='img_to_date' border='0' 
    alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>
    <input id="search" type="button" value="<?php echo xla('Search') ?>" data-bind="event:{click: search_reportable}"/>    
</script>
