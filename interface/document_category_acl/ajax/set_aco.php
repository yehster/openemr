<?php
/**
 * Copyright (C) 2015 Kevin Yeh <kevin.y@integralemr.com>
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


$sanitize_all_escapes = true;		//SANITIZE ALL ESCAPES

$fake_register_globals = false;		//STOP FAKE REGISTER GLOBALS
require_once("../../globals.php");


if(!acl_check('admin', 'practice'))
{
     die(xlt('Not authorized!'));
}

if(!isset($_REQUEST['category_id']))
{
    die(xlt("No category specified!"));
}

$category_id=$_REQUEST['category_id'];

if(!isset($_REQUEST['section_value']))
{
    die(xlt("No ACO section value specified!"));
}
$section_value=$_REQUEST['section_value'];

if(!isset($_REQUEST['value']))
{
    die(xlt("No ACO value specified!"));
}
$value=$_REQUEST['value'];


// Valid ACO input
if($section_value !=="none" && $value !="none")
{
    $queryACO= "SELECT name FROM gacl_aco WHERE section_value=? and value=?";
    $res=sqlQuery($queryACO,array($section_value,$value));
    if(!$res)
    {
        echo $section_value.":".$value."<br>";
        die(xlt("Invalid ACO option"));
    }
    $aco_name=xl_gacl_group($res['name']);
}
else
{
    // If both entries are null, then setting no ACO
    $aco_name=xlt("None");
    $section_value=null;
    $value=null;
}

$updateCategoryACO="UPDATE categories SET acl_section_value=?, acl_value=? WHERE id=?";
sqlStatement($updateCategoryACO,array($section_value,$value,$category_id));




echo xlt("ACO requirement set to") .":" . $aco_name;
