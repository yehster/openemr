
$("input.dontsave").after("<button class='save_and_print'>Save and Print</button>");
$("button.save_and_print").click(function()
{
    $("button.save_and_print").after("<input name='print' value='print' type='hidden'></input>");
    top.restoreSession(); 
    $("#my_form").submit();
    return false;
})