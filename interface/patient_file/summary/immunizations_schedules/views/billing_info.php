<script type="text/html" id="billing-info">
    <div data-bind="visible: justifyCodes().length>0">
        <div data-bind="visible: encounter()==0"><?php echo 'Create or choose encounter then refresh for billing'?></div>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Fee</th>
                </tr>  
            <tbody>
                <tr>
                    <td data-bind="text: procedureCode().code"></td>
                    <td data-bind="text: procedureCode().description=='' ? 'Unknown' : procedureCode().description"></td>
                    <td><input type="text" data-bind="value: procedureCode().price"/></td>
                </tr>
            </tbody>        
        </table>
        <table>
            <tbody data-bind="foreach: justifyCodes">
                <tr>
                    <td data-bind="text: code"></td>
                    <td data-bind="text: description"></td>
                </tr>
            </tbody>
        </table>
    </div>
    
</script>
