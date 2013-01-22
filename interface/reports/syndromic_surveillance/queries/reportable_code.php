<?php

class reportable_code {

    public function __construct($id,$code,$code_type,$description)
    {
        $this->id=$id;
        $this->code=$code;
        $this->code_type=$code_type;
        $this->description=$description;
        $this->code_key="ICD9";
    }
    public $id;
    public $code;
    public $code_type;
    public $description;
}
class facility
{
    public function __construct($name,$npi)
    {
        $this->name=$name;
        $this->npi=$npi;
    }
    public $name;
    public $npi;
}
?>
