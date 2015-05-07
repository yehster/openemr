/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var visits_view_model=
{
    parameters: {},
    results: {}
};

function setup_parameters()
{
    var parameters=visits_view_model.parameters;
    parameters.clinics=ko.observableArray();
    parameters.clinics_details=ko.observable(false);

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
    alert(JSON.stringify(data));
}

function search_visits()
{
    var search_parameters={};
    search_parameters.from=$("#form_from_date").val();
    search_parameters.to=$("#form_to_date").val();
    
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