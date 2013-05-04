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
        view_model.tabStates()[1].src(pathWebroot+data.url);
        set_visible(1,false);
    }
    return true;
}