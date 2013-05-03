/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function menu_view_model(data)
{
    this.data=data.children;
    return this;
}

function menu_click()
{
    
    //top.displayInFrame(this.target,"",this.url);
    left_nav.loadFrame2("",this.target,this.url);
    return true;
}