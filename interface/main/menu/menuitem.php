<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of menuitem
 *
 * @author yehster
 */
$target_map=["Cal"=>0,"RTop"=>1,"RBot"=>2];
class menuitem implements JsonSerializable
{
    protected $children;
    function __construct()
    {
        $this->children=array();
    }
    public function addChild(&$entry)
    {
        array_push($this->children,$entry);
    }
    
    public function jsonSerialize()
    {
        $retval = array();
        if(isset($this->url))
        {
            $retval['url']=$this->url;
        }
        if(isset($this->description))
        {
            $retval['description']=$this->description;
        }
        if(isset($this->type))
        {
            $retval['type']=$this->type;
        }
        if(isset($this->requirement))
        {
            $retval['requirement']=$this->requirement;
        }
        if(isset($this->target))
        {
            
            $retval['target']=$GLOBALS['target_map'][$this->target];
        }

        
        if(property_exists($this,'dynamic') && $this->dynamic=="show forms")
        {
            //handle the dynamic forms differently in the future.
//            $retval['children']=array();
//                foreach($this->children as $child)
                {
//                    array_push($retval['children'],$child);
                }                
        }
        else
        {
            if(count($this->children)>0)
            {
                $retval['children']=array();
                foreach($this->children as $child)
                {
                    array_push($retval['children'],$child);
                }                
            }
        }
        return $retval;
    }
    
    public function firstChild()
    {
        return $this->children[0];
    }
}

?>
