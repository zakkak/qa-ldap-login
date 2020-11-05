<?php

class ldap_login {
  function load_module($directory, $urltoroot) {
    $this->directory=$directory;
    $this->urltoroot=$urltoroot;
  }
  function match_source($source) {
    return $source=='ldap';
  }
}

?>
