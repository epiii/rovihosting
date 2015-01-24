<?php 
	session_start();
	error_reporting(0);
	if(empty($_SESSION['levelx'])){
		echo 'silahkan login dulu <a href="../">login</a>';
	}else{
		header('location:../');
	}
?>
