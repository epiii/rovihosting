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
		#ambiledit ==============================================================================================
		case 'ambiledit':
			$sql	= 'SELECT* from news where idnews = '.$_GET['idnews'];
			//var_dump($sql);exit();
			$exe	= mysqli_query($con,$sql);
			$res	= mysqli_fetch_assoc($exe);
			//var_dump($jum);exit();
			if($exe){
				$res2 = mysqli_fetch_assoc($exe);
				echo '{
						"tittle":"'.$res['tittle'].'",
						"kategori":"'.$res['kategori'].'",
						"deskripsi":"'.$res['deskripsi'].'",
						"file":"'.$res['file'].'"
					}';
			}else{
				echo '{"status":"kosong"}';
			}
		break;
		
		#hapus ==============================================================================================
		case 'hapus':
			$sqlz	= "select * from news where idnews='$_GET[idnews]'";
			$exez	= mysqli_query($con,$sqlz);
			$resz	= mysqli_fetch_assoc($exez);

			if($exez){
				$linkx = '../../upload/down/'.$resz['file'];
				//var_dump($linkx);exit();
				
				if(unlink($linkx)){
					$sql1 	= "delete from news where idnews ='$_GET[idnews]'";
					$exe1	= mysqli_query($con,$sql1);
					if($exe1){
						echo '{"status":"sukses"}';
					}else{
						echo '{"status":"gagal"}';
					}
				}
			}
		break;
			
		#combo  ==============================================================================================
		case 'combo':
			switch($menu){
				case 'katkeg':
					$sqlU	= "select 
								k.idkeg, 
								k.nakeg
							from 
								katkeg t, kegiatan k 
							where 
								t.idkatkeg 	= k.katkeg and 
								k.katkeg 	= '$_GET[idkatkeg]' and 
								k.idkeg not in (
									select d.idkeg 
									from 
										dtk d, dsn s, user u
									where 
										s.iduser = u.iduser and 
										u.iduser = '$_SESSION[iduser]' and
										s.iddsn	 = d.iddsn
									group by d.idkeg
								)";
					if($_GET['idform']==''){ // add
						$sql = $sqlU;
					}else{ //edit
						$sql = $sqlU." UNION 
										select d.idkeg, k.nakeg
										from dtk d, kegiatan k
										where 
											d.iddtk = '$_GET[idform]' and
											d.idkeg = k.idkeg";
					}
						
					//var_dump($sql);exit();
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
				
				case 'nakeg':
					$sql	= "select * from  kegiatan  where idkeg = '$_GET[idkeg]'";
					//var_dump($sql);exit();
					
					$exe	= mysqli_query($con,$sql);
					$datax	= array();
					while($res=mysqli_fetch_assoc($exe)){
						$datax[]=$res;
					}
					
					if($datax!=NULL){
						echo json_encode($datax);
						//echo '{"status":"sukses","datay":"'.json_encode($datax).'"}';
					}else{
						echo '{"status":"gagal"}';
					}
				break;
			}
		break;
		
		#tampil  =============================================================================================
		case 'tampil' :
			$sql = "SELECT * from news where kategori ='$menu'  ORDER BY tglupdate DESC	";
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
				//$tb	='<thead></thead><tbody>';
				while($res = mysqli_fetch_array($result)){	
					$btn ="<td>
								<a class='btn' href=\"javascript:hapusNews('$res[idnews]','$res[kategori]');\" 
								 role='button'><i class='icon-remove'></i></a>
							 </td>
							 <td>
								 <a class='btn' href=\"javascript:editNews('$res[idnews]','$res[kategori]');\" 
								 role='button'><i class='icon-pencil'></i></a>
							 </td>";
					$des	= $res['deskripsi'];		 
					$des2	= substr($des,0,100);
					echo '<tr >
							<td><label class="control-label">'.$nox.'</label></td>
							<td><label class="control-label">'.$res['tittle'].'</label></td>
							<td><label class="control-label">'.$des2.' ...</label></td>
							<td><label class="control-label">'.tgl_indo($res['tglupdate']).'</label></td>
							'.$btn.'
						</tr>';
                	$nox++;
				}
				//echo $tb.'</tbody>';
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
