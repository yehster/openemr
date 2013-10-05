<?php
$id=$_REQUEST['id'];
require_once($webserver_root."/interface/forms_knockout/db_operations.php");

$form_ids=load_knockout_form(FRM_CIRCUMCISION,$id);

?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<body class="body_top">
<?php
require_once("circumcision_common.php");

?>
</body>
