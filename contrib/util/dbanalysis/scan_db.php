<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("cli_header.php");
require_once("create_pid_foreign_keys.php");


function collect_table_names()
{
    $query="SHOW TABLES";
    $retval=array();
    $res=  ExecuteQuery($query, array());
    $skip_prefixes=[
        "customlists" // Nation Notes Templates
        ,"documents_legal_categories" // offsite portal metadata
        ,"documents_legal_master" // offsite portal metadata
        ,"background_services","gacl_","pma_","geo_","icd","ccda_","lang_","menu_","module_","modules","external_","facility"];
    $skip_tables=["audit_details","automatic_notification"
        ,"drug_inventory","drug_templates","drugs",
"array","version","x12_partners","clinical_plans_rules","code_types","codes","categories","categories_seq","config","config_seq","enc_category_map","fee_sheet_options","globals","groups","issue_types","layout_options","prices","registry","sequences","supported_external_dataloads","template_users","product_warehouse","list_options"
        ,"rule_action","rule_action_item","rule_filter","rule_reminder","rule_target"
        ,"notification_settings"
        ,"patient_portal_menu"
        ,"openemr_module_vars","openemr_modules","openemr_postcalendar_categories","openemr_postcalendar_limits"
        ,"openemr_postcalendar_topics","openemr_session_info"
        ,"standardized_tables_track"
        ,"syndromic_surveillance"  // lists_id joins to either the lists table or the billing table on the id when a given diagnosis code is present
        ,"gprelations" // the tables to which the id1 and id2 columns join are indeterminant and thus it is not possible to have a foreign key relationship
        ,"integration_mapping" // foreign_id and local_id columns can map to different tables in different situations
        ,"user_settings","users","users_facility","users_secure"
        ,"addresses"  // foreign_id can join to multiple tables
        ,"phone_numbers" // foreign_id can join to multiple tables
        ,"esign_signatures" // TID can join to ID of any table
        ,"notes"  // foreign_id 
        ,"insurance_companies" // provider is varchar, insurance.id is int CreateForeignKeyNamedTable("insurance_data","provider","insurance_companies","id");
        ,"insurance_numbers"
        ,"misc_address_book" // Odd table, only seems to be accessed when writing with parse_patient_xml.php
        ,"payment_gateway_details" // Offsite portal.  No apparent foreign key relationships
        ];
    foreach($res as $result)
    {
        $skip=false;
        foreach($skip_prefixes as $prefix)
        {
            if(strpos($result[0],$prefix)===0)
            {
                $skip=true;
            }
        }
        foreach($skip_tables as $skip_table)
        {
            if($result[0]===$skip_table)
            {
                $skip=true;
            }
        }
        if(!$skip)
        {
            array_push($retval,$result[0]);        
        }
    }
    return $retval;
}

class field_info
{
    protected $_name;
    protected $_type;
    function __construct($row_data)
    {
        $this->_name=$row_data['Field'];
        $this->_type=$row_data['Type'];
    }
    
    function getName()
    {
        return $this->_name;
    }
    
    function getType()
    {
        return $this->_type;
    }
}

class field_table_list
{
    protected $_name;
    protected $_type;
    protected $_table_list;
    function __construct($field_name,$field_type)
    {
        $this->_name=$field_name;
        $this->_type=$field_type;
        $this->_table_list=array();
    }
    function AddTable($table_info)
    {
        array_push($this->_table_list,$table_info);
    }
    function getFieldName()
    {
        return $this->_name;
    }
    function getNumTables()
    {
        return count($this->_table_list);
    }
}

class table_info
{
    protected $_fields=array();
    protected $_name;
    function __construct($name)
    {
        $this->_name=$name;
        $query="SHOW COLUMNS FROM " .$name. " FROM " . $GLOBALS['dbase'];
        $res=  ExecuteQuery($query,array());
        foreach($res as $result)
        {
            array_push($this->_fields,new field_info($result));
            
        }
    }
    
    function getName()
    {
        return $this->_name;
    }
    function getFields()
    {
        return $this->_fields;
    }
    
    function hasFieldList(&$field_names)
    {
        foreach($this->_fields as $field)
        {
            foreach($field_names as $field_name)
            {
                if($field->getName()===$field_name)
                {
                    return $field;
                }
            }
            
        }
        return false;
    }

}

class field_type
{
    protected $_type;
    public $_fields;
    function __construct($mysqlType)
    {
        $this->_type=$mysqlType;
        $this->_fields=array();
    }
    
    function AddOrGetField($field_name)
    {
        if(!isset($this->_fields[$field_name]))
        {
            $new_field_info=new field_table_list($field_name,$this->_type);
            $this->_fields[$field_name]=$new_field_info;
            return $new_field_info;
        }
        else {
            return $this->_fields[$field_name];
        }
        
    }
    
    function getType()
    {
        return $this->_type;
    }
    
    function getNumFields()
    {
        return count($this->_fields);
    }
    
    function SortFields()
    {
        usort($this->_fields,"compare_field_table_list");
    }
    
    function getFields()
    {
        return $this->_fields;
    }
}
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

function compare_field_table_list($a,$b)
{
    if($a->getNumTables()>$b->getNumTables())
    {
        return 1;
    }
    elseif($a->getNumTables()===$b->getNumTables())
    {
        return strcmp($a->getFieldName(),$b->getFieldName());
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
CreateForeignKeyNamedTable("dated_reminders_link","dr_id","dated_reminders","dr_id");
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

