<?php

function user_allowed_delete()
{
    $authorized_delete_users=array('sonia'=>'yes','admin'=>'yes');
    if(isset($authorized_delete_users[$_SESSION['authUser']]))
    {
        if($authorized_delete_users[$_SESSION['authUser']]==='yes')
        {
            return true;
        }
    }
    return false;
}
?>