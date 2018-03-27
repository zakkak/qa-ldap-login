<?php
/* This class represents behavior and properties
/* for a Active Directory server with LDAP interfacing enabled.
/* Tested against a Windows 2008R2 domain AD master.
 */

class ActiveDirectoryLDAPServer extends LDAPServer {
  private $dn;
  private $authenticatedUser;
  private $fname;
  private $sname;
  private $mail;

  public function bindToLDAP($user,$pass) {
    $filter = "(".qa_opt('ldap_authentication_attribute')."=".$user.")";

    // Check if it authenticates the service account
    error_reporting(E_ALL^ E_WARNING);
    
    $bind_user = ldap_bind($this->con, qa_opt('ldap_login_ad_bind_domain').'\\'.$user, $pass);
    if (!$bind_user) {
      return false;
    }
    
    $fname_tag = qa_opt('ldap_login_fname');
    $sname_tag = qa_opt('ldap_login_sname');
    $mail_tag = qa_opt('ldap_login_mail');
    
    $attributes = array('dn', $fname_tag, $sname_tag, $mail_tag);

    $search = ldap_search($this->con, qa_opt('ldap_login_ad_basedn'), $filter, $attributes);
    $data = ldap_get_entries($this->con, $search);

    if (!isset($data[0])) {
      return false;
    }
    
    $this->authenticatedUser = $user;
    $this->fname = $data[0][strtolower($fname_tag)][0];
    $this->sname = $data[0][strtolower($sname_tag)][0];
    $this->mail = $data[0][strtolower($mail_tag)][0];

    error_reporting(E_ALL);

    return true;
  }

  public function getUserAttributes() {
    return array( $this->fname, $this->sname, $this->mail, $this->authenticatedUser);
  }
}

?>
