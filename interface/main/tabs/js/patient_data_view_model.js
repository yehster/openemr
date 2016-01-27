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

function patient_data_view_model(pname,pid,pubpid,str_dob)
{
    var self=this;
    self.pname=ko.observable(pname);
    self.pid=ko.observable(pid);
    self.pubpid=ko.observable(pubpid);
    self.str_dob=ko.observable(str_dob);
    
    self.encounterArray=ko.observableArray();
    
    return this;
}