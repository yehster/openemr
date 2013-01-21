/**
* knockoutjs view model for syndromic surveillance
*
* Copyright (C) 2013 Kevin Yeh <kevin.y@integralemr.com>
*
* LICENSE: This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 3
* of the License, or (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
*
* @package OpenEMR
* @author Kevin Yeh <kevin.y@integralemr.com>
* @link http://www.open-emr.org
*/

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

function display_event_data(data,encounter_id)
{
    var rp=view_model.reportParameters;
    rp.encounter.encounter_id(encounter_id)
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
            }else if(key=='date')
            {
                var date_str=data.encounter[key];
                var stripdate=date_str.replace(/[ :-]/g,"");
                rp.admit_date_time(stripdate);
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
        var enc_id=data.encounter;
        $.post(ajax_get_event_info,
        {
            pid:data.pid,
            encounter: data.encounter,
            list_id: data.list_id
        },
        function(data)
        {
            display_event_data(data,enc_id);
            view_model.tabs.selected_tab_idx(2);
            view_model.hl7Message.message("");
        },
        "json"
        );

    }
}
function show_hl7(data)
{
    view_model.hl7Message.message(data.hl7);
    view_model.tabs.selected_tab_idx(2);
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
    this.tabs={
                selected_tab_idx: ko.observable(1)
              }
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
                          type_options: {},
                          type: ko.observable(),
                          facility_options: {},
                          reporting_facility: ko.observable(),
                          event_facility: ko.observable(),
                          admit_date_time: ko.observable(),
                          discharge_date_time: ko.observable(),
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
                                encounter_id: ko.observable(),
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

