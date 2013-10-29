<?php
$id=$_REQUEST['id'];
require_once($webserver_root."/interface/forms_knockout/db_operations.php");

$form_data=load_knockout_form(FRM_INF_SICK,$id);

?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<body class="body_top">
<?php
require_once("infant_sick_common.php");

?>
</body>
