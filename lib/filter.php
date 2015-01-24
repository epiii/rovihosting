<?php
	function filterx($data){
		$filter = trim(mysql_real_escape_string(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)))));
		// $filter = trim(mysql_real_escape_string(stripslashes(strip_tags(htmlentities($data)))));
		// $filter = mysql_real_escape_string(strip_tags($data));
		return $filter;
	}

?>