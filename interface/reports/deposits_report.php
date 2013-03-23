<?php
// Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../globals.php");
require_once("../../library/patient.inc");
require_once("../../library/sql-ledger.inc");
require_once("../../library/invoice_summary.inc.php");
require_once("../../library/sl_eob.inc.php");
require_once("../../library/formatting.inc.php");
require_once "$srcdir/options.inc.php";
require_once "$srcdir/formdata.inc.php";

$INTEGRATED_AR = $GLOBALS['oer_config']['ws_accounting']['enabled'] === 2;

$alertmsg = '';
$bgcolor = "#aaaaaa";
$today = date("Y-m-d");
$form_date  = fixDate($_POST['form_date'], "");
$form_to_date  = fixDate($_POST['form_to_date'], "");
$form_facility  = $_POST['form_facility'];
$searchby = array('Billing Date' => 'bill_date', 'Deposit Date' => 'deposit_date', 'Posting Date' => 'post_time', 'Service Date' => 'f.date');
$initial_colspan = 1;
$final_colspan = $form_cb_adate ? 8 : 7;
$grand_total_charges     = 0;
$grand_total_adjustments = 0;
$grand_total_amt_paid  = 0;
$grand_total_pt_paid = 0;

//if (!$INTEGRATED_AR) SLConnect();

function bucks($amount) {
  if ($amount)
    echo oeFormatMoney($amount); // was printf("%.2f", $amount);
}

function endPatient($ptrow) {
  global $export_patient_count, $export_dollars, $bgcolor;
  global $grand_total_charges, $grand_total_adjustments, $grand_total_amt_paid, $grand_total_pt_paid; 
  global $grand_total_agedbal, $is_due_ins, $form_age_cols;
  global $initial_colspan, $final_colspan, $form_cb_idays, $form_cb_err;

  if (!$ptrow['pid']) return;

  $pt_balance = $ptrow['amount'] - $ptrow['amt_paid'] - $ptrow['pt_paid'];

  if ($_POST['form_csvexport']) {
    $export_patient_count += 1;
    $export_dollars += $pt_balance;
  }
  
   $grand_total_charges += $ptrow['charges'];
  $grand_total_adjustments += $ptrow['adjustments'];
  //$grand_total_paid        += $ptrow['paid'];
  $grand_total_amt_paid += $ptrow['amt_paid'];
  $grand_total_pt_paid += $ptrow['pt_paid'];
  for ($c = 0; $c < $form_age_cols; ++$c) {
    $grand_total_agedbal[$c] += $ptrow['agedbal'][$c];
  }
}


function endInsurance($insrow) {
  global $export_patient_count, $export_dollars, $bgcolor;
  global $grand_total_charges, $grand_total_adjustments, $grand_total_ins_paid, $grand_total_pt_paid;
  global $grand_total_agedbal, $is_due_ins, $form_age_cols;
  global $initial_colspan, $form_cb_idays, $form_cb_err;
  if (!$insrow['pid']) return;
  $ins_balance = $insrow['amount']  - $insrow['ins_paid'] - $insrow['pt_paid'];
  if ($_POST['form_export'] || $_POST['form_csvexport']) {
    // No exporting of insurance summaries.
    $export_patient_count += 1;
    $export_dollars += $ins_balance;
  }
  else {
    echo " <tr bgcolor='$bgcolor'>\n";
    echo "  <td class='detail'>" . $insrow['insname'] . "</td>\n";
    echo "  <td class='detotal' align='right'>&nbsp;" .
      oeFormatMoney($insrow['charges']) . "&nbsp;</td>\n";
    echo "  <td class='detotal' align='right'>&nbsp;" .
      oeFormatMoney($insrow['adjustments']) . "&nbsp;</td>\n";
    echo "  <td class='detotal' align='right'>&nbsp;" .
      oeFormatMoney($insrow['ins_paid']) . "&nbsp;</td>\n";
    echo "  <td class='detotal' align='right'>&nbsp;" .
      oeFormatMoney($insrow['pt_paid']) . "&nbsp;</td>\n";
    if ($form_age_cols) {
      for ($c = 0; $c < $form_age_cols; ++$c) {
        echo "  <td class='detotal' align='right'>&nbsp;" .
          oeFormatMoney($insrow['agedbal'][$c]) . "&nbsp;</td>\n";
      }
    }
    else {
      echo "  <td class='detotal' align='right'>&nbsp;" .
        oeFormatMoney($ins_balance) . "&nbsp;</td>\n";
    }
    echo " </tr>\n";
  }
  $grand_total_charges     += $insrow['charges'];
  $grand_total_adjustments += $insrow['adjustments'];
  $grand_total_ins_paid        += $insrow['ins_paid'];
  $grand_total_pt_paid        += $insrow['pt_paid'];
  for ($c = 0; $c < $form_age_cols; ++$c) {
    $grand_total_agedbal[$c] += $insrow['agedbal'][$c];
  }
}

