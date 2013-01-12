<?php
/**
 * library to simplify processing code_types
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


function diag_code_types($format='json',$include_external=false)
{
    global $code_types;
    $diagCodes=array();
    foreach($code_types as $key=>$ct)
    {
        if(((!isset($ct['active']) || $ct['active'])            // if the OpenEMR version predates the active column, active and external won't be defined so don't filter on those criteria
         && (!isset($ct['external']) || (($ct['external']==0) || ($ct['external']==4) || $include_external))     // filter out external codes if needed.
         && $ct['diag'] ))
        {
            if($format=='json')
            {
                $entry=array("key"=>$key,"id"=>$ct['id']);
            }
            else if($format=='keylist')
            {
                $entry="'".$key."'";
            }
            array_push($diagCodes,$entry);
        }
    }
    if($format=='json')
    {
        return json_encode($diagCodes);
    }
    if($format=='keylist')
    {
        return implode(",",$diagCodes);    
    }
}
?>
