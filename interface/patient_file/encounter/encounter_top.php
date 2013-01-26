<?php
// Cloned from patient_encounter.php.

include_once("../../globals.php");
include_once("$srcdir/pid.inc");
include_once("$srcdir/encounter.inc");

if (isset($_GET["set_encounter"])) {
 // The billing page might also be setting a new pid.
 if(isset($_GET["set_pid"]))
 {
     $set_pid=$_GET["set_pid"];
 }
 else if(isset($_GET["pid"]))
 {
     $set_pid=$_GET["pid"];
 }
 else
 {
     $set_pid=false;
 }
 if ($set_pid && $set_pid != $_SESSION["pid"]) {
  setpid($set_pid);
 }
 setencounter($_GET["set_encounter"]);
}
?>
<html>
<head>
<?php html_header_show();?>
</head>
<script>
    window.location="forms.php";
</script>
    
</html>
