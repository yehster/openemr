<script type="text/html" id="patient-data-template">
    <!-- ko if: patient -->
        <div data-bind="with: patient">
            <span class="patientDataColumn">
                <div>
                    <span>Patient:</span>
                    <b>
                    <a data-bind="click:refreshPatient" href="#">
                        <span data-bind="text: pname()"></span>(<span data-bind="text: pubpid"></span>)
                    </a>
                    </b>
                </div>
                <div>
                    <b>
                        <span data-bind="text:str_dob()"></span>
                    </b>
                </div>
            </span>
            <span class="patientDataColumn">
                <?php echo xlt("Allergies");?>:
                <b>NOT YET IMPLEMENTED</b>
            </span>
            <span class="patientDataColumn patientEncountersColumn">
                <div>
                    <span>Selected Encounter:</span>
                    <!-- ko if:selectedEncounter() -->
                        <span data-bind="text:selectedEncounter().date()"></span>
                        (<span data-bind="text:selectedEncounter().id()"></span>)
                    <!-- /ko -->
                    <!-- ko if:!selectedEncounter() -->
                        <?php echo xlt("None") ?>
                    <!-- /ko -->                
                </div>
                <span class="patientEncounterList" >
                    <div data-bind="click: clickNewEncounter"><?php echo xlt("New Encounter");?></div>
                    <div data-bind="click: clickEncounterList"><?php echo xlt("Past Encounter List");?>
                        (<span data-bind="text:encounterArray().length"></span>)
                    </div>
                    <table class="encounters">
                        <tbody>
                        <!-- ko  foreach:encounterArray -->
                            <tr >
                                <td data-bind="click: chooseEncounterEvent">
                                    <span data-bind="text:date"></span>
                                    <span data-bind="text:category"></span>
                                </td>
                                <td class="review" data-bind="click: reviewEncounterEvent">Review
                                </td>
                            </tr>
                        <!-- /ko -->
                        </tbody>
                    </table>
                </span>
            </span>
        </div>
    <!-- /ko -->
</script>