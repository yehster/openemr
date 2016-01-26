/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function tabStatus(title,url,closable,visible,locked)
{
    var self=this;
    self.visible=ko.observable(visible);
    self.locked=ko.observable(locked);
    self.closable=ko.observable(closable);
    self.title=ko.observable(title);
    self.url=ko.observable(url);
    self.window=null;
    return this;
}

function tabs_view_model()
{
    this.tabsList=ko.observableArray();
    this.tabsList.push(new tabStatus("One","/iframe/interface/main/main_info.php",false,true,false));
    this.tabsList.push(new tabStatus("Two","/iframe/interface/main/messages/messages.php?form_active=1",false,false,false));
//    this.tabsList.push(new tabStatus("Three"));
    this.text=ko.observable("Test");
    return this;
}

function activateTab(data)
{
    for(var tabIdx=0;tabIdx<app_view_model.application_data.tabs.tabsList().length;tabIdx++)
    {
        var curTab=app_view_model.application_data.tabs.tabsList()[tabIdx];
        if(data!==curTab)
        {
            if(!curTab.locked())
            {
                curTab.visible(false);
            }
        }
        else
        {
            curTab.visible(true);
        }
    }    
}

function tabClicked(data,evt)
{
    activateTab(data);
}

function tabRefresh(data,evt)
{
    data.window.location=data.window.location;
    activateTab(data);
}

function tabClose(data,evt)
{
        app_view_model.application_data.tabs.tabsList.remove(data);
}


function navigateTab(url,tabIdx)
{
    var curTab;
    if(tabIdx<app_view_model.application_data.tabs.tabsList().length)
    {
        curTab=app_view_model.application_data.tabs.tabsList()[tabIdx];
        curTab.url(url);
        
    }
    else if (tabIdx===app_view_model.application_data.tabs.tabsList().length)

    {
        curTab=new tabStatus("New",url,true,false,false);
        app_view_model.application_data.tabs.tabsList.push(curTab);
    }
    activateTab(curTab);
}

function tabLockToggle(data,evt)
{
    data.locked(!data.locked());
    if(data.locked())
    {
        activateTab(data);
    }
    else
    {
        data.visible(false);
    }
}