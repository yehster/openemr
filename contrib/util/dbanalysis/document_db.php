<?php
require_once("cli_header.php");
require_once("db_metadata.php");


$table_names=collect_table_names();

$fields=array();

$tables=array();

$field_types=array();

$field_tables=array();

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
        if(!isset($field_tables[$field->getName()]))
        {
            $field_table_list=new field_table_list($field->getName(),"any");
            $field_tables[$field->getName()]=$field_table_list;
        }
        else
        {
            $field_table_list=$field_tables[$field->getName()];
        }
        $field_table_list->AddTable($table_info);

    }
}


function tag_tables(&$tables,$field_list,$tag)
{
    foreach($tables as $table)
    {
        $field=$table->hasFieldList($field_list);
        if($field)
        {
            echo $table->getName().":".$field->getName().":".$field->getType() ."\n";
            $table->addTag($tag,$field);
        }            
    }

}

function tag_tables_by_name(&$tables,$name,$prefixed,$tag_name,$tag_content)
{
    foreach($tables as $table)
    {
            if(($prefixed && (strpos($table->getName(),$name)===0))
               || ($table->getName()===$name))
            {
                $table->addTag($tag_name,$tag_content);
            }
    }    
}

function tag_tables_by_list(&$tables,&$list)
{
    foreach($tables as $table)
    {
        if(isset($list[$table->getName()]))
        {
            echo "HERE!";
            $tags=$list[$table->getName()];
            foreach($tags as $tag=>$value)
            {
                $table->addTag($tag,$value);
            }
        }
    }
}

function sortFieldCounts(&$fieldTableList)
{
    usort($fieldTableList,"compare_field_table_list");
    usort($fieldTableList,"compare_field_table_list_alphabetical"); 
    foreach($fieldTableList as $field)
    {
        echo $field->getFieldName();
        echo "|";
        echo $field->getNumTables();
        echo "\n";
    }
}

function showUntagged($tables)
{
    $untaggedCount=0;
    foreach($tables as $table)
    {
        if(!$table->hasTags())
        {
            echo $table->getName() . "\n";
            $untaggedCount++;
        }
    }
    echo "\nUntagged:".$untaggedCount ."\n";
}

$pid_synonmys=['patient_id','pid',"ct_pid","pc_pid","dld_pid"];

tag_tables($tables,$pid_synonmys,'patientLink');

$encounter_synonmys=['encounter','encounter_id'];
tag_tables($tables,$encounter_synonmys,'encounterLink');


tag_tables_by_name($tables,"gacl",true,"module","Generic Access Control Lists");
tag_tables_by_name($tables,"pma_",true,"module","PHPMyAdmin");
tag_tables_by_name($tables,"module",true,"module","Zend Modules");
tag_tables_by_name($tables,"icd",true,"module","ICD Codes");
tag_tables_by_name($tables,"menu_",true,"module","iframes UI Menu Options");
tag_tables_by_name($tables,"documents_legal_",true,"module","Offsite Portal Documents Management");
tag_tables_by_name($tables,"geo_",true,"module","Geographic Codes");
tag_tables_by_name($tables,"lang_",true,"module","Translation Data");



$single_table_tags=array(
    "background_services"=>array("module"=>"Background Services Management")
    ,"customlists"=>array("module"=>"Nation Notes")
    ,"x12_partners"=>array("module"=>"billing")
    ,"sequences"=>array("module"=>"ID Generation")
    ,"registry"=>array("module"=>"OpenEMR Forms Management")
    ,"supported_external_dataloads"=>array("module"=>"Code Sets Importing")
    ,"version"=>array("module"=>"OpenEMR Version Tracking")
    ,"globals"=>array("module"=>"OpenEMR Configuration")
    );

tag_tables_by_list($tables,$single_table_tags);
showUntagged($tables);
