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
		#combo ==============================================================================================
		case 'combo':
			switch($menu){
				case 'jab':
					// if($_GET['id_jab']!=''){
					// 	$sql = "SELECT
					// 				*
					// 			FROM
					// 				(
					// 					SELECT
					// 						g.id_jab,
					// 						j.jab,
					// 						count(r.id_rule) jumr
					// 					FROM
					// 						gol g
					// 					LEFT JOIN rule r ON r.id_gol = g.id_gol
					// 					JOIN jab j ON j.id_jab = g.id_jab
					// 					GROUP BY
					// 						g.id_jab
					// 				) tbjab
					// 			WHERE
					// 				tbjab.jumr NOT IN (
					// 					SELECT
					// 						COUNT(id_jab) jumg
					// 					FROM
					// 						gol
					// 					GROUP BY
					// 						id_jab
					// 				)
					// 			UNION
					// 				SELECT
					// 					id_jab,
					// 					jab,
					// 					id_jab AS jumr
					// 				FROM
					// 					jab
					// 				WHERE
					// 					id_jab = '$_GET[id_jab]'";
					// }else{
					// 	$sql	= "SELECT
					// 					*
					// 				FROM
					// 					(
					// 						SELECT
					// 							g.id_jab,j.jab,
					// 							count(r.id_rule) jumr
					// 						FROM
					// 							gol g
					// 						LEFT JOIN rule r ON r.id_gol = g.id_gol
					// 						JOIN jab j ON j.id_jab = g.id_jab
					// 						GROUP BY
					// 							g.id_jab
					// 					) tbjab
					// 				WHERE
					// 					tbjab.jumr NOT IN (
					// 						SELECT
					// 							COUNT(id_jab) jumg
					// 						FROM
					// 							gol
					// 						GROUP BY
					// 							id_jab
					// 					)";
					// }
					$sql = 'SELECT * from jab order by jab asc';
					// var_dump($sql);exit();

					$exe	= mysqli_query($con,$sql);
					$datax	= array();
					while($res=mysqli_fetch_assoc($exe)){
						$datax[]=$res;
					}
										
					if($datax!=NULL){
						echo json_encode($datax);
						#var_dump($datax);exit();
					}else{
						echo '{"status":"gagal"}';
					}
				break;
				
				case 'gol':
					// $sql	= 'SELECT
					// 			id_gol,gol,
					// 			id_jab
					// 		FROM
					// 			gol
					// 		WHERE
					// 			id_gol NOT IN (SELECT id_gol FROM rule)
					// 		AND id_jab = '$_GET[id_jab]'
					// 		UNION
					// 			SELECT
					// 			g.id_gol,g.gol,
					// 			j.id_jab
					// 		FROM
					// 			rule r,
					// 			gol g,
					// 			jab j
					// 		WHERE
					// 			g.id_jab = j.id_jab AND 
					// 			r.id_gol = g.id_gol AND 
					// 			r.id_gol = '.$_GET['id_gol'];
					
					$sql = 'SELECT * FROM gol where id_jab ='.$_GET['id_jab'];
					#var_dump($sql);exit();
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
			$sql = "SELECT
						g.id_gol,
						g.gol,
						j.id_jab,
						j.jab,
						r.masa,
						r.point,
						p.pt,
						p.id_pt
					FROM
						rule r
					INNER JOIN gol g ON r.id_gol = g.id_gol
					INNER JOIN pt p ON p.id_pt = r.id_pt
					LEFT JOIN jab j ON j.id_jab = g.id_jab
					where r.id_rule = '$_GET[id_rule]'
					ORDER BY
						gol ASC";
			//var_dump($sql);exit();
			$exe	= mysqli_query($con,$sql);
			#var_dump($exe);exit();
			$res	= mysqli_fetch_assoc($exe);
			if($exe){
				echo '{
					"jabatan":"'.$res['jab'].'",
					"id_jab":"'.$res['id_jab'].'",
					"jab":"'.$res['jab'].'",
					"id_gol":"'.$res['id_gol'].'",
					"gol":"'.$res['gol'].'",
					"masa":"'.$res['masa'].'",
					"id_pt":"'.$res['id_pt'].'",
					"point":"'.$res['point'].'"
				}';
			}else{
				echo '{"status":"gagal"}';
			}
		break;
		
		#ubah  ==============================================================================================
		case 'ubah':
			$sql = "update  rule set 	point	= '".mysqli_real_escape_string($con,$_POST['pointTB'])."',
										id_pt	= '".mysqli_real_escape_string($con,$_POST['id_ptTB'])."',
										id_gol		= '".mysqli_real_escape_string($con,$_POST['id_golTB'])."',
										masa	= '".mysqli_real_escape_string($con,$_POST['masaTB'])."'
							where id_rule=".$_GET['id_rule'];
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
			$sql = "INSERT into rule set	id_gol	= '".mysqli_real_escape_string($con,$_POST['id_golTB'])."',
											masa	= '".mysqli_real_escape_string($con,$_POST['masaTB'])."',
											id_pt	= '".mysqli_real_escape_string($con,$_POST['id_ptTB'])."',
											point	= '".mysqli_real_escape_string($con,$_POST['pointTB'])."'";
			// print_r($sql);exit();
			$exe	= mysqli_query($con,$sql);
			if($exe){
				echo '{"status":"sukses"}';
			}else{
				echo '{"status":"gagal"}';
			}
		break;
		
		#hapus ==============================================================================================
		case 'hapus':
			$sql	= "delete from rule where id_rule ='$_GET[id_rule]'";
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
						r.id_rule,
						g.gol,
						j.jab,
						r.point,
						r.masa,
						p.pt
					FROM
						rule r
					INNER JOIN gol g ON r.id_gol = g.id_gol
					INNER JOIN pt p ON p.id_pt = r.id_pt
					LEFT JOIN jab j ON j.id_jab = g.id_jab
					ORDER BY
						gol ASC";
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
			if(mysqli_num_rows($result)!=0)
			{
				$nox 	= $starting+1;
				while($res = mysqli_fetch_array($result)){	
					$btn ="	 <td>
								 <a class='btn' href=\"javascript:editRule('$res[id_rule]');\" 
								 role='button'><i class='icon-pencil'></i></a>
								 <a class='btn' href=\"javascript:hapusRule('$res[id_rule]');\" 
								 role='button'><i class='icon-remove'></i></a>
							 </td>";
					echo '<tr>
							<td><label class="control-label">'.$nox.'</label></td>
							<td><label class="control-label">'.$res['jab'].'</label></td>
							<td><label class="control-label">'.$res['gol'].'</label></td>
							<td><label class="control-label">1 th</label></td>
							<td><label class="control-label">'.$res['masa'].' th</label></td>
							<td><label class="control-label">'.$res['point'].'</label></td>
							<td><label class="control-label">'.$res['pt'].'</label></td>
							'.$btn.'
						</tr>';
                	$nox++;
				}
			}
			#kosong
			else
			{
				echo "<tr align='center'>
						<td  colspan=9 ><span style='color:red;text-align:center;'>
						... data masih kosong...</span></td></tr>";
			}
			#link paging
			echo "<tr class='info'><td colspan=9>".$obj->anchors."</td></tr>";
			echo "<tr class='info'><td colspan=9>".$obj->total."</td></tr>";
	break;
	
} ?>			
