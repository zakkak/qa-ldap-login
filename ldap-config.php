<?php

// This account must have read acces to the AD
// Can also be in the form of the UPN, like TEST@TEST.local
$ldap_service_account_bind = "CN=TEST,CN=Service Accounts,DC=TEST,DC=local";
$ldap_service_account_pwd = "PASS";
//The search for the use will take place in the following context, change it to yours
$user_dn = "OU=SBSUsers,OU=Users,OU=MyBusiness,DC=TEST,DC=local";
$ldap_userfield = "sAMAccountName";
$ldap_hostname = 'ldap://192.168.1.1'; // use ldap:// for non ssl encrypted servers
$ldap_port = 389; // use 389 for non ssl encrypted servers
$ldap_filter = '|(objectClass=*)';
$ldap_fname = 'givenname';
$ldap_sname = 'sn';
$ldap_mail = 'mail';

?>
