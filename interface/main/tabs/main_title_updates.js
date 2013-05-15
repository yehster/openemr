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
    top.window.parent.left_nav.loadFrame2('nen1','RBot','forms/newpatient/new.php?autoloaded=1&calenc=');
    return true;
}
else if(rawdata==='Past Encounter List')
{
    top.window.parent.left_nav.loadFrame2('pel1','RBot','patient_file/history/encounters.php');
    return true;
}
    top.set_encounter(rawdata);
}
