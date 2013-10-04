<?php
include_once("../../globals.php");
include_once($GLOBALS["srcdir"]."/api.inc");
include_once ($GLOBALS['fileroot']."/library/patient.inc");
require_once($GLOBALS['fileroot']."/interface/stats/growth_stats.php");

function US_weight($pounds,$mode=1)
{

    if($mode==1)
    {
        return $pounds . " " . xl('lb') ;
    }
    else
    {
        $pounds_int=floor($pounds);
        $ounces=round(($pounds-$pounds_int)*16);
        if($ounces==16)
        {
            $ounces=0;
            $pounds_int++;
        }
        return $pounds_int . " " . xl('lb') . " " . $ounces . " " . xl('oz');
    }
}

function vitals_report( $pid, $encounter, $cols, $id, $print = true) {
  $count = 0;
  $data = formFetch("form_vitals", $id);
  $patient_data = getPatientData($GLOBALS['pid']);
  $patient_age = getPatientAge($patient_data['DOB']);
  $ageYMD=getPatientAgeYMD($patient_data['DOB'],$data['date']);
  $age_in_months = $ageYMD['age_in_months'];
  error_log("Age:".$age_in_months);
  if($age_in_months<=24)
  {
      $who_data=get_who_stats($age_in_months,$patient_data['sex'],$data['weight']/2.204,$data['height']*2.54,$data['head_circ']*2.54);
      $data["Height %ile"]=number_format($who_data['height_pct'],1);
      $data["Weight %ile"]=number_format($who_data['weight_pct'],1);
      $data["Weight for Height %ile"]=number_format($who_data['weight_height_pct'],1);
      $data["Head Circ %ile"]=number_format($who_data['head_pct'],1);
  }
  if(($age_in_months>=23.5) && ($age_in_months<240.5))
  {
      $cdc_data=get_cdc_stats($age_in_months,$patient_data['sex'],$data['weight']/2.204,$data['height']*2.54,$data['BMI']);
      $data["Height %ile"]=number_format($cdc_data['height_pct'],1);
      $data["Weight %ile"]=number_format($cdc_data['weight_pct'],1);
      $data["Weight for Height %ile"]=number_format($cdc_data['weight_height_pct'],1);
  }
  if ($data) {
    $vitals = "<table><tr>";

    foreach($data as $key => $value) {

      if ($key == "id" || $key == "pid" || 
          $key == "user" || $key == "groupname" || 
          $key == "authorized" || $key == "activity" || 
          $key == "date" || $value == "" || 
          $value == "0000-00-00 00:00:00" || $value == "0.0" )
      {
        // skip certain data
        continue;
      }

      if ($value == "on") { $value = "yes"; } 

      $key = ucwords(str_replace("_"," ",$key));

      //modified by BM 06-2009 for required translation
      if ($key == "Temp Method" || $key == "BMI Status") { 
        if ($key == "BMI Status") {
          if ($patient_age <= 20 || (preg_match('/month/', $patient_age))) { 
            $value = "See Growth-Chart"; 
            if($age_in_months>=24)
            {

                $pct=number_format($cdc_data['BMI_pct'],1);
                $value=$pct."%";
                $value.=" (".$cdc_data['BMI_status'].")";
            }
            if($age_in_months<24)
            {
                $value = "Undefined";
            }
          }
        }
        $vitals .= "<td><span class=bold>" . xl($key) . ": </span><span class=text>" . xl($value) . "</span></td>"; 
      } 
      elseif ($key == "Bps") {
        $bps = $value;
        if ($bpd) {
          $vitals .= "<td><span class=bold>" . xl('Blood Pressure') . ": </span><span class=text>" . $bps . "/". $bpd  . "</span></td>";
        }
        else {
          continue;   
        }
      }
      elseif ($key == "Bpd") {
        $bpd = $value;
        if ($bps) {
          $vitals .= "<td><span class=bold>" . xl('Blood Pressure') . ": </span><span class=text>" . $bps . "/". $bpd  . "</span></td>";
        }
        else {
          continue;   
        }
      }
      elseif ($key == "Weight") {
        $convValue = number_format($value*0.45359237,2);
        $vitals.="<td><span class=bold>" . xl($key) . ": </span><span class=text>";
        // show appropriate units
        $mode=$GLOBALS['us_weight_format'];
        if ($GLOBALS['units_of_measurement'] == 2) {
          $vitals .=  $convValue . " " . xl('kg') . " (" . US_weight($value,$mode) . ")";
        }
        elseif ($GLOBALS['units_of_measurement'] == 3) {
          $vitals .=  US_weight($value,$mode) ;
        }
        elseif ($GLOBALS['units_of_measurement'] == 4) {
          $vitals .= $convValue . " " . xl('kg') ;
        }
        else { // = 1 or not set
          $vitals .= US_weight($value,$mode) . " (" . $convValue . " " . xl('kg')  . ")";
        }
        $vitals.= "</span></td>";
      }
      elseif ($key == "Height" || $key == "Waist Circ"  || $key == "Head Circ") {
        $convValue = number_format($value*2.54,2);
        // show appropriate units
        if ($GLOBALS['units_of_measurement'] == 2) { 
          $vitals .= "<td><span class=bold>" . xl($key) . ": </span><span class=text>" . $convValue . " " . xl('cm') . " (" . $value . " " . xl('in')  . ")</span></td>";
        }
        elseif ($GLOBALS['units_of_measurement'] == 3) {
          $vitals .= "<td><span class=bold>" . xl($key) . ": </span><span class=text>" . $value . " " . xl('in') . "</span></td>";
        }
        elseif ($GLOBALS['units_of_measurement'] == 4) {
          $vitals .= "<td><span class=bold>" . xl($key) . ": </span><span class=text>" . $convValue . " " . xl('cm') . "</span></td>";
        }
        else { // = 1 or not set
          $vitals .= "<td><span class=bold>" . xl($key) . ": </span><span class=text>" . $value . " " . xl('in') . " (" . $convValue . " " . xl('cm')  . ")</span></td>";
        }
      }
      elseif ($key == "Temperature") {
        $convValue = number_format((($value-32)*0.5556),2);
        // show appropriate units
        if ($GLOBALS['units_of_measurement'] == 2) {
          $vitals .= "<td><span class=bold>" . xl($key) . ": </span><span class=text>" . $convValue . " " . xl('C') . " (" . $value . " " . xl('F')  . ")</span></td>";
        }
        elseif ($GLOBALS['units_of_measurement'] == 3) {
          $vitals .= "<td><span class=bold>" . xl($key) . ": </span><span class=text>" . $value . " " . xl('F') . "</span></td>";
        }
        elseif ($GLOBALS['units_of_measurement'] == 4) {
          $vitals .= "<td><span class=bold>" . xl($key) . ": </span><span class=text>" . $convValue . " " . xl('C') . "</span></td>";
        }
        else { // = 1 or not set
          $vitals .= "<td><span class=bold>" . xl($key) . ": </span><span class=text>" . $value . " " . xl('F') . " (" . $convValue . " " . xl('C')  . ")</span></td>";
        }
      }

      elseif ($key == "Pulse" || $key == "Respiration"  || $key == "Oxygen Saturation" || $key == "BMI") {
        $value = number_format($value,0);
        if ($key == "Oxygen Saturation") {
          $vitals .= "<td><span class=bold>" . xl($key) . ": </span><span class=text>" . $value . " " . xl('%') . "</span></td>";
        }
        elseif ($key == "BMI") {
          $vitals .= "<td><span class=bold>" . xl($key) . ": </span><span class=text>" . $value . " " . xl('kg/m^2') . "</span></td>";
        }
        else { //pulse and respirations
          $vitals .= "<td><span class=bold>" . xl($key) . ": </span><span class=text>" . $value . " " . xl('per min') . "</span></td>";
        }
      }
      else { 
        $vitals .= "<td><span class=bold>" . xl($key) . ": </span><span class=text>$value</span></td>"; 
      } 

      $count++;

      if ($count == $cols) {
          $count = 0;
          $vitals .= "</tr><tr>\n";
      }
    }

    $vitals .= "</tr></table>";
  }
  if ($print) echo $vitals ;
  else return $vitals;
}
?>
