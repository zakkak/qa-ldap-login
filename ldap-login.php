<?php

class ldap_login {
  function load_module($directory, $urltoroot) {
    $this->directory=$directory;
    $this->urltoroot=$urltoroot;
  } // end function load_module

  // check_login checks to see if user is already logged in by looking for
  // a cookie or session variable (dependent on 'remember me' setting
  function check_login() {
    $valid_external_ip = qa_opt('ldap_authentication_valid_external_ip');
    if (isset($_SERVER["HTTP_HOST"]) && $_SERVER["HTTP_HOST"] == $valid_external_ip){
        //Chequeo solo sea un pedido del propio servidor y moodle quien llama
        if (isset($_POST["lti_message_type"]) && $_POST["lti_message_type"]=='basic-lti-launch-request'){
          //Si fue moodle que me mando un post en los parametros me vienen datos del usuario
          $_SESSION["qa-login_fname"] = $_POST["lis_person_name_given"];
          $_SESSION["qa-login_lname"] = $_POST["lis_person_name_family"];
          $_SESSION["qa-login_email"] = $_POST["lis_person_contact_email_primary"];
          $_SESSION["qa-login_user"]  = $_POST["lis_person_name_full"];
        }
    }

    if(!isset($_COOKIE["qa-login_fname"]) && !isset($_SESSION["qa-login_fname"])) {
      return false;
    } else {
      if(isset($_COOKIE["bdops-login_fname"])) {
        $fname = $_COOKIE["qa-login_fname"];
        $lname = $_COOKIE["qa-login_lname"];
        $email = $_COOKIE["qa-login_email"];
        $username = $_COOKIE["qa-login_user"];
      } else {
        $fname = $_SESSION["qa-login_fname"];
        $lname = $_SESSION["qa-login_lname"];
        $email = $_SESSION["qa-login_email"];
        $username = $_SESSION["qa-login_user"];
      }
      $source = 'ldap';
      $identifier = $email;

      $fields['email'] = $email;
      $fields['confirmed'] = true;
      $fields['handle'] = $username;
      $fields['name'] = $fname . " " . $lname;
      qa_log_in_external_user($source,$identifier,$fields);
    }
  } // end function check_login

  function match_source($source) {
    return $source=='ldap';
  }

  function login_html($tourl, $context) {} 

  function logout_html ($tourl) {
    require_once QA_INCLUDE_DIR."qa-base.php";

    $_SESSION['logout_url'] = $tourl;
    $logout_url = qa_path('auth/logout', null, qa_path_to_root());
    echo('<a href="'.$logout_url.'">'.qa_lang_html('main/nav_logout').'</a>');
  } // end function logout_html

} // end class ldap_login

?>
