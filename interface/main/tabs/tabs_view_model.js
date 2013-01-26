function goPid(pid)
{
    top.restoreSession();
    top.RTop.location = pathWebroot+'patient_file/summary/demographics.php' + '?set_pid=' + pid;
    set_visible(1,false);
}
function override_events(frame)
{
    if(typeof frame.goPid!='undefined')
    {
        frame.goPid= top.goPid;
    }
    if(typeof frame.openNewForm!='undefined') {
        frame.openNewForm=function(sel)
        {
            frame.location.href=sel;
        }
    }    
    $(frame.document).ready(function(){$("a[target='_parent']",frame.document).removeAttr("target")});    
}

function set_tab_title(proxy)
{
    var title=$(".title",proxy.frame.document).eq(0);
    if(title.length==1)
    {
        proxy.title(title.text());
        return;
    }    
    title=$(".main_title",proxy.frame.document).eq(0);
    if(title.length==1)
    {
        proxy.title(title.text());
        return;
    }     
    var subFrames=proxy.frame.frames;
    if(subFrames.length==1) {
            proxy.frame.location=subFrames[0].location.href;
    }
    else
        {
            if($("title",proxy.frame.document).length>0)
            {
                proxy.title($("title",proxy.frame.document).text());
                return;
            }
            else
            {
                if(proxy.frame.location.href.indexOf("calendar")>0)
                    {
                        proxy.title("Calendar");
                    }
                    else
                    {
                        var bold=$("b",proxy.frame.document);
                        if(bold.length==1)
                            {
                                proxy.title(bold.text());
                            }
                    }
            }
        }
}
function frame_ready(evt)
{

    var proxy=frame_proxies[$(this).attr("name")];
    proxy.jqFrame=$("iframe[name='"+$(this).attr("name")+"']");
    proxy.jqFrame.off('load');
    proxy.jqFrame.on({load:frame_ready});    
    override_events(proxy.frame);
    set_tab_title(proxy);
}
function frame_proxy(frame,default_title,idx)
{
    this.frame=frame;
    frame.idx=idx;
    this.location=frame.location;
    this.visible=ko.observable(false);
    this.title=ko.observable(default_title);
    this.idx=idx;
    this.jqFrame=$(frame).on({load:frame_ready});
    this.name=this.jqFrame.attr("name");
    this.watch("location", function(property,oldval,newval)
                    {
                        this.frame.location=newval;
                        if(newval.indexOf('demographics.php?set_pid')>0)
                            {
                                set_visible(1,false);
                            }
                    });
    return this;
}
function displayInFrame(old_fname,url,title)
{
    window[old_fname].location=url;
    window[old_fname].title(title);
    set_visible(window[old_fname].idx,false);
}

function set_visible(chosen_idx,toggle)
{
    if(view_model.multi())
        {
            if(toggle)
                {
                    view_model.tabStates[chosen_idx].visible(!view_model.tabStates[chosen_idx].visible());                   
                }
                else
                {
                    view_model.tabStates[chosen_idx].visible(true);                                       
                }
            var visible=0;
            for(var idx=0;idx<view_model.tabStates.length;idx++)
            {
                if(view_model.tabStates[idx].visible())
                    {
                        visible++;
                    }
            }
            if(visible==0)
                {
                    visible=1;
                    view_model.tabStates[chosen_idx].visible(true);
                }
            var size=100/visible+"%";
            $("div.main").css("width",size);
        }
        else
            {
                $("div.main").css("width","100%");
                for(var idx=0;idx<view_model.tabStates.length;idx++)
                {
                    if(idx==chosen_idx)
                        {
                            view_model.tabStates[idx].visible(true);
                        }
                        else
                        {
                            view_model.tabStates[idx].visible(false);
                        }
                }                
            }
}
function tab_button_click(data,event)
{
    var clicked_idx=event.target.attributes['tab_idx'].value;
    set_visible(clicked_idx,true);
}
function tabState(title,visible)
{
    this.visible=ko.observable(visible);
    this.title=ko.observable(title);
    return this;
}

function tabs_view_model()
{
    this.tabStates=[new frame_proxy(frames['main0'],"Calendar","0"),new frame_proxy(frames['main1'],"Patient","1"),new frame_proxy(frames['main2'],"Messages","2")];
    this.multi=ko.observable();
    return this;
}


var view_model=new tabs_view_model();
window.Cal=view_model.tabStates[0];
window.Cal.visible(true);
window.RTop=view_model.tabStates[1];
window.RBot=view_model.tabStates[2];    

frame_proxies={};
for(idx=0;idx<view_model.tabStates.length;idx++)
{
    var proxy=view_model.tabStates[idx];
    frame_proxies[proxy.name]=proxy;
}