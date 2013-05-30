// Find the sections we want to reorder
var DEM_TR=$("#DEM").parent().parent().parent();
var vitals=$("span.text > b:contains('Vitals')").parents("div.section-header").parent().parent();

var table=DEM_TR.parent();

// Add them back to the top of the page.
table.prepend(vitals);
table.prepend(DEM_TR);
