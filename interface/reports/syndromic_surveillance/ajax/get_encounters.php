<?php
/**
* get the details of an encounter to be used in SS hl7 message
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


/**
 * This function retrieves the encounter information related to the given list of codes
 * Codes which are listed as part of a "multi diagnosis" Issue are ignored.
 */
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../../globals.php");
require_once("../queries/syndromic_classes.php");
require_once("../queries/syndromic_queries.php");

if(isset($_REQUEST['from_date']))
{
    $from_date=$_REQUEST['from_date'];
}
if(isset($_REQUEST['to_date']))
{
    $to_date=$_REQUEST['to_date'];
}
if(isset($_REQUEST['diags']))
{
    $json_diags=json_decode($_REQUEST['diags']);
    $diags=array();
    foreach($json_diags as $diag)
    {
        $diags[]=new reportable_code($diag->{'id'},$diag->{'code'},$diag->{'code_type'},$diag->{'description'},$diag->{'code_key'});
    }
}
else
{
    // If the choice of codes wasn't passed in, then just use all of them.
    $diags=get_reportable_codes();
}
$events=find_events($from_date,$to_date,$diags);
echo json_encode($events);
?>
