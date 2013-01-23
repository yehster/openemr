<script type="text/html" id="encounter_info">
    <div>
        <span class="label">Chief Complaint</span>
        <input type="text" data-bind="value: reason"></input>
    </div>
    <table>
        <thead>
            <tr>
                <th>Diagnosis</th>
                <th>Code</th>
            </tr>
        </thead>
        <tbody data-bind="{foreach: diagnoses}">
            <tr>
                <td data-bind="text:description"></td>
                <td data-bind="text:code"></td>
                <td><select data-bind="value:diagnosis_type, options: ['A','W','D']"></select></td>
            </tr>
        </tbody>
    </table>
</script>