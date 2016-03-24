<?php

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
    
    return ExecuteQuery($sql);
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

function CreateForeignKey($table,$field,$foreign_table,$foreign_field)
{
  $sql = "ALTER TABLE ".escapeSQLComponent($table) 
         ." ADD CONSTRAINT ".escapeSQLComponent($table."_".$foreign_table)
         . " FOREIGN KEY (".escapeSQLComponent($field).")"
         . " REFERENCES ".escapeSQLComponent($foreign_table) . " (".escapeSQLComponent($foreign_field).")"
         . " ON DELETE NO ACTION "            
         . " ON UPDATE CASCADE ";
    
  ExecuteQuery($sql,[]);
}

function create_pid_foreign_keys(&$table_list)
{
    $pid_synonmys=['patient_id','pid',"ct_pid","pc_pid"];
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
                CreateForeignKey($table->getName(),$field->getName(),"patient_data","pid");
            }
            
        }
    }
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