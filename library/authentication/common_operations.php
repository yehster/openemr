<?php
define("TBL_USERS_SECURE","users_secure");
define("TBL_USERS","users");

define("COL_PWD","password");
define("COL_UNM","username");
define("COL_ID","id");
define("COL_SALT","salt");
define("COL_LU","last_update");
define("COL_PWD_H1","password_history1");
define("COL_SALT_H1","salt_history1");

define("COL_PWD_H2","password_history2");
define("COL_SALT_H2","salt_history2");

/**
 * mechanism to use "super user" for SQL queries related to password operations
 * 
 * @param type $sql
 * @param type $params
 * @return type
 */
function privStatement($sql,$params)
{
    return sqlStatement($sql,$params);
}

function privQuery($sql,$params)
{
    return sqlQuery($sql,$params); 
}

function blowfish_salt($rounds='05')
{
    $Allowed_Chars ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
    $Chars_Len = 63;

    $Salt_Length = 21;

    $salt = "";
    
    for($i=0; $i<$Salt_Length; $i++)
    {
        $salt .= $Allowed_Chars[mt_rand(0,$Chars_Len)];
    }    

    //This string tells crypt to apply blowfish $rounds times.
    $Blowfish_Pre = '$2a$'.$rounds.'$';
    $Blowfish_End = '$';
    
    return $Blowfish_Pre.$salt.$Blowfish_End;
}

/**
 * 
 * @param type $username
 * @param type $password  Passing by reference so additional copy is not created in memory
 */
function initializePassword($username,$userid,&$password)
{

    $salt=blowfish_salt();
    $hash=crypt($password,$salt);
    $passwordSQL= "INSERT INTO ".TBL_USERS_SECURE.
                  " (".implode(",",array(COL_ID,COL_UNM,COL_PWD,COL_SALT,COL_LU)).")".
                  " VALUES (?,?,?,?,NOW()) ";
                  
    $params=array(
                    $userid,
                    $username,
                    $hash,
                    $salt
    );
    privStatement($passwordSQL,$params); 
}


/**
 * 
 * @param type $username
 * @param type $userid
 */
function purgeCompatabilityPassword($username,$userid)
{
    $purgeSQL = " UPDATE " . TBL_USERS 
                ." SET ". COL_PWD . "='NoLongerUsed' "
                ." WHERE ".COL_UNM. "=? "
                ." AND ".COL_ID. "=?";
    privStatement($purgeSQL,array($username,$userid));
}
?>

