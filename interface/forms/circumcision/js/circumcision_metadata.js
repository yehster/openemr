function circumcision_document()
{
    this.entries=ko.observableArray();

    var r1= new document_phrase("Risks1",null,"Risks and benefits of circumcision explained fully at");
    this.encounter_date=new document_date("visit_date",r1);
    var r1b= new document_phrase("Risks1b",r1,"visit.");
    this.entries.push(r1);
    
    
    var r2 = new document_phrase("Risks2",null,"Risks, benefits, and alternatives to circumcision were reviewed. Infant was restrained with his blanket and circumcision restraint device. Sugar nipple (Sweetease) provided to help with anesthesia. Dorsal penile nerve block was administered with");
    
    var anesthesia = new document_select("anesthesia",r2,"1% lidocaine without epinephrine",["1% lidocaine without epinephrine"])
    anesthesia.showLabel(false);
    var anesthesia_amount = new document_quantity("anesthesia amount",r2,"ml");
    anesthesia_amount.value(("1.0"));
    anesthesia_amount.showLabel(false);
    anesthesia_amount.numeric_format.set(0,1,0.1,1);

    
    var r2b= new document_phrase("Risks2b",r2,". Infant was then prepped and draped in a sterile manner.");
    this.entries.push(r2);
    
    
    var clamp = new document_phrase("Clamp",null,"");
    var clamp_size=new document_select("clamp-size",clamp,"1.1 cm",["1.1 cm","1.3 cm","1.45 cm","1.6 cm"]);
    clamp_size.showLabel(false);
    
    var clamp_option=new document_select("clamp-option",clamp,"Gomco clamp",["Gomco clamp","Plastibell","Mogen"]);
    clamp_option.showLabel(false);
    var clamp_phrase = new document_phrase("Clamp-end",clamp,"was used for the procedure.");
    
    this.entries.push(clamp);
    
    this.ebl = new document_text_choice("Estimated blood loss",null,["none","few drops"]); 
    this.entries.push(this.ebl);
    
    
    
    var no_comp = new document_phrase("no_comp",null,"No complications. Infant tolerated circumcision well.");
    this.entries.push(no_comp);

    var gauze = new document_phrase("gauze",null,"Vaseline gauze applied.");
    this.entries.push(gauze);
    
    var call = new document_phrase("call",null,"We instructed the family to call us 24 hours per day if they have any questions or concerns.");
    this.entries.push(call);
    
    var tol = new document_phrase("tolerated",null,"Patient tolerated procedure well.");
    this.entries.push(tol);

    return this;
}