<?php
include_once("../globals.php");
require_once("$srcdir/authentication/rsa.php");
require_once("$srcdir/authentication/password_change.php");


$rsa_manager = new rsa_key_manager();
$rsa_manager->load_from_db($_REQUEST['pk']);
$curPass=$rsa_manager->decrypt($_REQUEST['curPass']);
$newPass=$rsa_manager->decrypt($_REQUEST['newPass']);
$newPass2=$rsa_manager->decrypt($_REQUEST['newPass2']);

if($newPass!=$newPass2)
{
    echo "Passwords Don't match!";
    exit;
}
$errMsg='';
$success=update_password($_SESSION['authId'],$_SESSION['authId'],$curPass,$newPass,$errMsg);
if($success)
{
    echo xlt("Password change successful");
}
else
{
    echo text($errMsg);
}
?>
