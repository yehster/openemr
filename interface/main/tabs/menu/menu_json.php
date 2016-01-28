<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("menu_data.php");
$menu_parsed=json_decode($menu_json);

// TODO: Transform menu objects with xlt and apply ACL/global restrictions

?>
<script type="text/javascript">
    
    function menu_entry(object)
    {
        var self=this;
        self.label=ko.observable(object.label);

//        alert(object.label+":"+object.url)
        self.header=false;
        if('url' in object )
        {
            self.url=ko.observable(object.url);
            self.header=false;
        }
        else
        {
            self.header=true;
        }
        if(object.requirement===0)
        {
            self.enabled=ko.observable(true);
        } else if(object.requirement===1)
        {
            self.enabled=ko.computed(function()
            {
                return app_view_model.application_data.patient()!==null;
            });
        } else if(object.requirement===2)
        {
            self.enabled=ko.computed(function()
            {
                return (app_view_model.application_data.patient()!==null
                        && app_view_model.application_data.patient().selectedEncounter()!=null);
            });
            
        }
        if(self.header)
        {
            self.children=ko.observableArray();
            for(var childIdx=0;childIdx<object.children.length;childIdx++)
            {
                var childObj=new menu_entry(object.children[childIdx]);
                self.children.push(childObj);
            }
        }
        return this;
    }
    function process_menu_object(object,target)
    {
        var newEntry=new menu_entry(object);
        target.push(newEntry);
    }
    var menu_objects=<?php echo json_encode($menu_parsed); ?>;
    app_view_model.application_data.menu=ko.observableArray();
    for(var menuIdx=0;menuIdx<menu_objects.length;menuIdx++)
    {
        var curObj=menu_objects[menuIdx];
        process_menu_object(curObj,app_view_model.application_data.menu);
    }
</script>