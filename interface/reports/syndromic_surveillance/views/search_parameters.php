<?php
/**
* knockout template to display search options for SS
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
