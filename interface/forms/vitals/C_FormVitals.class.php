<?php

require_once ($GLOBALS['fileroot'] . "/library/classes/Controller.class.php");
require_once ($GLOBALS['fileroot'] . "/library/forms.inc");
require_once ($GLOBALS['fileroot'] . "/library/patient.inc");
require_once($GLOBALS['include_root']."/stats/growth_stats.php");                
require_once("FormVitals.class.php");

class C_FormVitals extends Controller {

	var $template_dir;

    function C_FormVitals($template_mod = "general") {
    	parent::Controller();
    	$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
    	$this->template_mod = $template_mod;
    	$this->template_dir = dirname(__FILE__) . "/templates/vitals/";
    	$this->assign("FORM_ACTION", $GLOBALS['web_root']);
    	$this->assign("DONT_SAVE_LINK",$GLOBALS['webroot'] . "/interface/patient_file/encounter/$returnurl");
    	$this->assign("STYLE", $GLOBALS['style']);

        // send the unit selection
        $this->assign("units_of_measurement",$GLOBALS['units_of_measurement']);
    }

    function default_action_old() {
    	//$vitals = array();
    	//array_push($vitals, new FormVitals());
    	$vitals = new FormVitals();
    	$this->assign("vitals",$vitals);
    	$this->assign("results", $results);
    	return $this->fetch($this->template_dir . $this->template_mod . "_new.html");
	}

    function default_action($form_id) {

        if (is_numeric($form_id)) {
    		$vitals = new FormVitals($form_id);
    	}
    	else {
    		$vitals = new FormVitals();
    	}

    	$dbconn = $GLOBALS['adodb']['db'];
    	//Combined query for retrieval of vital information which is not deleted
      $sql = "SELECT fv.*, fe.date AS encdate " .
        "FROM form_vitals AS fv, forms AS f, form_encounter AS fe WHERE " .
        "fv.id != $form_id and fv.pid = " . $GLOBALS['pid'] . " AND " .
        "f.formdir = 'vitals' AND f.deleted = 0 AND f.form_id = fv.id AND " .
        "fe.pid = f.pid AND fe.encounter = f.encounter " .
        "ORDER BY encdate DESC, fv.date DESC";
    	$result = $dbconn->Execute($sql);

        // get the patient's current age
    	$patient_data = getPatientData($GLOBALS['pid']);
        $patient_dob=$patient_data['DOB'];
        $patient_sex=$patient_data['sex']==='Male' ? 1 : 2;
        $patient_age = getPatientAge($patient_dob);
        $patient_age_YMD=getPatientAgeYMD($patient_data['DOB']);
        $this->assign("patient_age_months",$patient_age_YMD['age_in_months']);
    	$this->assign("patient_age", $patient_age);
        $this->assign("patient_dob",$patient_dob);
        $this->assign("patient_sex",$patient_sex);  
        
    	$i = 1;
    	while($result && !$result->EOF)
    	{
            $results[$i]['id'] = $result->fields['id'];
            $results[$i]['encdate'] = substr($result->fields['encdate'], 0, 10);
            $results[$i]['date'] = $result->fields['date'];
            $results[$i]['activity'] = $result->fields['activity'];
            $results[$i]['bps'] = $result->fields['bps'];
            $results[$i]['bpd'] = $result->fields['bpd'];
            $results[$i]['weight'] = $result->fields['weight'];
            $results[$i]['height'] = $result->fields['height'];
            $results[$i]['temperature'] = $result->fields['temperature'];
            $results[$i]['temp_method'] = $result->fields['temp_method'];
            $results[$i]['pulse'] = $result->fields['pulse'];
            $results[$i]['respiration'] = $result->fields['respiration'];
            $results[$i]['BMI'] = $result->fields['BMI'];
            $results[$i]['note'] = $result->fields['note'];
            $results[$i]['waist_circ'] = $result->fields['waist_circ'];
            $results[$i]['head_circ'] = $result->fields['head_circ'];
            $results[$i]['oxygen_saturation'] = $result->fields['oxygen_saturation'];
            $ageYMD=getPatientAgeYMD($patient_data['DOB'],$result->fields['date']);
            $age_in_months = $ageYMD['age_in_months'];
            if($age_in_months<24)
            {
                $who_stats=get_who_stats($age_in_months,$patient_data['sex'],$results[$i]['weight']/2.204,$results[$i]['height']*2.54,$results[$i]['head_circ']*2.54);
                foreach($who_stats as $field=>$stat)
                {
                    $results[$i][$field]=number_format($stat,1);
                }
            }
            else if($age_in_months>=23.5)
            {
                $cdc_stats=get_cdc_stats($age_in_months,$patient_sex,$results[$i]['weight']/2.204,$results[$i]['height']*2.54,$results[$i]['BMI']);
                foreach($cdc_stats as $field=>$stat)
                {
                    if($field!='BMI_status')
                    {
                        $results[$i][$field]=number_format($stat,1);                    
                    }
                }                
                $bmi_pct=number_format($cdc_stats['BMI_pct'],1);
                $results[$i]['BMI_pct']=$bmi_pct;
                $results[$i]['BMI_status'] = bmi_pct_to_status($bmi_pct);
                    
            }
            else if($age_in_months>=240.5)
            {
                $results[$i]['BMI_status'] = $result->fields['BMI_status'];    
            }
            $i++;
            $result->MoveNext();
    	}

    	$this->assign("vitals",$vitals);
    	$this->assign("results", $results);

    	$this->assign("VIEW",true);
	return $this->fetch($this->template_dir . $this->template_mod . "_new.html");

    }
	
    function default_action_process() {
		if ($_POST['process'] != "true")
			return;

		$weight = $_POST["weight"];
		$height = $_POST["height"];
		if ($weight > 0 && $height > 0) {
			$_POST["BMI"] = ($weight/$height/$height)*703;
		}
		if     ( $_POST["BMI"] > 42 )   $_POST["BMI_status"] = 'Obesity III';
		elseif ( $_POST["BMI"] > 34 )   $_POST["BMI_status"] = 'Obesity II';
		elseif ( $_POST["BMI"] > 30 )   $_POST["BMI_status"] = 'Obesity I';
		elseif ( $_POST["BMI"] > 27 )   $_POST["BMI_status"] = 'Overweight';
		elseif ( $_POST["BMI"] > 25 )   $_POST["BMI_status"] = 'Normal BL';
		elseif ( $_POST["BMI"] > 18.5 ) $_POST["BMI_status"] = 'Normal';
		elseif ( $_POST["BMI"] > 10 )   $_POST["BMI_status"] = 'Underweight';
		$temperature = $_POST["temperature"];
		if ($temperature == '0' || $temperature == '') {
			$_POST["temp_method"] = "";
		}

		$this->vitals = new FormVitals($_POST['id']);
		
		parent::populate_object($this->vitals);
		
		$this->vitals->persist();
		if ($GLOBALS['encounter'] < 1) {
			$GLOBALS['encounter'] = date("Ymd");
		}
		if(empty($_POST['id']))
		{
			addForm($GLOBALS['encounter'], "Vitals", $this->vitals->id, "vitals", $GLOBALS['pid'], $_SESSION['userauthorized']);
			$_POST['process'] = "";
		}
		return;
    }

}

?>
