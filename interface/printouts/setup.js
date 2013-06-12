/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function setupPrintouts()
{
    var targetTable=$($("body>table").get(1));
    var printoutsTD=$("<td class='small'></td>");
    printoutsTD.appendTo(targetTable.find("tr"));
    var engLink=$("<a href='../../printouts/GrowingUpHealthy/generate.php')>Growing Up Healthy</a>");

    printoutsTD.append(engLink);
    engLink.before("&nbsp;|&nbsp;");

    engLink.after("&nbsp;|&nbsp;");

    var spLink=$("<a href='../../printouts/GrowingUpHealthy/generate.php?language=SP')>Growing Up Healthy-SP</a>");
    printoutsTD.append(spLink);
}
setupPrintouts();
