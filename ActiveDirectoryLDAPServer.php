<?php
/* This class represents behavior and properties
/* for a Active Directory server with LDAP interfacing enabled.
/* Tested against a Windows 2008R2 domain AD master.
 */

class ActiveDirectoryLDAPServer extends LDAPServer {
  private $dn;
  private $authenticatedUser;

  public function bindToLDAP($user,$pass) {
    $filter = "(".qa_opt('ldap_authentication_attribute')."=".$user.")";

    // Check if it authenticates the service account
    error_reporting(E_ALL^ E_WARNING);
    if(!qa_opt('ldap_login_ad_pwd')) {
        @$bind_service_account = ldap_bind($this->con);
    } else {
        @$bind_service_account = ldap_bind($this->con,qa_opt('ldap_login_ad_bind'), qa_opt('ldap_login_ad_pwd'));
    }

    if($bind_service_account) {
      $attributes = array('dn');
      $search = ldap_search($this->con, qa_opt('ldap_login_ad_basedn'), $filter, $attributes);
      $data = ldap_get_entries($this->con, $search);
    } else {
      return false;
    }

    // if the user is found, try to authenticate with his DN and password entered
    if (isset($data[0])) {
      $this->dn = $data[0]['dn'];
      @$bind_user = ldap_bind($this->con, $this->dn, $pass);
    } else {
      return false;
    }

    error_reporting(E_ALL);

    //we have to preserve the username entered if auth was succesfull
    if($bind_user) {
      $this->authenticatedUser=$user;
      return($bind_user);
    }

    return false;
  }

  public function getUserAttributes() {
    $fname_tag = qa_opt('ldap_login_fname');
    $sname_tag = qa_opt('ldap_login_sname');
    $mail_tag = qa_opt('ldap_login_mail');

    $filter = qa_opt('ldap_login_filter');
    $attributes = array('dn', $fname_tag, $sname_tag, $mail_tag);

    // The DN is known so just use it to read attributes
    $read = ldap_read($this->con, $this->dn, $filter, $attributes);
    $data = ldap_get_entries($this->con, $read);

    $fname = $data[0][strtolower($fname_tag)][0];
    $sname = $data[0][strtolower($sname_tag)][0];
    $mail  = $data[0][strtolower($mail_tag)][0];

    return array( $fname, $sname, $mail, $this->authenticatedUser);
  }
}

?>
