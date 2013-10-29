<?php
require_once($webserver_root."/interface/forms_knockout/db_operations.php");


function infant_sick_visit_report( $pid, $encounter, $cols, $id)
{ 
    $form_data=load_knockout_form(FRM_INF_SICK,$id);
    ?>
    <h1>To Do! Infant Sick Report</h1>
    <span><?php echo $form_data['uuid'] ?></span>
<?php
}
?>