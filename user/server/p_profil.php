<?php
	session_start();
	require_once '../../lib/koneksi.php';
	require_once '../../lib/tglindo.php'; 

	$aksi	= isset($_GET['aksi'])?$_GET['aksi']:'';
	$menu	= isset($_GET['menu'])?$_GET['menu']:'';

	switch ($aksi){
#hapus akun user (dosen) secara keseluruhan secara paralel otomatis (user + dsn + histjab + dtk + bukeg) =====================
		case 'hapusAkun':
			$sql = 'DELETE from user where iduser ='.$_SESSION['iduser'];
			$exe = mysql_query($sql);
			if($exe){
				echo '{"status":"berhasil_hapus"}';
			}
		break;
#hapus akun user (dosen)=======================================================================================================

#combo ========================================================================================================================
		case 'combo':
			switch($menu){
		#pendidikan terakhir---------------------------------------------
				case 'pt':
					$sql 	= "	SELECT * from pt  order by pt  asc  ";
					$kue	= mysql_query($sql);
					$ambil  = array();
					
					while($ambilR	= mysql_fetch_array($kue)){
						$ambil[]=$ambilR;
					}
					
					if($ambil!=NULL){
						echo json_encode($ambil);
					}else{
						echo '{"status":"gagal"}';
					}
				break;	
		#end of pendidikan terakhir----------------------------------------

		#golongan ---------------------------------------------------------
				case 'gol':
					$sql 	= "SELECT * from gol order by gol asc  ";
					$kue	= mysql_query($sql);
					$ambil  = array();
					
					while($ambilR	= mysql_fetch_array($kue)){
						$ambil[]=$ambilR;
					}
					
					if($ambil!=NULL){
						echo json_encode($ambil);
					}else{
						echo '{"status":"gagal"}';
					}
				break;	
		#end of golongan ---------------------------------------------------------

		#jabatan -----------------------------------------------------------------
				case 'jab':
					$sql 	= "	select * from jab order by jab asc  ";
					$kue	= mysql_query($sql);
					$ambil  = array();
					
					while($ambilR	= mysql_fetch_array($kue)){
						$ambil[]=$ambilR;
					}
					
					if($ambil!=NULL){
						echo json_encode($ambil);
					}else{
						echo '{"status":"gagal"}';
					}
				break;	
		#end of jabatan ---------------------------------------------------------
			}
		break;

# edit/ubah =======================================================================================================
		case 'ubah':
			# tabel dsn -------------------
			$sql	= 'UPDATE dsn set  	gelard	= "'.trim(mysql_real_escape_string($_POST['gelardTB'])).'",
										gelarb	= "'.trim(mysql_real_escape_string($_POST['gelarbTB'])).'",
										namad	= LOWER("'.trim(mysql_real_escape_string($_POST['namadTB'])).'"),
										namab	= LOWER("'.trim(mysql_real_escape_string($_POST['namabTB'])).'"),
										nip		= '.mysql_real_escape_string($_POST['nipTB']).',
										karpeg	= UPPER("'.trim(mysql_real_escape_string($_POST['karpegTB'])).'"),
										agama	= "'.$_POST['agamaTB'].'",
										jk		= "'.$_POST['jkTB'].'",
										tl		= "'.$_POST['tlTB'].'",
										tgll	= "'.tgl_indo3(trim(mysql_real_escape_string($_POST['tgllTB']))).'"
								WHERE	iduser	= '.$_SESSION['iduser'];						
			# end of  tabel dsn ------------
			// print_r($sql);exit();
			# tabel user -------------------
				if(isset($_POST['passBTB2']) && !empty($_POST['passBTB2'])){
					$pass =',password="'.md5(trim(mysql_real_escape_string($_POST['passBTB2']))).'"';
				}else{
					$pass='';
				}
				$sql2 	= 'UPDATE user set username = "'.trim(mysql_real_escape_string($_POST['usernameTB'])).'"  '.$pass.' WHERE iduser='.$_SESSION['iduser'];
			# end of tabel user -------------------
			
			# tabel histjab -----------------------
				$sqlj = 'SELECT
							h.idhistjab
						FROM
							histjab h,
							dsn d
						WHERE
							d.iddsn = h.iddsn AND 
							d.iduser = '.$_SESSION['iduser'].' AND 
							h.STATUS = 1';
				$exej	= mysql_query($sqlj);
				$resj	= mysql_fetch_assoc($exej);
				$sql3	= 'UPDATE histjab set 	id_pt		= '.$_POST['ptTB'].',
												id_gol	 	= '.$_POST['golsTB'].',
												id_jab		= '.$_POST['jabsTB'].',
												tgljabs  	= "'.tgl_indo3(trim(mysql_real_escape_string($_POST['TMTjabsTB']))).'", 
												tglgols		= "'.tgl_indo3(trim(mysql_real_escape_string($_POST['TMTgolsTB']))).'"
										where  	idhistjab	='.$resj['idhistjab'];
			# end of tabel histjab ---------------
			// print_r($sql3);exit();

			# eksekusi kueri ---------------------
				$exe	= mysql_query($sql);
				$exe2	= mysql_query($sql2);
				$exe3	= mysql_query($sql3);
				if($exe && $exe2 && $exe3){
					echo '{"status":"sukses"}';
				}else{
					echo '{"status":"gagal"}';
				}
			# end of eksekusi kueri---------------
		break;
#end of ubah ================================================================================
		
#view (login + biodata + jabatan)  ==========================================================
		case 'tampil' :
			$sql = 'SELECT
						u.username,u.level,
						d.iddsn,d.gelard,d.gelarb,d.namad,d.namab,d.agama,d.jk,d.tl,d.tgll,
						d.nip,d.karpeg,pt.id_pt,pt.pt,j.id_jab,j.jab,g.id_gol,g.gol,h.tgljabs,h.tglgols,h.tgltmp,g.gol,j.jab,
						YEAR (CURDATE()) - YEAR (h.tglgols) AS masagols,
						YEAR (CURDATE()) - YEAR (h.tgljabs) AS masajabs,
						(YEAR(CURDATE()) - YEAR(tgll)) AS umur

					FROM
						dsn d
					JOIN USER u ON u.iduser = d.iduser
					JOIN prodi p ON p.idprodi = d.idprodi
					JOIN jur jr ON jr.idjur = p.idjur
					JOIN fak f ON f.idfak = jr.idfak
					JOIN histjab h ON h.iddsn = d.iddsn
					JOIN pt ON pt.id_pt = h.id_pt
					JOIN gol g ON g.id_gol = h.id_gol
					JOIN jab j ON j.id_jab = g.id_jab

					WHERE
						h.`status` = 1
					AND d.iduser ='.$_SESSION['iduser'];
			// print_r($sql);exit();
			$exe	= mysql_query($sql);
			$res 	= mysql_fetch_array($exe);	
			if($exe){
				// if($res['gtot']==''){
				// 	$gtot= 0;
				// }else{
				// 	$gtot = $res['gtot'];
				// }
				
				echo'{
					"status":"ada",
					"username":"'.$res['username'].'",

					"nip":"'.$res['nip'].'",
					"gelard":"'.$res['gelard'].'",
					"gelarb":"'.$res['gelarb'].'",
					"namad":"'.$res['namad'].'",
					"namab":"'.$res['namab'].'",
					"pt":"'.$res['pt'].'",
					"karpeg":"'.$res['karpeg'].'",
					"agama":"'.$res['agama'].'",
					"jk":"'.$res['jk'].'",
					"tl":"'.$res['tl'].'",
					"tgll2":"'.tgl_indo4($res['tgll']).'",
					"tgll":"'.tgl_indo($res['tgll']).'",
					"umur":"'.$res['umur'].'",
					
					"id_pt":"'.$res['id_pt'].'",
					"id_jabs":"'.$res['id_jab'].'",
					"id_gols":"'.$res['id_gol'].'",
					"jabs":"'.$res['jab'].'",
					"gols":"'.$res['gol'].'",
					"tgljabs":"'.tgl_indo($res['tgljabs']).'",
					"tglgols":"'.tgl_indo($res['tglgols']).'",
					"tgljabs2":"'.tgl_indo4($res['tgljabs']).'",
					"tglgols2":"'.tgl_indo4($res['tglgols']).'",
					"masajabs":"'.$res['masajabs'].'",
					"masagols":"'.$res['masagols'].'"
				}';
			}else{
				echo '{"status":"kosong"}';	
			}
		break;
#end of tampil  =====================================================================================
	}
?>