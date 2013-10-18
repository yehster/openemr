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

function title_weight()
{
    // find weight. Zero it out if can't find it
    var vitals=$("div#vitals");
    var title_frame=top.window["Title"];
    var title_info=$("#current_patient",title_frame.document);
    title_info.find(".weight").remove();
    var weight_label=vitals.find("span:contains('Weight:')");
    if(weight_label.length>0)
        {
            var date_marker='Most recent vitals from:';
            var date=vitals.find("b:contains('"+date_marker+"')");
            var date_text=date.text();
            date_text=date_text.substring(date_marker.length+date_text.indexOf(date_marker)+1);
            var weight_value=weight_label.siblings(":contains('kg')");
            var weight=weight_value.text();
            var pos=weight.indexOf('kg');
            var numStr=weight.substring(0,pos);
            var kg=parseFloat(numStr);
            var weight_info=$("<span class='weight'>&nbsp;&nbsp;&nbsp;Weight:"+kg+ " kg<span style='font-size:10px'>&nbsp;"+date_text+"</span></span>");
            title_info.append(weight_info);

        }
    
}

title_weight();