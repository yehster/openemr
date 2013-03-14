function create_billing(evt)
{
    var billing_info={
        pid: pid,
        task: "add_diags",
        encounter: enc
    };
    var diags=[];
    var justify_string=""
    for(var idx=0;idx<si.justifyCodes().length;idx++)
    {
        var cur_code=si.justifyCodes()[idx];
        var code_parts=cur_code.code.split(":");
        var code=code_parts[1];
        var code_type=code_parts[0];
        justify_string+=code_type+"|"+code+":";
        var diag={description: cur_code.description,code: code, code_type:code_type};
        diags.push(diag);
    }
    billing_info.diags=JSON.stringify(diags);
    var proc=[];
    var proc_info=si.procedureCode();
    var proc_code_info=proc_info.code.split(":");
    proc[0]={description:proc_info.description,
             fee:proc_info.price,
             code: proc_code_info[1],
             code_type: proc_code_info[0],
             units: 1,
             justify: justify_string};
    billing_info.procs=JSON.stringify(proc);
    $.post(ajax_billing,
    billing_info,function()
    {
    
    },
    "json");
}

function update_immunization_info(code_info)
{
        $("#cvx_description").text(code_info.code_description);
        si.procedureCode(code_info.procedure_info);
        si.justifyCodes.removeAll();
        for(var idx=0;idx<code_info.justify_info.length;idx++)
        {
            si.justifyCodes.push(code_info.justify_info[idx]);

        }
    
}

function choose_imm_entry_base(data,callback)
{
    $("#cvx_code").val(data.cvx_code);
    $("input[name='manufacturer']").val(data.manufacturer);
    $.post(ajax_code_info,
            {
                pid: pid,
                lookup_description: "CVX:"+data.cvx_code,
                lookup_procedure_code: data.proc_codes,
                lookup_justify_codes: data.justify_codes
            },
            callback
            ,
            "json"
    );
}
function choose_imm_entry(data)
{
    choose_imm_entry_base(data,update_immunization_info);
}

function update_codes(data,info)
{
    if(info.initial_call==true)
        {
            info.initial_call=false;
            return;
        }
    $.post(ajax_info,
            {
                schedule_id:data.id
            },
            function(codes)
            {
                info.scheduleCodes.removeAll();
                for(var idx=0;idx<codes.codes.length;idx++)
                    {
                        info.scheduleCodes.push(codes.codes[idx]);
                    }
            }
            ,
            "json");
}
function schedule_view_model()
{
    
    this.schedule_info = {
        schedules:ko.observableArray(),
        scheduleChoice: ko.observable(),
        scheduleCodes:ko.observableArray(),
        initial_call:true,
        justifyCodes:ko.observableArray(),
        procedureCode:ko.observable({"code":"","description":"","price":""}),
        encounter:ko.observable()
    };
    var si=this.schedule_info;
    this.schedule_info.scheduleChoice.subscribe(function(data){update_codes(data,si);});
    return this;
}