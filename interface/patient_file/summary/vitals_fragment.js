function displayChart()
{
    var hidden_vitals=$("#hidden_vitals");
    var vitals_document=hidden_vitals.get(0).contentDocument;    
    $("#htmlchart",vitals_document).click();
}
function showGrowthChart()
{
    top.restoreSession();
    var hidden_vitals=$("#hidden_vitals");
    if(hidden_vitals.length==0)
    {
        hidden_vitals=$("<iframe style='display:none' onload='displayChart()' id='hidden_vitals' src='../encounter/trend_form.php?formname=vitals'></iframe)");
        hidden_vitals.appendTo($('body'));
    }
    else
    {
        var vitals_document=hidden_vitals.get(0);
        vitals_document.src=vitals_document.src;
    }
}
