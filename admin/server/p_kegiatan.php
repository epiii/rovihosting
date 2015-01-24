<?php
	session_start();
	// error_reporting(0);
	include"../../lib/koneksi.php";
	include"../../lib/pagination_class.php";
	include "../../lib/tglindo.php"; 
	
 	$aksi 	=  isset($_GET['aksi'])?$_GET['aksi']:'';
	$menu	=  isset($_GET['menu'])?$_GET['menu']:'';
	$page 	=  isset($_GET['page'])?$_GET['page']:'';
	$cari	=  isset($_GET['cari'])?$_GET['cari']:'';
	$tabel	=  isset($_GET['tabel'])?$_GET['tabel']:'';	
	
	switch ($aksi){
		case 'combo':
			switch($menu){
				case 'subkatkeg':
					$sql	= 'SELECT * from subkatkeg where id_katkeg='.$_GET['id_katkeg'];
					$exe	= mysql_query($sql);
					$datax	= array();

					while($res=mysql_fetch_assoc($exe)){
						$datax[]=$res;
					}
					// print_r($datax);exit();
					if($datax!=NULL){
						echo json_encode($datax);
					}else{
						echo '{"status":"gagal"}';
					}
				break;
			}
		break;

		#ubah  ==============================================================================================
		case 'ubah':
			$sql = "UPDATE  kegiatan set 	nakeg		= '".mysql_real_escape_string($_POST['nakegTB'])."',
											id_subkatkeg= '".$_POST['id_subkatkegTB']."',
											poin		= '".mysql_real_escape_string($_POST['poinTB'])."',
											bukeg		= '".mysql_real_escape_string($_POST['bukegTB'])."',
											batut		= '".mysql_real_escape_string($_POST['batutTB'])."' 
								where idkeg =".$_POST['idkeg'];
			$exe	= mysql_query($sql);
			if($exe){
				echo '{"status":"sukses"}';
			}else{
				echo '{"status":"gagal"}';
			}
		break;

		#tambah  ==============================================================================================
		case 'tambah':
			$sql = "INSERT into kegiatan set 	nakeg		= '".mysql_real_escape_string($_POST['nakegTB'])."',
												id_subkatkeg= '".mysql_real_escape_string($_POST['id_subkatkegTB'])."',
												poin		= '".mysql_real_escape_string($_POST['poinTB'])."',
												bukeg		= '".mysql_real_escape_string($_POST['bukegTB'])."',
												batut		= '".mysql_real_escape_string($_POST['batutTB'])."'";
			#var_dump($sql);exit();
			$exe	= mysql_query($sql);
			if($exe){
				echo '{"status":"sukses"}';
			}else{
				echo '{"status":"gagal"}';
			}
		break;
		
		#cek  ==============================================================================================
		case 'cek':
			switch($menu){
				case 'nakeg':
					if(isset($_GET['nakeg1'])and !empty($_GET['nakeg1']) ){ //edit
						$sqlx = " and not(nakeg ='$_GET[nakeg1]')";
					}else{
						$sqlx='';
					}				
					// SELECT * 
					// FROM 
					//      kegiatan k 
					//      left join subkatkeg s on s.id_subkatkeg = k.id_subkatkeg 
					//      left join katkeg kt on kt.idkatkeg = s.id_katkeg
					// WHERE 
					//       kt.idkatkeg='.$menu.' 
					// ORDER BY 
					//       k.idkeg DESC

					$sql	= '	SELECT * 
								FROM 
								     kegiatan k 
								     left join subkatkeg s on s.id_subkatkeg = k.id_subkatkeg 
								     left join katkeg kt on kt.idkatkeg = s.id_katkeg
								WHERE 
									kt.idkatkeg ='.$_GET['idkatkeg'].' and 
									k.nakeg != "" and 
									k.nakeg = "'.$_GET['nakeg'].'" '.$sqlx;
					// var_dump($sql);exit();
					$exe	= mysql_query($sql);
					$jum 	= mysql_num_rows($exe);
					//var_dump($jum);exit();
					if($exe){
						if($jum>0){
							echo '{"status":"terpakai"}';
						}else{
							echo '{"status":"tersedia"}';
						}
					}else{
						echo '{"status":"error_kueri_kegiatan"}';	
					}
				break;
			}
		break;
		
		case 'ambiledit':
			$sql	= 	'SELECT * 
						FROM 
						     kegiatan k 
						     left join subkatkeg s on s.id_subkatkeg = k.id_subkatkeg 
						     left join katkeg kt on kt.idkatkeg = s.id_katkeg
						WHERE 
						      k.idkeg='.$_GET['idkeg'].' 
						ORDER BY 
					      k.idkeg DESC';
			$exe	= mysql_query($sql);
			$res	= mysql_fetch_assoc($exe);

			if($exe){
				echo '{
						"kondisi":"sukses",
						"idkatkeg":"'.$res['idkatkeg'].'",
						"id_subkatkeg":"'.$res['id_subkatkeg'].'",
						"katkeg":"'.$res['katkeg'].'",
						"nakeg":"'.$res['nakeg'].'",
						"poin":"'.$res['poin'].'",
						"bukeg":"'.$res['bukeg'].'",
						"batut":"'.$res['batut'].'"
					}';
				// "sks":"'.$res['sks'].'"
			}else{
				echo '{"status":"gagal"}';	
			}
		break;
		
		#hapus ==============================================================================================
		case 'hapus':
			$sql	= 'DELETE from kegiatan where idkeg ='.$_GET['idkeg'];
			$exe	= mysql_query($sql);
			
			//var_dump($jumz);exit();
			if($exe){
				echo '{"status":"sukses"}';
			}else{
				echo '{"status":"gagal"}';	
			}
		break;
			
		#tampil  =============================================================================================
		case 'tampil' :
			$sql = 'SELECT * 
					FROM 
					     kegiatan k 
					     left join subkatkeg s on s.id_subkatkeg = k.id_subkatkeg 
					     left join katkeg kt on kt.idkatkeg = s.id_katkeg
					WHERE 
					      kt.idkatkeg='.$menu.' 
					ORDER BY 
					      k.idkeg DESC';
			// print_r($sql);exit();
			if(isset($_GET['starting'])){ //nilai awal halaman
				$starting=$_GET['starting'];
			}else{
				$starting=0;
			}
			//var_dump($sql);exit();
			
			//record per halaman
			//var_dump($menu);exit();
			$recpage= 5;//jumlah data per halaman
			$obj 	= new pagination_class($menu,$sql,$starting,$recpage);
			$result =$obj->result;
			#end of paging	 
			
			#ada data
			$out='';
			if(mysql_num_rows($result)!=0)
			{
				$nox 	= $starting+1;
				//$tb	='<thead></thead><tbody>';
				while($res = mysql_fetch_array($result)){	
					$btn ='<td>
								<a class="btn" href="javascript:hapusKeg('.$res['idkeg'].','.$res['idkatkeg'].');" 
								 role="button"><i class="icon-remove"></i></a>
							 </td>
							 <td>
								 <a class="btn" href="javascript:editKeg('.$res['idkeg'].');" 
								 role="button"><i class="icon-pencil"></i></a>
							 </td>';
					$out.= '<tr>
							<td><label class="control-label">'.$nox.'</label></td>
							<td><label class="control-label">'.$res['nakeg'].'</label></td>
							<td><label class="control-label">'.$res['poin'].'</label></td>
							'.$btn.'
						</tr>';
                	$nox++;
				}
			}
			#kosong
			else
			{
				$out.='<tr align="center">
						<td  colspan="7" ><span style="color:red;text-align:center;">
						... data masih kosong...</span></td></tr>';
			}
			#link paging
			$out.= '<tr class="info"><td colspan="7">'.$obj->anchors.'</td></tr>';
			$out.= '<tr class="info"><td colspan="7">'.$obj->total.'</td></tr>';
			echo $out;
	break;
	
} ?>			
