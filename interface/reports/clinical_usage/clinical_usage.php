<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once("../../globals.php");
ini_set('display_errors',1);
require_once("../dbutils/nonpersistent_dbconnect.php");
require_once("../dbutils/sql_constants.php");

    function insert_matching_forms($temp_table,$form_name)
    {
        $temp_vitals = sqlStatement("CREATE TEMPORARY TABLE ".$temp_table." (".COL_ENC_ID." int, ".COL_VITALS_ID. " int)");
            
        $populate_vitals = sqlStatement("INSERT INTO ".$temp_table." SELECT encounter,form_id FROM forms "
            ."WHERE encounter in (SELECT ".COL_ENC_ID." FROM ".TMP_ENCOUNTERS.")"
            ." AND ".COL_FORM_NAME."=?"
            ,array($form_name));
    }

    $temp_encounters = sqlStatement("CREATE TEMPORARY TABLE ".TMP_ENCOUNTERS. 
                    "(".COL_ENC_ID." int "
                    .",".COL_ENC_DATE." date "
                    .",".COL_ENC_REASON." LONGTEXT "
                    
                    .")");
    $enc_from="2014-10-01";
    $enc_to="2015-03-31";
    $facility="St. John's Clinic";
    
    $populate_encounters = "INSERT INTO ".TMP_ENCOUNTERS." SELECT id,date,reason FROM ".TBL_ENCOUNTERS
            ." WHERE date >=? and date <=? and facility=?";
    
    sqlStatement($populate_encounters,array($enc_from,$enc_to,$facility));
    
    insert_matching_forms(TMP_VITALS,"Vitals");

    insert_matching_forms(TMP_CONTRACEPTION,"Contraception");
    
    
?>
Hello World!


    
<?php
    $database->close();
?>