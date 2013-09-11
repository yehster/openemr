function add_decline_text()
{
    var Notification_text="Caregiver declines vaccinations today. I explained the risk of death and permanent disability from vaccine-preventable illnesses, and despite this, caregiver understands risks and declines vaccination at this time.";
    
    // find the element to populate.  The # indicates find the element who's ID is 'form_Decline_VAX' 
    var decline_control=$("#form_Decline_VAX");
    if(decline_control.val()==="")  // Check the value of the control.  If it's blank (e.g. this is the first time loading the form
        {
            decline_control.val(Notification_text); // Then set the value to be the previously defined text.
        }
}

// Execute the previously defined function.  All of the code in "add_decline_text" could have just be put here directly, but it's cleaner to see what's going on to break things up into functions  
add_decline_text();  