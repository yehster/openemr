<?php
if(isset($_REQUEST['menu']))
{
    $menu_xml=$_REQUEST['menu'];
    error_log($menu_xml);
    echo $menu_xml;
    file_put_contents(getcwd()."/menu.xml",$menu_xml);
}


?>
