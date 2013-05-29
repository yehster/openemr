toencounter=function(rawdata) {
//This is called in the on change event of the Encounter list.
//It opens the corresponding pages.
document.getElementById('EncounterHistory').selectedIndex=0;
if(rawdata==='')
{
    return false;
}
else if(rawdata==='New Encounter')
{
    top.tabs_navigate(pathWebroot+'forms/newpatient/new.php?autoloaded=1&calenc=',2);
    return true;
}
else if(rawdata==='Past Encounter List')
{
    top.tabs_navigate(pathWebroot+'patient_file/history/encounters.php',2);
    return true;
}
    top.set_encounter(rawdata);
}
