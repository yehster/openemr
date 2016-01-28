/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function parse_link(link,entry)
{
    if(link)
    {
        var parameters=link.substring(link.indexOf('(')+1,link.indexOf(')'));
        if(link.indexOf("loadFrame2")===-1)
        {
            var url=parameters.replace(/\'/g,"").replace(/\"/g,"").replace("../","/interface/");
            entry.url=url;
            entry.target="report";
        }
        else
        {
            parameters=parameters.replace(/\'/g,"").replace(/\"/g,"");
            var params=parameters.split(",");
            entry.target=params[1];
            entry.url=params[2].replace("../","/");
            if(entry.url.indexOf("/")>0)
            {
                entry.url="/interface/"+entry.url;
            }

        }
    }
}

function menu_entry(label,link,menu_id)
{
    var self=this;
    self.label=label;
    self.menu_id=menu_id;
    parse_link(link,self);
    self.menu_target;
    self.children=[];
    self.requirement=0;
    
    if(menu_id)
    {
        if(menu_id.charAt(3)==='1')
        {
            alert(self.url);
            self.requirement=1;
        } else
        if(menu_id.charAt(3)==='2')
        {
            alert(self.url);
            self.requirement=2;
        }
    }

      
    return this;
}

function menu_entry_from_jq(elem)
{
    return new menu_entry(elem.text(),elem.attr("onClick"),elem.attr("id"));
}
var menu_entries=[];
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
    var $=top.left_nav.$;
    jqLeft.ready(function(){

        var menuTop=jqLeft.find("#navigation-slide");
        menuTop.children().each(
                function(idx,elem)
                {
                    // Header or content
                    var jqElem=$(elem);
                    var anchor=jqElem.children("a");
                    var subMenu = jqElem.children("ul");
                    
                    var newEntry=menu_entry_from_jq(anchor); 
                    if(subMenu.length>0)
                    {
                        // 2 (Second) level menu items
                        subMenu.children("li").each(function(idx,elem)
                        {
                            var sub_anchor=$(elem).children("a");
                            var sub_entry=menu_entry_from_jq(sub_anchor);
                            if(sub_anchor.length!==1)
                            {
                                alert(sub_anchor.text());
                            }
                            var subSubMenu=$(elem).children("ul");
                            //Third Level Menu Items
                            subSubMenu.children("li").each(function(idx,elem)   
                            {
                                var sub_sub_anchor=$(elem).children("a");
                                var sub_sub_entry=menu_entry_from_jq(sub_sub_anchor);
                                sub_entry.children.push(sub_sub_entry);
                                
                            });
                            //End Third Level Menu Items
                            newEntry.children.push(sub_entry);
                        });
                        // End Second level menu items
                    }
                    else
                    {

                        
                    };
                    menu_entries.push(newEntry);
                }
                    
        );
        // Process Complete
        var data=$("<div id='#menuData'></div>");
        data.text(JSON.stringify(menu_entries));
        $("body").append(data);
    });
}
var toID=setTimeout(analyze_menu,1000);