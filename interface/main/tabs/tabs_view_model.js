function tab_refresh(data,event)
{
    var tab_idx=parseInt($(event.target).attr("tab_idx"));
    var tab_info=view_model.tabStates()[tab_idx];
    tab_info.frame.location.reload(true);
    set_visible(tab_idx,false);
}



function set_tab_title(proxy)
{
    override_events(proxy.frame);
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

function frame_ready()
{
    this.frame = window.frames[parseInt(this.idx)+3]; // clunky but works for now!
    set_tab_title(this);
}

function frame_proxy(default_title,idx,src)
{
    this.visible=ko.observable(false);
    this.title=ko.observable(default_title);
    this.idx=idx;
    this.src=ko.observable(src);
    this.watch("location", function(property,oldval,newval)
    {
        if(typeof this.frame!=='undefined')
            {
                    if(this.frame.location!==newval)
                        {
                            this.frame.location=newval;
                        }
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
                    view_model.tabStates()[chosen_idx].visible(!view_model.tabStates()[chosen_idx].visible());                   
                }
                else
                {
                    view_model.tabStates()[chosen_idx].visible(true);                                       
                }
            var visible=0;
            for(var idx=0;idx<view_model.tabStates().length;idx++)
            {
                if(view_model.tabStates()[idx].visible())
                    {
                        visible++;
                    }
            }
            if(visible==0)
                {
                    visible=1;
                    view_model.tabStates()[chosen_idx].visible(true);
                }
            var size=100/visible+"%";
            $("div.main").css("width",size);
        }
        else
            {
                $("div.main").css("width","100%");
                for(var idx=0;idx<view_model.tabStates().length;idx++)
                {
                    if(idx==chosen_idx)
                        {
                            view_model.tabStates()[idx].visible(true);
                        }
                        else
                        {
                            view_model.tabStates()[idx].visible(false);
                        }
                }
                view_model.activeIdx=chosen_idx;
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

function encounter_info()
{
    this.id=ko.observable(0);
    this.date=ko.observable();
    return this;
}

function tabs_view_model()
{
    this.tabStates=ko.observableArray([new frame_proxy("Calendar","0","main_info.php")
                   ,new frame_proxy("Patient","1","finder/dynamic_finder.php")
                   ,new frame_proxy("Messages","2","messages/messages.php")]);
    this.multi=ko.observable();
    this.activeIdx=ko.observable(0);
    this.encounter=new encounter_info();
    
    return this;
}


var view_model=new tabs_view_model();
window.Cal=view_model.tabStates()[0];
window.Cal.visible(true);
window.RTop=view_model.tabStates()[1];
window.RBot=view_model.tabStates()[2];    

