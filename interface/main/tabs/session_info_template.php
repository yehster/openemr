<script type="text/html" id="session-info">
    <!-- ko if:$data.patient()!==false -->
        <span data-bind="text:patient().name"></span>
        <span data-bind="text:patient().age_info"></span>
    <!-- /ko -->
    <span data-bind="visible:$data.encounter.id()!==0">
        <span>Encounter:</span>
        <span data-bind="text:$data.encounter.date()"></span>
        (<span data-bind="text:$data.encounter.id()"></span>)
    </span>
</script>