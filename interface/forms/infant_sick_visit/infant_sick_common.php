<?php require_once($webserver_root."/interface/forms_knockout/base_document_common.php"); ?>

<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/interface/forms/infant_sick_visit/js/infant_sick_visit_metadata.js?<?php $ts = new DateTime(); echo $ts->getTimestamp();?>"></script>
<script>
    var view_model=new infant_sick_visit_document();
    var uuid='<?php echo $form_data['uuid']?>';
    var formname='<?php echo FRM_INF_SICK?>';
</script>
