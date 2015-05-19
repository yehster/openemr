<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/javascript">
function value_tag_to_descriptions(tag)
{
    if(tag==='active_days')
    {
        return '<?php echo xlt('Active Days'); ?>';
    }
    else if(tag==='number_clients')
    {
        return '<?php echo xlt('Number of Clients'); ?>';
    }
    else if(tag==='number_visits')
    {
        return '<?php echo xlt('Number of Visits'); ?>';
    }
    else if(tag==='number_services')
    {
        return '<?php echo xlt('Number of Services'); ?>';
    }
    else if(tag==='daily_clients')
    {
        return '<?php echo xlt('Daily Clients'); ?>';
    }
    else if(tag==='daily_services')
    {
        return '<?php echo xlt('Daily Services'); ?>';
    }
    else if(tag==='services_per_client')
    {
        return '<?php echo xlt('Services per Client'); ?>';
    }    
    else
    {
        return tag;
    }
           
}
</script>
<script type="text/html" id="visits-results">
    <h1>Results</h1>
    <!-- ko if: report_type()=="Summary" -->
        <table>
            <thead>
                <tr data-bind="foreach: headers">
                    <th data-bind="text: $data"></th>
                </tr>
            </thead>
            <tbody data-bind="foreach: data_rows">
                <tr data-bind="foreach: $data">
                    <td data-bind="text: $data">
                    </td>

                </tr>
            </tbody>
        </table>
    <!-- /ko -->
    <!-- ko if: report_type()=="Providers" -->
        <table>
            <thead>
                <tr data-bind="foreach: headers">
                    <th data-bind="text: $data"></th>
                </tr>
            </thead>
            <tbody data-bind="foreach: data_rows">
                <tr data-bind="foreach: $data">
                    <td data-bind="text: $data==null ? 0 : $data"></td>

                </tr>
            </tbody>
        </table>
    <!-- /ko -->
</script>
