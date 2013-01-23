<?php

define('SEP_FIELD','|');
define('SEP_COMPONENT','^');
define('SEP_SUB_COMP','&');
define('SEP_REPETITION','~');
define('HL7_ESCAPE','\\');
define('SEP_SEG',"\r");

class hl7_message
{
    
    protected $segments;
    
    public function __construct()
    {
        $this->segments=array();
    } 
    public function addSegment($segName)
    {
        $newSeg=new hl7_segment($segName);
        $this->segments[]=$newSeg;
        return $newSeg;
    }
    public function getSegments($segName)
    {
        return $this->segments[$segName];
    }
    
    public function toString()
    {
        $retval="";
        foreach($this->segments as $seg)
        {
            $strSeg=$seg->toString();
            if($strSeg!="")
            {
                $retval.=$strSeg.SEP_SEG;            
            }
        }
        return $retval;
    }
}

class hl7_segment
{
    protected $ID;

    protected $repeats;
    protected $repeatIdx;
    
    // 1(one) based index of fields (1 based to correspond to HL7 Specs more easily);
    protected $fields;
    public function __construct($ID)
    {
        if($ID=="MSH")
        {
            $this->fields=array(1=>new hl7_field(""),2=>new hl7_field("^~\&"),3=>new hl7_field(""));        
        }
        else
        {
            $this->fields=array(1=>new hl7_field(""));
        }
        $this->ID=$ID;
        $this->repeats=array();
        $this->repeatIdx=1;
    }
    public function getID()
    {
        return $this->ID;
    }
    
    public function &getField($field_index)
    {
        if(count($this->fields)<$field_index)
        {
            for($idx=count($this->fields)+1;$idx<=$field_index;$idx++)
            {
                $this->fields[$idx]=new hl7_field("");
            }
        }
        return $this->fields[$field_index];
    }
    
    public function setField()
    {
        // First argument is the field index
        // Subsequent arguments are the component values
        $field_index=func_get_arg(0);
        $params=array();
        for($idx=1;$idx<func_num_args();$idx++)
        {
            $params[]=func_get_arg($idx);
        }
        call_user_func_array(array($this->getField($field_index),'setComponents'),$params);
    }   

    public function getRepeat($repeatIdx)
    {
        if($repeatIdx==1)
        {
            return $this;
        }
        else
        {
            $arrayIdx=$repeatIdx-1;
            $numRepeats=count($this->repeats);
            for($count=$numRepeats+1;$count<=$arrayIdx;$count++)
            {
                $this->repeats[$count]=new hl7_segment($this->ID);
                $this->repeats[$count]->repeatIdx=$count+1; // Number the repeat segment
            }
            return $this->repeats[$arrayIdx];
            
        }
    }
    public function getRepeatIdx()
    {
        return $this->repeatIdx;
    }
    public function toString()
    {
        if((count($this->fields)==1) && (count($this->repeats)==0))
        {
            if($this->fields[1]->toString()=="")
            {
                return "";
            }
        }
        $retval=$this->ID;
        
        foreach($this->fields as $key=>$field)
        {
            if(!($this->ID=="MSH" && $key==1))
            {
                $retval.=SEP_FIELD.$field->toString();
                
            }
        }
        foreach($this->repeats as $repeat)
        {
            $retval.=SEP_SEG.$repeat->toString();
        }
        return $retval;
    }
    
}

class hl7_field
{
    // 1(one) based array of the components
    protected $components;
    protected $repeated_values;
    public function __construct($val)
    {
        $this->components=array(1=>new hl7_component($val));
        $this->repeated_values=array();
    }
    public function setComponents()
    {
        $this->components=array();
        for($idx=0;$idx<func_num_args();$idx++)
        {
            $this->components[$idx+1]=new hl7_component(func_get_arg($idx));
        }
    }

    public function setComponent($idx,$val)
    {
        $numComps=count($this->components);
        if($idx>$numComps)
        {
            for($counter=$numComps+1;$counter<=$idx;$counter++)
            {
                $this->components[$counter]=new hl7_component("");
            }
        }
        $this->components[$idx]->setVal($val);
        
    }
    public function getRepeat($repeatNumber)
    {
        $num_repeats=count($this->repeated_values);
        if($repeatNumber>$num_repeats)
        {
            for($counter=$num_repeats+1;$counter<=$repeatNumber;$counter++)
            {
                $this->repeated_values[$counter]=new hl7_field("");
            }
        }
        return $this->repeated_values[$repeatNumber];
    }
    public function toString()
    {
        $retval="";
        for($idx=1;$idx<=count($this->components);$idx++)
        {
            if($idx>1)
            {
                $retval.=SEP_COMPONENT;
            }
            $retval.=$this->components[$idx]->toString();
        }
        foreach($this->repeated_values as $rv)
        {
            $retval.=SEP_REPETITION.$rv->toString();
        }
        return $retval;
    }    
}
class hl7_component
{
    protected $value;
    public function __construct($val)
    {
        $this->value=$val;
    }
    public function setVal($val)
    {
        $this->value=$val;
    }
    public function toString()
    {
        return $this->value;
    }
}
?>
