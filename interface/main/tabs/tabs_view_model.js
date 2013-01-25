function override_events(frame)
{
    
}

function frame_ready(evt)
{

    var proxy=frame_proxies[$(this).attr("name")];
    proxy.jqFrame=$("iframe[name='"+$(this).attr("name")+"']");
    proxy.jqFrame.off('load');
    proxy.jqFrame.on({load:frame_ready});    
    override_events(proxy.frame);
    proxy.title($(this).attr("name"));
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
    this.watch("location", function(property,oldval,newval){this.frame.location=newval;});
    return this;
}
function displayInFrame(old_fname,url)
{
    window[old_fname].location=url;
    set_visible(window[old_fname].idx);
}

function set_visible(chosen_idx)
{
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
function tab_button_click(data,event)
{
    var clicked_idx=event.target.attributes['tab_idx'].value;
    set_visible(clicked_idx);
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