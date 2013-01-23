<script type="text/html" id="search_results">    
    <table class="results" data-bind="visible:encounter_info().length>0">
        <thead>
            <tr>
                <th>PID</th>
                <th>ENC</th>
                <th>List ID</th>
                <th>Diagnosis</th>
            </tr>
        </thead>
        <tbody data-bind="foreach: $data.encounter_info">
            <tr data-bind="event: {click: choose_event}">
                <td data-bind="text:pid"></td>
                <td data-bind="text:encounter"></td>
                <td data-bind="text:list_id"></td>
                <td data-bind="text:diagnosis_code"></td>
            </tr>
        </tbody>
    </table>
</script>

