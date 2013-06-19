/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function addItem(caption,link,parent)
{
    var separator="&nbsp;|&nbsp;";
    var item=$("<A>"+caption+"</a>");
    item.attr("href",link);
    parent.append(item);
    item.before(separator)
    return item;
}
function setupPrintouts()
{
    var separator="&nbsp;|&nbsp;";
    var targetTable=$($("body>table").get(1));
    var printoutsTD=$("<td class='small'></td>");
    printoutsTD.appendTo(targetTable.find("tr"));
    var engLink=$("<a href='../../printouts/GrowingUpHealthy/generate.php')>Growing Up Healthy</a>");

    printoutsTD.append(engLink);
    engLink.before(separator);

    engLink.after(separator);

    var spLink=$("<a href='../../printouts/GrowingUpHealthy/generate.php?language=SP')>Growing Up Healthy-SP</a>");
    printoutsTD.append(spLink);
    
    
    var pm160=$("<a>PM160</a>");
    pm160.attr("href","../../printouts/PM160/generate.php");
    printoutsTD.append(pm160);
    pm160.before(separator);

    var WIC=$("<a>WIC</a>");
    WIC.attr("href","../../printouts/WIC/generate.php");
    printoutsTD.append(WIC);
    WIC.before(separator);
    
    var SchoolNote=addItem("School Note","#",printoutsTD);
    SchoolNote.click(function()
    {
        $.post("../../printouts/DirectSchool/generate.php",{}
            ,function(data)
            {
                if(data!="Not Found!")
                {
                    window.open(data);                   
                }
                else
                {
                    window.alert("No note found");
                }
            });
    });
    
}
setupPrintouts();
