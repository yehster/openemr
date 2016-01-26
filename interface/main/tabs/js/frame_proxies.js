/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var RTop = {
    set location(url)
    {
        navigateTab(url,"pat");
        activateTabByName("pat",true);
    }
};



var left_nav = {
    
};

left_nav.setPatient = function(pname, pid, pubpid, frname, str_dob)
{
    navigateTab(webroot_url+"/interface/patient_file/history/encounters.php","enc");
    activateTabByName("enc",false);
};
left_nav.setPatientEncounter = function(EncounterIdArray,EncounterDateArray,CalendarCategoryArray)
{
}
left_nav.setEncounter=function(edate, eid, frname)
{
}

left_nav.loadFrame=function(id,name,url)
{
    navigateTab(webroot_url+"/interface/"+url,3)
}

left_nav.setRadio = function(raname, rbid)
{
};

left_nav.syncRadios = function()
{
    
};