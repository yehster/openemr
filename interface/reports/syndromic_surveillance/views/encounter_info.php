<script type="text/html" id="encounter_info">
    <div>
        <div class="label">Chief Complaint</div>
        <textarea class="chief_complaint" data-bind="value: reason"></textarea>
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
                <td><select data-bind="value:diagnosis_type, options: ['A','W','F']"></select></td>
            </tr>
        </tbody>
    </table>
</script>