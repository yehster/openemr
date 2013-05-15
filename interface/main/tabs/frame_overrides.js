function goPid(pid)
{
    top.restoreSession();
    top.RTop.location = pathWebroot+'patient_file/summary/demographics.php' + '?set_pid=' + pid;
    set_visible(1,false);
}

function set_encounter(data)
{
    var parse_data=data.split("~");
    var id=parse_data[0];
    var date=parse_data[1];
    view_model.encounter.id(id);
    view_model.encounter.date(date);
    var url=pathWebroot+'patient_file/encounter/encounter_top.php?set_encounter=' + id;
    top.tabs_navigate(url,2);
}

function override_events(frame)
{
//    window.alert("Override Events!");
    if(typeof frame.goPid!=='undefined')
    {
        frame.goPid= top.goPid;
    }
    if(typeof frame.openNewForm!=='undefined') {
        frame.openNewForm=function(sel)
        {
            frame.location.href=sel;
        };
    }
    if(typeof frame.toencounter!=='undefined') {
        frame.toencounter=set_encounter;
    }
    $(frame.document).ready(function(){$("a[target='_parent']",frame.document).removeAttr("target")});    
}
