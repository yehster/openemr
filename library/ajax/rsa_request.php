<?php
    $ignoreAuth=true;
    $fake_register_globals=false;
    $sanitize_all_escapes=true;    
    
    require_once("../../interface/globals.php");
    require_once("../authentication/rsa.php");
    
    
    $key_manager=new rsa_key_manager;
    $key_manager->initialize();
    echo $key_manager->get_pubKeyJS();
?>
