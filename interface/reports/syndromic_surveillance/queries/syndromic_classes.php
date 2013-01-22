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
