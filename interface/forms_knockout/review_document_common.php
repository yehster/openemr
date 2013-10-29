<?php
function include_knockout_dependencies($formname)
{
    global $initialized_knockout_forms;
    if(!isset($initialized_knockout_forms))
    {
        $initialized_knockout_forms=true;
        ?>
        <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/knockout/knockout-3.0.0.js"></script>
        <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/interface/forms_knockout/js/document_elements.js"></script>
        
        <?php
        $base_require=$GLOBALS['webserver_root']."/interface/forms_knockout/";
        $templates_dir=$base_require."templates/";
    
        require_once($templates_dir."review_document.php");
        $initialized_knockout_forms=true;
    }
    if(!isset($GLOBALS[$formname]))
    {
        ?>
        <script type="text/javascript" src="<?php echo $GLOBALS['webroot']."/interface/forms/".$formname."/js/".$formname."_metadata.js";?>"></script>        
        <?php
        $GLOBALS[$formname]=true;
    }
}

?>