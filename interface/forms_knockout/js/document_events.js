function edit_phrase(data,event)
{
    if(event.type=='blur')
        {
            data.editing(false);
        }
    else if(event.type=='click')
        {
            data.editing(!data.editing());
        }
}

