/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



var ajax_base="interface/document_category_acl/ajax/";

function setup_ui()
{
    $.post(ajax_base+"load_ui.php",{category:category_id},function(data)
        {
            $("#categoryACL").html(data);
        }
    );
}

setup_ui();