function getInsName($payerid) {
  $tmp = sqlQuery("SELECT name FROM insurance_companies WHERE id = '$payerid'");
  return $tmp['name'];
}

// In the case of CSV export only, a download will be forced.
if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=deposits_report.csv");
  header("Content-Description: File Transfer");
}
else {
?>
<html>
<head>
<?php if (function_exists('html_header_show')) html_header_show(); ?>
<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>

<title><?php xl('Deposits Report','e')?></title>
<style type="text/css">

@media print {
    #report_parameters {
        visibility: hidden;
        display: none;
    }
    #report_parameters_daterange {
        visibility: visible;
        display: inline;
    }
    #report_results {
       margin-top: 30px;
    }
}

/* specifically exclude some from the screen */
@media screen {
    #report_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}

</style>

<script language="JavaScript">

function checkAll(checked) {
 var f = document.forms[0];
 for (var i = 0; i < f.elements.length; ++i) {
  var ename = f.elements[i].name;
  if (ename.indexOf('form_cb[') == 0)
   f.elements[i].checked = checked;
 }
}

</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class='body_top' >

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Deposits','e'); ?></span>

<form method='post' action='<?php echo htmlentities($_SERVER['PHP_SELF']); ?>' enctype='multipart/form-data' id='theform'>

<div id="report_parameters">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>
<table>
 <tr>
  <td width='72%'>
        <div style='float:left'>

        <table class='text'>
                <tr>
                        <td class='label' >
                                <?php xl('Facility','e'); ?>:
                        </td>
                        <td colspan='5'>
                        <?php dropdown_facility(strip_escape_custom($form_facility), 'form_facility', true); ?>
                        </td>
                </tr>
            <tr>
            <td class='label' align="right"><?php xl('Search By:','e'); ?></td>
                                <td >
                           <select name='form_searchby'><?php
                                //build options for search date.
                                      foreach ($searchby as $key => $value) {
                                      echo "    <option value='$value'";
                                      if ($_POST['form_searchby'] == $value) {
                                      echo " selected";
                                     } else {
                                if (!$_POST['form_searchby'] && $key == 'Deposit Date') {
                                echo " selected"; //Default search
                                }
                                  }
                               echo ">" . xl($key) . "</option>\n";
                               }

                                ?></select></td><td class='label'>
                           <?php xl('From','e'); ?>:
                        </td>
                                      <td>
                           <input type='text' name='form_date' id="form_date" size='10' value='<?php echo $form_date ?>'
                                onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
                           <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
                                id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
                                title='<?php xl('Click here to choose a date','e'); ?>'>
                        </td>
                        <td class='label'>
                           <?php xl('To','e'); ?>:
                        </td>
                        <td>
                           <input type='text' name='form_to_date' id="form_to_date" size='10' value='<?php echo $form_to_date ?>'
                                onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
                           <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
                                id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
                                title='<?php xl('Click here to choose a date','e'); ?>'>
                        </td>
            </tr>
        </table>

        </div>
        </td>
  <td align='left' valign='middle' height="100%">
        <table style='border-left:1px solid; width:100%; height:100%' >
                <tr>
                        <td>
                                <div style='margin-left:15px'>
                                        <a href='#' class='css_button' onclick='$("#form_refresh").attr("value","true"); $("#form_csvexport").attr("value",""); $("#theform").submit();'>
                                        <span>
                                                <?php xl('Submit','e'); ?>
                                        </span>
                                        </a>

                                        <?php if ($_POST['form_refresh'] || $_POST['form_csvexport']) { ?>
                                        <div id="controls">
                                        <a href='#' class='css_button' onclick='window.print()'>
                                                <span>
                                                        <?php xl('Print','e'); ?>
                                                </span>
                                        </a>
                                        <a href='#' class='css_button' onclick='$("#form_refresh").attr("value",""); $("#form_csvexport").attr("value","true"); $("#theform").submit();'>
                                                <span>
                                                        <?php xl('CSV Export','e'); ?>
                                                </span>
                                        </a>
                                        </div>
                                        <?php } ?>
                                </div>
                        </td>
                </tr>
        </table>
  </td>
 </tr>
</table>

</div> <!-- end of parameters -->

<?php
     } // end not form_csvexport
     if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
     $rows = array();
     $where = "ars.payer_id NOT REGEXP '^[0,8]$' AND ar.pay_amount != 0 ";
 
    if ($INTEGRATED_AR) {
      if ($form_date) {
            if ($where)
              $where .= " AND ";
            if ($form_to_date) {
              $where .= "$form_searchby >= '$form_date 00:00:00' AND $form_searchby <= '$form_to_date 23:59:59'";
            } else {
              $where .= "$form_searchby >= '$form_date 00:00:00' AND $form_searchby <= '$form_date 23:59:59'";
            }
         }
         if ($form_facility) {
            if ($where)
              $where .= " AND ";
            $where .= "f.facility_id = '$form_facility'";
          }
         if (!$where) {
            $where = "1 = 1";
         }

          $query = "SELECT inscomp.name, b.code AS bcode, " .
            "ars.payer_id, ars.reference,  ars.check_date, ars.deposit_date, ar.pay_amount, ars.pay_total, " .
            "ar.pid, ar.encounter, f.id, f.date AS fdate, f.facility, b.bill_date, ar.post_time, u.lname AS provider " .
            "FROM ar_session AS ars " .
            "JOIN ar_activity AS ar ON ars.session_id = ar.session_id " .
            "JOIN form_encounter AS f ON f.pid = ar.pid AND f.encounter = ar.encounter " .
            "LEFT OUTER JOIN billing AS b ON b.pid = f.pid AND b.encounter = f.encounter AND b.code = ar.code " .
            "LEFT JOIN insurance_companies AS inscomp ON inscomp.id = ars.payer_id " .
            "LEFT OUTER JOIN users AS u ON u.id = b.provider_id " .
            "WHERE $where " .
            //"AND $form_searchby >= '$form_date 00:00:00' AND $form_searchby <= '$form_to_date 23:59:59' " .
            "ORDER BY ars.payer_id, reference, $form_searchby";
              
            $eres = sqlStatement($query);
        ?>
        
         <?php
                  $grand_total_amt_paid = 0;
                    //This removes the "." from "f.date" to enable $erow[$form_searchby] to be valid.
                    //"f.date" needs to be used in the query WHERE clause as the fdate alias is not allowed.
                    $form_searchby = str_replace(".", "", "$form_searchby");

                  while ($erow = sqlFetchArray($eres)) {
                    //echo $erow["$form_searchby"]; //debug
                    //echo "<pre>";
                    //print_r ($erow);
                    //echo "</pre>";

                    $row = array();

                    $row['id'] = $erow['id'];
                    $row['invnumber'] = $erow['pid'] . "." . $erow['encounter'];
                    $row['custid'] = $patient_id;
                    $row['name'] = $erow['name'];
                    $row['address1'] = $erow['street'];
                    $row['city'] = $erow['city'];
                    $row['state'] = $erow['state'];
                    $row['zipcode'] = $erow['postal_code'];
                    $row['phone'] = $erow['phone_home'];
                    $row['duncount'] = $duncount;
                    $row['dos'] = substr($erow['fdate'], 0, 10);
                    $row['search_date'] = $erow['$form_searchby'];
                    $row['deposit_date'] = $erow['deposit_date'];
                    $row['ss'] = $erow['ss'];
                    $row['DOB'] = $erow['check_date'];
                    $row['pubpid'] = $erow['pubpid'];
                    $row['billnote'] = ($erow['genericname2'] == 'Billing') ? $erow['genericval2'] : '';
                    $row['provider'] = $erow['provider'];
                    $row['amt_paid'] = $erow['pay_amount'];
                    $row['chk_total'] = $erow['pay_total'];
                    $row['search_date'] = substr($erow["$form_searchby"], 0, 10);
                    $row['check_num'] = $erow['reference'];
                    if ($erow['payer_id'] != 1) {
                      $row['ins1'] = $erow['name'];
                    } else {
                      $row['ins1'] = 'Patient';
                    }
                    $row['cpt'] = $erow['bcode'];
                    $rows[$erow['payer_id'] . '|' . $erow['pid'] . '|' . $erow['encounter']] = $row;
                  }


              if ($_POST['form_csvexport']) {
                // CSV headers:
                if (true) {
                  echo '"Check Number",';
                  echo '"Insurance",';
                  echo '"Provider",';
                  echo '"Invoice",';
                  echo '"DOS",';
                  echo '"' . array_search($form_searchby, $searchby) . '",';
                  echo '"Deposit Date",';
                  echo '"CPT",';
                  echo '"Amt Paid",';
                  echo '"Chk Total",' . "\n";
                }
              } else {
          ?> <div id="report_results">
                  <table >
                  
                     <thead>
                      <th>
                      <?php xl('Check Number','e'); ?>
                       </th>
                       <th>
                       <?php xl('Insurance','e'); ?>
                      </th>
                     <th>
                     <?php xl('Provider','e'); ?>
                     </th>
                     <th>
                     <?php xl('Invoice','e'); ?>
                     </th>
                     <th >
                     <?php xl('Svc Date','e'); ?>
                     </th>
                      <th >
                     <?php xl(array_search($form_searchby, $searchby), 'e') ?>
                      </th>
                     <th >
                     <?php xl('Deposit Date','e'); ?>
                     </th>
                     <th align="right">
                     <?php xl('CPT','e'); ?>
                     </th>
                     <th align="right">
                     <?php xl('Amt Paid','e'); ?>
                     </th>
                     <th align="right">
                     <?php xl('Chk Total','e'); ?>
                     </th>
                     </thead>


          <?php
              }
          $orow = -1;
          foreach ($rows as $key => $row) {
              if (!$is_ins_summary && !$_POST['form_csvexport']) {
              ?>
                    <tr>
                      <td bgcolor = '<?php echo ($bgcolor = (($row['amt_paid'] == 0) ? "#ffff00" : $bgcolor)); ?>' class="detail">
                        &nbsp;<?php echo $row['check_num']; ?>
                      </td>
                      <td bgcolor = '<?php echo $bgcolor; ?>' class='detail'>
                        &nbsp;<?php echo $row['ins1']; ?>
                      </td>
                      <td bgcolor = '<?php echo $bgcolor; ?>' class='detail'>
                        &nbsp;<?php echo $row['provider']; ?>
                      </td>
                      <td bgcolor = '<?php echo $bgcolor; ?>' class="detail">
                        &nbsp;<a href="../billing/sl_eob_invoice.php?id=<?php echo $row['id'] ?>"
                                 target="_blank"><?php echo $row['invnumber'] ?></a>
                      </td>

                      <td bgcolor = '<?php echo ($bgcolor = (($row['amt_paid'] == 0) ? "#ffff00" : $bgcolor)); ?>' class="detail" align="left">
                        &nbsp;<?php echo $row['dos']; ?>
                      </td>
                      <td bgcolor = '<?php echo $bgcolor; ?>' class='detail'>
                        &nbsp;<?php echo $row['search_date']; ?>
                      </td>
                      <td bgcolor = '<?php echo $bgcolor; ?>' class="detail" align="left">
                <?php echo $row['deposit_date'] ?>&nbsp;
                      </td>
                      <td bgcolor = '<?php echo $bgcolor; ?>' class="detail" align="right">
                <?php echo $row['cpt'] ?>&nbsp;
                      </td>
                    <td bgcolor = '<?php echo $bgcolor; ?>' class="detail" align="right">
                <?php bucks($row['amt_paid']) ?>&nbsp;
                <?php $bgcolor = (($orow & 1) ? "#ffdddd" : "#ddddff"); ?>
                    </td>
                    <td bgcolor = '<?php echo $bgcolor; ?>' class="detail" align="right">
                <?php bucks($row['chk_total']) ?>&nbsp;
                <?php $bgcolor = (($orow & 1) ? "#ffdddd" : "#ddddff"); ?>
                    </td>
                </tr>
          <?php
          $bgcolor = ((++$orow & 1) ? "#ffdddd" : "#ddddff");
                        $grand_total_amt_paid += $row['amt_paid'];
                          } // end not export and not insurance summary
                else if ($_POST['form_csvexport']) {
                  // The CSV detail line is written here.
                  echo '"' . $row['check_num'] . '",';
                  echo '"' . $row['ins1'] . '",';
                  echo '"' . $row['provider'] . '",';
                  echo '"' . $row['invnumber'] . '",';
                  echo '"' . $row['dos'] . '",';
                  echo '"' . $row['search_date'] . '",';
                  echo '"' . $row['deposit_date'] . '",';
                  echo '"' . $row['cpt'] . '",';
                  echo '"' . oeFormatMoney($row['amt_paid']) . '",';
                  echo '"' . oeFormatMoney($row['chk_total']) . '"' . "\n";
                } // end $form_csvexport
              } // end loop
if (!$_POST['form_csvexport']) {
                  echo " <tr bgcolor='#ffffff'>\n";
                  echo "  <td class='detail' colspan='6'>\n";
                  echo "   &nbsp;</td>\n";
                  echo "  <td class='dehead' colspan='2'" .
                  "'>&nbsp;Total Posted Deposits:</td>\n";
                  echo "  <td class='dehead' align='right'>&nbsp;" .
                  oeFormatMoney($grand_total_amt_paid) . "&nbsp;</td><td>&nbsp;</td>\n";
                  echo " </tr>\n";
          ?>
            
                </table>    </div>
        <?php
}

} // end $INTEGRATED_AR
}
 ?>

<?php if (!$_POST['form_csvexport']) {
if ((isset($_POST['form_refresh']) || isset($_POST['form_csvexport'])) && $grand_total_amt_paid == '0')
        {
                echo "<span style='font-size:10pt;'>";
                echo xl('No matches found. Try search again.','e');
                echo "</span>";
                echo '<script>document.getElementById("report_results").style.display="none";</script>';
                echo '<script>document.getElementById("controls").style.display="none";</script>';
 }
?>


<?php
 
if (!$_POST['form_refresh'] && !$_POST['form_csvexport']) { ?>
<div class='text'>
        <?php echo xl('Please input search criteria above, and click Submit to view results.', 'e' ); ?>
</div>
<?php } ?>
</form>

<script language="JavaScript">
<?php
                if ($alertmsg) {
                  echo "alert('" . htmlentities($alertmsg) . "');\n";
                }
?>
            </script>


</body>

<!-- stuff for the popup calendar -->

<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>

<script language="Javascript">
 Calendar.setup({inputField:"form_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
 top.restoreSession();
</script>

</html>
<?php
  } // End not csv export
?>
