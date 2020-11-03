<?php

/*
/* This class represents administator settings
/* for the LDAP plugin.
*/

class ldap_login_admin_form {
  function option_default($option)   {
    if ($option=='ldap_login_hostname')
      return 'ldap://localhost';
    if ($option=='ldap_login_port')
      return 389;

    if ($option=='ldap_login_filter')
      return '(objectClass=*)';
    if ($option=='ldap_login_fname')
      return 'givenname';
    if ($option=='ldap_login_sname')
      return 'sn';
    if ($option=='ldap_login_mail')
      return 'mail';

    if ($option=='ldap_login_ad')
      return true;
    if ($option=='ldap_login_ad_bind')
      return 'CN=serviceaccount,CN=Managed Service Accounts,DC=contoso,DC=local';
    if ($option=='ldap_login_ad_pwd')
      return '12345678';
    if ($option=='ldap_login_ad_basedn')
      return 'OU=Users,DC=contoso,DC=local';

    if ($option=='ldap_login_generic_search')
      return 'uid=USERNAME,OU=people,DC=company,DC=local/uid=USERNAME,OU=people3,DC=company,DC=local';

    if ($option=='ldap_authentication_attribute')
      return 'sAMAccountName'; // The legacy logon name in a Windows AD environment

    if ($option=='ldap_login_allow_normal')
      return true;
    if ($option=='ldap_login_allow_registration')
      return false;
    return null;
  }

  function admin_form(&$qa_content) {
    $saved=false;

    if (qa_clicked('ldap_login_save_button')) {
      qa_opt('ldap_login_hostname', qa_post_text('ldap_login_hostname_field'));
      qa_opt('ldap_login_port', (int) qa_post_text('ldap_login_port_field'));

      qa_opt('ldap_login_filter', qa_post_text('ldap_login_filter_field'));
      qa_opt('ldap_login_fname', qa_post_text('ldap_login_fname_field'));
      qa_opt('ldap_login_sname', qa_post_text('ldap_login_sname_field'));
      qa_opt('ldap_login_mail', qa_post_text('ldap_login_mail_field'));

      qa_opt('ldap_login_ad', (bool) qa_post_text('ldap_login_ad_field'));
      qa_opt('ldap_login_ad_bind', qa_post_text('ldap_login_ad_bind_field'));
      qa_opt('ldap_login_ad_pwd', qa_post_text('ldap_login_ad_pwd_field'));
      qa_opt('ldap_login_ad_basedn', qa_post_text('ldap_login_ad_basedn_field'));
      qa_opt('ldap_login_generic_search', qa_post_text('ldap_login_generic_search_field'));

      qa_opt('ldap_authentication_attribute', qa_post_text('ldap_authentication_attribute_field'));

      qa_opt('ldap_login_allow_normal', (bool) qa_post_text('ldap_login_allow_normal_field'));
      qa_opt('ldap_login_allow_registration', (bool) qa_post_text('ldap_login_allow_registration_field'));

      $saved=true;
    }

    qa_set_display_rules($qa_content, array(
      'ldap_login_allow_registration_display' => 'ldap_login_allow_normal_field',
      'ldap_login_ad_bind_display' => 'ldap_login_ad_field',
      'ldap_login_ad_pwd_display' => 'ldap_login_ad_field',
      'ldap_login_ad_basedn_display' => 'ldap_login_ad_field',
      'ldap_login_generic_search_display' => '!ldap_login_ad_field',
    ));

    return array(
      'ok' => $saved ? 'LDAP settings saved' : null,

      'fields' => array(
        array(
          'label' => 'Hostname for LDAP Server (ldap://x.y.z for non-SSL, ldaps://x.y.z for SSL)',
          'type' => 'text',
          'value' => qa_opt('ldap_login_hostname'),
          'tags' => 'name="ldap_login_hostname_field"',
        ),

        array(
          'label' => 'Port for LDAP Server (389 for non-SSL, 636 for SSL)',
          'type' => 'number',
          'value' => qa_opt('ldap_login_port'),
          'tags' => 'name="ldap_login_port_field"',
        ),

        array(
          'label' => 'LDAP filter',
          'type' => 'text',
          'value' => qa_opt('ldap_login_filter'),
          'tags' => 'name="ldap_login_filter_field"',
        ),

        array(
          'label' => 'LDAP Firstname field',
          'type' => 'text',
          'value' => qa_opt('ldap_login_fname'),
          'tags' => 'name="ldap_login_fname_field"',
        ),

        array(
          'label' => 'LDAP Surname field',
          'type' => 'text',
          'value' => qa_opt('ldap_login_sname'),
          'tags' => 'name="ldap_login_sname_field"',
        ),

        array(
          'label' => 'LDAP Email field',
          'type' => 'text',
          'value' => qa_opt('ldap_login_mail'),
          'tags' => 'name="ldap_login_mail_field"',
        ),

        array(
          'label' => 'Use Active Directory Server (unchecked: use generic LDAP)',
          'type' => 'checkbox',
          'value' => qa_opt('ldap_login_ad'),
          'tags' => 'name="ldap_login_ad_field" id="ldap_login_ad_field"',
        ),

        array(
          'id' => 'ldap_login_ad_bind_display',
          'label' => 'Binding account for AD',
          'type' => 'text',
          'value' => qa_opt('ldap_login_ad_bind'),
          'tags' => 'name="ldap_login_ad_bind_field"',
        ),

        array(
          'id' => 'ldap_login_ad_pwd_display',
          'label' => 'Password for AD binding account',
          'type' => 'text',
          'value' => qa_opt('ldap_login_ad_pwd'),
          'tags' => 'name="ldap_login_ad_pwd_field"',
        ),

        array(
          'id' => 'ldap_login_ad_basedn_display',
          'label' => 'Base DN for AD (Usually default user OU in the Active Directory, otherwise top of the tree)',
          'type' => 'text',
          'value' => qa_opt('ldap_login_ad_basedn'),
          'tags' => 'name="ldap_login_ad_basedn_field"',
        ),

        array(
          'id' => 'ldap_login_generic_search_display',
          'label' => 'Generic LDAP search strings (at login the plugin will replace USERNAME with the user\'s username. Separate each query string with \'/\')',
          'type' => 'text',
          'value' => qa_opt('ldap_login_generic_search'),
          'tags' => 'name="ldap_login_generic_search_field"',
        ),

        array(
          'id' => 'ldap_authentication_attribute_display',
          'label' => 'Generic LDAP search authenticate attribute (e.g. sAMAccountName, sn, mail, givenName etc.)',
          'type' => 'text',
          'value' => qa_opt('ldap_authentication_attribute'),
          'tags' => 'name="ldap_authentication_attribute_field"',
        ),


        array(
          'label' => 'Allow normal logins as a fallback to LDAP',
          'type' => 'checkbox',
          'value' => qa_opt('ldap_login_allow_normal'),
          'tags' => 'name="ldap_login_allow_normal_field" id="ldap_login_allow_normal_field"',
        ),

        array(
          'id' => 'ldap_login_allow_registration_display',
          'label' => 'Allow registration when normal logins are allowed',
          'type' => 'checkbox',
          'value' => qa_opt('ldap_login_allow_registration'),
          'tags' => 'name="ldap_login_allow_registration_field"',
        ),
      ),

      'buttons' => array(
        array(
          'label' => 'Save Changes',
          'tags' => 'name="ldap_login_save_button"',
        ),
      ),
    );
  }

}

/*
  Omit PHP closing tag to help avoid accidental output
*/
