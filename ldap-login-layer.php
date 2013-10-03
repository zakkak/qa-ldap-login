<?php

class qa_html_theme_layer extends qa_html_theme_base {

	function nav_list($navigation, $navtype, $level=null) {
		unset($navigation['register']);

		qa_html_theme_base::nav_list($navigation, $navtype);
	} // end function nav_list

}

?>
