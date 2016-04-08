<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("cli_header.php");
require_once("db_metadata.php");
require_once("create_pid_foreign_keys.php");


$table_names=collect_table_names();

$fields=array();

$tables=array();

$field_types=array();

$field_counts=array();

foreach($table_names as $name)
{
    $table_info=new table_info($name);
    $tables[$name]=$table_info;
    foreach($table_info->getFields() as $field)
    {
        if(!isset($field_types[$field->getType()]))
        {
            $field_type=new field_type($field->getType());
            $field_types[$field->getType()]=$field_type;
        }
        else
        {
            $field_type=$field_types[$field->getType()];
        }
        $field_info=$field_type->AddOrGetField($field->getName());
        $field_info->AddTable($table_info);
    }
}


foreach($field_types as $type)
{
//    usort($type->fields,"compare_field_counts");
    
}

function compare_field_counts($a,$b)
{
    if($a->count>$b->count)
    {
        return 1;
    }
    if($a->count===$b->count)
    {
        return strcmp($a->field,$b->field);
    }
    return -1;
}

foreach($field_types as $field_type)
{
    echo $field_type->getType() .":". $field_type->getNumFields() . "\n";
}
echo "Total Number of Tables:".count($table_names)."\n";

$field_types['int(11)']->SortFields();
$field_types['bigint(20)']->SortFields();
//var_dump($field_types['int(11)']);


foreach($field_types['bigint(20)']->getFields() as $field_table_list)
{

    if($field_table_list->getNumTables()>1)
    {
        echo $field_table_list->getFieldName() .":".$field_table_list->getNumTables()."\n";        
    }
}

foreach($field_types['int(11)']->getFields() as $field_table_list)
{

    if($field_table_list->getNumTables()>1)
    {
        echo $field_table_list->getFieldName() .":".$field_table_list->getNumTables()."\n";        
    }
    
}


//ChangeTablesToInnodb($tables);

create_pid_foreign_keys($tables);

create_form_encounter_index();
create_encounter_foreign_keys($tables);

CreateForeignKeyNamedTable("amendments_history","amendment_id","amendments","amendment_id");
CreateForeignKeyNamedTable("prescriptions","pharmacy_id","pharmacies","id");
CreateForeignKeyNamedTable("lbf_data","form_id","forms","form_id");
CreateForeignKeyNamedTable("categories_to_documents","document_id","documents","id");
CreateForeignKeyNamedTable("lbt_data","form_id","transactions","id");

CreateForeignKeyNamedTable("dated_reminders_link","dr_id","dated_reminders","dr_id");
CreateForeignKeyNamedTable("dated_reminders_link","to_id","users","id");
CreateForeignKeyNamedTable("report_results","report_id","report_itemized","report_id");
CreateForeignKeyNamedTable("patient_tracker_element","pt_tracker_id","patient_tracker","id");
CreateForeignKeyNamedTable("onotes","user","users","username");
CreateForeignKeyNamedTable("eligibility_verification","response_id","eligibility_response","response_id");

ChangeColumnType("log_comment_encrypt","log_id","bigint(20)");
CreateForeignKeyNamedTable("log_comment_encrypt","log_id","log","id");

CreateForeignKeyNamedTable("procedure_answers","procedure_order_id","procedure_order","procedure_order_id");
CreateForeignKeyNamedTable("procedure_report","procedure_order_id","procedure_order","procedure_order_id");
CreateForeignKeyNamedTable("procedure_result","procedure_report_id","procedure_report","procedure_report_id");


CreateForeignKeyNamedTable("procedure_order_code","procedure_order_id","procedure_order","procedure_order_id"); // MyISAM table incompatible because of autoincrement.  Foreign key ignored



function DetermineUnmodifiedTables()
{
    global $tables;
    global $modified_tables;
    $retval=array();
    echo count($modified_tables) . " Mods\n";
    foreach($tables as $table)
    {
        if(!isset($modified_tables[$table->getName()]))
        {
           array_push($retval,$table);
        }
    }
    return $retval;
}

$unmodified_tables=DetermineUnmodifiedTables();
foreach($unmodified_tables as $unmod)
{
    echo $unmod->getName() . "\n";
}


echo "\n" ."Number of Tables" .":".count(DetermineUnmodifiedTables()) . "\n";


foreach($tables as $table)
{
    echo ($table->getName()) ."\n";
}

// Drug sales, foreign key constraint not strictly enforced... (pid=0)