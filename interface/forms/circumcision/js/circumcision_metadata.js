/*
Risks and benefits of circumcision explained fully at 10/15/2013 visit, and re-iterated risk of bleeding and infection prior to this procedure.

Risks, benefits, and alternatives to circumcision were reviewed. Infant was restrained with his blanket and circumcision restraint device. Sugar nipple (Sweetease) provided to help with anesthesia. Dorsal penile nerve block was administered with 1% lidocaine without epinephrine 1.0 ml. Infant was then prepped and draped in a sterile manner.

1.1cm Gomco clamp was used for the procedure.

Estimated blood loss: <5ml.

No complications. Infant tolerated circumcision well.

Vaseline gauze applied.

Family instructed to call 24 hours if any question or concern.

 */
function circumcision_document()
{
    this.entries=ko.observableArray();

    var r1= new document_phrase("Risks1",null,"Risks and benefits of circumcision explained fully at");
    var encounter_date=new document_date("visit_date",r1);
    var r1b= new document_phrase("Risks1b",r1,"visit, and re-iterated risk of bleeding and infection prior to this procedure.");
    this.entries.push(r1);
    
    
    var r2 = new document_phrase("Risks2",null,"Risks, benefits, and alternatives to circumcision were reviewed. Infant was restrained with his blanket and circumcision restraint device. Sugar nipple (Sweetease) provided to help with anesthesia. Dorsal penile nerve block was administered with");
    
    var anesthesia = new document_select("anesthesia",r2,"1% lidocaine without epinephrine",["1% lidocaine without epinephrine","1% lidocaine with epinephrine"])
    anesthesia.showLabel(false);
    var anesthesia_amount = new document_quantity("anesthesia amount",r2,"ml");
    anesthesia_amount.value(("1.0"));
    anesthesia_amount.showLabel(false);
    //1% lidocaine without epinephrine 1.0 ml. Infant was then prepped and draped in a sterile manner.");
    
    var r2b= new document_phrase("Risks2b",r2,". Infant was then prepped and draped in a sterile manner.");
    this.entries.push(r2);
    
    
    var clamp = new document_phrase("Clamp",null,"");
    var clamp_option=new document_select("clamp-option",clamp,"1.1 cm Gomco clamp",["1.1 cm Gomco clamp","1.3 cm Gomco clamp"]);
    clamp_option.showLabel(false);
    var clamp_phrase = new document_phrase("Clamp-end",clamp,"was used for the procedure.");
    
    this.entries.push(clamp);
    
    this.ebl = new document_quantity("Estimated blood loss",null,"ml"); 
    this.entries.push(this.ebl);
    
    
    
    var no_comp = new document_phrase("no_comp",null,"No complications. Infant tolerated circumcision well.");
    this.entries.push(no_comp);

    var gauze = new document_phrase("gauze",null,"Vaseline gauze applied.");
    this.entries.push(gauze);
    
    var call = new document_phrase("call",null,"We instructed the family to call us 24 hours per day if they have any questions or concerns.");
    this.entries.push(call);
    
    var tol = new document_phrase("tolerated",null,"Tolerated procedure well.");
    this.entries.push(tol);

    return this;
}