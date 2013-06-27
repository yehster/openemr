<?php
function create_row(DOMDocument $DOM,$label,$value)
{
    $retval=$DOM->createElement("tr");
    $tdLabel=$DOM->createElement("td",$label);
    $tdValue=$DOM->createElement("td",$value);
    
    $retval->appendChild($tdLabel);
    $retval->appendChild($tdValue);    
    return $retval;
}

function appointment_info(DOMDocument $DOM, DOMElement $parent,$pid)
{
      $apptQuery = "SELECT e.pc_eid, e.pc_aid, e.pc_title, e.pc_eventDate, " .
      "e.pc_startTime, e.pc_hometext, u.fname, u.lname, u.mname, " .
      "c.pc_catname, e.pc_apptstatus " .
      "FROM openemr_postcalendar_events AS e, users AS u, " .
      "openemr_postcalendar_categories AS c WHERE " .
      "e.pc_pid = ? AND e.pc_eventDate > CURRENT_DATE AND " .
      "u.id = e.pc_aid AND e.pc_catid = c.pc_catid " .
      " AND NOT (pc_apptstatus IN ('x','%')) ".
      "ORDER BY e.pc_eventDate, e.pc_startTime LIMIT 1";
     $appointment = sqlQuery($apptQuery, array($pid) );
     if($appointment!=false)
     {
        $dispampm = "am";
        $disphour = substr($appointment['pc_startTime'], 0, 2) + 0;
        $dispmin  = substr($appointment['pc_startTime'], 3, 2);
        if ($disphour >= 12) {
            $dispampm = "pm";
            if ($disphour > 12) $disphour -= 12;
        }
        $date_parts=explode("-",$appointment['pc_eventDate']);
        $apptDate=$date_parts[1]."-".$date_parts[2]."-".$date_parts[0];
        $apptTime=$disphour.":".$dispmin." ".$dispampm;
        $retval=create_row($DOM,"Next Appointment",$apptDate." ".$apptTime);
        $parent->appendChild($retval);
        return $retval;
     }
}

function stature_info(DOMDocument $DOM, DOMElement $parent,$pid)
{
    $sqlVitals = "SELECT height,weight,BMI FROM form_vitals WHERE pid=? ORDER BY date desc LIMIT 1";
    $vitals_data = sqlQuery($sqlVitals,array($pid));
    if($vitals_data['weight']!=0) 
    {
       $pounds_int=floor($vitals_data['weight']);            
       $parent->appendChild(create_row($DOM,"Weight",sprintf("%dlb %doz",$pounds_int,($vitals_data['weight']-$pounds_int)*16)));             
    }
    if($vitals_data['height']!=0) 
        $parent->appendChild(create_row($DOM,"Height",$vitals_data['height']." in"));
    if($vitals_data['BMI']!=0) 
    $parent->appendChild(create_row($DOM,"BMI",$vitals_data['BMI']));

     if($vitals_data!==false)
     {
         if($vitals_data['length']!=0) $patient_info['length']=$vitals_data['height']." in";
         if($vitals_data['BMI']!=0) $patient_info['bmi']=$vitals_data['BMI'];
     }
}
?>
