<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function create_encounters_temp($startDate,$endDate,$dimensions)
{
    
    $columns=array_merge($dimensions,
            array(COL_ENC_ID=>"int",
                                                 COL_ENC_DATE=>"date",
                                                 COL_PID=>"int",
                                                ));
    
    create_temporary_table(TMP_ENCOUNTERS, $columns);
    $insert_columns=array();
    foreach($dimensions as $key=>$value)
    {
        if($key===COL_PERIOD)
        {
            $insert_columns[]="'' as ".COL_PERIOD;
        }
        else
        {
            $insert_columns[]=$key;
        }
    }
    
    $insert_columns[]=COL_ENCOUNTER ." as ".COL_ENC_ID;
    $insert_columns[]=COL_DATE ." as ". COL_ENC_DATE;
    $insert_columns[]=COL_PID;
    
    $select_columns=implode(",",$insert_columns);
    $populate_encounters = "INSERT INTO ".TMP_ENCOUNTERS." SELECT ".$select_columns
            ." FROM ".TBL_ENCOUNTERS
            ." WHERE date >=? and date <=?";
    
    sqlStatement($populate_encounters,array($startDate,$endDate));    
}

function dimension_names($dimensions)
{
    foreach($dimensions as $key=>$value)
    {
        
    }
}

function setup_periods_data($dimensions)
{
    
    $columns=array_merge($dimensions,array(COL_ACTIVE_DAYS=>"int",
                                                  COL_NUMBER_CLIENTS=>"int",
                                                  COL_NUMBER_VISITS=>"int",
                                                  COL_NUMBER_SERVICES=>"int"));
    create_temporary_table(TMP_PERIODS_DATA,$columns);
    
    $select_values=array();
    foreach(array_keys($dimensions) as $column)
    {
        $select_values[]="distinct ".$column;
    }
    $populate_periods_data="INSERT INTO ".TMP_PERIODS_DATA
            . " (".implode(array_keys($dimensions),",").") "
            . " SELECT " . implode(array_keys($dimensions),",")
            . " FROM " . TMP_ENCOUNTERS
            . " GROUP BY ".implode(array_keys($dimensions),",");
    
    sqlStatement($populate_periods_data);
}

function set_periods($type)
{
    if($type==='m')
    {
      $convert= "DATE_FORMAT(".COL_ENC_DATE.",'%Y-%m')";  
    }
    if($type==='y')
    {
      $convert= "CONCAT(YEAR(".COL_ENC_DATE."))";  
    }
    if($type==='q')
    {
      $convert= "CONCAT(YEAR(".COL_ENC_DATE."),'-','Q',((MONTH(".COL_ENC_DATE.") - 1) DIV 3) + 1)";  
    }

    $sql_months_period = "UPDATE ".TMP_ENCOUNTERS. " SET ".COL_PERIOD."="
            . $convert;
    sqlStatement($sql_months_period);
    
}

function update_results_from_encounters($source_column,$result_column,$dimensions)
{
    $on_phrases=array();
    foreach($dimensions as $column)
    {
        $on_phrases[]="results.".$column."=".TMP_PERIODS_DATA.".".$column;
    }
    $dimensions_list= implode($dimensions,",");
    $sql_update_result =
            " UPDATE ".TMP_PERIODS_DATA
            ." INNER JOIN ("
            ." SELECT count(DISTINCT ".$source_column.") ".$result_column.", "
            . $dimensions_list
            ." FROM ".TMP_ENCOUNTERS
            ." GROUP BY ".$dimensions_list
            .") results "
            ." ON ".implode($on_phrases," AND ")
            ." SET ".TMP_PERIODS_DATA.".".$result_column. "=results.".$result_column;
    sqlStatement($sql_update_result);
            
}

function update_results_from_billing($source_column,$result_column,$dimensions)
{
    $on_phrases=array();
    foreach($dimensions as $column)
    {
        $on_phrases[]="results.".$column."=".TMP_PERIODS_DATA.".".$column;
    }
    $dimensions_list= implode($dimensions,",");
    $sql_update_result =
            " UPDATE ".TMP_PERIODS_DATA
            ." INNER JOIN ("
            ." SELECT count(".$source_column.") ".$result_column.", "
            . $dimensions_list
            ." FROM ".TMP_BILLING_DATA
            ." GROUP BY ".$dimensions_list
            .") results "
            ." ON ".implode($on_phrases," AND ")
            ." SET ".TMP_PERIODS_DATA.".".$result_column. "=results.".$result_column;
    sqlStatement($sql_update_result);
    
}

