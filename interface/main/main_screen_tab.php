<?php
 include_once("../globals.php");
 session_write_close();
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
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../../library/js/knockout/knockout-2.2.1.js"></script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type='text/javaScript'>
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>
</script>
<link rel="stylesheet" type="text/css" href="tabs/layout.css"/>
</head>
<body>
    <iframe src='daemon_frame.php' name='Daemon' style="display:none"></iframe>
        <div data-bind="template:'menu-base'"></div>
        <div style="height:1.4em;"></div>
        <div data-bind="template:'session-info'"></div>
        <div><iframe src='main_title_tab.php' name='Title'></iframe></div>
        <div class="right buttons">                    
            <ul data-bind="foreach: tabStates">                      
                        <li data-bind="css: {'active':visible}">
                            <span data-bind="text: title, click: tab_button_click, attr:{'tab_idx': $index}"></span>
                            <span data-bind="attr:{'tab_idx': $index}, click:tab_refresh">&#x27f3;</span>
                        </li>

                    </ul>
                    <input id="multiTabs" type="checkbox" title="Enable Multiple Tabs" data-bind="checked: multi"/>
        </div>
        <div data-bind="foreach: tabStates">
            <div class="main" data-bind="visible: visible,attr:{'tab_idx': $index}">
                <iframe class="main" data-bind="attr:{'src': src,'tab_idx': $index,'name': $index},event:{load: frame_ready}"></iframe>
            </div>
        </div>
        <div style="display:none">
            <iframe name="left_nav" src="left_nav_tab.php"></iframe>
        </div>
</body>
</html>
<?php require_once("tabs/setup.php"); ?>

