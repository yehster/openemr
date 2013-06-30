<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("../../interface/globals.php");
echo session_regenerate_id();
echo session_id(); 


?>
<script>
    var oemr_session_name = '<?php echo session_name(); ?>';
    var oemr_session_id   = '<?php echo session_id(); ?>';
    document.cookie = oemr_session_name + '=' + oemr_session_id + '; path=/';
    window.location='<?php echo $webroot;?>/interface/main/main_screen.php?patientID=1';
</script>