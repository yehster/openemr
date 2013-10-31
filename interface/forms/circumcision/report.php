<?php
require_once($webserver_root."/interface/forms_knockout/db_operations.php");
require_once($webserver_root."/interface/forms_knockout/review_document_common.php");

function circumcision_report( $pid, $encounter, $cols, $id)
{ 
    $form_data=load_knockout_form(FRM_CIRCUMCISION,$id);
    include_knockout_dependencies("circumcision",$form_data);
    ?>

<?php
}
?>