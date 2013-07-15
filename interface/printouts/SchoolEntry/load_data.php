<?php

function process_tb_data(&$patient_info,&$tb_data)
{
    foreach($tb_data as $entry)
    {
        if($entry['name']=='TB_Risk')
        {
            if($entry['value']=='NO')
            {
                $patient_info['tb-info']='Low Risk';
            }
            else
            {
                $patient_info['tb-info']='See Below';
            }
        }
    }
}

function process_physical(&$patient_info,$pid)
{
    $select="SELECT DATE_FORMAT(form_encounter.date,'%m/%d/%Y') as date FROM billing,form_encounter  where code_type='ICD9' and code='V20.2'"
            ." AND billing.pid=? AND form_encounter.pid=?"
            ." AND billing.encounter=form_encounter.encounter"
            ." ORDER by form_encounter.date desc "
            ." LIMIT 1";
    $data=sqlQuery($select,array($pid,$pid));
    if($data)
    {
        $date_parts=explode("/",$data['date']);
        $entries=array("history-date","pe-date","dental-date","nutrition-date","developmental-date");
        foreach($entries as $entry)
        {
            $patient_info[$entry."_month"]=$date_parts[0];
            $patient_info[$entry."_day"]=$date_parts[1];
            $patient_info[$entry."_year"]=$date_parts[2];
        }
    }
}
?>
