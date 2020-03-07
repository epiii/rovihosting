<?php
	function filterx($data){
		$filter = trim(mysqli_real_escape_string($con,stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)))));
		// $filter = trim(mysqli_real_escape_string($con,stripslashes(strip_tags(htmlentities($data)))));
		// $filter = mysqli_real_escape_string($con,strip_tags($data));
		return $filter;
	}

?>