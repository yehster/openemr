<?php
require_once("../../globals.php");
require_once("$srcdir/patient.inc");
require_once("../../../custom/code_types.inc.php");


?>
<html>
    <header>
        <Title><?php echo xlt("Syndromic Surveillance");?></Title>
    </header>
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<style type="text/css">@import url(<?php echo $web_root;?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $web_root;?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $web_root;?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $web_root;?>/library/textformat.js"></script>
<script>
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>
var mypcc=1;
</script>

<body class="body_top">
    <span><?php echo xlt("From:");?></span>
    <input type='text' name='form_from_date' id="form_from_date"
    size='10' value='<?php echo $form_from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' 
    title='yyyy-mm-dd'>
    <img src='../../pic/show_calendar.gif' align='absbottom' 
    width='24' height='22' id='img_from_date' border='0' 
    alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>

    <span><?php echo xlt("To:");?></span>
    <input type='text' name='form_to_date' id="form_to_date" 
    size='10' value='<?php echo $form_to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' 
    title='yyyy-mm-dd'>
    <img src='../../pic/show_calendar.gif' align='absbottom' 
    width='24' height='22' id='img_to_date' border='0' 
    alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>   
</body>
<script>
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>
</html>