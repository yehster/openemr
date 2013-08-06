<?php
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../../globals.php");

session_write_close();
require_once("$srcdir/acl.inc");
require_once("../database_constants.php");

if(!acl_check('patients', 'demo'))
{
    header("HTTP/1.0 403 Forbidden");    
    echo "Not authorized for demographics";   
    return false;
}

if(!isset($_REQUEST['pid']))
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No PID specified";   
    return false;
}
$pid=$_REQUEST['pid'];
if(!isset($_REQUEST['mode']))
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No mode specified";   
    return false;    
}
$mode=$_REQUEST['mode'];
if($mode==="set")
{
    if(!isset($_REQUEST['kilos']))
    {
        header("HTTP/1.0 403 Forbidden");    
        echo "No weight specified";   
        return false;
    }

    if(!is_numeric($_REQUEST['kilos']))
    {
        header("HTTP/1.0 403 Forbidden");    
        echo "Non-numeric weight";   
        return false;    
    }
    $weight=floatval($_REQUEST['kilos']);    
    
    $sqlUpdateWeight="INSERT INTO ".TBL_STATS_BIRTH_WEIGHT." (".COL_PID.",".COL_WEIGHT_KILO.") VALUES (?,?) ".
                     " ON DUPLICATE KEY UPDATE ".COL_WEIGHT_KILO."=VALUES(".COL_WEIGHT_KILO.")";
    sqlStatement($sqlUpdateWeight,array($pid,$weight));
}
else if($mode==="get")
{
    $sqlGetBirthWeight="SELECT ".COL_WEIGHT_KILO." FROM ".TBL_STATS_BIRTH_WEIGHT.
                       " WHERE ".COL_PID."=?";
    $res=sqlQuery($sqlGetBirthWeight,array($pid));
    if($res)
    {
        echo $res[COL_WEIGHT_KILO];
    }
    else
    {
        echo 0;
    }
}
else
{
        header("HTTP/1.0 403 Forbidden");    
        echo "Invalid mode";   
        return false;   
}


?>
