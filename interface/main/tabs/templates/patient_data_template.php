<script type="text/html" id="patient-data-template">
    <div data-bind="with: patient">
        <div>
            <span>Patient:</span>
            <a data-bind="click:refreshPatient" href="#">
                <span data-bind="text: pname"></span>(<span data-bind="text: pubpid"></span>)
            </a>
        </div>
        <div>
            <span data-bind="text:str_dob"></span>
        </div>
    </div>
</script>