<?php

/*
  Plugin Name: LDAP-Login
  Plugin Description: Allows ldap authentication within Q2A
  Plugin Version: 0.3
  Plugin Date: 2013-01-25
  Plugin Authors: Karl Bitz, Foivos Zakkak
  Plugin License: Free
  Plugin Minimum Question2Answer Version: 1.4
*/

error_reporting(E_ALL);

// don't allow this page to be requested directly from browser
if (!defined('QA_VERSION')) {
	header('Location: ../../');
	exit;
}

qa_register_plugin_module('login','ldap-login.php','ldap_login','LDAP Login');
qa_register_plugin_layer('ldap-login-layer.php','LDAP Login Layer');
qa_register_plugin_module('page','ldap-login-logout-page.php','ldap_logout_process','LDAP Logout Process');
qa_register_plugin_module('module', 'ldap-login-admin-form.php', 'ldap_login_admin_form', 'LDAP Login');

/*
  Omit PHP closing tag to help avoid accidental output
*/
