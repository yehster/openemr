/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/* This is code needed to connect the iframe for a dialog back to the window which makes the call.
 * It is neccessary to include this script at the "top" of any php file that is used as a dialog.
 * It was not possible to inject this code at "document ready" because sometimes the opened dialog 
 * has a redirect or a close before the document ever becomes ready.
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
