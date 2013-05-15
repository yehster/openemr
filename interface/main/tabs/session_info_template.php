<script type="text/html" id="session-info">
    <span data-bind="visible:$data.encounter.id()!==0">
        <span>Encounter:</span>
        <span data-bind="text:$data.encounter.date()"></span>
        (<span data-bind="text:$data.encounter.id()"></span>)
    </span>
</script>