/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function setup_birth_weight()
{
    var knockout_div=$("<div></div>");
    knockout_div.attr("data-bind","template: {name: 'birth-weight-display', data: birth_weight_data}")
    find_stats_column().prepend(knockout_div);
    $.ajax({
        url:birth_weight_ajax,
        data:{pid: pid,
              mode: 'get'
            },
        success:function(data)
        {
            birth_weight_view_model={birth_weight_data:new birth_weight_vm(parseFloat(data))};
            ko.applyBindings(birth_weight_view_model);
        }
    });    
}

function birth_weight_vm(kilos)
{
    var self=this;
    self.pounds_per_kilo=2.204;
    self.pounds_ounces=function(kilos)
    {
        var ounces_total=(parseFloat(kilos)*self.pounds_per_kilo*16).toFixed(1);
        var pounds_int=Math.floor(ounces_total/16);
        var ounces = (ounces_total-(pounds_int*16)).toFixed(1);

        
        return {pounds:pounds_int,ounces:ounces}
    }
    self.weight=ko.observable(kilos);
    self.display=ko.computed(function()
            {
                if((self.weight()===null)||(self.weight()==0))
                    {
                        return "Unspecified";
                    }
                    else
                    {
                        var english_units=self.pounds_ounces(self.weight());
                        return parseFloat(this.weight()).toFixed(3)+" kg " +"("+english_units.pounds+"lb "+english_units.ounces+"oz"
                    +")";
                    }
            },this
        );
    var lb_value=self.pounds_ounces(kilos)
    self.pounds=ko.observable(lb_value.pounds);
    self.ounces=ko.observable(lb_value.ounces);
    self.edit=ko.observable(false);
    self.edit_pounds=ko.observable(false);
    self.edit_birth_weight=function(data,event)
    {
        data.edit(!data.edit());
//        data.edit_pounds(data.edit());
        $("#birth_weight_pounds").select();
    };
    self.sync_pounds_ounces=function(data)
    {

        if(!self.updating)
        {
            var kilos=self.pounds()/self.pounds_per_kilo+ self.ounces()/16/self.pounds_per_kilo;
            self.weight(kilos.toFixed(3));
        }
    };
    self.pounds.subscribe(self.sync_pounds_ounces);
    self.ounces.subscribe(self.sync_pounds_ounces);
    self.update_weight=function(data,event)
    {
        var pounds_ounces=self.pounds_ounces(self.weight());
        self.updating=true;
        self.pounds(pounds_ounces.pounds);
        self.ounces(pounds_ounces.ounces);        
        self.updating=false;
        $.ajax({
            url:birth_weight_ajax,
            data:{pid: pid,
                  mode: 'set',
                  kilos: self.weight()
                },
            success:function(data)
            {
                birth_weight_view_model={birth_weight_data:new birth_weight_vm(parseFloat(data))};
                ko.applyBindings(birth_weight_view_model);
            }
        });            
    };
    self.weight.subscribe(self.update_weight);

    self.handle_keyup=function(data,event)
    {
        if(event.keyCode===13)
            {
                self.edit(false);
            }
        return true;
    }
}


setup_birth_weight();;