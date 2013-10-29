function save_section(obj)
{
        var retval={
            name: obj.name,
            type: obj.type,
        }
        retval.children=[];
        for(var idx=0;idx<obj.children().length;idx++)
        {
            retval.children[idx]=obj.children()[idx].persistentForm();
        }
        return retval;
}

function save_value(obj)
{
        var retval={
            name: obj.name,
            type: obj.type,
            value: obj.value()
        }
        retval.children=[];
        for(var idx=0;idx<obj.children().length;idx++)
        {
            retval.children[idx]=obj.children()[idx].persistentForm();
        }
        return retval;
}
function document_metadata(name,parent)
{
    this.name=name;
    this.children=ko.observableArray();
    this.type="undefined";
    if((parent!=null) &&(typeof parent!='undefined'))
    {
        this.parent=parent;
        this.parent.children.push(this);
    }
    var self=this;
    this.persistentForm=function()
    {
        return save_value(this);
    }    
    return this;
}

function document_section(name,parent)
{
    var retval=new document_metadata(name,parent);
    retval.type="section";
    retval.expanded=ko.observable(true);
    retval.persistentForm=function()
    {
        return save_section(retval);
    }    
    
    return retval;
}

function document_option_set(name,parent)
{
    var retval=new document_metadata(name,parent);
    retval.type="option_set";
    retval.value=ko.observable(false);
    retval.sub_components=ko.computed(function()
        {
            return retval.children().length>0 && !retval.value();
        }
        
        );
    return retval;
}

function document_option_select(name,parent,def,choices)
{
    var retval=new document_metadata(name,parent);
    retval.type="option_select";
    retval.value=ko.observable(false);
    retval.selection=ko.observable(def);
    retval.choices=ko.observableArray(choices);
    retval.sub_components=ko.computed(function()
        {
            return retval.children().length>0 && !retval.value();
        }
        
        );
    return retval;
}

function document_freetext(name,parent)
{
    var retval=new document_metadata(name,parent);
    retval.type="freetext";
    retval.value=ko.observable();
    return retval;
    
}

function document_choice(name,parent)
{
    var retval=new document_metadata(name,parent);
    retval.type="choice";
    retval.value=ko.observable(false);
    return retval;
}

function document_side(name,parent)
{
    var retval=new document_metadata(name,parent);
    retval.type="side";
    retval.value=ko.observable();
    retval.locations=ko.observableArray(["","Left","Right","Both"]);
    return retval;
    
}

function document_duration(name,parent,unit_options)
{
    var retval=new document_metadata(name,parent);
    retval.type="duration";
    retval.value=ko.observable();
    retval.units=ko.observable("days");
    if(typeof unit_options==='undefined')
        {
            unit_options=["days","weeks","months","hours","minutes","seconds"]
        }
    retval.unit_choices=ko.observableArray(unit_options);

    return retval;
    
}

// Free text description with duration option
function document_text_history(name,parent)
{
    var retval= new document_metadata(name,parent);
    retval.type="text_history";
    retval.value=ko.observable();
    
    return retval;
}

function document_text_finding(name,parent)
{
    var retval= new document_metadata(name,parent);
    retval.type="text_finding";
    retval.value=ko.observable();
    
    return retval;
}


function document_quantity(name,parent,units,unit_choices)
{
    var retval=new document_metadata(name,parent);
    retval.type="quantity";
    retval.value=ko.observable();
    retval.units=ko.observable(units);
    retval.showLabel=ko.observable(true);
    
    if(typeof unit_choices==='undefined')
        {
            unit_choices=[units];
        }
    retval.unit_choices=ko.observableArray(unit_choices);
    return retval;
    
}

function document_grouping(name,parent)
{
    var retval=new document_metadata(name,parent);
    retval.type="grouping";
    retval.persistentForm=function()
    {
        return save_section(retval);
    }       
    return retval;
}

function document_select(name,parent,def,options)
{
    var retval=new document_metadata(name,parent);
    retval.type="select";
    retval.value=ko.observable(def);
    retval.options=ko.observableArray(options);
    retval.showLabel=ko.observable(true);
    return retval;
}


function choice_list(parent,list)
{
    for(var i=0;i<list.length;i++)
        {
            var new_choice=new document_choice(list[i],parent);
        }
}

function add_liquids(sections)
{
    for(var i=0;i<sections.length;i++)
        {
            var liquid = new document_quantity("amount",sections[i],"ounces",["ounces per day","small cups  per day","large cups per day","cups per day"]);
        }
}

function set_and_duration(name,parent)
{
    var retval = new document_option_set(name,parent);
    var duration = new document_duration("duration",retval);
    return retval;
}

function document_phrase(name,parent,def)
{
    var retval=new document_metadata(name,parent);
    retval.type="phrase";
    retval.value=ko.observable(def);
    retval.default=def;

    return retval;
}

function document_date(name,parent)
{
    var retval=new document_metadata(name,parent);
    retval.type="date";
    retval.value=ko.observable();

    return retval;
    
}

function apply_metadata(md,entry)
{
    for (var prop in entry)
    {
        if(prop=='value')
        {
            md[prop](entry[prop]);
        }
    }
    for(var idx=0;idx<entry.children.length;idx++)
    {
        apply_metadata(md.children()[idx],entry.children[idx]);
    }
}

function apply_to_view(view_model,data)
{
    for(var idx=0;idx<data.length;idx++)
    {
        if(view_model.entries()[idx].name===data[idx].name)
            {
                apply_metadata(view_model.entries()[idx],data[idx]);
            }
    }
}