<?php

class reportable_code {

    public function __construct($id,$code,$code_type,$description,$code_key="ICD9")
    {
        $this->id=$id;
        $this->code=$code;
        $this->code_type=$code_type;
        $this->description=$description;
        $this->code_key=$code_key;
    }
    public $id;
    public $code;
    public $code_type;
    public $description;
    public $code_key;
    public function dbKey()
    {
        return $this->code_key.":".$this->code;
    }
}

class event
{
    public $list_id;
    public $encounter;  
    public $encounter_date;
    public $pid;
    public $description;
    public $diagnosis_code;
    
    public function __construct($list_id,$encounter,$encounter_date,$pid,$desc,$diagnosis)
    {
        $this->list_id=$list_id;
        $this->encounter=$encounter;
        $this->encounter_date=$encounter_date;
        $this->pid=$pid;
        $this->description=$desc;
        $this->diagnosis_code=$diagnosis;
    }
}
?>
