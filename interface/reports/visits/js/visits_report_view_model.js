/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var visits_view_model=
{
    parameters: {},
    results: {
        
    }
};

function setup_parameters()
{
    var parameters=visits_view_model.parameters;
    parameters.clinics=ko.observableArray();
    parameters.clinics_details=ko.observable(false);

    parameters.period_size=ko.observable(period_options[0]);
    for(var clinic_idx=0;clinic_idx<clinics.length;clinic_idx++)
    {
        
        parameters.clinics.push(
                    {   name: clinics[clinic_idx]
                       ,selected: ko.observable(false)
                    }
                );
    }
    
    parameters.providers=ko.observableArray();
    parameters.providers_details=ko.observable(false);
    
    for(var providers_idx=0;providers_idx<providers.length;providers_idx++)
    {
        
        providers[providers_idx].selected=ko.observable(false);
        parameters.providers.push(providers[providers_idx]);
    }
    
}

function process_results(data,status, jqXHR)
{
    visits_view_model.results.periods_map={};
    visits_view_model.results.clinics_map={};
    visits_view_model.results.providers_map={};
    
    for(var data_idx=0;data_idx<data.length;data_idx++)
    {
        var cur_data=data[data_idx];
        visits_view_model.results.periods_map[cur_data.period]=cur_data.period;
        if(cur_data.hasOwnProperty("facility"))
        {
            // Handle facility first, then provider id if present
            var facility_name=cur_data.facility;
            var cur_facility_data={};
            if(visits_view_model.results.clinics_map.hasOwnProperty(facility_name))
            {
                cur_facility_data=visits_view_model.results.clinics_map[facility_name];
            }
            else
            {
                visits_view_model.results.clinics_map[facility_name]=cur_facility_data;
                cur_facility_data.facility=facility_name;
            }
            if(cur_data.hasOwnProperty("provider_id"))
            {
                var cur_providers={};
                if(cur_facility_data.hasOwnProperty("providers"))
                {
                    cur_providers=cur_facility_data.providers;
                }
                else
                {
                    cur_providers=cur_facility_data.providers={};
                }
                if(!cur_providers.hasOwnProperty(cur_data["provider_id"]))
                {
                    cur_providers[cur_data["provider_id"]]=cur_data["provider_id"];
                }
            }
        }
        else
        {
            // Handle provider_id separately if no facilities are specified.
            if(cur_data.hasOwnProperty("provider_id"))
            alert("Provider");
            
        }
    }
    alert(JSON.stringify(visits_view_model.results.clinics_map));
    visits_view_model.results.periods=[]
    for(period in visits_view_model.results.periods_map)
    {
        visits_view_model.results.periods.push(period);
    }
    visits_view_model.results.periods.sort();
    alert(JSON.stringify(visits_view_model.results.periods));
}

function search_visits()
{
    var search_parameters={};
    search_parameters.from=$("#form_from_date").val();
    search_parameters.to=$("#form_to_date").val();
    
    search_parameters.clinics_details=visits_view_model.parameters.clinics_details();
    
    search_parameters.providers_details=visits_view_model.parameters.providers_details();
    
    search_parameters.period_size=visits_view_model.parameters.period_size().id;
    $.ajax(query_ajax,
    {
        data: {parameters: JSON.stringify(search_parameters) }
        ,dataType: "json"
        ,method: "POST"
        ,success: process_results
    });
    
}
setup_parameters();
ko.applyBindings(visits_view_model);