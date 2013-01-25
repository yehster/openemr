var msgAddPat=top.msgAddPat;
var msgSelEnc=top.msgSelEnc;
var pathWebroot=top.pathWebroot;

function loadFrame(fname, frame, url) {
  top.restoreSession();
  var i = url.indexOf('{PID}');
  if (i >= 0) url = url.substring(0,i) + active_pid + url.substring(i+5);
  top[frame].location = pathWebroot + url;
  if (frame == 'RTop') topName = fname; else botName = fname;
 }
 
 
  function loadFrame2(fname, frame, url) {
  var usage = fname.substring(3);
  if (active_pid == 0 && usage > '0') {
   alert(msgAddPat);
   return false;
  }
  if (active_encounter == 0 && usage > '1') {
   alert(msgSelEnc);
   return false;
  }
  var f = document.forms[0];
  top.restoreSession();
  var i = url.indexOf('{PID}');
  if (i >= 0) url = url.substring(0,i) + active_pid + url.substring(i+5);
  if(f.sel_frame)
   {
var fi = f.sel_frame.selectedIndex;
if (fi == 1) frame = 'RTop'; else if (fi == 2) frame = 'RBot';
   }
  if (!f.cb_bot.checked) frame = 'RTop'; else if (!f.cb_top.checked) frame = 'RBot';
  top.displayInFrame(frame, pathWebroot + url);
  if (frame == 'RTop') topName = fname; else botName = fname;
  return false;
 }

  function loadCurrentPatientFromTitle() {
            top.displayInFrame('RTop','../patient_file/summary/demographics.php');

 }

function loadCurrentEncounterFromTitle()
{
    top.displayInFrame("RBot",'../patient_file/encounter/encounter_top.php');
}
