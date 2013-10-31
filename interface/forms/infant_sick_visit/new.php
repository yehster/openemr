<?php
require_once($webserver_root."/interface/forms_knockout/db_operations.php");

$form_data=new_knockout_form(FRM_INF_SICK,$_SESSION["encounter"],$_SESSION['authUser'],$_SESSION['authProvider'],$_SESSION['pid']);

require_once("infant_sick_common.php");


?>
<script>
    ko.applyBindings(view_model);
</script>