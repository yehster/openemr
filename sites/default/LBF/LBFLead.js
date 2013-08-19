function enterUndetected()
{
    var data_entry=$("#form_lead").val("<3.3");
    $("input[type='submit']").click();
    
}

function setupUndetected()
{
    var tdEntry=$("#form_lead").parent("td");
    var inputNormal=$("<input type='button' value='Undetected' title='Submit for Lead Level &lt;3.3 mcg/dL and save'/>");
    tdEntry.append(inputNormal);
    inputNormal.click(enterUndetected);
}

setupUndetected();