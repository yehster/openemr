/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function set_education_request(val)
{
    var education_request=$("input[name='education_request']");
    if((val==="none") || (val==="weight") || val==="diabetes")
    {
        education_request.val(val);
    }
    else if(val==="other")
    {
        education_request.val($("input[name='education_description']").val());
    }
    
}

function set_coptp()
{
    $("input[name='coptp_primary']").val($("input[name='coptp_primary_choice']:checked").val());
    $("input[name='coptp_secondary']").val($("input[name='coptp_secondary_choice']:checked").val());
}
function update_fields()
{
    set_education_request($("input[name='education_request_type']:checked").val());
    set_coptp();
    document.forms[0].submit();
}
function populate_fields()
{
    var education_request=$("input[name='education_request']").val();
    if((education_request==='none') || 
       (education_request==='weight') ||
       (education_request==='diabetes'))
        {
            $("input[name='education_request_type'][value='"+education_request+"']").prop("checked",true);
        }
        else
        {
            $("input[name='education_request_type'][value='other']").prop("checked",true);
            $("input[name='education_description']").val(education_request);
        }
  var coptp_primary=$("input[name='coptp_primary']").val();
  $("input[name='coptp_primary_choice'][value='"+coptp_primary+"']").prop("checked",true);

  var coptp_secondary=$("input[name='coptp_secondary']").val();
  $("input[name='coptp_secondary_choice'][value='"+coptp_secondary+"']").prop("checked",true);

}

function setup_events()
{
    $("input[name='update']").on({click: update_fields});
    populate_fields();
}
$(document).ready(
        function()
        {
            setup_events();
});
