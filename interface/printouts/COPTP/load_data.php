<?php
function process_language_choice(&$patient_info,$language)
{
    $language=strtolower($language);
    if($language=="")
    {
        $language="english";
    }
    $language_options=array("english"=>1,"spanish"=>1,"vietnamese"=>1,"farsi"=>1);
    if(isset($language_options[$language]))
    {
        set_checkbox($patient_info,"language",$language);
    }
    else
    {
        set_checkbox($patient_info,"language","other");
        $patient_info['language_other_text']=  ucfirst($language);
    }
}

function process_education_request(&$patient_info,$education_request)
{
    if(($education_request=="diabetes") || ($education_request=="weight"))
    {
        set_checkbox($patient_info,"education_request",$education_request);    
    }
    else if($education_request!="none")
    {
        set_checkbox($patient_info,"education_request","other");    
        $patient_info['education_request_text']=$education_request;
    }
}
?>
