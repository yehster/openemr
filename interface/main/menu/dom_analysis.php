<?php

function recurse_menu_xml(&$parent,$child)
{
    if(!empty($child))
    {
        if($child->hasAttributes())
        {
            foreach($child->attributes as $attr)
            {
                echo $attr->name.":".$attr->value . "<br>";
                error_log( $attr->name.":".$attr->value);

            }            
        }
        if($child->hasChildNodes())
        {
            foreach($child->childNodes as $sub)
            {
                recurse_menu_xml($parent,$sub);
            }
//            for($idx=0;$idx<$child->childNodes->length;$idx++)
//            {
//                recurse_menu_xml($root,$child->childNodes->item($idx));
//            }                   
        }
    }
    
}
function build_menu($filename)
{
    $DOM=new DOMDocument('1.0', 'iso-8859-1');
    $DOM->load($filename);
    $root=new menuitem();
    foreach($DOM->childNodes as $child)
    {
        recurse_menu_xml($root,$child);
    }
    return $root;
}
?>
