function tab_button_click(data,event)
{
    var clicked_idx=event.target.attributes['tab_idx'].value;
    for(var idx=0;idx<view_model.tabStates.length;idx++)
    {
        if(idx==clicked_idx)
            {
                view_model.tabStates[idx].visible(true);
            }
            else
                {
                    view_model.tabStates[idx].visible(false);
                }
    }
}
function tabState(title,visible)
{
    this.visible=ko.observable(visible);
    this.title=ko.observable(title);
    return this;
}
function tabVisible(element)
{
    window.alert(element.attributes['tab_idx'].value);
    return element.attributes['tab_idx'].value=='3';
}
function tabs_view_model()
{
    this.tabStates=[new tabState('calendar',true),new tabState('patient',false),new tabState('message',false)];
    return this;
}
var view_model=new tabs_view_model();