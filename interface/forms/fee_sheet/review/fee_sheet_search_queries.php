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

/**
 * 
 * Search for codes that match the query. Only the codes table and 
 * the icd9_dx_code (external ID=4) are supported for now.  
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
    global $code_types;
    $external_table_id=0;
    if(isset($code_types[$search_type]['external']))
    {
        $external_table_id=$code_types[$search_type]['external'];
    }
    $code_column="code";
    $description_column="code_text";
    $table_name="codes";
    if($external_table_id==4)  // 4 is the only external table we know how to handle for now.  
    {
        $code_column='formatted_dx_code';
        $description_column='long_desc';
        $table_name='icd9_dx_code';
    }
    $retval=array();
    $sqlBase=" SELECT ".$code_column.",".$description_column." from ".$table_name
        ." WHERE active=1 ";
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
