<?php
	session_start();
	// error_reporting(0);
	include"../../lib/koneksi.php";
	include"../../lib/pagination_class.php";
	include "../../lib/tglindo.php"; 
	
 	$aksi 	=  isset($_GET['aksi'])?$_GET['aksi']:'';
	$page 	=  isset($_GET['page'])?$_GET['page']:'';
	$cari	=  isset($_GET['cari'])?$_GET['cari']:'';
	$tabel	=  isset($_GET['tabel'])?$_GET['tabel']:'';
	$menu	=  isset($_GET['menu'])?$_GET['menu']:'';
	$level	=  isset($_GET['level'])?$_GET['level']:'';
	
	switch ($aksi){
		#ambiledit==============================================================================================
		case 'ambiledit':
			$sql = 'SELECT * from user  where iduser='.$_GET['iduser'];
			$exe	= mysql_query($sql);
			$res	= mysql_fetch_assoc($exe);
			if($exe){
				echo '{
					"username":"'.$res['username'].'",
					"level":"'.$res['level'].'"
				}';
			}else{
				echo '{"status":"gagal"}';
			}
		break;
		
		#ubah  ==============================================================================================
		case 'ubah':
			if(isset($_POST['passwordTB']) and $_POST['passwordTB']!=''){
				$pass = ",password = '".md5(mysql_real_escape_string($_POST['passwordTB']))."'";
			}else{
				$pass="";
			}
			$sql = "update  user set 	username 	= '".mysql_real_escape_string($_POST['usernameTB'])."',
										level		= '".mysql_real_escape_string($_POST['levelTB'])."'
										$pass
							where iduser=".$_GET['iduser'];
			//var_dump($sql);exit();
			$exe	= mysql_query($sql);
			if($exe){
				echo '{"status":"sukses"}';
			}else{
				echo '{"status":"gagal"}';
			}
			
		break;
		#tambah  ==============================================================================================
		case 'tambah':
			$sql = "INSERT into user set 	username= '".trim(mysql_real_escape_string($_POST['usernameTB']))."',
											password= '".md5(mysql_real_escape_string($_POST['passwordTB']))."',
											level	= '$_POST[levelTB]'";
			$exe	= mysql_query($sql);
			if($exe){
				echo '{"status":"sukses"}';
			}else{
				echo '{"status":"gagal"}';
			}
		break;
		
		#hapus ==============================================================================================
		case 'hapus':
			$sql	= "delete from user where iduser ='$_GET[iduser]'";
			$exe	= mysql_query($sql);
			
			if($exe){
				echo '{"status":"sukses"}';
			}else{
				echo '{"status":"gagal"}';	
			}
		break;
			
		#tampil  =============================================================================================
		case 'tampil' :
			$sql = "SELECT * FROM user where level != 'user' order by iduser desc";
			//var_dump($sql);exit();
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
			if(mysql_num_rows($result)!=0)
			{
				$nox 	= $starting+1;
				while($res = mysql_fetch_array($result)){	
					if($res['level']=='adminf'){
						$level = 'adm. falkultas';
					}else{
						$level = 'adm. universitas';
					}
					$btn ="	 <td>
								 <a class='btn' href=\"javascript:editAdmin('$res[iduser]');\" 
								 role='button'><i class='icon-pencil'></i></a>
								 <a class='btn' href=\"javascript:hapusAdmin('$res[iduser]');\" 
								 role='button'><i class='icon-remove'></i></a>
							 </td>";
					echo '<tr>
							<td><label class="control-label">'.$nox.'</label></td>
							<td><label class="control-label">'.$res['username'].'</label></td>
							<td><label class="capitizer  control-label">'.$level.'</label></td>
							'.$btn.'
						</tr>';
                	$nox++;
				}
			}
			#kosong
			else
			{
				echo "<tr align='center'>
						<td  colspan=7 ><span style='color:red;text-align:center;'>
						... data masih kosong...</span></td></tr>";
			}
			#link paging
			echo "<tr class='info'><td colspan=7>".$obj->anchors."</td></tr>";
			echo "<tr class='info'><td colspan=7>".$obj->total."</td></tr>";
	break;
	
} ?>			
