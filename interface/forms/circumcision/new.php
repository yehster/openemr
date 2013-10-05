<?php
require_once($webserver_root."/interface/forms_knockout/db_operations.php");

$form_ids=new_knockout_form(FRM_CIRCUMCISION,$_SESSION["encounter"],$_SESSION['authUser'],$_SESSION['authProvider'],$_SESSION['pid']);

require_once("circumcision_common.php");


?>

<script>
    view_model.ebl.value("<5");
</script>    