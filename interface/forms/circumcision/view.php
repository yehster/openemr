<?php
$id=$_REQUEST['id'];
require_once($webserver_root."/interface/forms_knockout/db_operations.php");

$form_data=load_knockout_form(FRM_CIRCUMCISION,$id);

?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<body class="body_top">
<?php
require_once("circumcision_common.php");

?>
<script>
        var loaded=<?php echo $form_data['json'];?>;
        apply_to_view(view_model,loaded);
        ko.applyBindings(view_model);
</script>
</body>
