<?php
	#start of : cek username/password 
	if(!isset($_POST['username']) or !isset($_POST['password'])){
		echo '<script>window.location=\'./\'</script>';
	}else{
		require_once 'lib/koneksi.php';
		function anti_injection($data){
			$filter = mysqli_real_escape_string($con,stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
			return $filter;
		}

		$username = anti_injection($_POST['username']);
		$pass     = anti_injection(md5($_POST['password']));

		$sql = "SELECT * FROM user WHERE username='$username' AND password='$pass' ";
		$login	= mysqli_query($con,$sql);
		$ketemu	= mysqli_num_rows($login);
		$r		= mysqli_fetch_array($login);

		$sql2 = "SELECT * FROM dsn WHERE iduser='$r[iduser]'";
		$exe2 = mysqli_query($con,$sql2);
		$r2   = mysqli_fetch_array($exe2);
		
		// Apabila username dan password ditemukan
		if ($ketemu > 0){
			session_start();
			
			$_SESSION['iduser']   	= $r['iduser'];
			$_SESSION['usernamex']  = $r['username'];
			$_SESSION['levelx']		= $r['level'];
			$_SESSION['passwordx']	= $r['password'];
			$_SESSION['namalengkap']= $r2['namad'].' '.$r2['namab'];
			$_SESSION['namad']		= $r2['namad'];
			$_SESSION['login'] 		= 1;
			
			$sid_lama = session_id();
			session_regenerate_id();
			$sid_baru = session_id();
			$_SESSION['idsesi']		= $sid_baru;
	
			if($_SESSION['levelx']=='user'){
				header("Location:user");
			}elseif($_SESSION['levelx']=='adminf' or $_SESSION['levelx']=='adminu'){
				header("Location:admin");
			}
		}else{
			echo "<script>alert('username / password salah ');window.location='./';</script>";
		}
	}#end  of : cek username/password 
