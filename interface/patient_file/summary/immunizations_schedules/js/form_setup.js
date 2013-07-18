
function add_immunzation_schedule_controls()
{
    var cvx_tr=$("#cvx_code").parents("tr");
    var td=$("<td class='schedule'></td>").appendTo(cvx_tr);
    var template=$("<span class='schedule_template'></span>");    
    template.attr("data-bind","template: {name: 'schedules-main', data: schedule_info}");
    td.append(template);

    var billing_template=$("<span></span>");
    billing_template.attr("data-bind","template: {name: 'billing-info', data: schedule_info}");
    var billing_row=$("<tr></tr>");
    var billing_cell=$("<td colspan='2'></td>");
    billing_cell.append(billing_template);
    billing_row.append(billing_cell);
    cvx_tr.siblings("tr:last").before(billing_row);
    sch_vm=new schedule_view_model();
    si=sch_vm.schedule_info;
    si.encounter(enc);
    $.post(ajax_info,
        {
            age_in_months: ageInMonths
        
        },
        function(data)
        {
            for(var idx=0;idx<data.schedules.length;idx++)
                {
                    var schedule=data.schedules[idx];
                    si.schedules.push(schedule);
                }
            for(idx=0;idx<data.codes.length;idx++)
                {
                    si.scheduleCodes.push(data.codes[idx]);
                }
            ko.applyBindings(sch_vm,template.get(0));
            ko.applyBindings(sch_vm,billing_template.get(0));
        }
        ,"json"
    );
        
    $("#add_immunization").on("submit",create_billing);
}

var sch_vm;
$(document).ready(function()
{
    add_immunzation_schedule_controls();
}
);