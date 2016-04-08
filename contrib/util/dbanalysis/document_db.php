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

function showUntagged($tables,$specific_tag=false)
{
    $untaggedCount=0;
    foreach($tables as $table)
    {
        if(($specific_tag && !$table->hasTag($specific_tag))
            || (!$specific_tag && !$table->hasTags()))
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
tag_tables_by_name($tables,"module",true,"module","External Modules");
tag_tables_by_name($tables,"icd",true,"module","Code Lists");
tag_tables_by_name($tables,"menu_",true,"module","iframes UI Menu Options");
tag_tables_by_name($tables,"documents_legal_",true,"module","Offsite Portal Documents Management");
tag_tables_by_name($tables,"geo_",true,"module","Geographic Codes");
tag_tables_by_name($tables,"lang_",true,"module","Translation Data");
tag_tables_by_name($tables,"user",true,"module","User Management");
tag_tables_by_name($tables,"rule_",true,"module","Clinical Decision Rules");
tag_tables_by_name($tables,"openemr_postcalendar",true,"module","Calendar/Appoinments");
tag_tables_by_name($tables,"openemr_module",true,"module","Calendar/Appoinments");
tag_tables_by_name($tables,"openemr_session_info",true,"module","Calendar/Appoinments");
tag_tables_by_name($tables,"facility",true,"module","Practice Settings");
tag_tables_by_name($tables,"insurance",true,"module","Practice Settings");

tag_tables_by_name($tables,"config",true,"module","Deprecated?");
tag_tables_by_name($tables,"categories",true,"module","Documents");
tag_tables_by_name($tables,"ccda",true,"module","Continuity of Care Documents");
tag_tables_by_name($tables,"procedure_",true,"module","Laboratory Exchange");
tag_tables_by_name($tables,"eligibility_",true,"module","Insurance Eligibility (EDI 271)");
tag_tables_by_name($tables,"external_",true,"module","Care Coordination Module");
tag_tables_by_name($tables,"form",true,"module","Encounter Forms");

$single_table_tags=array(
    "background_services"=>array("module"=>"Background Services Management")
    ,"documents"=>["module"=>"documents"]
    ,"customlists"=>array("module"=>"Nation Notes")
    ,"template_users"=>array("module"=>"Nation Notes")
    ,"x12_partners"=>array("module"=>"Billing Options")
    ,"sequences"=>array("module"=>"ID Generation")
    ,"registry"=>array("module"=>"Forms Management")
    ,"supported_external_dataloads"=>array("module"=>"Code Sets Importing")
    ,"version"=>array("module"=>"Version Tracking")
    ,"globals"=>array("module"=>"Configuration")
    ,"layout_options"=>["module"=>"Layouts"]
    ,"list_options"=>["module"=>"Layouts"]
    ,"codes"=>["module"=>"Code Lists"]
    ,"standardized_tables_track"=>["module"=>"Code Import"]
    ,"code_types"=>["module"=>"Code Metadata"]
    ,"fee_sheet_options"=>["module"=>"Billing Options"]
    ,"prices"=>["module"=>"Billing Options"]
    ,"payment_gateway_details"=>["module"=>"Billing Options"]
    ,"product_warehouse"=>["module"=>"Inventory"]
    ,"drug_inventory"=>["module"=>"Inventory"]
    ,"drugs"=>["module"=>"Inventory"]
    ,"drug_templates"=>["module"=>"Inventory"]
    ,"drug_sales"=>["module"=>"Inventory"]
    ,"patient_portal_menu"=>["module"=>"Offsite Portal Configuration"]
    ,"issue_types"=>["module"=>"Metadata"]
    ,"notification_settings"=>["module"=>"Email/SMS Gateway"]
    ,"automatic_notification"=>["module"=>"Email/SMS Gateway"]
    ,"notification_log"=>["module"=>"Email/SMS Gateway"]
    ,"direct_message_log"=>["module"=>"DIRECT Messages"]
    ,"batchcom"=>["module"=>"Email/SMS Gateway"]
    ,"groups"=>["module"=>"User Management"]
    ,"report_results"=>["module"=>"Clinical Decision Rules"]
    ,"report_itemized"=>["module"=>"Clinical Decision Rules"]
    ,"enc_category_map"=>["module"=>"Clinical Decision Rules"]
    ,"clinical_plans_rules"=>["module"=>"Clinical Decision Rules"]
    ,"clinical_plans"=>["module"=>"Clinical Decision Rules"]
    ,"clinical_rules"=>["module"=>"Clinical Decision Rules"]
    ,"clinical_rules_log"=>["module"=>"Clinical Decision Rules"]
    ,"patient_reminders"=>["module"=>"Clinical Decision Rules"]
    ,"amc_misc_data"=>["module"=>"Clinical Decision Rules"]
    ,"syndromic_surveillance"=>["module"=>"Code Reporting(Epidemiology)"]
    ,"pharmacies"=>["module"=>"Practice Settings"]
    ,"gprelations"=>["module"=>"General Purpose Relationships"]
    ,"integration_mapping"=>["module"=>"SQL Ledger (Deprecated)"]
    ,"amendments_history"=>["module"=>"eSign"]
    ,"esign_signatures"=>["module"=>"eSign"]
    ,"amendments"=>["module"=>"eSign"]
    ,"onotes"=>["module"=>"Notes (Intraoffice)"]
    ,"notes"=>["module"=>"Notes (Generic)"]
    ,"audit_details"=>["module"=>"Zend Module Audit"]
    ,"audit_master"=>["module"=>"Zend Module Audit"]
    ,"log_comment_encrypt"=>["module"=>"Access Log"]
    ,"log"=>["module"=>"Access Log"]
    ,"extended_log"=>["module"=>"Access Log"]
    ,"dated_reminders_link"=>["module"=>"Dated Reminders"]
    ,"dated_reminders"=>["module"=>"Dated Reminders"]
    ,"addresses"=>["module"=>"Address Book"]
    ,"phone_numbers"=>["module"=>"Address Book"]
    ,"misc_address_book"=>["module"=>"Care Coordination Module"]
    ,"patient_tracker_element"=>["module"=>"Patient Tracker"]
    ,"patient_tracker"=>["module"=>"Patient Tracker"]
    ,"lbf_data"=>["module"=>"Encounter Forms"]
    ,"shared_attributes"=>["module"=>"Encounter Forms"]
    ,"lbt_data"=>["module"=>"Patient Transactions"]
    ,"array"=>["module"=>"Deprecated?"]
    ,"prescriptions"=>["module"=>"Prescriptions"]
    ,"pnotes"=>["module"=>"Notes (Patient)"]
    ,"lists"=>["module"=>"Patient Issues"]
    ,"issue_encounter"=>["module"=>"Patient Issues"]
    ,"lists_touch"=>["module"=>"Patient Issues"]
    ,"history_data"=>["module"=>"Patient History"]
    ,"immunizations"=>["module"=>"Patient Immunizations"]
    ,"transactions"=>["module"=>"Patient Transactions"]
    ,"chart_tracker"=>["module"=>"Chart Tracker"]
    ,"erx_ttl_touch"=>["module"=>"ePrescription"]
    ,"patient_access_onsite"=>["module"=>"Login Credentials Patient Portal"]
    ,"patient_access_offsite"=>["module"=>"Login Credentials Patient Portal"]
    ,"ar_activity"=>["module"=>"Billing"]
    ,"ar_session"=>["module"=>"Billing"]
    ,"billing"=>["module"=>"Billing"]
    ,"claims"=>["module"=>"Billing"]
    ,"payments"=>["module"=>"Billing"]
    ,"patient_data"=>["module"=>"Patient Information"]    
    ,"employer_data"=>["module"=>"Patient Information"]    
    );

tag_tables_by_list($tables,$single_table_tags);
showUntagged($tables,"module");


$foreign_id_synonmys=['foreign_id'];
tag_tables($tables,$foreign_id_synonmys,'foreignLink');