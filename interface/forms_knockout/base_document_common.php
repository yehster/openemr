<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/knockout/knockout-3.0.0.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/interface/forms_knockout/js/document_elements.js?<?php echo time(); ?>"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/interface/forms_knockout/js/document_events.js?<?php echo time(); ?>"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    var update_ajax='<?php echo $GLOBALS['webroot'] ?>/interface/forms_knockout/ajax/update_json.php';
</script>
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['webroot'] ?>/interface/forms_knockout/css/base_document.css?<?php echo time(); ?>"/>

<?php
    $base_require=$webserver_root."/interface/forms_knockout/";
    $templates_dir=$base_require."templates/";
    
    require_once($templates_dir."base_document.php");
    
?>
<div data-bind="template: {name: 'base-document' ,data:entries }">
</div>

<input type="button" name="close" value='Close'/>
<script>
    function save()
    {
        var entries=[];
        for(var idx=0;idx<view_model.entries().length;idx++)
        {
            entries[idx]=view_model.entries()[idx].persistentForm();
        }
        JSON.stringify(entries);
        $.ajax(update_ajax,{
            async: false,
            type: "POST",
            data:
            {
                uuid: uuid,
                formname: formname,
                json: JSON.stringify(entries)
            },

        }
        );
    }
    function close()
    {
        window.location.href= "<?php echo $GLOBALS['webroot'] ?>/interface/patient_file/encounter/encounter_top.php";
    }
    $("input[name='close']").click(close);
    window.onbeforeunload=save;
</script>