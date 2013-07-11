<?php


?>
<div>
<form action="generate.php" method="post">
<div class="info">
    <table>
        <thead>
            <tr><th colspan="2">COPTP Primary</th></tr>
        </thead>
        <tbody>
            <tr><td><input type="radio" name="coptp_primary_choice" value="none"/></td><td>None</td></tr>
            <tr><td><input type="radio" name="coptp_primary_choice" value="278.00"/></td><td>Obesity, Unspecified</td></tr>
            <tr><td><input type="radio" name="coptp_primary_choice" value="278.01"/></td><td>Morbid Obesity</td></tr>
            <tr><td><input type="radio" name="coptp_primary_choice" value="278.02"/><td>Overweight</td></td></tr>
        </tbody>
    </table>
</div>
<div class="info">
    <table>
        <thead>
            <tr><th colspan="2">COPTP Secondary</th></tr>
        </thead>
        <tbody>
            <tr><td><input type="radio" name="coptp_secondary_choice" value="none"/></td><td>None</td></tr>
            <tr><td><input type="radio" name="coptp_secondary_choice" value="V85.53"/></td><td>BMI 85-94 percentile</td></tr>
            <tr><td><input type="radio" name="coptp_secondary_choice" value="V85.54"/></td><td>BMI 95+ percentile</td></tr>
        </tbody>
    </table>
</div>

<div class="info">
    <table>
        <thead>
            <tr><th colspan="2">Health Education</th></tr>
        </thead>
        <tbody>
            <tr><td><input type="radio" name="education_request_type" value="none"/><td>None</td></td></tr>
            <tr><td><input type="radio" name="education_request_type" value="weight"/><td>Weight Management</td></td></tr>
            <tr><td><input type="radio" name="education_request_type" value="diabetes"/></td><td>Diabetes</td></tr>
            <tr><td><input type="radio" name="education_request_type" value="other"/></td>
                <td>Other<input type="text" name="education_description" 
                          value="<?php echo isset($_REQUEST['education_description']) ? $_REQUEST['education_description'] : '';?>"/></td></tr>
        </tbody>
    </table>
    <div>What do you want the patient to learn?<br><input type="text" name="to_learn" size="60" value="<?php echo ris("to_learn");?>"/>
</div>
<input type="hidden" name="education_request" value="<?php echo $education_request;?>"/>
<input type="hidden" name="coptp_primary" value="<?php echo $coptp_primary;?>"/>
<input type="hidden" name="coptp_secondary" value="<?php echo $coptp_secondary;?>"/>
</form>
</div>
<input type="button" name="update" value="Update" />

<div class="display">