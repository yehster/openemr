/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function tabStatus(title,url,closable)
{
    var self=this;
    self.visible=ko.observable(false);
    self.pinned=ko.observable(false);
    self.closable=ko.observable(closable);
    self.title=ko.observable(title);
    self.url=ko.observable(url);
    return this;
}

function tabs_view_model()
{
    this.tabsList=ko.observableArray();
    this.tabsList.push(new tabStatus("One","/iframe/interface/main/main_info.php",false));
    this.tabsList.push(new tabStatus("Two","/iframe/interface/main/messages/messages.php?form_active=1",false));
//    this.tabsList.push(new tabStatus("Three"));
    this.text=ko.observable("Test");
    return this;
}