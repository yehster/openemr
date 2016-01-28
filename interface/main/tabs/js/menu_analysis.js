/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function analyze_menu()
{
    if(!top.left_nav)
    {
        setTimeout(analyze_menu,1000);
        return;
    }
    else
    {
        if(!top.left_nav.$)
        {
            alert("no jq!");
            setTimeout(analyze_menu,1000);
            return;
        }
    }
    var jqLeft=top.left_nav.$(top.left_nav.document)
    jqLeft.ready(function(){alert("jquery!");});
}
var toID=setTimeout(analyze_menu,1000);