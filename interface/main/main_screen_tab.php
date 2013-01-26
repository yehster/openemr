<?php
 include_once("../globals.php");
 require_once("$srcdir/formdata.inc.php");
 $_SESSION["encounter"] = "";

 // Fetching the password expiration date
 $is_expired=false;
 if($GLOBALS['password_expiration_days'] != 0){
 $is_expired = false;
 $q=formData('authUser','P');
 $result = sqlStatement("select pwd_expiration_date from users where username = '".$q."'");
 $current_date = date("Y-m-d");
 $pwd_expires_date = $current_date;
 if($row = sqlFetchArray($result)) {
  $pwd_expires_date = $row['pwd_expiration_date'];
 }

// Displaying the password expiration message (starting from 7 days before the password gets expired)
 $pwd_alert_date = date("Y-m-d", strtotime($pwd_expires_date . "-7 days"));

 if (strtotime($pwd_alert_date) != "" && strtotime($current_date) >= strtotime($pwd_alert_date) && 
     (!isset($_SESSION['expiration_msg']) or $_SESSION['expiration_msg'] == 0)) {

  $is_expired = true;
  $_SESSION['expiration_msg'] = 1; // only show the expired message once
 }
}

if ($is_expired) {
  $frame1url = "pwd_expires_alert.php"; //php file which display's password expiration message.
}
else if (!empty($_POST['patientID'])) {
  $patientID = 0 + $_POST['patientID'];
  $frame1url = "../patient_file/summary/demographics.php?set_pid=$patientID";
}
else if ($GLOBALS['athletic_team']) {
  $frame1url = "../reports/players_report.php?embed=1";
}
else if (isset($_GET['mode']) && $_GET['mode'] == "loadcalendar") {
  $frame1url = "calendar/index.php?pid=" . $_GET['pid'];
  if (isset($_GET['date'])) $frame1url .= "&date=" . $_GET['date'];
}
else if ($GLOBALS['concurrent_layout']) {
  // new layout
  if ($GLOBALS['default_top_pane']) {
    $frame1url=$GLOBALS['default_top_pane'];
  } else {
    $frame1url = "main_info.php";
  }
}
else {
  // old layout
  $frame1url = "main.php?mode=" . $_GET['mode'];
}

$nav_area_width = $GLOBALS['athletic_team'] ? '230' : '130';
if (!empty($GLOBALS['gbl_nav_area_width'])) $nav_area_width = $GLOBALS['gbl_nav_area_width'];
?>
<html>
<head>
<title>
<?php echo $openemr_name ?>
</title>
<script type="text/javascript" src="../../library/js/objectWatch.js"></script>
<script type="text/javascript" src="../../library/topdialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery.min.js"></script>
<script type="text/javascript" src="../../library/js/knockout/knockout-2.2.0.js"></script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type='text/javaScript'>
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>
</script>
<link rel="stylesheet" type="text/css" href="tabs/layout.css"/>
</head>
<body>
    <iframe src='daemon_frame.php' name='Daemon' style="display:none"></iframe>
    <table class="main_window body_top"">
        </tbody>
            <tr class="header">
                <td colspan="2"><iframe src='main_title.php' name='Title'></iframe></td>
                
            </tr>
            <tr>
                <td class="nav" rowspan="2">
                    <iframe name="left_nav" src="left_nav.php"></iframe>
                </td>
                <td  class="right buttons">
                    <ul>                      
                        <li tab_idx="0" id="butTab0" data-bind="text: tabStates[0].title, click: tab_button_click, css: {'active':tabStates[0].visible}"/></li>
                        <li tab_idx="1" id="butTab1" data-bind="text: tabStates[1].title, click: tab_button_click, css: {'active':tabStates[1].visible}"/></li>
                        <li tab_idx="2" id="butTab2" data-bind="text: tabStates[2].title, click: tab_button_click, css: {'active':tabStates[2].visible}"/></li>
                        <li><input id="multiTabs" type="checkbox" title="Enable Multiple Tabs"/></li>

                    </ul>
                </td>

            </tr>
            <tr>
                <td class="right">
                    <div tab_idx="0" id="divMain-0" class="main" data-bind="visible: tabStates[0].visible">
                        <iframe name="main0" class="main" src="main_info.php"></iframe>
                    </div>
                    <div tab_idx="1" id="divMain-1" class="main" data-bind="visible: tabStates[1].visible">
                        <iframe name="main1" class="main " src="../new/new.php"></iframe>
                    </div>
                    <div tab_idx="2" id="divMain-2" class="main" data-bind="visible: tabStates[2].visible">
                        <iframe name="main2" class="main" src="messages/messages.php"></iframe>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
<?php require_once("tabs/setup.php"); ?>

