<?php
	/* This script grabs the user/pass combo directly
	 * from the Question2Answer login page and checks
	 * it against a LDAP authentication source. Following
	 * this check, it either creates a SESSION array or
	 * a cookie that can be checked by the ldap-login
	 * module's check_login function, and bypasses the
	 * internal QA auth mechanism by redirecting back to
	 * the login page. 
	*/

	function ldap_process ($user,$pass) 
	{	
		
		require_once QA_INCLUDE_DIR."../qa-plugin/qa-ldap-login/ldap-config.php";
		
		// Establish link with LDAP server
		$con =  ldap_connect($hostname,$port) or die ("Could not connect to ldap host.");
		if (!is_resource($con)) trigger_error("Unable to connect to $hostname",E_USER_WARNING);
		ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($con, LDAP_OPT_REFERRALS, 0);
		
		// Removing @email.com
		if(strstr($user, '@')){
			$parts = preg_split("/@/", $user);
			$user = $parts[0];
		}

		// Check if user/pass combo authenticates
		$bind = ldap_bind($con,$user . $account_suffix, $pass);
		
		if ($bind) {
			
		} else {
			return false;
		}

		// Connect to LDAP with read-only admin account
		$bind = ldap_bind($con,$username . $account_suffix, $password);

		if ($bind) {
			
			// Run query to determine user's name
			// Replace DOMAIN & com with ldap domain info
			$dn = "CN=Users,DC=DOMAIN,DC=com";
			$filter = "(&(objectClass=user)(sAMAccountName=".$uname."))";
			$attributes = array("displayname");

			$search = ldap_search($con, $dn, $filter, $attributes);
			$data = ldap_get_entries($con, $search);

			$explode = ldap_explode_dn($data[0]["dn"], 0);
			$name = explode(" ",str_replace("CN=","",$explode[0]));
			
			// Close LDAP link
			ldap_close($con);
			
			// Return user's name in array
			$name[2] = $user;
			return $name;
		}
	}

	function validateEmpty($attr){
		if($attr == '' || preg_match("/^[[:space:]]+$/", $attr)){

		}else{
			return true;
		}
	}
	
	$expire = 14*24*60*60;

	if (validateEmpty($inemailhandle)) {
		
		if (validateEmpty($inpassword)) {
		
			$name = ldap_process($inemailhandle,$inpassword);
		
			if ($name) {
				// Set name variables based on results from LDAP
				$fname = $name[0];
				$lname = $name[1];
				$email = $name[2];

				if($inremember == 'true') {
					setcookie("qa-login_lname", $lname, time() + $expire, '/');
					setcookie("qa-login_fname", $fname, time() + $expire, '/');
					setcookie("qa-login_email", $email, time() + $expire, '/');
				} else {
					$_SESSION["qa-login_lname"] = $lname;
					$_SESSION["qa-login_fname"] = $fname;
					$_SESSION["qa-login_email"] = $email;
				}
				header("Location: /login");
				exit();
			} else {
				$error = 'emailhandle';
			}
		
		} else {
			$error = 'password';
		}
	} else {
		$error = 'emailhandle';
	}
?>
