<html>
<head>

<!-- Main style sheet comes after the page-specific stylesheet to facilitate overrides. -->
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/css/encounters.css" type="text/css">
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>

<style type="text/css">
#itemized_billing_table {
    font-family:sans-serif;
    text-align:left;
    font-size:12px;
    border-collapse:collapse;
}
#itemized_billing_table td {
    padding: 0px 10px;
}
#itemized_billing_table td.dollar_column {
    text-align:right;
}
#itemized_billing_table tr {
    margin:0;
    padding:0;
}
.new_encounter_row td
{
    border-top: 1px solid black;
}
</style>

</head>
<body>
<?php
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

require_once("../../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/pnotes.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/lists.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/sql-ledger.inc");
require_once("$srcdir/invoice_summary.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("../../../custom/code_types.inc.php");
require_once("$srcdir/formdata.inc.php");

if (empty($pid)) exit("Error: No Patient Selected.");

$patient_name = sqlQuery("select fname, lname from patient_data where id=$pid");

//get facility name
$facilities = getFacilities();

$facility_name = "";
$facility_address = "";
if (!empty($facilities))
{
    $f = $facilities[0];
    $facility_name = $f['name'];
    $facility_address = $f['street']."<br>\r\n".$f['city'].', '.$f['state'].' '.$f['postal_code'];
}

//get patient insurance data
//make the array like $insd_data['Primary'] = "Healthcare Insurance Plan"
/* removed 2012-07-26
$insd_data = array();
$insd_sql_res = sqlStatement("select type, ic.name as plan_name from insurance_data as insd
                             left join insurance_companies as ic on insd.provider = ic.id
                             where pid=$pid and ic.name != ''");
while ($insd_row = sqlFetchArray($insd_sql_res))
    $insd_data[] = $insd_row;

for ($i = 0; $i < count($insd_data); $i++)
{
    $insd_data[ucfirst($insd_data[$i]['type'])] = $insd_data[$i]['plan_name'];
    unset($insd_data[$i]);
}
*/
?>

<h1 style="border-bottom:2px solid black;"><?php echo $facility_name; ?></h1>
<h2>Itemized Billing Report</h2>
<?php
$provider_info = $_SESSION['pc_facility'] ? getProviderInfo('%', true, $_SESSION['pc_facility']) : getProviderInfo();

if (isset($provider_info[0]))
{
    $p = $provider_info[0];
    echo "<div><b>Physician: </b>{$p['fname']} {$p['lname']}";
    if (!empty($p['info'])) echo ", ".$p['info'];
    echo "</div><br>";
}
?>
<div><?php echo $facility_address; ?></div>
<br>
<div><b>Patient:&nbsp;</b>
<?php echo $patient_name['lname'].", ".$patient_name['fname']; ?><br>
<!--<b>Insurance: </b><br>-->
<?php
/* insurance information removed 2012-07-26
    foreach ($insd_data as $plan_type=>$plan_name)
        echo "$plan_type: $plan_name<br>\n";
    */
?>
</div>
<br>
<table id='itemized_billing_table'>
<thead>
<tr>
    <th>Date</th><th>Code</th><th>Code Description</th><th>Chg</th><th>Paid</th><th>Adj</th><th>Bal</th>
</tr>
</thead>
<tbody>
<?php
//read in all the sql data first
$billing_data = array();
$totals_data = array('chg'=>0, 'pay'=>0, 'adj'=>0, 'bal'=>0);
$billing_sql_res = sqlStatement("
select fe.encounter, fe.date, b.code_type, b.code, b.code_text, b.fee,
    MAX(ar.pay_amount) as pay_amount, MAX(ar.adj_amount) as adj_amount
from form_encounter as fe inner join billing as b
    on fe.encounter = b.encounter
left outer join ar_activity as ar
    on ar.pid = fe.pid
    and ar.encounter = fe.encounter
    and ar.code = b.code
where
    fe.pid=$pid
    and b.activity = 1
group by
    fe.encounter, b.code
order by fe.date desc, b.date asc"
);


while ($billing_row = sqlFetchArray($billing_sql_res))
{
    $billing_row['fee'] = floatval($billing_row['fee']);
    $billing_row['pay_amount'] = floatval($billing_row['pay_amount']);
    $billing_row['adj_amount'] = floatval($billing_row['adj_amount']);
    
    if ($billing_row['code_type'] == 'COPAY')
    {
        $billing_row['pay_amount'] = floatval($billing_row['code']);
    }
    
    //copay does not contribute to the charge total, but has its payment amount in the charge
    //field, so we have to ignore it
    if ($billing_row['code_type'] != 'COPAY')
        $totals_data['chg'] += $billing_row['fee'];
    
    $totals_data['pay'] += $billing_row['pay_amount'];
    $totals_data['adj'] += $billing_row['adj_amount'];
    
    //since copay fields have different meanings, we need to handle balance calculation separately
    if ($billing_row['code_type'] == 'COPAY')
    {
        $totals_data['bal'] -= $billing_row['pay_amount'];
    }
    else
    {
        $totals_data['bal'] += ($billing_row['fee'] - $billing_row['pay_amount'] - $billing_row['adj_amount']);
    }
    
    $billing_data[] = $billing_row;
}

/* debugging. use to see a table's content.
$sql_data = array();
$sql_res = sqlStatement("select type, ic.name as plan_name from insurance_data as insd
                             left join insurance_companies as ic on insd.provider = ic.id
                             where pid=$pid and ic.name != ''");

while ($sql_row = sqlFetchArray($sql_res))
    $sql_data[] = $sql_row;

print_r($sql_data);
*/

//start populating the table
$last_encounter = "";
foreach ($billing_data as $billing_line)
{
    //keep track of if the current billing line is from a different encounter than the previous, so we may add some formatting
    $is_diff_encounter = ($billing_line['encounter']!=$last_encounter);
    
    echo "<tr".($is_diff_encounter?" class='new_encounter_row'":"").">\n";
    
    //we explode because 'date' is "{date} {time}", and we only want the first field
    $date_time = explode(' ',$billing_line['date']);
    echo "<td>".($is_diff_encounter?$date_time[0]:"")."</td>\n";
    
    //for some reason, the developers decided to put the copayment amount in the "code" field if the code_type is COPAY
    echo "<td>".($billing_line['code_type'] == "COPAY" ? "CO-PAY" : $billing_line['code'])."</td>\n";
    
    echo "<td>".$billing_line['code_text']."</td>";
    
    //chg paid adj bal
    if ($billing_line['code_type'] == "COPAY")
    {
        echo "<td class='dollar_column'>0.00</td>";
        echo "<td class='dollar_column'>".sprintf("%.2f",-$billing_line['fee'])."</td>";
        echo "<td class='dollar_column'>".sprintf("%.2f",$billing_line['adj_amount'])."</td>";
        echo "<td class='dollar_column'>".sprintf("%.2f",$billing_line['fee'])."</td>";
    }
    else
    {
        echo "<td class='dollar_column'>".sprintf("%.2f",$billing_line['fee'])."</td>";
        echo "<td class='dollar_column'>".sprintf("%.2f",$billing_line['pay_amount'])."</td>";
        echo "<td class='dollar_column'>".sprintf("%.2f",$billing_line['adj_amount'])."</td>";
        echo "<td class='dollar_column'>".sprintf("%.2f",$billing_line['fee']-$billing_line['adj_amount']-$billing_line['pay_amount'])."</td>";
    }
    echo "</tr>\n";
    
    $last_encounter = $billing_line['encounter'];
}
//do the itemization total
?>
<tr><td>&nbsp;</td></tr>
<tr style='font-weight:bold; border-top: 3px solid black;'><td></td><td></td><td>TOTALS</td>
    <?php foreach (array('chg','pay','adj','bal') as $column): ?>
    <td class='dollar_column'><?php echo sprintf("%.2f",$totals_data[$column]); ?></td>
    <?php endforeach; ?>
</tr>

</tbody>
</table>
</body>
</html>