<?php
require_once("../globals.php");
require_once("directory_definitions.php");

//Prefix of allowable files to be viewed in this manner.
$prefixes=array("GrowingUpHealthy_","WIC_","COPTP_");
if(isset($_REQUEST['filename']))
{
    $filename=$_REQUEST['filename'];
    // don't allow path changes
    if(strpos($filename,"/")!==false)
    {
        exit();
    }

    // confirm this is a .pdf
    $pdf_loc=strpos($filename,".pdf");
    if($pdf_loc!==strlen($filename)-4)
    {
        exit();
    }
    $valid=false;
    foreach($prefixes as $prefix)
    {
        if(strpos($filename,$prefix)===0)
        {
            $valid=true;
        }
    }
    if(!$valid)
    {
        echo "Attempt to view unrecognized file prefix.";
        exit();
    }
}
else
{
    exit();
}
$filename=PDF_OUTPUT_DIR.$_REQUEST['filename'];
error_log($filename);
header('Content-type: application/pdf'); 
readfile($filename);
unlink($filename);
?>
