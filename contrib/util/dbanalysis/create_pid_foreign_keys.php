<?php

$modified_tables=array();

function escapeSQLComponent($entry)
{
    return "`".$entry."`";
    
}
function ChangeToInnoDB($table_name)
{
    $sql=" ALTER TABLE ".escapeSQLComponent($table_name)
         . " ENGINE = InnoDB";
    return ExecuteQuery($sql,[]);
}


function ChangeColumnType($table,$field,$new_type)
{
    $sql= "ALTER TABLE  ". escapeSQLComponent($table) 
          ." CHANGE COLUMN " .escapeSQLComponent($field) . " " 
          .escapeSQLComponent($field) . " ". $new_type . " NULL DEFAULT NULL " ;
    
    return ExecuteQuery($sql,[]);
}

function ChangeTablesToInnodb(&$table_list)
{
    foreach($table_list as $table)
    {
        if(ChangeToInnodb($table->getName())===false)
        {
            echo $table->getName()."====";
        };
    }    
}

function CreateForeignKey(&$table,$field,$foreign_table,$foreign_field)
{
        if($table->getName()==='drug_sales')
        {
            echo "--------->drug_sales\n";
            echo "--------->drug_sales\n";
            echo "--------->drug_sales\n";
        }    
    global $modified_tables;
    global $tables;
    $table_name=$table->getName();
    $sql = "ALTER TABLE ".escapeSQLComponent($table_name) 
         ." ADD CONSTRAINT ".escapeSQLComponent($table_name."_".$foreign_table)
         . " FOREIGN KEY (".escapeSQLComponent($field).")"
         . " REFERENCES ".escapeSQLComponent($foreign_table) . " (".escapeSQLComponent($foreign_field).")"
         . " ON DELETE NO ACTION "            
         . " ON UPDATE CASCADE ";
    
  ExecuteQuery($sql,[]);
  if(!isset($modified_tables[$table->getName()]))
  {
      $modified_tables[$table->getName()]=$table;  
  }
  if(!isset($modified_tables[$foreign_table]))
  {
      $modified_tables[$foreign_table]=$tables[$foreign_table];  
      
  }
}

function create_pid_foreign_keys(&$table_list)
{

    $pid_synonmys=['patient_id','pid',"ct_pid","pc_pid","dld_pid"];
    foreach($table_list as $table)
    {
        
        if($table->getName()!=='patient_data')
        {
            if($field=$table->hasFieldList($pid_synonmys))
            {
                echo $table->getName().":".$field->getName().":".$field->getType() ."\n";
                if($field->getType()!=="bigint(20)")
                {
                    ChangeColumnType($table->getName(),$field->getName(),"bigint(20)");   
                }    
                CreateForeignKey($table,$field->getName(),"patient_data","pid");
            }
            
        }
    }
}

function create_form_encounter_index()
{
    $sql="ALTER TABLE `form_encounter` ADD INDEX `encounter` (`encounter` ASC)";
    ExecuteQuery($sql, []);

}

function create_encounter_foreign_keys(&$table_list)
{

    $encounter_synonmys=['encounter','encounter_id'];
    foreach($table_list as $table)
    {
        if($table->getName()!=='form_encounter')
        {
            if($field=$table->hasFieldList($encounter_synonmys))
            {
                echo $table->getName().":".$field->getName().":".$field->getType() ."\n";
                if($field->getType()!=="bigint(20)")
                {
                    ChangeColumnType($table->getName(),$field->getName(),"bigint(20)");   
                }    
                CreateForeignKey($table,$field->getName(),"form_encounter","encounter");
            }
            
        }
    }
}


function CreateForeignKeyNamedTable($table_name,$field,$foreign_table,$foreign_field)
{
    global $tables;
    $table=$tables[$table_name];
    return CreateForeignKey($table,$field,$foreign_table,$foreign_field);
}
/*
  ALTER TABLE `transactions` 
        ADD CONSTRAINT `transactions_patient_data`
        FOREIGN KEY (`pid`)
        REFERENCES `patient_data` (`pid`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;

 */

/*
 
 ALTER TABLE `iframes`.`ar_activity` 
    ENGINE = InnoDB ; 
 */