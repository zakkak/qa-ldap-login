<?php

  class qa_html_theme_layer extends qa_html_theme_base {
  
    function nav_list($navigation, $navtype, $level=null) {
      // remove the registration link unless normal login AND allow registration booleans are set in options
      if(!(qa_opt('ldap_login_allow_normal') && qa_opt('ldap_login_allow_registration'))) {
        unset($navigation['register']);
      }

      qa_html_theme_base::nav_list($navigation, $navtype, $level);
    } // end function nav_list
  }

?>
