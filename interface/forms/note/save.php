<!-- Work/School Note Form created by Nikolai Vitsyn: 2004/02/13 and update 2005/03/30 
     Copyright (C) Open Source Medical Software 

     This program is free software; you can redistribute it and/or
     modify it under the terms of the GNU General Public License
     as published by the Free Software Foundation; either version 2
     of the License, or (at your option) any later version.

     This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. -->

<?php
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");

/* 
 * name of the database table associated with this form
 */
$table_name = "form_note";

$print=false;
if ($encounter == "") $encounter = date("Ymd");
if(isset($_POST["print"]))
{
    if  ($_POST["print"]=="print"){
        $print=true;
    }
    unset($_POST["print"]);
}
if ($_GET["mode"] == "new") {
    $newid = formSubmit($table_name, $_POST, $_GET["id"], $userauthorized);
    addForm($encounter, "Work/School Note", $newid, "note", $pid, $userauthorized);
} 
elseif ($_GET["mode"] == "update") {
    $success = formUpdate($table_name, $_POST, $_GET["id"], $userauthorized);
}

if($print){
    ?>
<script>
 newwin = window.open("<?php echo $rootdir."/patient_file/report/custom_report.php?printable=1&note_".$_GET["id"]."=".$encounter; ?>");
</script>
    <?php
}
$_SESSION["encounter"] = $encounter;
formHeader("Redirecting....");
formJump();
formFooter();
?>
