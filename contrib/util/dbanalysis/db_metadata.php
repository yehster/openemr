<?php
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

    
function collect_table_names()
{
    $query="SHOW TABLES";
    $retval=array();
    $res=  ExecuteQuery($query, array());
    $skip_prefixes=array();
    $skip_tables=array();
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
    protected $_tags = array();
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
    function addTag($tag,$field)
    {
        $this->_tags[$tag]=$field;
    }
    
    function hasTags()
    {
        return count($this->_tags)>0;
    }

    function hasTag($tag)
    {
        if(isset($this->_tags[$tag]))
        {
            return $this->_tags[$tag];
        }
        else
        {
            return false;
        }
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


function compare_field_table_list_alphabetical($a,$b)
{
    return strcmp($a->getFieldName(),$b->getFieldName());
}