<?php
require_once($webserver_root."/interface/forms_knockout/db_operations.php");
require_once($webserver_root."/interface/forms_knockout/review_document_common.php");

function infant_sick_visit_report( $pid, $encounter, $cols, $id)
{ 
    $form_data=load_knockout_form(FRM_INF_SICK,$id);
    include_knockout_dependencies(DIR_INF_SICK,$form_data);
    ?>
<?php
}
?>