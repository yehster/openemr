/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


ko.bindingHandlers.location={
    init: function(element,valueAccessor, allBindings,viewModel, bindingContext)
    {
        var tabData = ko.unwrap(valueAccessor());
        tabData.window=element.contentWindow;
        element.addEventListener("load",
            function()
            {
                
                var cwDocument=this.contentWindow.document
                $(cwDocument).ready(function(){
                        var jqDocument=$(cwDocument);
                        var titleText="Unknown";
                        var titleClass=jqDocument.find(".title:first");
                        if(titleClass.length>=1)
                        {
                            titleText=titleClass.text();
                        }
                        else
                        {
                            var frameDocument=jqDocument.find("frame");
                            if(frameDocument.length>=1)
                            {
                                var jqFrameDocument=$(frameDocument.get(0).contentWindow.document);
                                titleClass=jqFrameDocument.find(".title:first");
                                if(titleClass.length>=1)
                                {
                                    titleText=titleClass.text();                                
                                }
                                else
                                {
                                    titleText=frameDocument.attr("name");
                                }
                                
                            }
                            
                        }
                        tabData.title(titleText);
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


