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
        tabs_navigate(pathWebroot+data.url);
    }
    return true;
}

function tabs_navigate(url)
{
    view_model.tabStates()[view_model.activeIdx()].src(url);
    set_visible(view_model.activeIdx(),false);
}