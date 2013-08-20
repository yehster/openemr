function quickPickResult(evt)
{
    var which=$(evt.target).val();
    if((which==="Passed Both") || which==="Passed Left")
    {
            $("#form_Left_Ear").val("PASSED");
    }
    if((which==="Passed Both") || which==="Passed Right")
    {
            $("#form_Right_Ear").val("PASSED");
    }
    $("input[type='submit']").click();
}

function setupControls()
{
    $("input[name='form_cb_lbf2']").click();
    var passBoth=$("<input type='button' id='passed_left' value='Passed Both'/>");
    var passLeft=$("<input type='button' id='passed_left' value='Passed Left'/>");
    var passRight=$("<input type='button' id='passed_left' value='Passed Right'/>");
    var notes=$("#form_Notes");
    notes.after(passRight);
    notes.after(passLeft);
    notes.after(passBoth);
    notes.after("<br>");
    
    passRight.click(quickPickResult);
    passLeft.click(quickPickResult);
    passBoth.click(quickPickResult);
    
    
}
setupControls();