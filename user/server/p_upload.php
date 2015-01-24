<?php 
	session_start();
	require_once '../../lib/koneksi.php';
	require_once '../../lib/filter.php';
	$upDir 	= '../../upload/bukeg/';
	$data 	= array();
	
//proses upload file nya =======================================================
	if(isset($_GET['files'])){	
		$error = false;
		$files = array();
		
		#id unik ---------------------------------------------------------------
		if(!empty($_GET['iddtk'])){
			$idunik = $_GET['iddtk'];
		}
		//add
		else{
			$sql 	= 'select max(iddtk)as idnew from dtk';
			$exe 	= mysql_query($sql)or die('eror sql cek id terakhir');
			$res 	= mysql_fetch_assoc($exe);
			$jumx 	= mysql_num_rows($exe);
			if($jumx=0){ // baru
				$idunik==1;
			}else{ 		// data lanjutan
				$idunik=$res['idnew']+1;
			}
		}#end of id unik---------------------------------------------------------

		#upload file (loop) -----------------------------------------------------
		foreach($_FILES as $file){
			$tipex		= substr($file['type'],6);
			$namaAwal 	= $file['name'];
			$namaSkrg	= $idunik.$_SESSION['iduser'].'_'.substr((md5($namaAwal.rand())),2,10).'.'.$tipex;
			$src		= $file['tmp_name'];
			$destix		= $upDir .basename($namaSkrg);
			
			#proses upload -------------------------
				//berhasil
				if(move_uploaded_file($src, $destix)){
					$files[] = $namaSkrg;
				}
				//gagal 
				else{ 
					$error = true;
				}
			#end of proses upload -------------------
		}#end of upload file (loop) -------------------------------------------------
	
		#pesan upload ---------------------------------------------------------------
			//gagal
			if($error){ 
				$data=array(
					'error' => 'gagal upload file'
					); 
			}
			//berhasil 
			else{
				$data=array(
					'files' => $files
					);	
			}
		#pesan upload -------------------------------------------------------------
	}
//end of upload file ==============================================================
	
