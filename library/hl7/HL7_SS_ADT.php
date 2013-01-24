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
    public $pv2;
    public $dg1;
    public $pr1;
    public $obx;
    public $in1;        
    
    protected $processing_id;
    protected $message_control_id;
    function __construct($type,$message_control_id,$proc_id='D')
    {
        $this->type=$type;
        $this->hl7=new hl7_message();
        
        $this->msh=$this->hl7->addSegment("MSH");
        
        $this->evn=$this->hl7->addSegment("EVN");

        $this->pid=$this->hl7->addSegment("PID");

        $this->pv1=$this->hl7->addSegment("PV1");

        $this->pv2=$this->hl7->addSegment("PV2");

        if($type=="A03")
        {
            $this->dg1=$this->hl7->addSegment("DG1");
            $this->pr1=$this->hl7->addSegment("PR1");
            $this->obx=$this->hl7->addSegment("OBX");
            $this->constrained_message_structure="ADT_A03";
        }
        else
        {
            $this->obx=$this->hl7->addSegment("OBX");
            $this->dg1=$this->hl7->addSegment("DG1");
            $this->pr1=$this->hl7->addSegment("PR1");
            $this->constrained_message_structure="ADT_A01";            
        }
        
        
        $this->in1=$this->hl7->addSegment("IN1");

        $this->processing_id=$proc_id;
        $this->message_control_id=$message_control_id;
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
        $patientNameRepeat=$this->pid->getField(5)->getRepeat(2);
        $patientNameRepeat->setComponent(7,"S");  // Transmitting a blank name per the suggestion in the documentation
    }

    function setupEVN()
    {
        
    }
    function setupPV1()
    {
        $this->pv1->setField(1,"1");
    }
    
    function setupOBX()
    {
        $this->obx->setField(1,"1");
    }

    function applyData()
    {
        $this->setupMSH();
        $this->setupEVN();
        $this->setupPID();
        $this->setupPV1();
        $this->setupOBX();

    }
    function toString()
    {
        return $this->hl7->toString();
    }
}

?>
