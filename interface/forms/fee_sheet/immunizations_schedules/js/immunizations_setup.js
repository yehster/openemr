/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function add_immunzation_schedule_controls()
{
    var schedule_neighbor=$("td.review_td");
    var td=$("<td class='schedule'></td>");
    schedule_neighbor.before(td);
    var template=$("<span class='schedule_template'></span>");    
    template.attr("data-bind","template: {name: 'schedules-main', data: schedule_info}");
    td.append(template);

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
        }
        ,"json"
    );
        
}

function bill_immunization(ci)
{
    top.restoreSession();
    var diag_list=[];
    var justify_string="";
    for(var idx=0;idx<ci.justify_info.length;idx++)
        {
            var justify_code=ci.justify_info[idx];
            var code_split=justify_code.code.split(":");
            var justify_json={code: code_split[1],
                              code_type:code_split[0],
                              description: ci.description};
            justify_string+=code_split[0]+"|"+code_split[1]+":";
            diag_list.push(justify_json);
        }
    var proc_list=[];
    var proc_info=ci.procedure_info;
    var proc_code_split=proc_info.code.split(":");
    var fee=proc_info.price;
    var proc={
        code: proc_code_split[1],
        code_type: proc_code_split[0],
        description: proc_info.description,
        fee: fee,
        justify: justify_string,
        units: 1,
        modifiers:""
        
    };
    proc_list.push(proc);
    
    $.post(review_ajax,{
        pid: pid,
        encounter: enc,
        task: 'add_diags',
        diags: JSON.stringify(diag_list),
        procs: JSON.stringify(proc_list)
    },
    function(data)
        {
            refresh_codes();
        }

    );

}
choose_imm_entry = function(data)
{
    choose_imm_entry_base(data,bill_immunization);
}

var sch_vm;
$(document).ready(function()
{
    add_immunzation_schedule_controls();
}
);