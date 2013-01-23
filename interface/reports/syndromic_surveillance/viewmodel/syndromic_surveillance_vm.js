function diagnosis_object(description,code)
{
    this.description=ko.observable(description);
    this.code=ko.observable(code);
    this.diagnosis_type=ko.observable(); // Working, Admitting or Discharge
    return this;
}
function display_reportable_query(data)
{
    view_model.searchResults.encounter_info.removeAll();
    for(idx=0;idx<data.length;idx++)
        {
            view_model.searchResults.encounter_info.push(data[idx]);
        }
}

function display_event_data(data)
{
    var rp=view_model.reportParameters;
    for(var key in data.patient)
        {
            if(typeof rp.patient[key]!='undefined')
                {
                    rp.patient[key](data.patient[key]);                    
                }
        }
    for(key in data.encounter)
        {
            if(key=='diagnoses')
            {
                rp.encounter[key].removeAll();
                for(var idx=0;idx<data.encounter[key].length;idx++)
                {
                    var diag=data.encounter[key][idx];
                    rp.encounter['diagnoses'].push(new diagnosis_object(diag.description,diag.code));
                }
            }
            else
            {
                
                if(typeof rp.encounter[key]!='undefined')
                {
                    rp.encounter[key](data.encounter[key]);                    
                }
            }
        }
}
function search_reportable(data,event)
{
    data.from($("#form_from_date").val());
    data.to($("#form_to_date").val());

    $.post(ajax_get_encounters,{
        from_date: data.from(),
        to_date: data.to(),
        diags: JSON.stringify(data.diags())
        },
        display_reportable_query,
        "json"
    );
}
function choose_event(data,event)
{
    if(isNaN(parseInt(data.encounter)))
    {
        window.alert(NO_ENC_MESSAGE);
    }
    else
    {
        $.post(ajax_get_event_info,
        {
            pid:data.pid,
            encounter: data.encounter,
            list_id: data.list_id
        },
        display_event_data,
        "json"
        );

    }
}
function show_hl7(data)
{
    view_model.hl7Message.message(data.hl7);
}
function generate_hl7(data,event)
{
    $.post(ajax_generate_json,
        {
            data: ko.toJSON(view_model.reportParameters)
        },
        show_hl7,
        "json"
    );
}
function ss_view_model()
{
    this.searchParameters={
                            from: ko.observable(),
                            to: ko.observable(),
                            diags: {},
                            diag_options: {}
                           };
    
    this.searchResults={
                            encounter_info: ko.observableArray()
                       };
                       
    this.reportParameters=
                        {
                          facility_options: {},
                          reporting_facility: ko.observable(),
                          event_facility: ko.observable(),
                          patient:{
                              fname:ko.observable(),
                              lname:ko.observable(),
                              DOB: ko.observable(),
                              pubpid: ko.observable(),
                              sex: ko.observable(),
                              age: ko.observable()
                          },
                          encounter:
                              {
                                reason: ko.observable(),
                                date: ko.observable(),
                                diagnoses:ko.observableArray()
                              }
                        }
    this.hl7Message={
        message: ko.observable()
    }
    return this;
}

