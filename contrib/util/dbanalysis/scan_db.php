<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("cli_header.php");

require_once($openemr_root_dir."/interface/main/tabs/menu/menu_data.php");


function collect_table_data()
{
    $query="SHOW TABLES";
    $retval=array();
    $res=  ExecuteQuery($query, array());
    foreach($res as $result)
    {
        array_push($retval,$result[0]);
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
    function __construct($name)
    {
        $query="SHOW COLUMNS FROM " .$name. " FROM " . $GLOBALS['dbase'];
        $res=  ExecuteQuery($query,array());
        foreach($res as $result)
        {
            array_push($this->_fields,new field_info($result));
            
        }
    }
    
    function getFields()
    {
        return $this->_fields;
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
$table_names=collect_table_data();

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
    }}