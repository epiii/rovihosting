<?php
	// error_reporting(0);
	require_once 'lib/koneksi.php';
	require_once 'lib/tglindo.php';
	
	$aksi		= isset($_POST['aksi'])?$_POST['aksi']:'';
	$kategori	= isset($_POST['kategori'])?$_POST['kategori']:'';
	
	// pr($_REQUEST);
	
	switch($aksi){
		//cek ============
		case 'combo':
			switch ($kategori) {
				case 'fak':
					$sql="select * from jur where idfak='$_POST[fak]'";
					#var_dump($sql);exit();
					$exe=mysqli_query($con,$sql);
					$data=array();
					while($res=mysqli_fetch_assoc($exe)){
						$data[]=$res;
					}	
					if($data!=NULL){
						echo json_encode($data);
					}else{
						echo '{"status":"gagal"}';
					}
				break;
				case 'jur':
					$sql="select * from prodi where idjur='$_POST[jur]'";
					#var_dump($sql);exit();
					$exe=mysqli_query($con,$sql);
					$data=array();
					while($res=mysqli_fetch_assoc($exe)){
						$data[]=$res;
					}	
					if($data!=NULL){
						echo json_encode($data);
					}else{
						echo '{"status":"gagal"}';
					}
				break;
				
			}
		break;
		
		case 'cek':
			switch($kategori){
				case 'username':
					$sql	= "select * from user where username = '$_POST[username]'";
					//var_dump($sql);exit();
					$exe	= mysqli_query($con,$sql);
					$jum	= mysqli_num_rows($exe);
					if($exe && $jum>0){
						echo '{"status":"ada"}';
					}else{
						echo '{"status":"kosong"}';
					}
				break;
				case 'nip':
					$sql	= "select * from dsn where nip = '$_POST[nip]'";
					//var_dump($sql);exit();
					$exe	= mysqli_query($con,$sql);
					$jum	= mysqli_num_rows($exe);
					if($exe && $jum>0){
						echo '{"status":"ada"}';
					}else{
						echo '{"status":"kosong"}';
					}
				break;
				case 'karpeg':
					$sql	= "select * from dsn where karpeg = '$_POST[karpeg]'";
					//var_dump($sql);exit();
					$exe	= mysqli_query($con,$sql);
					$jum	= mysqli_num_rows($exe);
					if($exe && $jum>0){
						echo '{"status":"ada"}';
					}else{
						echo '{"status":"kosong"}';
					}
				break;
			}
		break;
		
		//tambah ============
		case 'tambah':
		
				$sql1	= "insert into user set username	= '".trim(mysqli_real_escape_string($con,$_POST['_un']))."',
												password	= '".md5($_POST['_ps'])."',
												level		= 'user'";
				// var_dump($sql1);exit();
				$exe1	= mysqli_query($con,$sql1);
				#var_dump($exe1);exit();
				$id1	= mysqli_insert_id($con);
				
				$tgl	= tgl_indo3($_POST['_tgll']);
				$sql2	= "insert into dsn set  iduser 	='$id1',
												gelard 	='".trim(mysqli_real_escape_string($con,$_POST['_gd']))."',
												namad 	='".trim(mysqli_real_escape_string($con,$_POST['_nd']))."',
												namab 	='".trim(mysqli_real_escape_string($con,$_POST['_nb']))."',
												gelarb 	='".trim(mysqli_real_escape_string($con,$_POST['_gb']))."',
												idprodi = ".$_POST['_prodi'].",
												nip 	=".mysqli_real_escape_string($con,$_POST['_nip']).",
												karpeg 	=UPPER('".trim($_POST['_kg'])."'),
												tl 		=LOWER('".$_POST['_tl']."'),
												tgll 	='".trim(mysqli_real_escape_string($con,$tgl))."',
												jk 		='".$_POST['_jk']."',
												agama	='".$_POST['_agm']."'";
				// var_dump($sql2);exit();
				$exe2	= mysqli_query($con,$sql2);
				#var_dump($exe2);exit();
				$id2	= mysqli_insert_id($con);
			
			$sql3	= "insert into histjab set 	iddsn	= '".$id2."',
												id_pt	= '".$_POST['_pt']."',
												tglgols	= '".trim(mysqli_real_escape_string($con,tgl_indo3($_POST['_tglgol'])))."',
												tgljabs	= '".trim(mysqli_real_escape_string($con,tgl_indo3($_POST['_tgljab'])))."',
												id_gol	= '".$_POST['_gol']."',
												id_jab	= '".$_POST['_jab']."',
												status	= 1 ";
			#var_dump($sql3);exit();
			$exe3	= mysqli_query($con,$sql3);
			#var_dump($exe3);exit();
			//end of simpan histjob
			// var_dump($sql3);exit();
			//end of simpan dsn
			if($exe1 and $exe2 and $exe3){
				echo '{"status":"sukses"}';
			}else{
				echo '{"status":"gagal"}';
			}
		break;
	}
