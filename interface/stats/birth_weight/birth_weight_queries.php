<?php
require_once("database_constants.php");
function get_birth_weight($pid)
{
    $sqlGetBirthWeight="SELECT ".COL_WEIGHT_KILO." FROM ".TBL_STATS_BIRTH_WEIGHT.
                       " WHERE ".COL_PID."=?";
    $res=sqlQuery($sqlGetBirthWeight,array($pid));
    if($res)
    {
       $retval=array();
       $retval['kg']=$res[COL_WEIGHT_KILO];
       $retval['pounds']=$res[COL_WEIGHT_KILO]*2.204;
       $ounces_total=$retval['pounds']*16;
       $retval['pounds_int']=floor($ounces_total/16);
       $retval['ounces']=round($ounces_total-($retval['pounds_int']*16));
       return $retval;
    }
    else
    {
        return false;
    }
}
?>
