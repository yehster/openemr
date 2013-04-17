<?php
require_once("$srcdir/authentication/common_operations.php");

function update_password($activeUser,$targetUser,&$currentPwd,&$newPwd,&$errMsg)
{
    $status=false;
    $userSQL="SELECT ".implode(",",array(COL_PWD,COL_SALT,COL_PWD_H1,COL_SALT_H1,COL_PWD_H2,COL_SALT_H2))
            ." FROM ".TBL_USERS_SECURE
            ." WHERE ".COL_ID."=?";
    $userInfo=privQuery($userSQL,array($targetUser));
    if($activeUser==$targetUser)
    {
        // If this user is changing his own password, then confirm that they have the current password correct
        $hash_current=crypt($currentPwd,$userInfo[COL_SALT]);
        if(($hash_current!=$userInfo[COL_PWD]))
        {
            $errMsg=xl("Incorrect password");
            return false;            
        }
    }
    else {
        // If this is an administrator changing someone else's password, then check that they have the password right

        $adminSQL=" SELECT ".implode(",",array(COL_PWD,COL_SALT))
                  ." FROM ".TBL_USERS_SECURE
                  ." WHERE ".COL_ID."=?";
        $adminInfo=privQuery($adminSQL,array($activeUser));
        $hash_admin=crypt($currentPwd,$adminInfo[COL_SALT]);
        if($hash_admin!=$adminInfo[COL_PWD])
        {
            $errMsg=xl("Incorrect password!");
            return false;
        }
        if(!acl_check('admin', 'users'))
        {
            
            $errMsg=xl("Not authorized to change password!");
            return false;
        }
    }
    if(strlen($newPwd)==0)
    {
        $errMsg=xl("Empty Password Not Allowed");
        return false;
    }
    $require_strong=$GLOBALS['secure_password'] !=0;
    if($require_strong)
    {
        if(strlen($newPwd)<8)
        {
            $errMsg=xl("Password too short. Minimum 8 characters required.");
            return false;
        }
        $features=0;
        $reg_security=array("/[a-z]+/","/[A-Z]+/","/\d+/","/[\W_]+/");
        foreach($reg_security as $expr)
        {
            if(preg_match($expr,$newPwd))
            {
                $features++;
            }
        }
        if($features<3)
        {
            $errMsg=xl("Password does not meet minimum requirements and should contain at least three of the four following items: A number, a lowercase letter, an uppercase letter, a special character (Not a leter or number).");
            return false;
        }
    }

    $forbid_reuse=$GLOBALS['password_history'] != 0;
    if($forbid_reuse)
    {
        // password reuse disallowed
        $hash_current=crypt($newPwd,$userInfo[COL_SALT]);
        $hash_history1=crypt($newPwd,$userInfo[COL_SALT_H1]);
        $hash_history2=crypt($newPwd,$userInfo[COL_SALT_H2]);
        if(($hash_current==$userInfo[COL_PWD]) 
            ||($hash_history1==$userInfo[COL_PWD_H1]) 
            || ($hash_history2==$userInfo[COL_PWD_H2]))
        {
            $errMsg=xl("Reuse of three previous passwords not allowed!");
            return false;
        }
    }
    $newSalt = blowfish_salt();
    $newHash = crypt($newPwd,$newSalt);
    $updateParams=array();
    $updateSQL= "UPDATE ".TBL_USERS_SECURE;
    $updateSQL.=" SET ".COL_PWD."=?,".COL_SALT."=?"; array_push($updateParams,$newHash); array_push($updateParams,$newSalt);
    if($forbid_reuse){ 
        $updateSQL.=",".COL_PWD_H1."=?".",".COL_SALT_H1."=?"; array_push($updateParams,$userInfo[COL_PWD]); array_push($updateParams,$userInfo[COL_SALT]);
        $updateSQL.=",".COL_PWD_H2."=?".",".COL_SALT_H2."=?"; array_push($updateParams,$userInfo[COL_PWD_H1]); array_push($updateParams,$userInfo[COL_SALT_H1]);
        
        }
    $updateSQL.=" WHERE ".COL_ID."=?"; array_push($updateParams,$targetUser);
    privStatement($updateSQL,$updateParams);

    
    if($GLOBALS['password_expiration_days'] != 0){
            $exp_days=$GLOBALS['password_expiration_days'];
            $exp_date = date('Y-m-d', strtotime("+$exp_days days"));
            privStatement("update users set pwd_expiration_date=? where id=?",array($exp_date,$targetUser));
    }    
    return true;
}

?>
