<?php

class ldap_logout_process {
  var $directory;
  var $urltoroot;

  function load_module($directory, $urltoroot) {
    $this->directory=$directory;
    $this->urltoroot=$urltoroot;
  } // end function load_module

  function suggest_requests() {
    return array(
      array(
        'title' => 'Logout',
        'request' => 'auth/logout',
        'nav' => 'null',
      ),
    );
  } // end function suggest_requests

  function match_request($request) {
    if ($request=='auth/logout')
      return true;

    return false;
  } // end function match_request

  function process_request($request) {
    require_once QA_INCLUDE_DIR."qa-base.php";

    $expire = 14*24*60*60;
    if(isset($_SESSION['logout_url'])) {
      $tourl = $_SESSION['logout_url'];
    } else {
      $tourl = false;
    }
    session_destroy();
    if (!$tourl) {
      qa_redirect('logout');
    } else {
      header('Location: '.$tourl);
    }
    return null;
  } // end function process_request

}
?>
