<?php
/**
 * This function retrieves the encounter information related to the given list of codes
 * Codes which are listed as part of a "multi diagnosis" Issue are 
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
