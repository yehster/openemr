<?php
        //SANITIZE ALL ESCAPES
        $sanitize_all_escapes=true;

        //STOP FAKE REGISTER GLOBALS
        $fake_register_globals=false;

        //continue session
        session_start();

	//landing page definition -- where to go if something goes wrong
        $landingpage = "index.php?site=".$_SESSION['site_id'];	
	//

	// kick out if patient not authenticated
	if ( isset($_SESSION['pid']) && isset($_SESSION['patient_portal_onsite']) ) {
  		$pid = $_SESSION['pid'];
	}
	else {
                session_destroy();
  		header('Location: '.$landingpage.'&w');
                exit;
	}
	//
	
        $ignoreAuth=true; // ignore the standard authentication for a regular OpenEMR user
	include_once('../interface/globals.php');

?>