function set_active_days()
{
    $update_active_days =

   sqlStatement($update_active_days);
}

function update_services($dimensions,$categorize)
{
    // Create billing temp table
    $columns=array_merge($dimensions,array(
        COL_CODE=>"VARCHAR(20)",
        COL_CODE_TYPE=>"VARCHAR(15)"
    ));
    if($categorize)
    {
        $columns[COL_CODE_TYPE_ID]="int";
        $columns[COL_CATEGORY]="VARCHAR(31)";

    }
    
    $insert_columns=array_merge(
                       array_keys($dimensions),
                       array(
                       COL_CODE,
                       COL_CODE_TYPE));
    
    create_temporary_table(TMP_BILLING_DATA,$columns);

    $select_columns_encounters = array();
    foreach(array_keys($dimensions) as $dimension)
    {
        $select_columns_encounters[]=TMP_ENCOUNTERS.".".$dimension;
    }
    $select_columns_encounters[]=TBL_BILLING.".".COL_CODE;
    $select_columns_encounters[]=TBL_BILLING.".".COL_CODE_TYPE;
    
    $populate_billing_data=
            " INSERT INTO ".TMP_BILLING_DATA
            . "(".implode($insert_columns,",") . ")"
            . " SELECT ".implode($select_columns_encounters, ",")
            . " FROM " . TMP_ENCOUNTERS.",".TBL_BILLING
            . " WHERE " . TMP_ENCOUNTERS.".".COL_ENC_ID."=".TBL_BILLING.".".COL_ENCOUNTER
            . " AND ".TMP_ENCOUNTERS.".".COL_PID . " = " . TBL_BILLING.".".COL_PID;
    sqlStatement($populate_billing_data);
    
    if($categorize)
    {
        $update_code_type_id= " UPDATE ".TMP_BILLING_DATA . "," . TBL_CODE_TYPES 
                . " SET ".TMP_BILLING_DATA.".".COL_CODE_TYPE_ID." = ". TBL_CODE_TYPES.".".COL_CT_ID
                . " WHERE ".TMP_BILLING_DATA.".".COL_CODE_TYPE." = ". TBL_CODE_TYPES.".".COL_CT_KEY;

        sqlStatement($update_code_type_id);

        $update_categories = " UPDATE ".TMP_BILLING_DATA . "," . TBL_CODES
                . " SET ".TMP_BILLING_DATA.".".COL_CATEGORY . "=".TBL_CODES.".".COL_SUPERBILL
                . " WHERE ".TMP_BILLING_DATA.".".COL_CODE . "=".TBL_CODES.".".COL_CODE
                . " AND ".TMP_BILLING_DATA.".".COL_CODE_TYPE_ID . "=".TBL_CODES.".".COL_CODE_TYPE;
        sqlStatement($update_categories);        
    }
}

function query_visits($enc_from,$enc_to,$period_size,$categorize,$facility_filters=null,$provider_filters=null)
{
    if(!is_null($provider_filters))
    {
        $dimensions[COL_PROVIDER_ID]="int";
    }
    if(!is_null($facility_filters))
    {
        $dimensions[COL_FACILITY]="VARCHAR(255)";
        
    }
    $dimensions[COL_PERIOD]="VARCHAR(15)";
    $dimension_columns=array_keys($dimensions);
    
    
    // create encounters temp table
    create_encounters_temp($enc_from,$enc_to,$dimensions);
    
    
    // define periods
    set_periods($period_size);
    
    setup_periods_data($dimensions);
    // compute active days in each period
    update_results_from_encounters(COL_ENC_DATE,COL_ACTIVE_DAYS,$dimension_columns);
    
    // find unique patients
    update_results_from_encounters(COL_PID,COL_NUMBER_CLIENTS,$dimension_columns);

    // find visits
    update_results_from_encounters(COL_ENC_ID,COL_NUMBER_VISITS,$dimension_columns);
    
    // join with billing to find services
    update_services($dimensions,$categorize);
    
    // aggregate data.
    update_results_from_billing("*",COL_NUMBER_SERVICES,$dimension_columns);
    
//    $test_select="SELECT count(*),".implode($dimension_columns,",")." FROM ".TMP_BILLING_DATA . " GROUP BY ".implode($dimension_columns,",");
    $test_select="SELECT * FROM ".TMP_PERIODS_DATA;
    $res=sqlStatement($test_select);
    $retval=array();
    while($row=sqlFetchArray($res))
    {
        $retval[]=$row;
    }
    return $retval;
}

