<?php
require_once($webserver_root."/interface/forms_knockout/db_operations.php");


function circumcision_report( $pid, $encounter, $cols, $id)
{ 
    $form_ids=load_knockout_form(FRM_CIRCUMCISION,$id);
    ?>
    <h1>To Do! Circumcision Report</h1>
    <span><?php echo $form_ids['uuid'] ?></span>
<?php
}
?>