<?php

function root_file()
{
    $backtrace=debug_backtrace();
    $top=$backtrace[count($backtrace)-1];
    $pos=strpos($top['file'],$GLOBALS['webserver_root']);
    if($pos!==false)
    {
        return substr($top['file'],$pos+strlen($GLOBALS['webserver_root']));
    }
}

function strict_mode_off()
{
    $GLOBALS['adodb']['db']->ExecuteNoLog("SET SESSION sql_mode=''");
}
$strict_override_files=array();

// Begin list of files to disable strict mode
$strict_override_files["/interface/usergroup/usergroup_admin.php"]=true;

// End list of files to disable strict mode

if(array_key_exists(root_file(), $strict_override_files))
{
    strict_mode_off();
}
?>