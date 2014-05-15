<?php

  class qa_html_theme_layer extends qa_html_theme_base {
  
    function nav_list($navigation, $navtype, $level=null) {
      // remove the registration link unless normal login AND allow registration booleans are set in options
      if(!(qa_opt('ldap_login_allow_normal') && qa_opt('ldap_login_allow_registration'))) {
        unset($navigation['register']);
      }

      qa_html_theme_base::nav_list($navigation, $navtype);
    } // end function nav_list


    function head_script() // add a Javascript file from plugin directory
    {   

        $script_personal = '<SCRIPT SRC="'.
            qa_html(QA_HTML_THEME_LAYER_URLTOROOT.'js/my-script.js').
            '" TYPE="text/javascript"></SCRIPT>';

        $this->content['script'][]=$script_personal;
            
        qa_html_theme_base::head_script();

        $this->output($script_personal);
    }

  }

?>
