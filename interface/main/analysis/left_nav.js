/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * global menu options:
 * inhouse_pharmacy
 * lab_exchange_enable
 * portal_offsite_enable
 * enable_fees_in_left_menu
 * enable_cdr
 * enable_cqm
 * enable_amc
 * enable_amc_tracking
 * 
 * use_charges_panel
 * gbl_nav_visit_forms
 */

//return loadFrame2
//return repPopup
var onclick_headers=
        {
            "return loadFrame2": parseLoadFrame,
            "return repPopup": parseRepPopup
        }

function parseLoadFrame(entry,data)
{
    entry.type="LoadFrame";
    var fields=data.split(",");
    entry.url=fields[2];
    entry.id=fields[0];
    entry.behavior=entry.id.substr(4,1);
    entry.target=fields[1];
    if(entry.url.indexOf('patient_file/encounter/load_form.php')>0)
    {
        entry.parent.dynamic="show forms";
    }
    entry.parent.dynamic="form";

}
function parseRepPopup(entry,data)
{
    entry.type="Popup";
    entry.url=data;
}
function menu_entry(desc)
{
    this.description=desc;
    this.children=new Array();
    this.parse_onclick=function(info)
    {
        if(typeof(info)==="undefined")
        {
            this.type="header";
        }
        else
        {
            var paren_loc=info.indexOf("(");
            if(paren_loc>0)
            {
                var selector=info.substring(0,paren_loc);
                var data=info.substring(paren_loc+1,info.indexOf(")"));
                onclick_headers[selector](this,data);      
            }
        }
    }
    return this;
}
function evaluate_entry(parent,idx,elem)
{

    var entry=$(elem);
    var mi=entry.children("a:first");
    var new_parent=new menu_entry(mi.text());
    new_parent.parent=parent;
    new_parent.parse_onclick(mi.attr("onclick"));
    parent.children[idx]=new_parent;
    var sublist=entry.children("ul");
    sublist.children("li").each(function(idx,elem){evaluate_entry(new_parent,idx,elem);});
}


function pretty_print(entry,depth)
{
    var ret_phrase="";
    var tab_str="";
    for(var tabs=0;tabs<depth;tabs++)
        {
            tab_str+="\t";
        }
    ret_phrase+=tab_str+"<menuitem>\n";
    for(i in entry)
        {
        if(typeof entry[i]==='string')
        {
            ret_phrase +=tab_str+"\t<"+i+">"+entry[i]+"</"+i+">"+"\n";
        }
    }
    if(entry.children.length>0)
        {
            ret_phrase +=tab_str+"\t<children>\n";
            for(var idx=0;idx<entry.children.length;idx++)
            {
                ret_phrase+=pretty_print(entry.children[idx],depth+1);
            }
            ret_phrase +=tab_str+"\t</children>\n"
            
        }
    ret_phrase+=tab_str+"</menuitem>\n";
    return ret_phrase;
}

function scan_tree()
{
    root=new menu_entry("root");
    root.type="root";
    var root_entries=$("#navigation-slide > li");
    root_entries.each(function(idx,elem){evaluate_entry(root,idx,elem);});
    var xml=pretty_print(root,0);
    window.alert(xml)
    var info=$("<div></div>").text(xml);
    info.appendTo($("body"));
//    window.alert(info.html());
}

function setup_analyze()
{
    var refresh=$("<button>Refresh</button>").appendTo($("body"));
    refresh.on({click: function(evt){window.location.reload(true);}});
    var analzye=$("<button>Analyze</button>").appendTo($("body"));
    analzye.on({click: scan_tree});

}


