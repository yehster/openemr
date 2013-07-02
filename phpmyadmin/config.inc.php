<?php
/*
 * Customized for OpenEMR.
 *
 */

// Access control is dealt with by the ACL check
$ignoreAuth = true;
require_once("../interface/globals.php");
require_once("../library/acl.inc");
if ($GLOBALS['disable_phpmyadmin_link']) {
  echo "You do not have access to this resource<br>";
  exit;
}
if (! acl_check('admin', 'database')) {
  echo "You do not have access to this resource<br>";
  exit;
}

/* Servers configuration */
$i = 0;

/* Server localhost (config:openemr) [1] */
$i++;
$port_delimiter=strpos($sqlconf['host'],":");
error_log($port_delimiter);
if($port_delimiter!==false)
{
    
    
    $cfg['Servers'][$i]['host'] = substr($sqlconf['host'],0,$port_delimiter);
    error_log($cfg['Servers'][$i]['host']);
    $cfg['Servers'][$i]['port'] = substr($sqlconf['host'],$port_delimiter+1);
}
else
{
    $cfg['Servers'][$i]['host'] = $sqlconf['host'];
}
/* For standard OpenEMR database access */
$cfg['Servers'][$i]['auth_type'] = 'config';
$cfg['Servers'][$i]['user'] = $sqlconf['login'];
$cfg['Servers'][$i]['password'] = $sqlconf['pass'];
$cfg['Servers'][$i]['only_db'] = $sqlconf['dbase'];

/* Other mods for OpenEMR */
$cfg['ShowCreateDb'] = false;
$cfg['ShowPhpInfo'] = TRUE;
?>
