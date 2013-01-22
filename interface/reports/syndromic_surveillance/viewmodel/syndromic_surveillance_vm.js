function display_reportable_query(data)
{
    
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
        display_reportable_query
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
                          event_facility: ko.observable()
                        }
    return this;
}

