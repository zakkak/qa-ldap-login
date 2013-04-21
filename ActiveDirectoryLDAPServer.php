<?php
/* This class represents behavior and properties 
/* for a Active Directory server with LDAP interfacing enabled.
/* Tested against a Windows 2008R2 domain AD master.
 */
 
class ActiveDirectoryLDAPServer extends LDAPServer 
{
	// This LDAP attribute represents the legacy logon name in a Windows AD environment
	private $authenticationAttribute = "sAMAccountName";
	private $dn;
	private $password;
	private $authenticatedUser;

	public function bindToLDAP($user,$pass)
	{
		global $ldap_service_account_bind, $ldap_service_account_pwd, $base_dn;

		$this->password = $pass;

		$filter = "(".$this->authenticationAttribute."=".$user.")";  

		// Check if it authenticates the service account
        error_reporting(E_ALL^ E_WARNING);
        @$bind_service_account = ldap_bind($this->con,$ldap_service_account_bind,$ldap_service_account_pwd);

		if($bind_service_account)
		{
			$attributes = array('dn');
			$search = ldap_search($this->con, $base_dn, $filter, $attributes);  
			$data = ldap_get_entries($this->con, $search);
		}
		else
		{
			return false;
		}
		
		// if the user is found, try to authenticate with his DN and password entered
		$this->dn = $data[0]['dn'];
		@$bind_user = ldap_bind($this->con, $this->dn, $pass);
	
        error_reporting(E_ALL);

		//we have to preserve the username entered if auth was succesfull
		if($bind_user)
		{
			$this->authenticatedUser=$user;
		}
	
		return($bind_user); 
	}

	public function getUserAttributes()
	{

	  global $ldap_fname, $ldap_sname, $ldap_mail, $base_dn, $ldap_filter;

      $attributes = array('dn',$ldap_fname, $ldap_sname, $ldap_mail);

	  // The DN is known so just use it to read attributes	
      $read = ldap_read($this->con, $this->dn, $ldap_filter, $attributes);
      $data = ldap_get_entries($this->con, $read);
      
      $fname = $data[0][$ldap_fname][0];
      $sname = $data[0][$ldap_sname][0];
      $mail  = $data[0][$ldap_mail][0];
          
      return array( $fname, $sname, $mail, $this->authenticatedUser);

	}

}



?>
