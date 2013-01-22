<?php
class HL7_SS_ADT
{
    // One of A01, A03, A04, A08
    protected $type;
    
    // ADT_A01 for A01, A04, A08: ADT_A03 for A03
    protected $constrained_message_structure;
    protected $hl7;
    
    public $msh;
    public $evn;
    public $pid;
    public $pv1;
    
    protected $processing_id;
    protected $message_control_id;
    function __construct($type,$message_control_id,$proc_id='D')
    {
        $this->type=$type;
        if($type=="A03")
        {
            $this->constrained_message_structure="ADT_A03";
        }
        else
        {
            $this->constrained_message_structure="ADT_A01";            
        }
        $this->hl7=new hl7_message();
        
        $this->msh=$this->hl7->addSegment("MSH");
        $this->setupMSH();
        
        $this->evn=$this->hl7->addSegment("EVN");

        $this->pid=$this->hl7->addSegment("PID");
        $patientNameRepeat=$this->pid->getField(5)->getRepeat(1);
        $patientNameRepeat->setComponent(7,"S");
        $this->pv1=$this->hl7->addSegment("PV1");
     
        $this->processing_id=$proc_id;
        $this->message_control_id=$message_control_id;
        $this->setupMSH();
        $this->setupPID();
    }
    function setupMSH()
    {
        $this->msh->setField(3,"OPENEMR");
        $this->msh->setField(9,"ADT",$this->type,$this->constrained_message_structure);
        $this->msh->setField(10,$this->message_control_id);
        $this->msh->setField(11,$this->processing_id);
        $this->msh->setField(12,"2.5.1");
        
    }

    function setupPID()
    {
        $this->pid->setField(1,"1"); // Only a single PID segment is allowed, so the Set ID should always be 1;
    }
    
    function toString()
    {
        return $this->hl7->toString();
    }
}

?>
