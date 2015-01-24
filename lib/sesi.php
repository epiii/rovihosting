<?php
	session_start();
    if(empty($_SESSION['iduser']) || !isset($_SESSION['iduser']))
		header('location:../../logout.php');	
    else
?>