<?php

class LDAPServer {
  protected $con;
  protected $lastError;

  public function connectWithServer() {
    $ldap_host = qa_opt('ldap_login_hostname');
    // Establish link with LDAP server
    $this->con = ldap_connect($ldap_host, qa_opt('ldap_login_port')) or die ("Could not connect to $ldap_host host.");
    if (!is_resource($this->con)) trigger_error("Unable to connect to hostname", E_USER_WARNING);
    ldap_set_option($this->con, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($this->con, LDAP_OPT_REFERRALS, 0);
  }

  public function bindToLDAP($user,$pass) {}

  public function getUserAttributes() {}

  public function closeServerConnection() {
    $this->lastError = ldap_errno($this->con);
    ldap_close($this->con);
  }

  public function showErrors() {
    return $this->lastError;
  }
}

?>
