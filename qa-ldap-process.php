<?php
  /* This script grabs the user/pass combo directly
   * from the Question2Answer login page.
   * It uses a service account to find
   * the user in the ldap database. 
   * When found the user/pass combo is checked against the 
   * LDAP authentication source. Following
   * this check, it either creates a SESSION array or
   * a cookie that can be checked by the ldap-login
   * module's check_login function, and bypasses the
   * internal QA auth mechanism by redirecting back to
   * the login page.
  */

  require_once QA_INCLUDE_DIR."qa-base.php";
  require  QA_INCLUDE_DIR."../qa-plugin/qa-ldap-login/ldap-config.php";
  require_once QA_INCLUDE_DIR."../qa-plugin/qa-ldap-login/LDAPServer.php";
  require_once QA_INCLUDE_DIR."../qa-plugin/qa-ldap-login/ActiveDirectoryLDAPServer.php";
  require_once QA_INCLUDE_DIR."../qa-plugin/qa-ldap-login/GenericLDAPServer.php";

  global $ldapserver;

  function ldap_process ($user,$pass)
  {
    global $ldapserver;

    // Check ig user or pass is empty
    if ( '' == $user || '' ==  $pass ) {
      return false;
    }

    if (LDAPServerType::ActiveDirectory) 
    {
    	$ldapserver = new ActiveDirectoryLDAPServer();
    }
    else 
    {
      $ldapserver = new GenericLDAPServer();
    }

    $ldapserver->connectWithServer();

    if ($ldapserver->bindToLDAP($user,$pass)) {
      $data = $ldapserver->getUserAttributes();
      return $data;
    }

    $ldapserver->closeServerConnection();
    return false;
  }

  function validateEmpty($attr){
    if($attr == '' || preg_match("/^[[:space:]]+$/", $attr)){

    }else{
      return true;
    }
  }

  $expire = 14*24*60*60;

  if (validateEmpty($inemailhandle)) {

    if (validateEmpty($inpassword)) {

      $name = ldap_process($inemailhandle,$inpassword);
      if ($name) {
        // Set name variables based on results from LDAP
        $fname = $name[0];
        $lname = $name[1];
        $email = $name[2];
        $user = $name[3];
        
        if($inremember == 'true') {
          setcookie("qa-login_lname", $lname, time() + $expire, '/');
          setcookie("qa-login_fname", $fname, time() + $expire, '/');
          setcookie("qa-login_email", $email, time() + $expire, '/');
          setcookie("qa-login_user", $user, time() + $expire, '/');
        } else {
          $_SESSION["qa-login_lname"] = $lname;
          $_SESSION["qa-login_fname"] = $fname;
          $_SESSION["qa-login_email"] = $email;
          $_SESSION["qa-login_user"] = $user;
        }
        qa_redirect('login');
        exit();
      } else {
        if($ldap_allow_normal_login) {
          $error = 'emailhandle';
        } else {
          qa_redirect('login');
          exit();
        }
      }

    } else {
      $error = 'password';
    }
  } else {
    $error = 'emailhandle';
  }
?>
