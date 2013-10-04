<?php
/* This class represents behavior and properties
/* for a generic LDAP server.
/* Tested against OpenLDAP.
*/

class GenericLDAPServer extends LDAPServer {

  private $dn;
  private $authenticatedUser;

  public function bindToLDAP($user,$pass)
  {
    $ldap_search_strings = explode('/', qa_opt('ldap_login_generic_search'));

    foreach ($ldap_search_strings as &$search_post)
    {
      // check whether the search string contains USERNAME
      if ( strpos($search_post, 'USERNAME') !== false )
      {
        $this->dn = str_replace("USERNAME", $user, $search_post);
        // Check if it authenticates
        error_reporting(E_ALL^ E_WARNING);
        $bind = ldap_bind($this->con,$this->dn, $pass);
        error_reporting(E_ALL);

        //we have to preserve the username entered if auth was succesfull
        if($bind)
        {
          $this->authenticatedUser = $user;
          return $bind;
        }
      }
    }
    return false;
  }

  public function getUserAttributes()
  {
    $fname_tag = qa_opt('ldap_login_fname');
    $sname_tag = qa_opt('ldap_login_sname');
    $mail_tag = qa_opt('ldap_login_mail');

    // Run query to determine user's name
    $filter = qa_opt('ldap_login_filter');
    $attributes = array($fname_tag, $sname_tag, $mail_tag);

    $search = ldap_search($this->con, $this->dn, $filter, $attributes);
    $data = ldap_get_entries($this->con, $search);

    $fname = $data[0][$fname_tag][0];
    $sname = $data[0][$sname_tag][0];
    $mail  = $data[0][$mail_tag][0];

    return array( $fname, $sname, $mail, $this->authenticatedUser);
  }
}

?>
