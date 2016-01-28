<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("menu_data.php");
$menu_parsed=json_decode($menu_json);


?>
<script type="text/javascript">
    var menu_objects=<?php echo json_encode($menu_parsed); ?>;
    
</script>