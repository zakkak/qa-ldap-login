<?php

/*
  Plugin Name: LDAP-Login
  Plugin Description: Allows ldap authentication within Q2A
  Plugin URI: https://github.com/zakkak/qa-ldap-login
  Plugin Update Check URI: https://github.com/zakkak/qa-ldap-login/raw/master/qa-plugin.php
  Plugin Version: 0.4
  Plugin Date: 2013-10-05
  Plugin Author: Karl Bitz, Foivos S. Zakkak, Peter Edwards
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
