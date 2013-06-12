<?php

function embed_pdf($src)
{
    $src=$GLOBALS['webroot']."/interface/printouts/pdfDisplay.php?filename=".$src;
    $html="";
    $html.="<iframe style='width: 1024px; height: 768px' src='".$src."'></iframe>";
    return $html;
    
}
?>
