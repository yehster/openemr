/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


ko.bindingHandlers.location={
    init: function(element,valueAccessor, allBindings,viewModel, bindingContext)
    {
        element.addEventListener("load",
            function()
            {
                var tabData = ko.unwrap(valueAccessor());
                
                var cwDocument=this.contentWindow.document
                $(cwDocument).ready(function(){
                        var jqDocument=$(cwDocument);
                        tabData.title(jqDocument.find(".title:first").text());
                    }
                );
            }
            ,true
        );

    },
    update: function(element,valueAccessor, allBindings,viewModel, bindingContext)
    {
        var tabData = ko.unwrap(valueAccessor());
        element.src=tabData.url();
    }
}


