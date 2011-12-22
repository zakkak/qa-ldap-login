<?php
/*
	Plugin Name: LDAP-Login
	Plugin Description: Allows ldap authentication within Q2A
	Plugin Version: 0.2
	Plugin Date: 2011-12-22
	Plugin Author: Karl Bitz
	Plugin License: Free
	Plugin Minimum Question2Answer Version: 1.4
*/

// don't allow this page to be requested directly from browser
error_reporting(E_ALL);
ini_set('display_errors', True);

if (!defined('QA_VERSION'))
{ 
	header('Location: ../../');
	exit;
}

qa_register_plugin_module('login','ldap-login.php','ldap_login','LDAP Login');
qa_register_plugin_layer('ldap-login-layer.php','LDAP Login Layer');
qa_register_plugin_module('page','ldap-login_logout-page.php','ldap_logout_process','LDAP Logout Process');

?>
