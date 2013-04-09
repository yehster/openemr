<?php
define("TBL_USERS_SECURE","users_secure");
define("TBL_USERS","users");

define("COL_PWD","password");
define("COL_UNM","username");
define("COL_ID","id");
define("COL_SALT","salt");
define("COL_LU","last_update");

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
function updatePassword($username,$userid,&$password)
{

    $salt=blowfish_salt();
    $hash=crypt($password,$salt);
    $passwordSQL= "INSERT INTO ".TBL_USERS_SECURE.
                  " (".implode(",",array(COL_ID,COL_UNM,COL_PWD,COL_SALT,COL_LU)).")".
                  " VALUES (?,?,?,?,NOW()) " .
                  " ON DUPLICATE KEY UPDATE ".COL_PWD."=VALUES(".COL_PWD."), ".COL_SALT."=VALUES(".COL_SALT."), ".COL_LU."=VALUES(".COL_LU.")";
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
/**
 * 
 * @param type $username
 * @param type $password    password is passed by reference so that it can be "cleared out"
 *                          as soon as we are done with it.
 * @param type $provider
 */
function validate_user_password($username,&$password,$provider)
{
    $ip=$_SERVER['REMOTE_ADDR'];
    
    $valid=false;
    $getUserSecureSQL= " SELECT " . implode(",",array(COL_ID,COL_PWD,COL_SALT))
                       ." FROM ".TBL_USERS_SECURE
                       ." WHERE ".COL_UNM."=?";
    $userSecure=privQuery($getUserSecureSQL,array($username));
    if(is_array($userSecure))
    {
        $phash=crypt($password,$userSecure[COL_SALT]);
        if($phash!=$userSecure[COL_PWD])
        {
            
            return false;
        }
        $valid=true;
    }
    else
    {  
        if((!isset($GLOBALS['password_compatibility'])||$GLOBALS['password_compatibility']))           // use old password scheme if allowed.
        {
            $getUserSQL="select id, password from users where username = ?";
            $userInfo = privQuery($getUserSQL,array($username));            
            $dbPasswordLen=strlen($userInfo['password']);
            if($dbPasswordLen==32)
            {
                $phash=md5($password);
                $valid=$phash==$userInfo['password'];
            }
            else if($dbPasswordLen==40)
            {
                $phash=sha1($password);
                $valid=$phash==$userInfo['password'];
            }
            if($valid)
            {
//TODO: Uncomment when ready
//                updatePassword($username,$userInfo['id'],$password);
//                purgeCompatabilityPassword($username,$userInfo['id']);
                $_SESSION['relogin'] = 1;
            }
            else
            {
                return false;
            }
    }
        
    }
    $getUserSQL="select id, authorized, see_auth".
                        ", cal_ui, active ".
                        " from users where username = ?";
    $userInfo = privQuery($getUserSQL,array($username));
    
    if ($userInfo['active'] != 1) {
        newEvent( 'login', $username, $provider, 0, "failure: $ip. user not active or not found in users table");
        $password='';
        return false;
    }  
    // Done with the cleartext password at this point!
    $password='';
    if($valid)
    {
        if ($authGroup = privQuery("select * from groups where user=? and name=?",array($username,$provider)))
        {
            $_SESSION['authUser'] = $username;
            $_SESSION['authGroup'] = $authGroup['name'];
            $_SESSION['authUserID'] = $userInfo['id'];
            $_SESSION['authPass'] = $phash;
            $_SESSION['authProvider'] = $provider;
            $_SESSION['authId'] = $userInfo{'id'};
            $_SESSION['cal_ui'] = $userInfo['cal_ui'];
            $_SESSION['userauthorized'] = $userInfo['authorized'];
            // Some users may be able to authorize without being providers:
            if ($userInfo['see_auth'] > '2') $_SESSION['userauthorized'] = '1';
            newEvent( 'login', $username, $provider, 1, "success: $ip");
            $valid=true;
        } else {
            newEvent( 'login', $username, $provider, 0, "failure: $ip. user not in group: $provider");
            $valid=false;
        }
        
        
        
    }
    return $valid;
}

function verify_user_gacl_group($user)
{
    global $phpgacl_location;
    if (isset ($phpgacl_location)) {
      if (acl_get_group_titles($user) == 0) {
          newEvent( 'login', $user, $provider, 0, "failure: $ip. user not in any phpGACL groups. (bad username?)");
	  return false;
      }
    }
    return true;
}
?>
