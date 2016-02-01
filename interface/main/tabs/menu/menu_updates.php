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
    error_log("Updating modules");
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
    $formEntry->label=$title;
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
        $formEntry->label=$title;
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