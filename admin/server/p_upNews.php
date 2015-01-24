<?php 
	session_start();
	// error_reporting(0);
	require_once '../../lib/koneksi.php';
	$upDir 	= '../../upload/down/';
	$data 	= array();
	
//proses upload file nya =======================================================
	if(isset($_GET['files'])){	
		$error = false;
		$files = array();
		
		#id unik ---------------------------------------------------------------
		//edit
		if(!empty($_GET['idnews'])){
			$idunik = $_GET['idnews'];
		}
		//add
		else{
			$sql 	= 'select max(idnews)as idnew from news';
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
			//$tp			= $file['type'];
			$nm			= $file['name'];
			$tipex 		= substr($nm, strrpos( $nm, '.' )+1 );
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
		
		#tipe EDIT ----------------------------------------------------------------
		if(isset($_POST['idformTB']) && !empty($_POST['idformTB']) )
		{
			#ada file 
			if(isset($_POST['fileadd']) and !empty($_POST['fileadd']))
			{
				$sqlf 	= "select file from news where idnews = '".$_POST['idformTB']."'";
				$exef	= mysql_query($sqlf);
				$resf	= mysql_fetch_assoc($exef);
				$nama	= $resf['file'];
				$linkf	= $upDir.$nama;
				
				#hapus file lama
				if(unlink($linkf)){
					$sqlf	= "update news set	tittle		= '".trim(mysql_real_escape_string($_POST['tittleTB']))."',
												deskripsi	= '".trim(mysql_real_escape_string($_POST['deskripsiTB']))."',
												kategori	= '$_POST[kategoriTB]',
												file		= '".$_POST['fileadd'][0]."',
												tglupdate	= NOW()
								where idnews = '$_POST[idformTB]'";
					$exef	= mysql_query($sqlf);
					#berhasil simpan
					if($exef){
						$data	= array(
							'success'=>'berhasil_simpan_update',
							'formData'=>$_POST	
						);
					}
					#gagal simpan 
					else{
						$data	= array(
							'error'=>'gagal_simpan_update',
							'formData'=>$_POST	
						);
					}
				}
			}
			
			# tidak ada file 
			else{ 
				$sqlf	= "update news set	tittle		= '".trim(mysql_real_escape_string($_POST['tittleTB']))."',
											deskripsi	= '".trim(mysql_real_escape_string($_POST['deskripsiTB']))."',
											kategori	= '$_POST[kategoriTB]',
											tglupdate	= NOW()
									where idnews = '$_POST[idformTB]'";
				$exef	= mysql_query($sqlf);
				#berhasil simpan data
				if($exef){
					$data	= array(
						'success'=>'berhasil_simpan_update_',
						'formData'=>$_POST	
					);
				}
				#gagal  simpan data
				else{
					$data	= array(
						'error'=>'gagal_simpan_update',
						'formData'=>$_POST	
					);
				}#end of gagal simpan data ----------------------------------------
			}#end of tidak ada file -----------------------------------------------
		}#end of tipe EDIT ------------------------------------------------------------
		
		#tipe ADD ---------------------------------------------------------------------
		else{
			
			$jum = count($_POST['fileadd']);
			for($i=0; $i<$jum; $i++)
			{
				$sql	= "insert into news set	tittle		= '$_POST[tittleTB]',
												deskripsi	= '$_POST[deskripsiTB]',
												kategori	= '$_POST[kategoriTB]',
												tglupdate	= NOW(),
												file 		= '".$_POST['fileadd'][$i]."'";
				//var_dump($sql);exit();
				$exe	= mysql_query($sql);
				if($exe){
					$data	= array(
						'success'=>'berhasil_new_data',
						'formData'=>$_POST	
					);
				}else{
					$data	= array(
						'error'=>'gagal_simpan_new_data',
						'formData'=>$_POST	
					);
				}
			}
		}
	}
//proses simpan => database ==========================================================
echo json_encode($data);
?>