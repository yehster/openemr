<?php
    function lookup_procedure_fee($pid,$code,$code_type)
    {
        $query="SELECT pr_price "
                ." FROM prices,codes,patient_data "
                ." WHERE codes.id=prices.pr_id "
                ." AND pr_level=patient_data.pricelevel "
                ." AND patient_data.pid=? "
                ." AND codes.code=? "
                ." AND codes.code_type=? ";
        $ct=$GLOBALS['code_types'][$code_type]['id'];
        $res=sqlStatement($query,array($pid,$code,$ct));
        $row=sqlFetchArray($res);
        if($row)
        {
            return $row['pr_price'];
        }
        else
        {
            return "Unknown";
        }
    }
?>
