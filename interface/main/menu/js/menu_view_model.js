/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function menu_view_model(data)
{
    this.data=data.children;
    return this;
}

function menu_click(data,evt)
{
    
    //top.displayInFrame(this.target,"",this.url);
    if(this.type==="LoadFrame")
    {
        tabs_navigate(pathWebroot+data.url,data.target);
    }
    return true;
}

function tabs_navigate(url,target)
{
    var tab_idx=target;
    view_model.tabStates()[tab_idx].src(url);
    set_visible(tab_idx,false);
}