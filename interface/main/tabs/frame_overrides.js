function goPid(pid)
{
    top.restoreSession();
    top.RTop.location = pathWebroot+'patient_file/summary/demographics.php' + '?set_pid=' + pid;
    set_visible(1,false);
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
        frame.toencounter=function(data)
        {
            window.alert(data);
        };
    }
    $(frame.document).ready(function(){$("a[target='_parent']",frame.document).removeAttr("target")});    
}
