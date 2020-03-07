<?php
	require_once '../../lib/koneksi.php';
	require_once '../../lib/pagination_class.php';
	require_once '../../lib/tglindo.php'; 

	#isset => mengecek apakah variabel telah diset atau tidak (* diset = varibel berisi atau kosong ) 
 	$aksi 	=  isset($_GET['aksi'])?$_GET['aksi']:''; 
	$menu	=  isset($_GET['menu'])?$_GET['menu']:'';
	$page 	=  isset($_GET['page'])?$_GET['page']:'';
	$cari	=  isset($_GET['cari'])?$_GET['cari']:'';
	$tabel	=  isset($_GET['tabel'])?$_GET['tabel']:'';
	
	switch ($aksi){ 
		#tampil  =====================================================================================================
		case 'tampil' :
			$sql = 'SELECT * from news 
					where 
						kategori = "'.$menu.'" and tittle like "%'.$cari.'%"
					ORDER by tglupdate desc';
			
			if(isset($_GET['starting'])){ //nilai awal halaman
				$starting=$_GET['starting'];
			}else{
				$starting=0;
			}
			//var_dump($sql);exit();

			$recpage= 5;//jumlah data per halaman
			$obj 	= new pagination_class($menu,$sql,$starting,$recpage);
			$result =$obj->result;
			#end of paging	 
			
			#ada data
			if(mysqli_num_rows($result)!=0)
			{
				$nox 	= $starting+1;
				while($res = mysqli_fetch_array($result))
				{	
					$fullNews	= $res['deskripsi'];
					$fewNews 	= substr($fullNews,0,200);
					$tgl		= tgl_indo($res['tglupdate']);
					$idx		= $res['idnews'];
					
					echo 
						'<div class="col-lg-4" style="text-align:justify">
							<div>.</div><h2><a href="javascript:loadData(\'detail\',\'berita\','.$idx.');">'.$res['tittle'].'</a></h2>
							<p style="color:grey;">'.$tgl.'</p>';
					
					if($res['kategori']=='berita'){
						echo '<p>
								<img class="vwimg" src="../upload/down/'.$res['file'].'"><div>.</div>'.
									$fewNews
									.'...
							</p>
							<p>
								<a class="btn btn-primary" href="javascript:loadData(\'detail\',\'berita\','.$res['idnews'].');" role="button">
									selengkapnya »
								</a>
							</p>'; 
					}else{
						echo 	'<p>'.$fewNews.'...</p>
								<p>
									<a class="btn btn-primary" href="../upload/down/'.$res['file'].'" target="_blank" role="button">
										unduh <i class="icon-arrow-down"></i>
									</a>
								</p>'; 
					} 
					echo '</div>';
					$nox++;
				}
			}
			#kosong
			else
			{
				echo "<tr align='center'>
						<td  colspan=7 ><span style='color:yellow;text-align:center;'>
						... data tidak ditemukan ...</span></td></tr>";
			}
			#link paging
			echo "<tr><td colspan=7>".$obj->anchors."</td></tr>";
			echo "<tr><td colspan=7>".$obj->total."</td></tr>";
	break;
	
	case 'detail':
		$sql	= 'SELECT * from news where idnews = '.$_GET['id'];
		$exe	= mysqli_query($con,$sql);
		$res	= mysqli_fetch_assoc($exe);
		$jum	= mysqli_num_rows($exe);
		
		$tgl = tgl_indo($res['tglupdate']);
		if($jum>0 & $exe!=NULL){
			echo '
				<div class="col-lg-4" style="text-align:justify">
					<div>.</div><h2>'.$res['tittle'].'</h2>
					<p style="color:grey;">'.$tgl.'</p>
					<img class="vwimg" src="../upload/down/'.$res['file'].'">
					<p>'.$res['deskripsi'].'</p>
					<p>
						<a class="btn btn-primary" href="javascript:loadData(\'tampil\',\'berita\');" role="button"> 
							<< kembali 
						</a>
					</p>
				</div>';
		}else{
			echo 'maaf berita tidak ditemukan';
		}
	break;
} ?>			
