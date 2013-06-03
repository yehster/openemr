<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script>
    function setup_patient_options()
    {
        var search_types=[["ExternalID","External ID"],["PolicyNumber","Policy Number"],["PID","PID"],["Name","Name"]];
        var patient_info= $("<span id='patient_info'></span>");
        var td_patient_info=$("#patient_code").parent().siblings("td.text:first");
        td_patient_info.attr("id","td_patient_info");
        td_patient_info.prepend(patient_info);
        var search_type_html="<span id='patient_search_options'>";
            for(var idx=0;idx<search_types.length;idx++)
            {
                search_type_html+="<input type='radio' name='patient_search_type'"+ (idx===0? " checked='true'" : "")+" value='"  +search_types[idx][0] +"'>";
                search_type_html+=search_types[idx][1]+"</input>" +"<br>";
            }
        search_type_html   +="<span>";
        patient_info.prepend(search_type_html);
        $("input[name='patient_search_type']").click(function(){$("#patient_code").focus();$("#td_patient_info").mouseout();});
    }
    function patient_search(evt)
    {
        
    }
    function setup_overrides()
    {
//        $("#patient_code").unbind("keyup");
//        $("#patient_code").keyup(patient_search);
        setup_patient_options();
    };
</script>