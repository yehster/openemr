<?php
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../globals.php");
require_once($GLOBALS['webserver_root']."/interface/forms_knockout/db_operations.php");

function parse_request($parameter)
{
    if(!isset($_REQUEST[$parameter]))
    {
        header("HTTP/1.0 500 Forbidden");
        echo "Parameter Not Set:".$parameter;
        exit();
    }
    else
    {
        $GLOBALS[$parameter]=$_REQUEST[$parameter];    
    }
}
parse_request('formname');
parse_request('json');
parse_request('uuid');
update_knockout_form($formname,$uuid,$json);
?>
