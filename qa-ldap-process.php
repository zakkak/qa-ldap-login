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

  require_once QA_INCLUDE_DIR."qa-base.php";

	function ldap_process ($user,$pass)
	{

		require_once QA_INCLUDE_DIR."../qa-plugin/qa-ldap-login/ldap-config.php";

		// Establish link with LDAP server
		$con =  ldap_connect($ldap_hostname,$ldap_port) or die ("Could not connect to ldap host.");
		if (!is_resource($con)) trigger_error("Unable to connect to $hostname",E_USER_WARNING);
		ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($con, LDAP_OPT_REFERRALS, 0);

    // Check ig user or pass is empty
    if ( '' == $user || '' ==  $pass ) {
      return false;
    }

    foreach ($ldap_search_strings as &$search_post) {
      // check whether the search string contains USERNAME
      if ( strpos($search_post, 'USERNAME') !== false ) {

        $dn = str_replace("USERNAME", $user, $search_post);
        // Check if it authenticates
        $bind = ldap_bind($con,$dn, $pass);

        if ($bind) {

          // Run query to determine user's name
          $filter = "(|(objectClass=user)(objectClass=person))";
          $attributes = array("givenname", "surname", "mail");

          $search = ldap_search($con, $dn, $filter, $attributes);
          $data = ldap_get_entries($con, $search);

          $fname = $data[0]["givenname"][0];
          $sname = $data[0]["surname"][0];
          $mail  = $data[0]["mail"][0];

          // Close LDAP link
          ldap_close($con);

          return array( $fname, $sname, $mail);
        }
      }
    }

    return false;
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
				qa_redirect('login');
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
