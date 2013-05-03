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


var display_controls =
        {
            "adm": "Administration",
            "edi": "EDI",
            "bil": "Billing",
            "new": "Demographics",
            "pre": "Prescription"
        }
function parseLoadFrame(entry,data)
{
    entry.type="LoadFrame";
    var fields=data.split(",");
    entry.setUrl(fields[2]);
    entry.setRequirement(entry.id.substr(3,1));
    entry.target=fields[1].replace(/\'/g,"");
    if(entry.url.indexOf('patient_file/encounter/load_form.php')>=0)
    {
        if(entry.parent.description.indexOf("Visit Forms")>=0)
            {
                entry.parent.dynamic="show forms";        
            }
    }

}
function parseRepPopup(entry,data)
{
    entry.type="Popup";
    entry.setUrl(data);
}
function evalUrl(data)
{
    
    this.url=data.replace(/\'/g,"")
}
function setRequirement(data)
{
    if(data=='1')
    {
        this.requirement="Patient";
    }
    if(data=='2')
    {
        this.requirement="Encounter";
    }
}
function menu_entry(desc)
{
    this.description=desc;
    if(this.description.indexOf("Messages (")===0)
        {
            this.description="Messages";
        }
    this.children=new Array();
    this.requirement="none";
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
    this.setUrl=evalUrl;
    this.setRequirement=setRequirement;
    return this;
}

function evaluate_id(entry)
{
    if(typeof entry.id==='string')
    {
        var key=entry.id.substr(0,3);
        if(typeof display_controls[key]!=='undefined')
            {
                entry.control=display_controls[key];
            }           
    }
}
function evaluate_entry(parent,idx,elem)
{
    var entry=$(elem);
    var mi=entry.children("a:first");
    var new_parent=new menu_entry(mi.text());
    new_parent.parent=parent;
    new_parent.id=mi.attr("id");
    evaluate_id(new_parent);
    new_parent.parse_onclick(mi.attr("onclick"));
    parent.children[idx]=new_parent;
    var sublist=entry.children("ul");
    sublist.children("li").each(function(idx,elem){evaluate_entry(new_parent,idx,elem);});
}

function evalute_popups(parent,idx,elem)
{
    var jqElem=$(elem);
    {
        if(jqElem.val()!=="")
            {
                var new_entry=new menu_entry(jqElem.text());
                new_entry.parent=parent;
                parent.children.push(new_entry);
                new_entry.type="Popup";
                new_entry.setUrl(jqElem.val());
            }

    }
    
}
function pretty_print(entry,depth)
{
    var ret_phrase="";
    var tab_str="";
    for(var tabs=0;tabs<depth;tabs++)
        {
            tab_str+="\t";
        }
    ret_phrase+=tab_str+"<menuitem";
    for(i in entry)
        {
        if(typeof entry[i]==='string')
        {
            ret_phrase+=" "+ i+"='"+entry[i]+"' ";
        }
    }
    
    if(entry.children.length>0)
        {
            ret_phrase+=">\n"
            for(var idx=0;idx<entry.children.length;idx++)
            {
                ret_phrase+=pretty_print(entry.children[idx],depth+1);
            }

            ret_phrase+=tab_str+"</menuitem>\n";
        }
        else
            {
                ret_phrase+="/>\n"
            }
    return ret_phrase;
}

function scan_tree()
{
    root=new menu_entry("root");
    root.type="root";
    var root_entries=$("#navigation-slide > li");
    root_entries.each(function(idx,elem){evaluate_entry(root,idx,elem);});
    var popup_menu=new menu_entry("Popups");
    popup_menu.type="header";
    root.children.push(popup_menu);
    var popup_entries=$("select[name='popups'] option").each(function(idx,elem){evalute_popups(popup_menu,idx,elem);});
    var xml=pretty_print(root,0);
    $.post("analysis/process_menu_xml.php",
            {menu:xml.replace(/&/g,'&amp;')},
        function(data)
        {
            //window.alert(data);
            loadFrame2("","RTop","main/analysis/menu.xml");
        }
    )
}

function setup_analyze()
{
    var refresh=$("<button>Refresh</button>").appendTo($("body"));
    refresh.on({click: function(evt){window.location.reload(true);}});
    var analzye=$("<button>Analyze</button>").appendTo($("body"));
    analzye.on({click: scan_tree});

}


