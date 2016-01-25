<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$tabs=true;
if(isset($_REQUEST['tabs']))
{
    if($_REQUEST['tabs']='false')
    {
        $tabs=false;
    }
}
if ($tabs===true)
{
    $tabs_base_url=$web_root."/interface/main/tabs/main.php";
    header('Location: '.$tabs_base_url);
    exit();
}
?>