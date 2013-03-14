<?php
/**
 * Basic PHP setup for the immunization schedules
 * 
 * Copyright (C) 2013 Kevin Yeh <kevin.y@integralemr.com> and Medical Information Integration, LLC <www.mi-squared.com>
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


$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../../../globals.php");
require_once("../queries/schedule_info_queries.php");
include_once("$srcdir/jsonwrapper/jsonwrapper.php");
$retval=array();

if(isset($_REQUEST['age_in_months']))
{
    $ageInMonths=floatval($_REQUEST['age_in_months']);

    $retval['schedules']=get_schedules($ageInMonths);
    $default_id=$retval['schedules'][0]['id'];
    $retval['codes']=get_codes($default_id);
    echo json_encode($retval);
    return;
}

if(isset($_REQUEST['schedule_id']))
{
    $retval['codes']=get_codes($_REQUEST['schedule_id']);
    echo json_encode($retval);
    return;
    
}





    echo json_encode($retval);
    return;
?>
