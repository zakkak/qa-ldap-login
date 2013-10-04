## README

qa-ldap-login is an LDAP authentication mechanism for
Question2Answer. In it's current form, it is intended to
replace/augment the existing Q2A login form. The script will first
check user credentials against LDAP and can fall back to the internal
authentication if that fails. If a user exists in LDAP but not Q2A,
the script will create a new user account for the individual.

## PREREQS

In order for PHP's built-in LDAP functionality to work correctly, your
web server must be properly configured. In CentOS, this means openldap
must be installed, and any certificates necessary to authenticate with
LDAP specified in the openldap configuration.

## INSTALL

To install the plugin:

1. Add the qa-ldap-login directory with plugin files to the qa-plugin directory for your Q2A install.

2. Insert the following line of code above the if statement near line 59 of qa-include/qa-page-login.php

	require_once QA_INCLUDE_DIR.'../qa-plugin/qa-ldap-login/qa-ldap-process.php';

3. Change the options for the plugin in the administrator interface.

4. If your LDAP settings are configured correctly, that should be it!
