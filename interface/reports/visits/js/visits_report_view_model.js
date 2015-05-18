/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var visits_view_model=
{
    parameters: {},
    results: {
        headers: ko.observableArray(),
        data_rows: ko.observableArray()
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
function isEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return false;
    }

    return true;
}


function setup_results_array(rows,columns)
{
    var retval=new Array(rows);
    for(var row_idx=0;row_idx<rows;row_idx++)
    {
        retval[row_idx]=new Array(columns);
    }
    return retval;
}
function build_data_table_summary_only(data)
{
    var results_table=setup_results_array(visits_view_model.results.values_list.length,visits_view_model.results.periods.length+1);
    for(var data_idx=0;data_idx<data.length;data_idx++)
    {
        var cur_data=data[data_idx];
        var period_idx=visits_view_model.results.periods.indexOf(cur_data.period);
        
        for(var value in cur_data)
        {        
            var value_idx=visits_view_model.results.values_list.indexOf(value)
            if(value_idx!==-1)
            {
                results_table[value_idx][0]=value;
                results_table[value_idx][period_idx+1]=(cur_data[value]===null) ? 0 : cur_data[value];
            }
        }
    }
    return results_table;
}


function build_data_table_providers(data)
{
    
}

function build_data_table_clinics(data)
{
    var results_table=new Array(visits_view_model.results.clinics_list.length);
    for(var clinic_idx=0;clinic_idx<visits_view_model.results.clinics_list.length;clinic_idx++)
    {
        results_table[clinic_idx]=null;
    }
    for(var data_idx=0;data_idx<data.length;data_idx++)
    {
        var cur_data=data[data_idx];
        var period_idx=visits_view_model.results.periods.indexOf(cur_data.period);
        var clinic_idx=visits_view_model.results.clinics_list.indexOf(cur_data.facility);
        if((results_table[clinic_idx]===null))
        {
            results_table[clinic_idx]=setup_results_array(visits_view_model.results.values_list.length,visits_view_model.results.periods.length+1);
        }
        for(var value in cur_data)
        {        
            var value_idx=visits_view_model.results.values_list.indexOf(value)
            if(value_idx!==-1)
            {
                results_table[clinic_idx][value_idx][0]=value;
                results_table[clinic_idx][value_idx][period_idx+1]=(cur_data[value]===null) ? 0 : cur_data[value];
            }
        }
    }    
    return results_table;
}
function build_data_table(data)
{
    
    var clinics_details=!isEmpty(visits_view_model.results.clinics_map);
    var providers_details=!isEmpty(visits_view_model.results.providers_map);
    if(clinics_details)
    {
        visits_view_model.results.clinics_list=[];
        for(var clinic in visits_view_model.results.clinics_map)
        {
            visits_view_model.results.clinics_list.push(clinic);
        }
        visits_view_model.results.clinics_list.sort();
        
    }
    else if(providers_details)
    {
        visits_view_model.results.providers_list=[];
        for(var provider in visits_view_model.results.providers_map)
        {
            visits_view_model.results.providers_list.push(provider);
        }
    }
    
    visits_view_model.results.headers.removeAll();
    

    var results_table;
    if(!clinics_details && !providers_details)
    {
        results_table=build_data_table_summary_only(data);
    }
    else
    {
        if(providers_details)
        {
            results_table=build_data_table_providers(data);
        }
        else
        {
            if(clinics_details)
            {
                results_table=build_data_table_clinics(data);
            }
        }
    }
    
   
    visits_view_model.results.headers.push("");
    for(var period_idx=0;period_idx<visits_view_model.results.periods.length;period_idx++)
    {
        visits_view_model.results.headers.push(visits_view_model.results.periods[period_idx]);
    }
    visits_view_model.results.data_rows(results_table);
//    alert(JSON.stringify(results_table));
//    alert(JSON.stringify(visits_view_model.results.clinics_list));
//    alert(JSON.stringify(data));

}

function process_results(data,status, jqXHR)
{
    visits_view_model.results.periods_map={};
    visits_view_model.results.clinics_map={};
    visits_view_model.results.providers_map={};
    visits_view_model.results.values_map={};
    visits_view_model.results.values_list=[];
    
    
    for(var data_idx=0;data_idx<data.length;data_idx++)
    {
        var cur_data=data[data_idx];
        visits_view_model.results.periods_map[cur_data.period]=cur_data.period;
        for(var value in cur_data)
        {
            if((value!=="provider_id" )&& (value!=="facility") && (value!=="period"))
            if(!visits_view_model.results.values_map.hasOwnProperty(value))
            {
                visits_view_model.results.values_map[value]=value;
                visits_view_model.results.values_list.push(value);
            }
        }
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
            {
                var cur_provider;
                if(visits_view_model.results.providers_map.hasOwnProperty(cur_data.provider_id))
                {
                    cur_provider=visits_view_model.results.providers_map[cur_data.provider_id];
                }
                else
                {
                    visits_view_model.results.providers_map[cur_data.provider_id]=
                            {
                                provider_id: cur_data.provider_id
                            };
                }
            }
            
        }
    }
    
    // Generate an ordered list of the periods
    visits_view_model.results.periods=[]
    for(var period in visits_view_model.results.periods_map)
    {
        visits_view_model.results.periods.push(period);
    }
    visits_view_model.results.periods.sort();
    
    build_data_table(data);
    
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