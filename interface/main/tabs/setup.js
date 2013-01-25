frames_map={};
frames_map['RTop']=frames['main1'];
frames_map['RBot']=frames['main3'];

function displayInFrame(frame,url)
{
    frames_map[frame].location=url;
}