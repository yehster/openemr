<?php
require_once($webserver_root."/interface/forms_knockout/db_operations.php");
require_once($webserver_root."/interface/forms_knockout/review_document_common.php");

function circumcision_report( $pid, $encounter, $cols, $id)
{ 
    $form_data=load_knockout_form(FRM_CIRCUMCISION,$id);
    $form_id=str_replace("-","_",$form_data['uuid']);
    include_knockout_dependencies("circumcision");
    ?>
    <div id="<?php echo  $form_id;?>" data-bind="template: {name: 'review-document' ,data:entries }"></div>
    <script>
        var vm_<?php echo  $form_id;?>=new circumcision_document();
        var loaded=<?php echo $form_data['json'];?>;
        apply_to_view(vm_<?php echo  $form_id;?>,loaded);        
        ko.applyBindings(vm_<?php echo  $form_id;?>,document.getElementById('<?php echo  $form_id;?>'));
    </script>
<?php
}
?>