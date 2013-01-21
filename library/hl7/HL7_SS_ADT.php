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
    
    function __construct($type)
    {
        $this->type=$type;
        $this->hl7=new hl7_message();
        
        $this->msh=&$this->hl7->addSegment("MSH");
        $this->setupMSH();
        
        $this->evn=&$this->hl7->addSegment("EVN");

        $this->pid=&$this->hl7->addSegment("PID");
        $this->pv1=&$this->hl7->addSegment("PV1");
        
        $this->setupMSH();
    }
    function setupMSH()
    {
        $this->msh->setField(4,"FOO BAR","1234567890","NPI");
        $this->msh->setField(3,"OPENEMR");
        
    }
    
    function toString()
    {
        return $this->hl7->toString();
    }
}

?>
