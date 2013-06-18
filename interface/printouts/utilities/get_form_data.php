<?php

function find_forms($pid,array $forms)
{
    $columns= "max(form_id) as form_id, max(date) as date, count(form_id) as number, form_name, formdir";
    $params="";
    $query_params=array();
    array_push($query_params,$pid);
    for($idx=0;$idx<count($forms);$idx++)
    {
        if($idx>0)
        {
            $params.=",";
        }
        $params.="?";
        array_push($query_params,$forms[$idx]);
                
                
    }
    $sqlQuery = "SELECT ".$columns." FROM forms WHERE pid=? "
                ." AND form_name in (".$params.")"
                ." GROUP BY form_name, formdir";
   $res=sqlStatement($sqlQuery,$query_params);
   $retval=array();
   while($value=sqlFetchArray($res))
   {
       $retval[$value['form_name']]=
               array("form_id"=>$value['form_id']
                     ,"date"=>$value['date']
                     ,"number"=>$value['number']
                     ,"formdir"=>$value['formdir']                   
                     );
   }
   return $retval;
}

function get_form_data($form_id,$formdir)
{
    $query_params=array($form_id,$formdir);
    $sqlQuery = "SELECT lay.title field, lay.field_id,lbf.field_value, lis.title FROM lbf_data lbf"
                    ." JOIN layout_options lay ON lbf.field_id=lay.field_id"
                    ." LEFT JOIN list_options lis ON lbf.field_value=lis.option_id AND lay.list_id=lis.list_id"
                    ." WHERE lbf.form_id=? AND lay.form_id=?  "
                    ." ORDER BY lay.seq";
   $res=sqlStatement($sqlQuery,$query_params);
   $retval=array();
   while($data=sqlFetchArray($res))
   {
       $field_name = $data['field']==""?  $data['field_id'] : $data['field'];
       $field_value= $data['title']==""? $data['field_value'] : $data['title'];
       array_push($retval,array("name"=>$field_name,"value"=>$field_value));
   }
   return $retval;
}
?>
