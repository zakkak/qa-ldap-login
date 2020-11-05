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
  require_once QA_INCLUDE_DIR."../qa-plugin/qa-ldap-login/LDAPServer.php";
  require_once QA_INCLUDE_DIR."../qa-plugin/qa-ldap-login/ActiveDirectoryLDAPServer.php";
  require_once QA_INCLUDE_DIR."../qa-plugin/qa-ldap-login/GenericLDAPServer.php";

  global $ldapserver;

  function ldap_process ($user,$pass) {
    global $ldapserver;

    // Check ig user or pass is empty
    if ( '' == $user || '' ==  $pass ) {
      return false;
    }

    if (qa_opt('ldap_login_ad')) {
    	$ldapserver = new ActiveDirectoryLDAPServer();
    } else {
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

  function isEmpty($attr) {
    if($attr == '' || preg_match("/^[[:space:]]+$/", $attr)) {
      return true;
    }
    return false;
  }

  $expire = 14*24*60*60;

  if (!isEmpty($inemailhandle)) {
    if (!isEmpty($inpassword)) {
      $name = ldap_process($inemailhandle,$inpassword);
      if ($name) {
        // Set name variables based on results from LDAP
        $fname = $name[0];
        $lname = $name[1];
        $email = $name[2];
        $user = $name[3];

        // Do not login or create account if mail value is NULL
        if ( '' == $email ){
          // FIXME somehow print a message
          qa_redirect('login');
          exit();
        }
        $source = 'ldap';
        $identifier = $email;

        $fields['email'] = $email;
        $fields['confirmed'] = true;
        $fields['handle'] = $user;
        $fields['name'] = $fname . " " . $lname;
        qa_log_in_external_user($source, $identifier, $fields, $inremember);

        $topath=qa_get('to');
        if (isset($topath))
          qa_redirect_raw(qa_path_to_root().$topath); // path already provided as URL fragment
        else
          qa_redirect('');
        exit();
      } else {
        if(!qa_opt('ldap_login_allow_normal')) {
          // FIXME somehow print a message
          qa_redirect('login');
          exit();
        }
      }
    }
  }
?>
