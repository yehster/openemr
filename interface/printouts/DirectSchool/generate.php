<?php
    require_once("../../globals.php");
    $pid= isset($_REQUEST['pid']) ? $_REQUEST['pid']: $_SESSION['pid'];
    
    $find_form_id="SELECT encounter,form_id FROM forms WHERE formdir='note' AND pid=? ORDER BY form_id DESC LIMIT 1";
    $result=sqlQuery($find_form_id,array($pid));
    if($result)
    {
        $getVars="printable=1&note_".$result['form_id']."=".$result['encounter'];
        $newLocation="$webroot/interface/patient_file/report/custom_report.php?".$getVars;    
        echo $newLocation;        
    }
    else
    {
        echo "Not Found!";    
    }
?>
