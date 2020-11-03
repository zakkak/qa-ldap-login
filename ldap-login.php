<?php

class ldap_login {
  function load_module($directory, $urltoroot) {
    $this->directory=$directory;
    $this->urltoroot=$urltoroot;
  } // end function load_module

  function match_source($source) {
    return $source=='ldap';
  }

  /*
  REMY BLOM REMOVED ALL OTHER FUNCTIONS FROM THIS CLASS
  Their functionality is already provided by q2a-core...
  */

} // end class ldap_login

?>
