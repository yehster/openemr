<?php
/**
 * Functions to help search for codes on the fee sheet
 * 
 * Copyright (C) 2013 Kevin Yeh <kevin.y@integralemr.com> and OEMR <www.oemr.org>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Kevin Yeh <kevin.y@integralemr.com>
 * @link    http://www.open-emr.org
 */
require_once("$srcdir/../custom/code_types.inc.php");

$code_external_tables2=array();
define('EXT_COL_CODE','code');
define('EXT_COL_DESCRIPTION','description');
define('EXT_TABLE_NAME','table');
define('EXT_STATUS_CLAUSE','status_clause');
function define_external_table(&$results, $index, $table_name,$col_code, $col_description,$status_clause)
{
    $results[$index]=array(EXT_TABLE_NAME=>$table_name,EXT_COL_CODE=>$col_code,EXT_COL_DESCRIPTION=>$col_description,EXT_STATUS_CLAUSE=>$status_clause);
}
define_external_table($code_external_tables2,1,'icd10_dx_order_code','formatted_dx_code','long_desc',' active=1 ');
define_external_table($code_external_tables2,4,'icd9_dx_code','formatted_dx_code','long_desc',' active=1 ');
define_external_table($code_external_tables2,2,'sct_concepts','ConceptId','FullySpecifiedName',' ConceptStatus=0 and FullySpecifiedName LIKE \'%(disorder)\'');
define_external_table($code_external_tables2,7,'sct_concepts','ConceptId','FullySpecifiedName',' ConceptStatus=0 ');


/**
 * 
 * Search for codes that match the query. Only the codes table and 
 * the code types in $code_external_tables2 are supported.
 * 
 * The search query tries code "starts with" $search_query first.  
 * If that has results, then return those codes.
 * If no results then try a search of the descriptions in "code_text".
 * 
 * @param type $search_type_id      The integer ID used for code_type in codes (e.g. 2 for ICD9)
 * @param type $search_type         A string representing the code type to be searched on (e.g. ICD9, DSMIV)
 * @param type $search_query        The text to search on.
 * @return array
 */
function diagnosis_search($search_type_id,$search_type,$search_query)
{
    global $code_types,$code_external_tables2;
    $external_table_id=0;
    if(isset($code_types[$search_type]['external']))
    {
        $external_table_id=$code_types[$search_type]['external'];
    }
    $code_column="code";
    $description_column="code_text";
    $table_name="codes";
    $status_clause=" active=1 ";
    if($external_table_id>0)  
    {
        $code_external_data=$code_external_tables2[$external_table_id];
        $code_column=$code_external_data[EXT_COL_CODE];
        $description_column=$code_external_data[EXT_COL_DESCRIPTION];
        $table_name=$code_external_data[EXT_TABLE_NAME];
        $status_clause=$code_external_data[EXT_STATUS_CLAUSE];
    }
    $retval=array();
    $sqlBase=" SELECT ".$code_column.",".$description_column." from ".$table_name
        ." WHERE ".$status_clause;
    if($external_table_id==0)
    {
        $sqlBase .= " AND code_type=?";
    }
    $sqlCode = $sqlBase . " AND ".$code_column." like ?";
    $sqlCode .=" LIMIT 20";
    $code_param=$search_query."%";

    $code_param_array= ($external_table_id==0) ? array($search_type_id,$code_param) : array($code_param);
    $res=sqlStatement($sqlCode,$code_param_array);//,array($search_type_id,$code_param));
    if(sqlNumRows($res)>0)
    {
        foreach($res->GetArray() as $code)
        {
            array_push($retval,new code_info($code[$code_column],$search_type,$code[$description_column]));
        }
        return $retval;        
    }
    else
    {
        // no codes found, so search the descriptions;
        $sqlTextSearch=$sqlBase;
        $keywords=preg_split("/ /",$search_query,-1,PREG_SPLIT_NO_EMPTY);
        $params= ($external_table_id==0) ? array($search_type_id) : array();
        foreach($keywords as $kw)
        {
            array_push($params,"%".$kw."%");
            $sqlTextSearch.=" AND ".$description_column." like ?";
        }
        $sqlTextSearch.=" LIMIT 20";
        $res=sqlStatement($sqlTextSearch,$params);//,array($search_type_id,$code_param));
        if(sqlNumRows($res)>0)
        {
            foreach($res->GetArray() as $code)
            {
                array_push($retval,new code_info($code[$code_column],$search_type,$code[$description_column]));
            }
            return $retval;        
        }        
    }
}
?>