//proses simpan => database =======================================================
	else{
		#sql utama ----------------------------------------------------------------
			$sqlU = 'dtk SET
						status		= "new",
						idkeg		= '.$_POST['id_kegTB'].',
						ket 		= "'.filterx($_POST['ketTB']).'"';
			
			#ada tempat n waktu (penjar,pengab,penunj)  
			if(isset($_POST['tempatTB']) AND isset($_POST['waktuTB'])){
				$sqlU.=',tempat ="'.filterx($_POST['tempatTB']).'"
						,waktu ="'.filterx($_POST['waktuTB']).'"';
			}else{// (penel)
				$sqlU.=',tempat =""
						,waktu ="" ';
			}

			#pengabdian  
			if(isset($_POST['jabatanTB']) AND !empty($_POST['jabatanTB'])){
				$sqlU.=',jabatan ="'.filterx($_POST['jabatanTB']).'"';
			}else{
				$sqlU.=',jabatan ="" ';
			}

			#grup
			if(isset($_POST['statusRB'])){
				if($_POST['statusRB']=='individu'){ //individu
					$sqlU.=', isGroup="n",jumAnggota=0,isLeader="n" ';
				}else{ // kelompok
					$sqlU.=', isGroup="y" ';
					#ketua
					if(isset($_POST['status2RB']) AND $_POST['status2RB']=='ketua'){
						$sqlU.= ', isLeader ="y",jumAnggota=0 '; 
					}
					#anggota
					else if(isset($_POST['status2RB']) AND $_POST['status2RB']=='anggota'){
						$sqlU.= ', isLeader ="n", jumAnggota='.$_POST['jumlahTB']; 
					}
				}	
			}
			#penjar : none/null, pengabdian, penunjang
			else{ 
				#kuliah (penjar=>perkuliahan)
				$sqlU.=',isGroup=NULL,  isLeader=NULL';
				if(isset($_POST['sksTB']) && !empty($_POST['sksTB'])){
					$sqlU.= ',sks = '.$_POST['sksTB'].' 
							,jumAnggota = '.$_POST['jumlahTB'];
				}else{
					$sqlU.=', jumAnggota=0 ';
				}
			}
		#end of sql utama  ---------------------------------------------------------------

		#iddsn --------------------------------------------------------------------
			$sqldsnx	= "	SELECT * from dsn d,histjab h
							where 
								h.iddsn	 = d.iddsn and
								d.iduser =".$_SESSION['iduser'];
			$exedsnx= mysql_query($sqldsnx);
			$ambil 	= mysql_fetch_assoc($exedsnx);
			$iddsn 	= $ambil['iddsn'];
		#end of iddsn -------------------------------------------------------------
		
		#tipe EDIT ----------------------------------------------------------------
		if(isset($_POST['idformTB']) && !empty($_POST['idformTB']) ){
			$sql	= 'UPDATE '.$sqlU.' WHERE iddtk = '.$_POST['idformTB'];

			// print_r($sql);exit();
			$exe	= mysql_query($sql);

			$sql2	= 'UPDATE dsn set baru=1 where iduser='.$_SESSION['iduser'];
			$exe2	= mysql_query($sql2);
			
			#query update dtk -----------------------------------------------------
			if($exe){
				#hapus file (0 -) -------------------------------------------------
				if(isset($_POST['filedel']) && !empty($_POST['filedel']) && !isset($_POST['fileadd'])){
					#eksekusi hapus file (loop) -----------------------------------
					for($i=0; $i<count($_POST['filedel']); $i++){
						$sqlf 	= "select file from bukeg where idbukeg = '".$_POST['filedel'][$i]."'";
						$exef	= mysql_query($sqlf);
						$resf	= mysql_fetch_assoc($exef);
						$nama	= $resf['file'];
						$linkf	= $upDir.$nama;
						
						unlink($linkf);
						$sqld 	= "delete from bukeg where file = '$nama'";
						$exed	= mysql_query($sqld);
						if($exed){
							$data	= array(
								'success'=>'berhasil_simpan_bukeg_edit_del_only',
								'formData'=>$_POST	
							);
						}else{
							$data	= array(
								'error'=>'gagal_simpan_bukeg_edit_del_only',
								'formData'=>$_POST	
							);
						}
					}#end of eksekusi hapus file (loop) ----------------------------
				}#end of hapus file ------------------------------------------------

				#tambah file (+ 0) -------------------------------------------------
				elseif(isset($_POST['fileadd']) && !empty($_POST['fileadd']) && !isset($_POST['filedel'])){
					#eksekusi hapus file (loop)-------------------------------------
					for($i=0; $i<count($_POST['fileadd']); $i++){
						$sql	="insert into bukeg set iddtk='$_POST[idformTB]',file='".$_POST['fileadd'][$i]."'";
						$exe	= mysql_query($sql);
						if($exe){
							$data	= array(
								'success'=>'berhasil_simpan_bukeg_edit_add_only',
								'formData'=>$_POST	
							);
						}else{
							$data	= array(
								'error'=>'gagal_simpan_bukeg_edit_add_only',
								'formData'=>$_POST	
							);
						}
					}#end of eksekusi hapus file (loop)-----------------------------
				}#end of tambah file -----------------------------------------------
				
				#tambah + hapus file (+ -) -----------------------------------------
				elseif(	isset($_POST['fileadd']) && !empty($_POST['fileadd']) && isset($_POST['filedel']) && !empty($_POST['filedel'])){
					$exe1 =false;
					$exe2 =false;
					#eksekusi tambah file (loop)------------------------------------
					for($i=0; $i<count($_POST['fileadd']); $i++){
						$sql	="insert into bukeg set iddtk='$_POST[idformTB]',file='".$_POST['fileadd'][$i]."'";
						//var_dump($sql);exit();
						$exe1	= mysql_query($sql);
					}#end of eksekusi tambah file (loop)----------------------------
					
					#eksekusi hapus file (loop) ------------------------------------
					for($i=0; $i<count($_POST['filedel']); $i++){
						$sqlf 	= "select file from bukeg where idbukeg = '".$_POST['filedel'][$i]."'";
						//var_dump($sqlf);exit();
						$exef	= mysql_query($sqlf);
						$resf	= mysql_fetch_assoc($exef);
						$nama	= $resf['file'];
						//var_dump($nama);exit();
						$linkf	= $upDir.$nama;
						//var_dump($linkf);exit();
						
						unlink($linkf);
						$sqld 	= "delete from bukeg where file = '$nama'";
						//var_dump($sqld);exit();
						$exe2	= mysql_query($sqld);
					}#end of eksekusi hapus file (loop) -----------------------------
					
					#pesan eksekusi query adddel-----------
						if($exe1 && $exe2){
							$data	= array(
								'success'=>'berhasil_simpan_bukeg_edit_adddel',
								'formData'=>$_POST	
							);
						}else{
							$data	= array(
								'error'=>'gagal_simpan_bukeg_edit_adddel',
								'formData'=>$_POST	
							);
						}
					#end of pesan eksekusi query adddel----
				}#end of tambah + hapus file ---------------------------------------
				
				#no(tambah+hapus)file (0 0) ----------------------------------------
				elseif(!isset($_POST['fileadd']) && !isset($_POST['filedel'])){
					$data	= array(
						'success'=>'berhasil_simpan_dtk_edit_nochange',
						'formData'=>$_POST	
					);
					//var_dump($data);exit();
				}#end of no(tambah+hapus)file ---------------------------------------
			}#end of query dtk ------------------------------------------------------
			
			#gagal query dtk --------------------------------------------------------
			else{
				$data	= array(
						'success'=>'gagal_edit_dtk',
						'formData'=>$_POST	
					);
			}#end of gagal query dtk --------------------------------------------------
		}#end of tipe EDIT ------------------------------------------------------------
		
		#tipe ADD ---------------------------------------------------------------------
		else{
			$sql	= 'INSERT into '.$sqlU.', idhistjab	= '.$ambil['idhistjab'];
			
			// print_r($sql);exit();
			$exe	= mysql_query($sql);
			$iddtk	= mysql_insert_id();

			#query insert dtk --------------------------------------------------------
			if($exe){
				$sql2	= 'UPDATE dsn set baru=1 where iduser='.$_SESSION['iduser'];
				$exe2	= mysql_query($sql2);

				#eksekusi tambah file (loop)------------------------------------------
				$jum = count($_POST['fileadd']);
				for($i=0; $i<$jum; $i++){
					$sql	= "insert into bukeg set iddtk = '$iddtk', file='".$_POST['fileadd'][$i]."'";
					$exe	= mysql_query($sql);
					if($exe){
						$data	= array(
							'success'=>'berhasil_simpan_bukeg_new',
							'formData'=>$_POST	
						);
					}else{
						$data	= array(
							'error'=>'gagal_simpan_bukeg_new',
							'formData'=>$_POST	
						);
					}
				}#end of eksekusi tambah file (loop)----------------------------------
			}#end of query insert dtk ------------------------------------------------
		}#end of tipe ADD ------------------------------------------------------------
	}
//proses simpan => database ==========================================================
echo json_encode($data);
?>