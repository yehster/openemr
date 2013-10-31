<?php
function include_knockout_dependencies($formname,$form_data)
{
    global $initialized_knockout_forms;
    if(!isset($initialized_knockout_forms))
    {
        $initialized_knockout_forms=true;
        ?>
        <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/knockout/knockout-3.0.0.js"></script>
        <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/interface/forms_knockout/js/document_elements.js?<?php echo time(); ?>"></script>
        
        <?php
        $base_require=$GLOBALS['webserver_root']."/interface/forms_knockout/";
        $templates_dir=$base_require."templates/";
    
        require_once($templates_dir."review_document.php");
        $initialized_knockout_forms=true;
    }
    if(!isset($GLOBALS[$formname]))
    {
        ?>
        <script type="text/javascript" src="<?php echo $GLOBALS['webroot']."/interface/forms/".$formname."/js/".$formname."_metadata.js?".time();?>"></script>        
        <?php
        $GLOBALS[$formname]=true;
    }
    $form_id=str_replace("-","_",$form_data['uuid']);
    ?>
    <div id="<?php echo  $form_id;?>" class='text' data-bind="template: {name: 'review-document' ,data:entries }"></div>
    <script>
        var vm_<?php echo  $form_id;?>=new <?php echo $formname;?>_document();
        var loaded=<?php echo $form_data['json'];?>;
        apply_to_view(vm_<?php echo  $form_id;?>,loaded);        
        ko.applyBindings(vm_<?php echo  $form_id;?>,document.getElementById('<?php echo  $form_id;?>'));
    </script>        
    <?php
}

?>