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

function search_reportable(data,event)
{
    data.searchParameters.from($("#form_from_date").val());
    data.searchParameters.to($("#form_to_date").val());
    
}