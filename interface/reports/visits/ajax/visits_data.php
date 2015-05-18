<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("../../../globals.php");
ini_set('display_errors',1);
require_once("$webserver_root/interface/reports/dbutils/nonpersistent_dbconnect.php");
require_once("$webserver_root/interface/reports/dbutils/sql_constants.php");
require_once("$webserver_root/interface/reports/dbutils/temporary_tables.php");
require_once("$webserver_root/interface/reports/visits/visits_queries.php");


if(!acl_check('acct', 'rep'))
{
    header("HTTP/1.0 403 Forbidden");    
    echo "Not authorized for billing";   
    return false;
}

if(isset($_REQUEST['parameters']))
{
    $parameters=json_decode($_REQUEST['parameters']);
}
else
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No parameters in request";   
    return false;
    
}

if($parameters->{'clinics_details'})
{
    $facility_filters=array();
}
else
{
    $facility_filters=null;
}

if($parameters->{'providers_details'})
{
    $providers_filters=array();
}
else
{
    $providers_filters=null;
}
echo json_encode(query_visits($parameters->{'from'},$parameters->{'to'},$parameters->{'period_size'},true,$facility_filters,$providers_filters));