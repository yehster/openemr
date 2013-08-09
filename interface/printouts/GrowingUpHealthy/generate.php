<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 require_once("../../globals.php");
 require_once("../java_util.php");
 require_once("../viewer.php");
 require_once("$srcdir/patient.inc");
 require_once("../directory_definitions.php");
 require_once("../utilities/stature_data.php");
 ?>
<?php
    function age_to_filenum($months)
    {
        $age_cutoffs=array(3,5,7,10,13,16,24,36,48,72,108,156,204);
        $idx=0;
        $which_slot=count($age_cutoffs);
        for($idx=0;$idx<count($age_cutoffs);$idx++)
        {
            if($months<$age_cutoffs[$idx])
            {
                $which_slot=$idx;
                break;
            }
        }
        return $which_slot;
        
    }

    function generateOptions($selectedIdx)
    {
        $page_descriptions=array("Birth-2 Months","3-4 Months","5-6 Months","7-9 Months","10-12 Months","13-15 Months","16-23 Months","2 Years","3 Years"
                                 ,"4-5 Years","6-9 Years","9-12 Years","13-16 Years","17-20 Years");
        for($idx=0;$idx<count($page_descriptions);$idx++)
        {
            $selected=$idx==$selectedIdx ? " selected='true' " : "";
            echo "<option value='".$idx."' ".$selected.">".$page_descriptions[$idx]."</option>";
        }
    }    
    
    function format_weight($weight,$age)
    {
        
        $retval="";
        if($weight!=0)
        {
            if($age>=24.0)
            {
                $retval= sprintf("%d lb",$weight);
            }
            else {
                    $pounds_int=floor($weight);
                    return sprintf("%dlb %doz",$pounds_int,($weight-$pounds_int)*16);
            }            
        }
        return $retval;
    
    }
    $pid= isset($_REQUEST['pid']) ? $_REQUEST['pid']: $_SESSION['pid'];
?>


<?php
    $files_dir=$include_root."/printouts/GrowingUpHealthy/datafiles/";
    $base_file=$files_dir."GrowingUpHealthy-";
    $base_layout=$files_dir."GrowingUpLayout-";
    $language="EN";
    $filenum="0";
    $patient_info=array();

    if(isset($_REQUEST['language']))
    {
        if($_REQUEST['language']==="SP")
        {
            $language="SP";
        }
    }
    

    $patient_data  = getPatientData($pid, "fname,lname,sex, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
    $pname = $patient_data['fname']." ".$patient_data['lname'];
    $patient_info['name']=$pname;
    $age_info=getPatientAgeYMD($patient_data['DOB_YMD']);
    $filenum= isset($_REQUEST['filenum']) ? $_REQUEST['filenum'] : age_to_filenum($age_info['age_in_months']);
?>
<script type="text/javascript" src="../../../library/js/jquery-1.6.4.min.js"></script>

<select id='select_handout'>
    <?php generateOptions($filenum)   ?>
</select>    
<a href='generate.php?language=EN&pid=<?php echo $pid ?>&filenum=<?php echo $filenum;?>'>English</a>
<a href='generate.php?language=SP&pid=<?php echo $pid ?>&filenum=<?php echo $filenum;?>'>Spanish</a>
<script>
    var pid=<?php echo $pid; ?>;
    var lang='<?php echo $language; ?>';
    $("#select_handout").change(function(evt) {window.location='generate.php?filenum='+$(this).val()+"&pid="+pid+"&language="+lang;});
</script><?php
    $patient_info['age'] = getPatientAge($patient_data['DOB_YMD']);
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
        $patient_info['appt_date']=$date_parts[1]."-".$date_parts[2]."-".$date_parts[0];
        $patient_info['appt_time']=$disphour.":".$dispmin." ".$dispampm;
     }
     
     
     $vitals_data=array();
     stature_info($pid,$vitals_data,$patient_data['DOB_YMD'],$patient_data['sex']);
     if($vitals_data!==false)
     {
         $patient_info['weight']=format_weight($vitals_data['weight'],$age_info['age_in_months']);
         if($vitals_data['height']!=0) $patient_info['length']=$vitals_data['height']." in";
         if($vitals_data['BMI']!=0) {
             $patient_info['bmi']=$vitals_data['BMI'] . " (".$vitals_data['bmi_pct']."%)";
         }
     }

     $layoutOption="Young";
     if($filenum>=7)
     {
         $layoutOption="Older";
     }
     $source_file=$base_file.$language."-".$filenum.".pdf";
     $layout_file=$base_layout.$layoutOption."-".$language.".xml";
     $target_dir=PDF_OUTPUT_DIR;
     $target_file="GrowingUpHealthy_".uniqid().".pdf";
     $patient_info['today']=date("m-d-Y");
     
     stamp_pdf($source_file,$target_dir.$target_file,$layout_file,$patient_info);
     
     echo "<br>";
     echo embed_pdf($target_file);
?>
