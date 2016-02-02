<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("$srcdir/registry.inc");
    
$menu_update_map=array();
$menu_update_map["Visit Forms"]="update_visit_forms";
$menu_update_map["Modules"]="update_modules_menu";

function update_modules_menu(&$menu_list)
{
    $module_query = sqlStatement("select mod_directory,mod_name,mod_nick_name,mod_relative_link,type from modules where mod_active = 1 AND sql_run= 1 order by mod_ui_order asc");
    if (sqlNumRows($module_query)) {
      while ($modulerow = sqlFetchArray($module_query)) {
                    $acl_section = strtolower($modulerow['mod_directory']);
                    $disallowed[$acl_section] = zh_acl_check($_SESSION['authUserID'],$acl_section) ?  "" : "1";
                    $modulePath = "";
                    $added 		= "";
                    if($modulerow['type'] == 0) {
                            $modulePath = $GLOBALS['customModDir'];
                            $added		= "";
                    }
                    else{ 	
                            $added		= "index";
                            $modulePath = $GLOBALS['zendModDir'];
                    }

                    $relative_link ="modules/".$modulePath."/".$modulerow['mod_relative_link'].$added;
                    $mod_nick_name = $modulerow['mod_nick_name'] ? $modulerow['mod_nick_name'] : $modulerow['mod_name'];
          $newEntry=new stdClass();
          $newEntry->label=xlt($mod_nick_name);
          $newEntry->url=$relative_link;
          $newEntry->requirement=0;
          $newEntry->target='mod';
          array_push($menu_list->children,$newEntry);
       }
    }
}
function update_visit_forms(&$menu_list)
{
    $baseURL="/interface/patient_file/encounter/load_form.php?formname=";
    $menu_list->children=array();
$lres = sqlStatement("SELECT * FROM list_options " .
  "WHERE list_id = 'lbfnames' ORDER BY seq, title");
if (sqlNumRows($lres)) {
  while ($lrow = sqlFetchArray($lres)) {
    $option_id = $lrow['option_id']; // should start with LBF
    $title = $lrow['title'];
    $formURL=$baseURL . urlencode($option_id);
    $formEntry=new stdClass();
    $formEntry->label=xl_form_title($title);
    $formEntry->url=$formURL;
    $formEntry->requirement=2;
    $formEntry->target='enc';
    array_push($menu_list->children,$formEntry);
  }
}

    $reg = getRegistered();
    if (!empty($reg)) {
      foreach ($reg as $entry) {
        $option_id = $entry['directory'];
              $title = trim($entry['nickname']);
        if ($option_id == 'fee_sheet' ) continue;
        if ($option_id == 'newpatient') continue;
        if (empty($title)) $title = $entry['name'];
        
        $formURL=$baseURL . urlencode($option_id);
        $formEntry=new stdClass();
        $formEntry->label=xl_form_title($title);
        $formEntry->url=$formURL;
        $formEntry->requirement=2;
        $formEntry->target='enc';
        array_push($menu_list->children,$formEntry);
      }
    }    
    error_log("Updating Visit Forms");
    
}
function menu_update_entries(&$menu_list)
{
    global $menu_update_map;
    for($idx=0;$idx<count($menu_list);$idx++)
    {

        $entry = $menu_list[$idx];
        if(!isset($entry->url))
        {
            if(isset($menu_update_map[$entry->label]))
            {
                $menu_update_map[$entry->label]($entry);
            }                
        }
        // Translate the labels 
        $entry->label=xlt($entry->label);
        // Recursive update of children
        if(isset($entry->children))
        {
            menu_update_entries($entry->children);
        }
    }
}