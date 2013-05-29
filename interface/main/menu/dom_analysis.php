<?php

function recurse_menu_xml(&$parent,$child,$depth)
{
    if(!empty($child))
    {
        if(($child->nodeName!=="#text"))
        {   
            if($child->hasAttributes())
            {
                $entry=new menuitem();
                foreach($child->attributes as $attr)
                {
                    $attr_name=$attr->name;
                    $entry->$attr_name=$attr->value;

                }
                $parent->addChild($entry);
            }
            if($child->hasChildNodes())
            {
                foreach($child->childNodes as $sub)
                {
                    recurse_menu_xml($entry,$sub,$depth+1);
                }
            }
            return $entry;
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
        recurse_menu_xml($root,$child,0);
    }
    return $root->firstChild();
}
?>
