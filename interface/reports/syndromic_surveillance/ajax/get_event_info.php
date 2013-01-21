<?php
/**
* ajax to query for events that match reportable diagnoses
*
* Copyright (C) 2013 Kevin Yeh <kevin.y@integralemr.com>
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
* @author Kevin Yeh <kevin.y@integralemr.com>
* @link http://www.open-emr.org
*/
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../../globals.php");
require_once("../queries/syndromic_classes.php");
require_once("../queries/syndromic_queries.php");
require_once("../../../../library/patient.inc");

if(isset($_REQUEST['pid']))
{
    $pid=$_REQUEST['pid'];
}
if(isset($_REQUEST['list_id']))
{
    $list_id=$_REQUEST['list_id'];
}
if(isset($_REQUEST['encounter']))
{
    $encounter=$_REQUEST['encounter'];
}
$retval=array();
$retval['encounter']=get_encounter_info($encounter);
$retval['patient']=get_patient_info($pid,$retval['encounter']['date']);
echo json_encode($retval);
?>
