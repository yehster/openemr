/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function encounter_data(id,date,category)
{
    var self=this;
    self.id=ko.observable();
    self.date=ko.observable();
    self.category=ko.observable();
    return this;
}

function patient_data_view_model()
{
    var self=this;
    self.pname=ko.observable();
    self.pid=ko.observable();
    self.pubpid=ko.observable();
    self.str_dob=ko.observable();
    
    self.encounterArray=ko.observableArray();
    
    return this;
}