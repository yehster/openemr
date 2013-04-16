<?php
    $ignoreAuth=true;
    require_once("../../interface/globals.php");
    require_once("../authentication/rsa.php");
    
    
    $key_manager=new rsa_key_manager;
    $key_manager->initialize();
    echo $key_manager->get_pubKeyJS();
?>
