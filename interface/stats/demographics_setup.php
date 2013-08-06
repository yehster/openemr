<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script>
    pid='<?php echo $pid;?>';
</script>

<script src="<?php echo $web_root;?>/library/js/knockout/knockout-2.3.0.js"></script>
<script src="<?php echo $web_root;?>/interface/stats/demographics_setup.js"></script>
<?php require_once($include_root."/stats/birth_weight/birth_weight.php"); ?>