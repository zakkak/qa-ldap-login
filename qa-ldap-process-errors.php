<?php
	if($error == 'password') {
		$errors['password']=qa_lang('users/password_wrong');
	} elseif($error == 'emailhandle') {
		$errors['emailhandle']=qa_lang('users/user_not_found');
	}
?>
