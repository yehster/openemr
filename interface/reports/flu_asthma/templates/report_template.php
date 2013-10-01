<script type="text/html" id="review-display">
    <b>Asthma/Influenza Report</b>
    <table class="patient_list">
        <thead>
            <tr>
                 <th>First Name</td>
                 <th>Last Name</td>
                 <th>DOB</td>
                 <th>Home#</th>
                 <th>Cell#</th>
                 <th>External ID</td>
                 <th>Problem Description</td>
                 <th>Administration Date</td>
            </tr>
        </thead>
        <tbody data-bind="foreach:patients">
            <tr data-bind="click: goto_patient">
                <td data-bind="text: fname"></td>
                <td data-bind="text: lname"></td>
                <td data-bind="text: DOB"></td>
                <td data-bind="text: phone_home"></td>
                <td data-bind="text: phone_cell"></td>
                <td data-bind="text: pubpid"></td>
                <td data-bind="text: title"></td>    
                <td data-bind="text: administered_date"></td>
            </tr>
        </tbody>
    </table>
</script>
