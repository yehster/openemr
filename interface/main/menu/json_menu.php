<?php
require_once("menuitem.php");
require_once("dom_analysis.php");

$xmlsrc="/var/www/openemr/interface/main/analysis/menu.xml";

$menu_root=build_menu($xmlsrc);

echo json_encode($menu_root);
?>
