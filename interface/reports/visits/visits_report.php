<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("../../globals.php");
ini_set('display_errors',1);


if(!acl_check('acct', 'rep'))
{
    header("HTTP/1.0 403 Forbidden");    
    echo "Not authorized for billing";   
    return false;
}

function get_clinic_list()
{
    
    $retval=array();
    array_push($retval,"All");
    $query_clinic="select name from facility where service_location!=0 order by name asc";
    $res=  sqlStatement($query_clinic);
    while($row=sqlFetchArray($res))
    {
        array_push($retval,$row['name']);
    }
    return $retval;
}
function get_provider_list()
{
    $retval=array();
    array_push($retval,array("id"=>"ALL","lname"=>xl("--All Providers--"),"fname"=>""));
    $query_providers="select id,fname,lname from users where authorized!=0 and active!=0 order by lname,fname asc";
    $res=  sqlStatement($query_providers);
    while($row=sqlFetchArray($res))
    {
        array_push($retval,$row);
    }
    return $retval;
    
}

function get_service_categories_list()
{
    $retval=array();
    array_push($retval,array("option_id"=>"ALL","title"=>xl("--All Service Categories--")));
    $query_service_categories="select option_id,title from list_options where list_id='superbill' order by seq asc";
    $res=  sqlStatement($query_service_categories);
    while($row=sqlFetchArray($res))
    {
        array_push($retval,$row);
    }
    return $retval;
    
}


$clinic_list=  get_clinic_list();
$provider_list=get_provider_list();

$service_categories_list=get_service_categories_list();
$from_date=date("Y") . "-01-01";
$to_date=date('Y-m-d');

?>
<!DOCTYPE html>
<html>



<script type="text/javascript" src="<?php echo $web_root."/library/js/knockout/knockout-3.3.0.js"?>"></script>

<style type="text/css">@import url(<?php echo $web_root;?>/library/dynarch_calendar.css);</style>
<style type="text/css">@import url(<?php echo $web_root;?>/interface/reports/visits/visits_report.css);</style>
<script type="text/javascript" src="<?php echo $web_root;?>/library/dynarch_calendar.js"></script>
<script type="text/javascript" src="<?php echo $web_root;?>/library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="<?php echo $web_root;?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $web_root;?>/library/js/jquery-1.9.1.min.js"></script>

<script type="text/javascript">
    var clinics=<?php echo json_encode($clinic_list);?>;
    var providers=<?php echo json_encode($provider_list);?>;

    var service_categories=<?php echo json_encode($service_categories_list);?>;
    var query_ajax=<?php echo json_encode($web_root."/interface/reports/visits/ajax/visits_data.php");?>;
    var period_options=[
        {id: 'm',description:<?php echo json_encode(xla("Months"));?>}
        ,{id: 'q',description:<?php echo json_encode(xla("Quarters"));?>}
        ,{id: 'y',description:<?php echo json_encode(xla("Years"));?>}
    ];
    var report_title = <?php echo json_encode(xl('Service and Client Volume')); ?>;
    var title_by_provider = <?php echo json_encode(xl('Service and Client Volume by Provider')); ?>;
    var title_by_clinic = <?php echo json_encode(xl('Service and Client Volume by Clinic')); ?>;
    var title_by_clinic_and_provider = <?php echo json_encode(xl('Service and Client Volume by Clinic and Provider')); ?>;
</script>
<body>
    <title data-bind="text: title"></title>
    <h2><center data-bind="text: title"></center></h2>
<div id="queryParameters">
       <span class='label'><?php echo xlt('From'); ?></span>
      <input type='text' name='form_from_date' id='form_from_date' size='10' value='<?php echo $from_date ?>'
       onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='Start date yyyy-mm-dd'>
      <img class='datePicker' src='<?php echo $web_root;?>/interface/pic/show_calendar.gif' align='absbottom' width='24' height='22'
       id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
       title='<?php echo xla('Click here to choose a date'); ?>'>
      <span class='label'><?php echo xlt('To'); ?></span>
      <input type='text' name='form_to_date' id='form_to_date' size='10' value='<?php echo $to_date ?>'
       onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='End date yyyy-mm-dd'>
      <img class='datePicker' src='<?php echo $web_root;?>/interface/pic/show_calendar.gif' align='absbottom' width='24' height='22'
       id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
       title='<?php echo xla('Click here to choose a date'); ?>'>
      <span id='filters' data-bind="template:{name: 'visits-parameters', data: parameters}"></span>
</div>
    <div data-bind="template:{name: 'visits-results', data: results}"></div>
<script>
    Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
    Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>

<?php 
    require_once("templates/visits_parameters.php");
    require_once("templates/visits_results.php");
?>

<script type="text/javascript" src="js/export_and_print.js"></script>
<script type="text/javascript" src="js/visits_report_view_model.js"></script>

</body>
</html>
