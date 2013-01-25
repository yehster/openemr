frames_map={};
frames_map['RTop']=frames['main2'];
frames_map['RBot']=frames['main3'];
RTop=frames['main2'];
RBot=frames['main3'];
function displayInFrame(frame,url)
{
    frames_map[frame].location=url;
}

var left_script="<script src='"+pathWebroot+"main/tabs/left_nav_updates.js'></script>"

function checkForLeft()
{
    if(typeof frames['left_nav'].$=='undefined')
        {
            setTimeout("checkForLeft()",100);
        }
        else
        {
            var left_script="<script src='tabs/left_nav_updates.js'></script>";
            frames['left_nav'].$("body").append(left_script);
                
        }
}
function checkForTitle()
{
    if(typeof frames['Title'].toencounter=='undefined')
        {
            setTimeout("checkForTitle()",100);
        }
        else
            {
                var doc=frames['Title'].document;
                var head=doc.getElementsByTagName("head");
                if(head.length>0)
                {
                    var script=doc.createElement("script");
                    script.setAttribute("src",pathWebroot+"main/tabs/main_title_updates.js");
                    head.item(0).appendChild(script);
                }            
    }
}
$(document).ready(
function()
{
    checkForLeft();
    checkForTitle();
    ko.applyBindings(view_model);
}
);
