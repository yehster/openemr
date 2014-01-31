
function infant_sick_visit_document()
{
    this.entries=ko.observableArray();

    var cc= new document_section("Chief Complaint");
    var cc_ft=new document_freetext("Chief Complaint-Freetext",cc);
    
    this.entries.push(cc);

    var dh= new document_section("Diet History");
    var dh_bm=new document_option_set("Breast milk",dh);
    var dh_bm_number=new document_quantity("Number of feedings",dh_bm,"per 24 hours");
    var dh_bm_interval=new document_quantity("How often feeding, Every",dh_bm,"hours");
    
    var dh_for=new document_option_set("Formula",dh);
    choice_list(dh_for,["Enfamil Premium Newborn",
                        "Enfamil Premium Infant",
                        "Enfamil Gentlease",
                        "Enfamil ProSobee",
                        "Similac Advance",
                        "Similac Advance Organic",
                        "Enfagrow Premium Toddler",
                        "Enfamil AR",
                        "Nutramigen",
                        "Parent's Choice Wal-Mart",
                        "Similac for Supplementation",
                        "Similac Sensitive (lactose-free)",
                        "Similac Total Comfort (partially broken-down protein)",
                        "Similac Soy Isomil","Similac for Spit-Up",
                        "Similac Alimentum",
                        "Similac NeoSure"]);
                    
    var dh_for_cereal=new document_option_set("Rice cereal added",dh_for);
    var dh_for_cereal_qty = new document_quantity("amount",dh_for_cereal,"tsp/bottle");  

    var dh_foods= new document_grouping("foods",dh);
    choice_list(dh_foods,["Solid foods","Mashed table foods","Commercial prepared foods"]);

    var dh_juice= new document_option_set("Juice(natural)",dh);

    var dh_soda= new document_option_set("Soda",dh);

    var dh_gatorade= new document_option_set("Gatorade",dh);
 
    var dh_other= new document_option_set("Other sweet liquid",dh);

    var dh_water= new document_option_set("Water",dh);
    add_liquids([dh_juice,dh_soda,dh_gatorade,dh_other,dh_water]);
    
    this.entries.push(dh);


    // Breastfeeding BEGIN
    var bf= new document_section("Breastfeeding");
    var bf_pain=new document_option_set("Pain with latch",bf);
    var bf_pain_choice1 = new document_choice("Lasts few seconds",bf_pain);
    var bf_pain_choice2 = new document_choice("more than a few seconds",bf_pain);
    
    var bf_pain_duration=new document_duration("duration",bf_pain);
    var bf_pain_side=new document_side("side",bf_pain);
    
    var bf_mnp=new document_option_set("Maternal nipples bleeding",bf);
    var bf_mnp_duration=new document_duration("duration",bf_mnp);    
    var bf_bleeding=new document_side("side",bf_mnp);        
    
    var bf_cn=new document_option_set("Cracked nipples",bf);
    var bf_cn_duration=new document_duration("duration",bf_cn);        
    var bf_cn_side=new document_side("side",bf_cn);    
    
    // Free-text
    
    this.entries.push(bf);
    // Breastfeeding END


    var st= new document_section("Stooling");
    var st_frequency=new document_quantity("Frequency",st,"per day");
    
    var st_color=new document_option_set("Color",st);
    var st_color_choice1=new document_choice("black",st_color);
    var st_color_choice2=new document_choice("green",st_color);
    var st_color_choice3=new document_choice("black-green",st_color);
    var st_color_choice4=new document_choice("yellow seedy",st_color);
    
    var st_consistency=new document_option_set("Consistency",st);
    var st_consistency_choice1=new document_choice("hard",st_consistency);
    var st_consistency_choice2=new document_choice("soft",st_consistency);
    var st_consistency_choice3=new document_choice("liquid",st_consistency);
    var st_consistency_choice4=new document_choice("runny",st_consistency);
    
    
    this.entries.push(st);

    var hpi= new document_section("History of Present Illness");
    var hpi_con= new document_section("Constitutional",hpi);
    var hpi_con_text= new document_text_history("Constitutional-history",hpi_con);
    var hpi_con_fever = new document_section("Fever",hpi_con);
    var hpi_con_fever_tactile = new document_option_set("Tactile",hpi_con_fever);
    var hpi_con_fever_tmax = new document_quantity("Tmax",hpi_con_fever,"degrees F");
    var hpi_con_fever_duration= new document_select("Duration",hpi_con_fever,"",["","12 hours", "24 hours", "36 hours", "48 hours","60 hours","72 hours","1 day","2 days","3 days","4 days","5 days","6 days","7 days","1 week","2 weeks","Unknown"])
    var hpi_con_fever_text = new document_text_finding("Fever-text",hpi_con_fever);

    var hpi_con_fussy = new document_section("Fussy",hpi_con);
    var hpi_con_fussy_text = new document_text_history("Fussy-history",hpi_con_fussy);
    var hpi_con_fussy_consolable = new document_option_set("Consolable",hpi_con_fussy);
    var hpi_con_fussy_consolable_held = new document_choice("When held",hpi_con_fussy_consolable);
    var hpi_con_fussy_consolable_fed = new document_choice("When fed",hpi_con_fussy_consolable);
    var hpi_con_fussy_consolable_latch = new document_choice("When latched to breast",hpi_con_fussy_consolable);
    var hpi_con_fussy_consolable_free = new document_text_history("Consolable-history",hpi_con_fussy_consolable);
    
    
    var hpi_con_fussy_not_consolable = new document_option_set("Not Consolable",hpi_con_fussy);    

     var hpi_con_inactive = new document_option_set("Inactive",hpi_con);    
     var hpi_con_inactive_qty= new document_quantity("No activity during time of day when normally active for",hpi_con_inactive,"days",['days','hours','weeks']);
   
    
    var hpi_pain= new document_section("Pain",hpi);
    var hpi_pain_text= new document_text_history("Pain-history",hpi_pain);
    var hpi_pain_location= new document_select("Location",hpi_pain,"",["","Ear","Throat","Abdomen","Neck","Chest"]);
    var hpi_pain_side=new document_side("Pain-Location-Side",hpi_pain_location);
    var hpi_pain_duration=new document_duration("Pain-Duration",hpi_pain_location);

    // BEGINNING OF EYE
    var hpi_eye= new document_section("Eye",hpi);
    var hpi_eye_text= new document_text_history("Eye-history",hpi_eye);
    var hpi_eye_discharge = new document_option_set("with discharge",hpi_eye);
    var hpi_eye_discharge_color = new document_select("color",hpi_eye_discharge,"",["","yellow","green","white"]);
    var hpi_eye_discharge_duration = new document_duration("discharge duration",hpi_eye_discharge);
    
    var hpi_eye_shut = new document_option_set("stuck shut on awakening",hpi_eye);
    var hpi_eye_shut_duration = new document_duration("shut duration",hpi_eye_shut);

    var hpi_eye_not_moving = new document_option_set("not moving normally",hpi_eye);
    var hpi_eye_not_moving_duration = new document_duration("not moving duration",hpi_eye_not_moving);

    var hpi_eye_crossed = new document_option_set("crossed",hpi_eye);
    var hpi_eye_crossed_duration = new document_duration("crossed duration",hpi_eye_crossed);
    
    var hpi_eye_spot = new document_option_set("spot on white part of eye",hpi_eye);
    var hpi_eye_spot_color = new document_select("color",hpi_eye_spot,"",["","red","brown","black"]);
    var hpi_eye_spot_side=new document_side("side",hpi_eye_spot);
    
    var hpi_eye_tearing_watering=new document_option_set("Eye tearing/watering",hpi_eye);
    var hpi_eye_tearing_watering_side=new document_side("side",hpi_eye_tearing_watering);
    // END OF EYE
    
    
    // Begin Nose
    var hpi_nose= new document_section("Nose",hpi);
    var hpi_nose_text = new document_text_history("Nose-history",hpi_nose);
    
    var hpi_nose_plugged = new document_option_select("Nose blockage",hpi_nose,"plugged",["plugged","obstructed","stuffy"]); // Fix this
    var hpi_nose_plugged_duration = new document_duration("Nose blockage-duration",hpi_nose_plugged);
    
    var hpi_nose_running = set_and_duration("running",hpi_nose);
    var hpi_nose_bleeding = set_and_duration("bleeding",hpi_nose);
    var hpi_nose_itchy = set_and_duration("itchy",hpi_nose);
    // End Nose

    //BEGIN Mouth
    var hpi_m_t = new document_section("Mouth and Throat",hpi);
    var hpi_m_t_text = new document_text_history("Mouth and Throat-history",hpi_m_t)
    
    var hpi_m_t_pain = set_and_duration("pain with swallowing/feeding",hpi_m_t);    // Fix this
    var hpi_m_t_white_spots = new document_option_set("white spots",hpi_m_t);    
    var hpi_m_t_white_spots_location= new document_select("location",hpi_m_t_white_spots,"",["","in mouth","on gums","on roof of mouth"])
    var hpi_m_t_white_spots_duration = new document_duration("white spots-duration",hpi_m_t_white_spots);    
    var hpi_m_t_white_tongue = set_and_duration("white tongue",hpi_m_t);    
   //END Mouth

    // Begin Ear
    var hpi_ear= new document_section("Ear",hpi);
    var hpi_ear_text=new document_text_history("Ear-history",hpi_ear);
    var hpi_ear_pain = set_and_duration("pain",hpi_ear);
    var hpi_ear_pain_side = new document_side("side",hpi_ear_pain);

    var hpi_ear_pulling = set_and_duration("pulling on",hpi_ear);
    var hpi_ear_pulling_side = new document_side("side",hpi_ear_pulling);    

    var hpi_ear_discharge = new document_option_select("discharge",hpi_ear,"discharge",["discharge","drainage"]); 
    var hpi_ear_discharge_duration = new document_duration("discharge-duration",hpi_ear_discharge);        
    var hpi_ear_discharge_side = new document_side("side",hpi_ear_discharge);
    
    
    // End Ear

    // Begin Resp
    var hpi_resp= new document_section("Respiratory",hpi);
    var hpi_resp_text = new document_text_history("Respiratory-history",hpi_resp);
    var hpi_cough_set=new document_option_set("cough",hpi_resp);
    var hpi_cough_choice1=new document_choice("wet",hpi_cough_set);
    var hpi_cough_choice2=new document_choice("dry",hpi_cough_set);    
    var hpi_resp_cough_duration = new document_duration("resp-cough duration",hpi_cough_set);
    var hpi_resp_wheeze= set_and_duration("wheezing",hpi_resp);
    var hpi_resp_phlegm= set_and_duration("phlegm in chest",hpi_resp);
    
    var hpi_resp_difficulty = new document_option_set("difficulty breathing",hpi_resp);
    var hpi_resp_difficulty_duration = new document_duration("resp-difficulty duration",hpi_resp_difficulty);
    var hpi_resp_difficulty_choice1=new document_choice("due to nasal congestion",hpi_resp_difficulty);  
    var hpi_resp_difficulty_choice2=new document_choice("inside chest",hpi_resp_difficulty);  
    var hpi_resp_difficulty_choice3=new document_choice("working hard to breathe",hpi_resp_difficulty);  
    
    var hpi_resp_color= new document_option_set("Turning purple/blue",hpi_resp);
    var hpi_resp_color_choice1 = new document_choice("face",hpi_resp_color);
    var hpi_resp_color_choice2 = new document_choice("lips",hpi_resp_color);
    var hpi_resp_color_choice3 = new document_choice("around mouth",hpi_resp_color);
    var hpi_resp_color_choice4 = new document_choice("inside mouth",hpi_resp_color);
    var hpi_resp_color_choice5 = new document_choice("whole head",hpi_resp_color);
    var hpi_resp_color_text = new document_text_history("resp-color-text",hpi_resp_color);
    
    //End Resp

    // Begin GI
    var hpi_gi= new document_section("Gastrointestinal",hpi);   
    var hpi_gi_text = new document_text_history("gi-history",hpi_gi);
    var hpi_gi_spit_up = set_and_duration("spit-up",hpi_gi);
    var hpi_gi_spit_up_w_cough = new document_choice("with coughing",hpi_gi_spit_up);
    var hpi_gi_spit_up_wo_cough = new document_choice("without coughing",hpi_gi_spit_up);
    var hpi_gi_spit_up_w_choking = new document_choice("with choking",hpi_gi_spit_up);
    var hpi_gi_spit_up_wo_choking = new document_choice("without choking",hpi_gi_spit_up);
    var hpi_gi_spitup_green = create_yes_no("green",hpi_gi_spit_up);
    var hpi_gi_spitup_yellow = create_yes_no("yellow",hpi_gi_spit_up);
    var hpi_gi_spitup_text = new document_text_history("gi-spitup",hpi_gi_spit_up);
    
    
    
    var hpi_diarrhea = set_and_duration("diarrhea",hpi_gi);
    var hpi_diarrhea_normal = new document_choice("means normal yellow seedy liquid breastfed stools",hpi_diarrhea);
    var hpi_diarrhea_blood = create_yes_no("blood",hpi_diarrhea);
    var hpi_diarrhea_blood_qty= new document_quantity("amount",hpi_diarrhea,"",["","number of times","frequency of blood"]);
    
    var hpi_diarrhea_green = create_yes_no("green",hpi_diarrhea);
    var hpi_diarrhea_text= new document_text_history("diarrhea-history",hpi_diarrhea);
    
    var hpi_gi_constipation = new document_option_select("constipation",hpi_gi,"constipation",["constipation","hard stools"]);
    var hpi_gi_constipation_duration = new document_duration("constipation-duration",hpi_gi_constipation);
    var hpi_gi_constipation_blood = create_yes_no("blood",hpi_gi_constipation);
    var hpi_gi_constipation_blood_qty= new document_quantity("amount",hpi_gi_constipation,"",["","number of times","frequency of blood"]);
    var hpi_gi_constipation_pain=new document_choice("pain with stooling",hpi_gi_constipation);
    var hpi_gi_constipation_infrequent=new document_choice("infrequent stools",hpi_gi_constipation);
    var hpi_gi_constipation_frequency= new document_quantity("number of stools",hpi_gi_constipation,"",["","pre day","per week"]);
    var hpi_gi_constipation_history=new document_text_history("constipation-history",hpi_gi_constipation);
    
    var hpi_gi_vomitting = new document_option_set("vomitting",hpi_gi);
    var hpi_gi_vomitting_choice1= new document_choice("non-bilious",hpi_gi_vomitting);
    var hpi_gi_vomitting_choice2= new document_choice("non-bloody",hpi_gi_vomitting);    
    var hpi_gi_vomitting_choice3= new document_choice("green",hpi_gi_vomitting);    
    var hpi_gi_vomitting_choice4= new document_choice("yellow",hpi_gi_vomitting);    
    var hpi_gi_vomitting_choice5= new document_choice("mucous",hpi_gi_vomitting);    
    var hpi_gi_vomitting_choice6= new document_choice("partially digested food/milk",hpi_gi_vomitting);    
    var hpi_gi_vomitting_choice7= new document_choice("projectile",hpi_gi_vomitting);
    
    var hpi_gi_vomitting_history = new document_text_history("vomitting-history",hpi_gi_vomitting);
    var hpi_gi_vomitting_duration = new document_duration("vomitting-duration",hpi_gi_vomitting);    

    // End GI;
    
    // Begin Heme
    var hpi_heme= new document_section("Hematological",hpi);
    var hpi_heme_photo = set_and_duration("received phototheray in hospital",hpi_heme);
    var hpi_heme_color = new document_option_set("concern about yellow color of",hpi_heme);
    var hpi_heme_color_eyes = new document_choice("eyes",hpi_heme_color);
    var hpi_heme_color_skin = new document_choice("skin",hpi_heme_color);
    var hpi_heme_history = new document_text_history("heme-history",hpi_heme);
    var hpi_heme_duration= new document_duration("heme-duration",hpi_heme);
    // End Heme
    
    // Begin gu
    var hpi_gu= new document_section("Genitourinary",hpi);
    var hpi_gu_discharge = new document_option_set("discharge from vagina",hpi_gu);
    var hpi_gu_discharge_mucous = new document_choice("mucous",hpi_gu_discharge);
    var hpi_gu_discharge_blood = new document_choice("blood",hpi_gu_discharge);
    
    var hpi_gu_foreskin= new document_option_set("foreskin concern",hpi_gu);
    var hpi_gu_foreskin_history= new document_text_history("foreskin concern-history",hpi_gu_foreskin);    
    var hpi_gu_foreskin_duration = new document_duration("foreskin concern-duration",hpi_gu_foreskin);
    
    var hpi_gu_dysuria= new document_option_set("dysuria",hpi_gu);
    var hpi_gu_dysuria_history= new document_text_history("dysuria-history",hpi_gu_dysuria);    
    var hpi_gu_dysuria_duration = new document_duration("dysuria-duration",hpi_gu_dysuria);  
    

    var hpi_gu_blood= new document_option_set("blood in diaper",hpi_gu);
    var hpi_gu_blood_red = new document_choice("bright red",hpi_gu_blood);
    var hpi_gu_blood_brick = new document_choice("brick color",hpi_gu_blood);
    // end gu
    
    // begin derm
    var hpi_skin= new document_section("Skin",hpi);    
    var hpi_skin_history = new document_text_history("skin-history",hpi_skin);
    var hpi_skin_duration= new document_duration("skin-duration",hpi_skin);
    
    var hpi_skin_rash= set_and_duration("rash",hpi_skin);
    var hpi_skin_rash_location1 = new document_choice("diaper area",hpi_skin_rash);
    var hpi_skin_rash_location2 = new document_choice("upper extermities",hpi_skin_rash);
    var hpi_skin_rash_location3 = new document_choice("lower exteremities",hpi_skin_rash);
    var hpi_skin_rash_location4 = new document_choice("face",hpi_skin_rash);
    var hpi_skin_rash_location5 = new document_choice("cheeks",hpi_skin_rash);
    var hpi_skin_rash_location6 = new document_choice("forehead",hpi_skin_rash);
    var hpi_skin_rash_location7 = new document_choice("scalp",hpi_skin_rash);
    var hpi_skin_rash_location8 = new document_choice("abdomen",hpi_skin_rash);
    var hpi_skin_rash_location9 = new document_choice("chest",hpi_skin_rash);
    var hpi_skin_rash_location10 = new document_choice("back",hpi_skin_rash);
    var hpi_skin_rash_location11 = new document_choice("buttocks",hpi_skin_rash);
    var hpi_skin_rash_location_side = new document_side("rash-side",hpi_skin_rash);
    var hpi_skin_rash_choice1 = new document_choice("pruritic/itchy",hpi_skin_rash);    
    var hpi_skin_rash_choice2 = new document_choice("bump on breast",hpi_skin_rash);    
    
    // end derm
    var hpi_ortho= new document_section("Orthopedic",hpi);  
    var hpi_ortho_text = new document_text_history("Orthopedic-history",hpi_ortho);

    var hpi_neuro= new document_section("Neurologic",hpi);
    var hpi_neuro_flat=new document_option_set("Flat spot on head",hpi_neuro);
    
    var hpi_neuro_arm=new document_option_set("Not moving arm",hpi_neuro);
    var hpi_neuro_arm_side=new document_side("side",hpi_neuro_arm); 
    
    var hpi_neuro_leg=new document_option_set("Not moving leg",hpi_neuro);
    var hpi_neuro_leg_side=new document_side("side",hpi_neuro_leg);
    
    var hpi_neuro_shaking=new document_option_set("Shaking",hpi_neuro)
    
    var hpi_dev = new document_section("Developmental concern",hpi);
    var hpi_dev_not_talk = new document_option_set("Not talking",hpi_dev);

    var hpi_dev_speaking = new document_option_set("Speaking this many words",hpi_dev);
    var hpi_dev_number = new document_quantity("words",hpi_dev_speaking);
    
    var hpi_dev_not_speaking = new document_option_set("Not speaking well",hpi_dev);
    var hpi_dev_not_speaking_text=new document_text_finding("Not speaking well-text",hpi_dev_not_speaking);

    var hpi_dev_hearing = new document_option_set("Seems to hear well",hpi_dev);    

    var hpi_dev_see = new document_option_set("Seems to see well",hpi_dev);    

    this.entries.push(hpi);

    var pe= new document_section("Physical Exam");
    // All Normal
    
    var pe_gen = new document_section("General",pe);
    
    var pe_head = new document_section("Head",pe);

    var pe_eye = new document_section("Eyes",pe);
    
    var pe_ears = new document_section("Ears",pe);

    var pe_nose = new document_section("Nose",pe);
    
    var pe_mouth = new document_section("Mouth",pe);

    var pe_throat = new document_section("Throat",pe);

    var pe_neck = new document_section("Neck",pe);

    var pe_chest = new document_section("Chest",pe);

    var pe_heart = new document_section("Heart",pe);

    var pe_lungs = new document_section("Lungs",pe);

    var pe_abd = new document_section("Abdomen",pe);
    
    var pe_gu = new document_section("Genitourinary",pe);

    var pe_hips = new document_section("Hips",pe);

    var pe_back = new document_section("Back",pe);

    // BEGIN Extremities
    var pe_ext = new document_section("Extremities",pe);
    var pe_ext_none = new document_option_set("No deformities",pe_ext);
    var pe_ext_pdcty = new document_option_set("Polydactyly",pe_ext);
    var pe_ext_text = new document_text_finding("Extremities-PE-Text",pe_ext);
    // End Extremities
    
    
    // Begin Skin
    var pe_skin = new document_section("Skin",pe);
    var pe_skin_none=new document_option_set("no rashes or lesions noted",pe_skin);

    var pe_skin_text = new document_text_finding("Skin-PE-Text",pe_skin);
    
    // patches,erythema/pustules... TO DO
    
    var pe_skin_gpr= new document_option_set("greasy papular rash",pe_skin);
    var pe_skin_gpr_loc1= new document_choice("posterior to auricles",pe_skin_gpr);
    var pe_skin_gpr_loc2= new document_choice("eyebrows",pe_skin_gpr);
    var pe_skin_gpr_loc3= new document_choice("scalp",pe_skin_gpr);
    var pe_skin_gpr_loc4= new document_choice("with dandruff",pe_skin_gpr);
    var pe_skin_gpr_loc5= new document_choice("with scales",pe_skin_gpr);

    var pe_skin_excoriations= new document_option_set("linear excoriations",pe_skin);
    // End Skin
   
   
    this.entries.push(pe);

    for(var i=1;i<this.entries().length;i++)
        {
            this.entries()[i].expanded(false);
        }
        
        this.entries()[4].expanded(true);
        this.entries()[4].children()[0].expanded(false);
    return this;
}