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
