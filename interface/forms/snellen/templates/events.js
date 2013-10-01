function add_diagnosis()
{
    
}

$("#optometry_ref").click( function()
    {
        var notes=$("textarea[name='notes']");
        var phrase="Referred to optometry.";
        if(notes.val().indexOf(phrase)===-1)
            {
                var text=notes.val()+phrase;
                notes.val(text);    
            }
        $("input[name='Submit']").click();
    });
