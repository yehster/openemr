<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/knockout/knockout-2.3.0.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/interface/forms_knockout/js/document_elements.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['webroot'] ?>/interface/forms_knockout/css/base_document.css"/>

<?php
    $base_require=$webserver_root."/interface/forms_knockout/";
    $templates_dir=$base_require."templates/";
    
    require_once($templates_dir."base_document.php");
    
?>
<div data-bind="template: {name: 'base-document' ,data:entries }">
</div>
