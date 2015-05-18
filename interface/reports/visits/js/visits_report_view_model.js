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
        data_rows: ko.observableArray(),
        report_type: ko.observable("Summary")
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
    
    parameters.categorize_services=ko.observable(true);
    
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
    visits_view_model.results.report_type("Summary");
    return results_table;
}


function build_data_table_providers(data)
{
    
    visits_view_model.results.report_type("Providers");
    var results_table=setup_results_array(visits_view_model.results.providers_list.length*visits_view_model.results.values_list.length,visits_view_model.results.periods.length+2);    
    var num_values=visits_view_model.results.values_list.length;
    for(var data_idx=0;data_idx<data.length;data_idx++)
    {
        var cur_data=data[data_idx];
        var provider_idx=visits_view_model.results.providers_list.indexOf(cur_data.provider_id);
        var period_idx=visits_view_model.results.periods.indexOf(cur_data.period);
        
        for(var value in cur_data)
        {        
            var value_idx=visits_view_model.results.values_list.indexOf(value)
            if((value_idx!==-1) && (provider_idx!==-1))
            {
                if(value_idx===0)
                {
                    results_table[provider_idx*num_values +value_idx][0]=cur_data.provider_id;
                }
                else
                {
                    results_table[provider_idx*num_values +value_idx][0]="";
                }
                results_table[provider_idx*num_values +value_idx][1]=value;
                results_table[provider_idx*num_values +value_idx][period_idx+2]=(cur_data[value]===null) ? 0 : cur_data[value];
            }
        }
    }
    
    visits_view_model.results.headers.unshift("Provider");
    return results_table;
}

function build_data_table_clinic_only(data)
{
    
    visits_view_model.results.report_type("Providers");
    var results_table=setup_results_array(visits_view_model.results.clinics_list.length*visits_view_model.results.values_list.length,visits_view_model.results.periods.length+2);    
    var num_values=visits_view_model.results.values_list.length;
    for(var data_idx=0;data_idx<data.length;data_idx++)
    {
        var cur_data=data[data_idx];
        var facility_idx=visits_view_model.results.clinics_list.indexOf(cur_data.facility);
        var period_idx=visits_view_model.results.periods.indexOf(cur_data.period);
        
        for(var value in cur_data)
        {        
            var value_idx=visits_view_model.results.values_list.indexOf(value)
            if((value_idx!==-1) && (facility_idx!==-1))
            {
                if(value_idx===0)
                {
                    results_table[facility_idx*num_values +value_idx][0]=cur_data.facility;
                }
                else
                {
                    results_table[facility_idx*num_values +value_idx][0]="";
                }
                results_table[facility_idx*num_values +value_idx][1]=value;
                results_table[facility_idx*num_values +value_idx][period_idx+2]=(cur_data[value]===null) ? 0 : cur_data[value];
            }
        }
    }
    
    visits_view_model.results.headers.unshift("Clinic");
    return results_table;
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
        if(cur_data.hasOwnProperty("provider_id"))
        {
            var clinic_map = visits_view_model.results.clinics_map[cur_data.facility];
            
        }
        else
        {
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
        visits_view_model.results.providers_list.sort();
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
                results_table=build_data_table_clinic_only(data);
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

function build_provider_integer_map()
{
    provider_integer_map={};
    for(var idx=0;idx<providers.length;idx++)
    {
        var cur_provider=providers[idx];
        provider_integer_map[cur_provider.id]=cur_provider.lname + "," +cur_provider.fname;
    }
    provider_integer_map['0']="~~Unknown~~"
}
function provider_integer_to_name(id)
{
    return provider_integer_map[id];
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
        // Convert Provider IDs to names
        if(cur_data.hasOwnProperty("provider_id"))
        {
            cur_data.provider_id=provider_integer_to_name(cur_data.provider_id);
        }
        
        
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
    
    search_parameters.categorize_services=visits_view_model.parameters.categorize_services();
    
    if(search_parameters.clinics_details)
    {
        search_parameters.clinic_filter=[];
        for(var clinic_idx=0;clinic_idx<visits_view_model.parameters.clinics().length;clinic_idx++)
        {
            var cur_clinic=visits_view_model.parameters.clinics()[clinic_idx];
            
            if(cur_clinic.selected())
            {
                search_parameters.clinic_filter.push(cur_clinic.name)
                if(cur_clinic.name==="All")
                {
                    clinic_idx+=visits_view_model.parameters.clinics().length;
                }
            }
        }
    }
    
    if(search_parameters.providers_details)
    {
        search_parameters.provider_filter=[];
        for(var provider_idx=0;provider_idx<visits_view_model.parameters.providers().length;provider_idx++)
        {
            var cur_provider=visits_view_model.parameters.providers()[provider_idx];
            
            if(cur_provider.selected())
            {
                search_parameters.provider_filter.push(cur_provider.id)
                if(cur_provider.id==="ALL")
                {
                    provider_idx+=visits_view_model.parameters.clinics().length;
                }
            }
        }
    }
    $.ajax(query_ajax,
    {
        data: {parameters: JSON.stringify(search_parameters) }
        ,dataType: "json"
        ,method: "POST"
        ,success: process_results
    });
    
}
setup_parameters();
build_provider_integer_map();
ko.applyBindings(visits_view_model);