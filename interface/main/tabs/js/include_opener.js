/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



if(top.tab_mode===true)
{
    if(!opener)
    {
        opener=top.get_opener(window.name);
    }

    window.close=
            function()
            {
                var dialogDiv=top.$("#dialogDiv");
                var body=top.$("body");
                    var removeFrame=body.find("iframe.dialogIframe[name='"+window.name+"']");
                    removeFrame.remove();
                    if(body.children("iframe.dialogIframe").length===0)
                    {   
                        dialogDiv.hide();
                    };
                };    
}
