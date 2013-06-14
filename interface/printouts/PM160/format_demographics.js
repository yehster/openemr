function reformat_demographics()
{
    var visible=["PATIENTINFORMATION","Mother","Father","Guardian"];
    var dem_list=$("#dem_list");
    var dem_info_div=$("#dem_data>div.tab");
    dem_list.hide();
    var dem_display=$("#dem_display");
    dem_list.find("li").each(function(idx,elem){
        var info_div=dem_info_div.get(idx);
        var jqInfo_div=$(info_div);
        var header_info=$(elem).text();
        var header_id=header_info.replace(/\s/g,"");
        var table =jqInfo_div.children("table");
        table.attr("id","dem_"+header_id);
        table.hide();
        table.detach();
        var tbody=table.find("tbody");
        tbody.prepend("<tr class='dem_header'><td>"+header_info+"</td></tr>");
        dem_display.append(table);
    });
    for(var idx=0;idx<visible.length;idx++)
    {
        $("#dem_"+visible[idx]).show();
    }
    
}
$("#reload").click(function()
    {
        window.location.reload();
    });
reformat_demographics();
