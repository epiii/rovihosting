<?php
	include"../../lib/koneksi.php";
	include"../../lib/pagination_class.php";
	include "../../lib/tglindo.php"; 
	// error_reporting(0);

 	$aksi 	=  isset($_GET['aksi'])?$_GET['aksi']:'';
	$menu	=  isset($_GET['menu'])?$_GET['menu']:'';
	$page 	=  isset($_GET['page'])?$_GET['page']:'';
	$cari	=  isset($_GET['cari'])?$_GET['cari']:'';
	$tabel	=  isset($_GET['tabel'])?$_GET['tabel']:'';
	
	switch ($aksi){
		#tampil  =====================================================================================================
		case 'tampil' :
			//if (!empty($_GET['cari']) or $_GET['cari']!='undefined' or $_GET['cari']!=''){
			$sql = "SELECT * from news 
					where 
						kategori = '".$menu."' and tittle like '%".$cari."%' 
					ORDER by tglupdate desc";
			//}elseif(isset($_GET['menu']) or !empty($_GET['menu']) or empty($_GET['cari'])){
				//$sql = "select * from news where kategori ='$menu' order by tglupdate desc";
			//}
			
			//var_dump($sql);exit();
			if(isset($_GET['starting'])){ //nilai awal halaman
				$starting=$_GET['starting'];
			}else{
				$starting=0;
			}
			//var_dump($sql);exit();
			
			//record per halaman
			//var_dump($menu);exit();
			$recpage= 4;//jumlah data per halaman
			$obj 	= new pagination_class($menu,$sql,$starting,$recpage);
			$result =$obj->result;
			#end of paging	 
			
			#ada data
			if(mysql_num_rows($result)!=0)
			{
				$nox 	= $starting+1;
				while($res = mysql_fetch_array($result))
				{	
					$fullNews	= $res['deskripsi'];
					$fewNews 	= substr($fullNews,0,200);
					$tgl		= tgl_indo($res['tglupdate']);
					$idx		= $res['idnews'];
					//var_dump($idx);exit();			
					
					echo 
						'<div class="col-lg-3" style="text-align:left">
							<h3>
								<a href="javascript:loadData(\'detail\',\'berita\','.$idx.');">'.$res['tittle'].'</a>
							</h3>
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
										download »
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
		$sql	= 'select * from news where idnews = '.$_GET['id'];
		//var_dump($sql);
		$exe	= mysql_query($sql);
		$res	= mysql_fetch_assoc($exe);
		$jum	= mysql_num_rows($exe);
		
		$tgl		= tgl_indo($res['tglupdate']);
		if($jum>0 & $exe!=NULL){
			echo "
				<div class='col-lg-3' style='text-align:left'>
					<h3>$res[tittle]</h3>
					<p style='color:grey;'>$tgl</p>
					<img class='vwimg' src='../upload/down/$res[file]'>
					<p>$res[deskripsi]</p>";?>
					<p><a class="btn btn-primary" href="javascript:loadData('tampil','berita');" role="button"> << kembali </a></p>
			<?php echo	"</div>";
		}else{
			echo 'maaf berita tidak ditemukan';
		}
	break;
} ?>			
