<?php
function set_checkbox(&$patient_info,$field,$value)
{
    $field_key=$field."_".$value;
    $patient_info[$field_key]="X";
}

?>
