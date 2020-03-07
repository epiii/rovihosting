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
	
	switch ($aksi){
		#urutan =============================================================================================
		case 'urutan':
			$plh1 = mysqli_fetch_assoc(mysqli_query($con,"select id_gol from gol where urutan = '$_GET[awal]'"));
			$plh2 = mysqli_fetch_assoc(mysqli_query($con,"select id_gol from gol where urutan = '$_GET[akhir]'"));
			$exe1 = mysqli_query($con,"update gol set urutan = '$_GET[akhir]' where id_gol='$plh1[id_gol]'");
			$exe2 = mysqli_query($con,"update gol set urutan = '$_GET[awal]' where id_gol='$plh2[id_gol]'");
			if($exe1 && $exe2){
				echo '{"status":"sukses"}';
			}else{
				echo '{"status":"gagal"}';
			}
			#var_dump($sql1);exit();
		break;
		
		#combo ==============================================================================================
		case 'combo':
			switch($menu){
				case 'jab':
					#if($_GET['id_jab']!=''){
					#	$sql	= "select * from jab where id_jab = '$_GET[id_jab]'";
					#}else{
						$sql	= "select * from jab";
					#}
					$exe	= mysqli_query($con,$sql);
					$datax	= array();
					while($res=mysqli_fetch_assoc($exe)){
						$datax[]=$res;
					}
					
					if($datax!=NULL){
						echo json_encode($datax);
					}else{
						echo '{"status":"gagal"}';
					}
				break;
			}
		break;
		
		#ambiledit==============================================================================================
		case 'ambiledit':
			$sql = "SELECT *
					FROM jab j,gol g
					where 
						g.id_jab = j.id_jab AND
					 	g.id_gol = '$_GET[id_gol]'
					 ";
			// var_dump($sql);exit();
			$exe	= mysqli_query($con,$sql);
			#var_dump($exe);exit();
			$res	= mysqli_fetch_assoc($exe);
			if($exe){
				echo '{
					"jabatan":"'.$res['jab'].'",
					"id_jab":"'.$res['id_jab'].'",
					"jab":"'.$res['jab'].'",
					"id_gol":"'.$res['id_gol'].'",
					"gol":"'.$res['gol'].'"
				}';
				// "masa":"'.$res['masa'].'",
				// "id_pt":"'.$res['id_pt'].'",
				// "point":"'.$res['point'].'"
			}else{
				echo '{"status":"gagal"}';
			}
		break;
		
		#ubah  ==============================================================================================
		case 'ubah':
			$sql = "update gol set 	id_jab	= '".mysqli_real_escape_string($con,$_POST['id_jabTB'])."',
									gol 	= '".mysqli_real_escape_string($con,$_POST['golTB'])."'
								where id_gol=".$_GET['id_gol'];
		//var_dump($sql);exit();
			$exe	= mysqli_query($con,$sql);
			if($exe){
				echo '{"status":"sukses"}';
			}else{
				echo '{"status":"gagal"}';
			}
			
		break;
		#tambah  ==============================================================================================
		case 'tambah':
			$urutan	= mysqli_fetch_assoc(mysqli_query($con,"select (max(urutan)+1 ) as urut from gol"));
			$sql = "insert into gol  set	id_jab	= '".mysqli_real_escape_string($con,$_POST['id_jabTB'])."',
											gol		= '".mysqli_real_escape_string($con,$_POST['golTB'])."',
											urutan	= '$urutan[urut]'";
			#var_dump($sql);exit();
			$exe	= mysqli_query($con,$sql);
			if($exe){
				echo '{"status":"sukses"}';
			}else{
				echo '{"status":"gagal"}';
			}
		break;
		
		#hapus ==============================================================================================
		case 'hapus':
			$sql	= 'DELETE from gol where id_gol ='.$_GET['id_gol'];
			// var_dump($sql);exit();
			$exe	= mysqli_query($con,$sql);
			
			if($exe){
				echo '{"status":"sukses"}';
			}else{
				echo '{"status":"gagal"}';	
			}
		break;
			
		#tampil  =============================================================================================
		case 'tampil' :
		
			$sql = "SELECT
						*
					FROM
						jab j,
						gol g
					WHERE
						j.id_jab = g.id_jab
					ORDER BY g.urutan ASC";
			$jumTot = mysqli_num_rows(mysqli_query($con,$sql));
			#var_dump($jumTot);exit();
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
			#var_dump($result);exit();
			#end of paging	 
			
			#ada data
			$jum	= mysqli_num_rows($result);
			if($jum!=0){	
				$x	= $starting+1;
				while($res = mysqli_fetch_array($result)){	
					$nox='<select name="noTB" id="noTB_'.$x.'" class="span2" onchange="ubahUrutan('.$res['urutan'].','.$x.');">';
					#var_dump($nox);exit();
					for($i=1;$i<=$jumTot;$i++){
						if($res['urutan']==$i){
							$nox.= '<option value="'.$i.'" selected="selected">'.$i.'</option>';
						}else{
							$nox.= '<option value="'.$i.'" >'.$i.'</option>';
						}
					}
					$nox.='</select>';
					$btn ="	 <td>
								 <a class='btn btn-secondary' href=\"javascript:editGol('$res[id_gol]');\" 
								 role='button'><i class='icon-pencil'></i></a>
								 <a class='btn btn-secondary' href=\"javascript:hapusGol('$res[id_gol]');\" 
								 role='button'><i class='icon-remove'></i></a>
							 </td>";
					echo '<tr>
							<td><label class="control-label">'.$nox.'</label></td>
							<td><label class="control-label">'.$res['gol'].'</label></td>
							<td><label class="control-label">'.$res['jab'].'</label></td>
							'.$btn.'
						</tr>';
                	$x++;
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
