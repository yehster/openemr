<?php require_once($webserver_root."/interface/forms_knockout/base_document_common.php"); ?>

<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/interface/forms/infant_sick_visit/js/infant_sick_metadata.js"></script>
<script>
    var view_model=new infant_sick_document();
    ko.applyBindings(view_model);
</script>
