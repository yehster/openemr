<?php

/**
 * Copyright (C) 2015 Kevin Yeh <kevin.y@integralemr.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Kevin Yeh <kevin.y@integralemr.com>
 * @link    http://www.open-emr.org
 */

$sanitize_all_escapes = true;		//SANITIZE ALL ESCAPES

$fake_register_globals = false;		//STOP FAKE REGISTER GLOBALS
require_once("../../globals.php");


if(!acl_check('admin', 'practice'))
{
     die(xlt('Not authorized'));
}
$category_id=$_REQUEST['category'];

function get_aco_list()
{
    $retval=array(array("section_value"=>"none","value"=>"none","name"=>xl("None")));
    $sqlACO=" SELECT section_value,value,name FROM gacl_aco ORDER BY section_value, order_value ASC";
    $res=sqlStatement($sqlACO);
    while($row=  sqlFetchArray($res))
    {
        $row['name']=xl_gacl_group($row['name']);
        array_push($retval,$row);
    }
    return $retval;
}

function get_category_aco($category_id)
{
    $sqlCategoryACO=" SELECT acl_section_value, acl_value FROM categories WHERE id=? ";
    return sqlQuery($sqlCategoryACO,array($category_id));
}
?>


<script type="text/javascript" src="<?php echo $web_root."/library/js/knockout/knockout-2.2.1.js"; ?>"></script>
<script type="text/javascript">
    
    var aco_list=<?php echo json_encode(get_aco_list()) ?>;
    var aco_vm={
        options: ko.observableArray(aco_list),
        selected_aco: ko.observable()
    }
    var category_id=<?php echo json_encode($category_id); ?>;
    var category_aco=<?php echo json_encode(get_category_aco($category_id)); ?>;
    for(var option_idx=0;option_idx<aco_vm.options().length;option_idx++)
    {
        var cur_option=aco_vm.options()[option_idx];
        if(category_aco['acl_section_value']===cur_option['section_value'] &&
           category_aco['acl_value']===cur_option['value'])
        {
            aco_vm.selected_aco(cur_option);
        }
    }
    
</script>

<span><?php echo xlt("Category ACO requirement"); ?></span>

<select data-bind="options:options, optionsText: 'name',  value:selected_aco"></select>
<div id="status"></status>
<script>
    function update_aco(newValue)
    {
        $.post("<?php echo $webroot; ?>/interface/document_category_acl/ajax/set_aco.php",
            {
                category_id: category_id,
                section_value: newValue.section_value,
                value: newValue.value
            },
            function(data)
            {
                $("#status").html(data);
            }
        );
    }
    ko.applyBindings(aco_vm);
    aco_vm.selected_aco.subscribe(update_aco);
</script>