<?php
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../globals.php");

$flu_asthma_SQL= "SELECT fname,lname,pubpid,patient_data.pid,DOB,lists.title, cvx_code,administered_date, phone_home, phone_cell"
                 ." FROM patient_data,lists" 
                 ." LEFT JOIN immunizations on pid=patient_id and cvx_code in (140,141,149) and administered_date >=? " 
                 ." where (diagnosis like 'ICD9:493%' or diagnosis like 'ICD9:519.11%') and patient_data.pid=lists.pid "
                 ." order by administered_date asc, lname, fname;";

$parameters=array("08-01-2013");
$results=sqlStatement($flu_asthma_SQL,$parameters);

$patient_info=array();
foreach($results->GetArray() as $patient)
{
    $patient_info[]=$patient;
}
require_once("templates/report_template.php");        
?>
<html>
    <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
    <link rel="stylesheet" href="report.css" type="text/css">
    <header>
        <title>Asthma Patient Influenza Vaccine Status</title>
    </header>
    <script src='<?php echo $GLOBALS['webroot'];?>/library/js/knockout/knockout-2.3.0.js'></script>
    <script>
        var patient_info=<?php echo json_encode($patient_info);?>;
        var vm={patients:ko.observableArray(patient_info)};
    </script>
    <body class='body_top'>
        <div data-bind="template : { name: 'review-display' }"></div>
        
    </body>
    <script>
        function goto_patient(data,event)
        {
            top.restoreSession();
            window.location.href="<?php echo $GLOBALS['webroot'];?>/interface/patient_file/summary/demographics.php?set_pid="+data.pid;
        }
        ko.applyBindings(vm);
    </script>
</html>