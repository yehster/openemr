<script type='text/javascript'>
    var msgAddPat='<?php xl('You must first select or add a patient.','e') ?>';
    var msgSelEnc='<?php xl('You must first select or create an encounter.','e') ?>';
    var pathWebroot='<?php echo "$web_root/interface/" ?>';
</script>
<script type='text/javascript' src="tabs/setup.js"></script>
<script type='text/javascript' src="tabs/tabs_view_model.js"></script>
<?php
    require_once("menu/menuitem.php");
    require_once("menu/dom_analysis.php");
    require_once("menu/templates/menu_base.php");
    $xmlsrc="/var/www/openemr/interface/main/analysis/menu.xml";
    $menu_root=build_menu($xmlsrc);
?>

<script>
    var menu_def=<?php echo json_encode($menu_root);?>;
</script>
<script type='text/javascript' src="menu/js/menu_view_model.js"></script>
<script type='text/javascript' src="menu/js/menu_setup.js"></script>
<link rel="stylesheet" type="text/css" href="menu/css/menu_base.css"/>