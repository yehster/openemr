function ss_view_model()
{
    this.searchParameters={
                            from: ko.observable(),
                            to: ko.observable(),
                            diags: ko.observableArray(),
                            diag_options: ko.observableArray()
                           };
    
    this.searchResults={
                            encounter_info: ko.observableArray()
                       };
    return this;
}

function search_reportable(data,event)
{
    data.searchParameters.from($("#form_from_date").val());
    data.searchParameters.to($("#form_to_date").val());
}