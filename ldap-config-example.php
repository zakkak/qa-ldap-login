<?php

global $ldap_search_strings,$base_dn, $ldap_userfield, $ldap_hostname, $ldap_port, $ldap_filter, $ldap_fname,$ldap_sname,$ldap_mail, $ldap_service_account_bind, $ldap_service_account_pwd;

// Important!! set type of LDAP server you want to use
class LDAPServerType
{
	const ActiveDirectory = false;
	const GenericLDAP = true;
}


// use ldap:// for non ssl encrypted servers
// use ldaps:// for ssl encrypted servers
$ldap_hostname="ldap://localhost";
// use 389 for non ssl encrypted servers
// use 636 for ssl encrypted servers
$ldap_port="389";
$ldap_filter = '(objectClass=*)';
$ldap_fname = 'givenname';
$ldap_sname = 'sn';
$ldap_mail = 'mail';
$ldap_allow_normal_login = false;

// use this for Generic LDAP, Active Directory LDAP doesn't need it
// Can support more than one search strings, iterateds through all of them and returns if the bind succeeds
// Don't replace the USERNAME in uid, it is automatically replaced by the plugin at login with the user's username
$ldap_search_strings = array('uid=USERNAME,OU=people,DC=company,DC=local', 'uid=USERNAME,OU=people3,DC=company,DC=local');

// Below are specific Active Directory LDAP variables,don't bother if you use GenericLDAP
$ldap_service_account_bind = "CN=serviceaccount,CN=Managed Service Accounts,DC=contoso,DC=local"; 
$ldap_service_account_pwd = "12345678";
// Usually default user OU in the Active Directory, otherwise top of the tree
$base_dn = "OU=Users,DC=contoso,DC=local";

?>
