<?php
	session_start();
	require_once"../../lib/koneksi.php";
	require_once"../../lib/pagination_class.php";
	require_once"../../lib/tglindo.php"; 
	require_once "./f_hitung.php";
	$itemKrg= array();
	
	$aksi 	=  isset($_GET['aksi'])?$_GET['aksi']:'';
	$menu	=  isset($_GET['menu'])?$_GET['menu']:'';
	
	switch ($aksi){
		#ubah pangkat ==========================================================================================
		case 'naikTemp':
			$pointgt	= isset($_GET['pointgt'])?$_GET['pointgt']:'';
			$iddsn		= isset($_GET['iddsn'])?$_GET['iddsn']:'';

			#hitung total poin -------------------------------------------
			$sqltot ="SELECT 
						sum(ifnull(tbpoin.poinx,0))poinx,
						IFNULL(tbds.remain,0)remainx,(
							sum(ifnull(tbpoin.poinx,0))+IFNULL(tbds.remain,0)
						)as totpoinx,
						IFNULL(SUM(k.poin),0) AS totpoin,tbhj.tothist,d.iddsn,d.sisaStatus
					FROM dsn d
						LEFT JOIN histjab h ON h.iddsn = d.iddsn
						LEFT JOIN dtk t ON t.idhistjab= h.idhistjab
						LEFT JOIN kegiatan k ON t.idkeg = k.idkeg
						LEFT JOIN gol g ON g.id_gol = h.id_gol
						LEFT JOIN jab j ON j.id_jab = g.id_jab
						left join (
							SELECT 
								dtk.iddtk,
								CASE dtk.sisa 
									WHEN 0 THEN kegiatan.poin 
									ELSE dtk.sisa 
								END AS poinx
							from 
								dtk 
								join kegiatan on kegiatan.idkeg = dtk.idkeg
							where 	 
								dtk.status='valid' or (
									dtk.status='done' and dtk.sisa!=0
								)
						)tbpoin on tbpoin.iddtk = t.iddtk
						LEFT JOIN (
							SELECT iddsn, COUNT(*)tothist
							FROM histjab
							GROUP BY iddsn
						)tbhj ON tbhj.iddsn=d.iddsn
						LEFT JOIN (
							SELECT dtksisa.iddsn, SUM(dtksisa.remain) AS remain
							FROM dtksisa
							GROUP BY dtksisa.iddsn
						)tbds ON tbds.iddsn = d.iddsn
					WHERE h.status=1 and d.iddsn = $iddsn
					GROUP BY d.iddsn";
			$exetot = mysqli_query($con,$sqltot);
			$restot = mysqli_fetch_assoc($exetot);
			#end of hitung total poin -------------------------------------------

			#loop kategori kegiatan, subtotal poin, subtotal target, subtotal sisa poin sebelumnya----
			$sqlkat = "SELECT
							ktg.idkatkeg,
							ktg.katkeg,
							(ktg.subTotTgt * $pointgt /100)subTotTgtNum,
							ktg.tipe,
							tbdtk.sisa,
							tbdtk.remain,
							tbdtk.iddtksisa
						FROM 
							katkeg ktg
							LEFT JOIN kegiatan k ON k.katkeg = ktg.idkatkeg
							LEFT JOIN dtk t ON t.idkeg = k.idkeg
							LEFT JOIN histjab h ON h.idhistjab = t.idhistjab
							LEFT JOIN dsn ON dsn.iddsn = h.iddsn
							LEFT JOIN (
								SELECT
									katkeg.idkatkeg,
									tbds.poin as sisa,
									tbds.remain,
									tbds.iddtksisa
								FROM katkeg
									LEFT JOIN (
										SELECT 
											ds.iddtksisa,
											ds.idkatkeg,
											ds.poin,
											ds.remain
										FROM 
											dsn d
										LEFT JOIN dtksisa ds ON d.iddsn = ds.iddsn
										WHERE
											d.sisaStatus='valid' AND
										 	d.iddsn=$_GET[iddsn]
									)
									tbds ON tbds.idkatkeg = katkeg.idkatkeg
							)tbdtk ON tbdtk.idkatkeg = ktg.idkatkeg
						GROUP BY 
							ktg.idkatkeg";
			$exekat 	= mysqli_query($con,$sqlkat);
			// var_dump($exekat);exit();
			
			#loop kategori ------------------
			while($reskat=mysqli_fetch_assoc($exekat)){
				$idkatkeg 	= $reskat['idkatkeg'];
				$iddtksisa	= $reskat['iddtksisa'];
				$subremain	= $reskat['remain'];
				$subTotTgtNum= $reskat['subTotTgtNum'];
				// var_dump($subremain);exit();

				if($iddtksisa==null){// tidak  punya sisa poin (sebelumnya)
					$subTarget = $subTotTgtNum ;
					$state = hitungPangkat($iddsn,$idkatkeg,$subTarget);
				}else{ // punya sisa poin (sebelumnya)
					$selisih = $subTotTgtNum-$subremain;

					if ($subTotTgtNum<$subremain) {// target < sisa --
						$sql = 'UPDATE dtksisa set remain = '.abs($selisih).' where iddtksisa='.$iddtksisa;
						$exe = mysqli_query($con,$sql);
						$state = 'true';
					}else{ //target >= sisa --------------------------
						$sql = 'UPDATE dtksisa set remain = 0 where iddtksisa='.$iddtksisa;
						$exe = mysqli_query($con,$sql);
						if($subTotTgtNum>$subremain){ //lebih
							$subTarget = $selisih;
							$state = hitungPangkat($iddsn,$idkatkeg,$subTarget);
						}else{ //pas
							$state ='true'; 
						}
					}#end of target >= sisa ------------------
					// var_dump($subTarget);exit();
				}#end of punya sisa poin----------------------

		   	}#end of loop kategori ---------------------------

		    #update jabatan (db:histjab)----------------------
		    if($state='true'){
		    	//pilih history jabtan dosen x
		    	$sql = 	'SELECT *
						FROM histjab hh
						WHERE 
							hh.STATUS=1 AND 
							hh.iddsn='.$iddsn;
				$exe = mysqli_query($con,$sql);
				$res = mysqli_fetch_assoc($exe);

				//ubah status 1 jadi 0
				$sql2 ='UPDATE 
							histjab h 
						SET 
							h.status=0
						WHERE
							h.idhistjab='.$res['idhistjab'];
				$exe2 = mysqli_query($con,$sql2);
				
				//insert baru history jabatan status = 1
				$sql3 ='INSERT into histjab 
						SET 
							status=1,
							id_gol	='.$_GET['idgoltgt'].',
							id_jab	='.$_GET['idjabtgt'].',
							tgltmp	=NOW(),
							id_pt 	='.$res['id_pt'].',
							iddsn 	='.$iddsn;
				$exe3 = mysqli_query($con,$sql3);
				// var_dump($sql3);exit();
		    }
		    #end of update jabatan (db:histjab)--------------------------------------------------------
		   	echo '{"status":"'.$state.'"}';
		break;	

	#view sisa poin  ===========
		case 'viewSisa':
			$sql = 'SELECT  concat(gelard," ",namad," ",namab," ",gelarb)as nama
					from dsn where iddsn='.$_GET['iddsn'];
			$exe = mysqli_query($con,$sql);
			$res = mysqli_fetch_assoc($exe);
			// print_r($res);exit();
			
			$sql2 = 'SELECT
						katkeg.idkatkeg,
						katkeg.katkeg,
						tbds.poin,
						tbds.remain
					FROM katkeg
						LEFT JOIN (
							SELECT
								ds.idkatkeg,
								ds.poin,
								ds.remain
							FROM 
								dsn d
							LEFT JOIN dtksisa ds ON d.iddsn = ds.iddsn
							WHERE d.iddsn='.$_GET['iddsn'].'
						)
						tbds ON tbds.idkatkeg = katkeg.idkatkeg';
			// print_r($sql);exit();
			$exe2 = mysqli_query($con,$sql2);
			$sisaArr =array();
			$remainStatus = 0; //habis terpakai
			
			while ($res2=mysqli_fetch_assoc($exe2)) {
				$sisa = ($res2['poin']==null)?0:$res2['poin'];
				$remain = ($res2['remain']==null)?0:$res2['remain'];
				if($remain>0){ //remain masih tersisa 
					$remainStatus += $remain;
				}

				$sisaArr[]=array(
							'idkatkeg'=>$res2['idkatkeg'],
							'katkeg'=>$res2['katkeg'],
							'sisa'=>$sisa,
						);
			}

			if($sisaArr!=NULL){
				echo '{"remainStatus":'.$remainStatus.',
					"nama":"'.$res['nama'].'",
					"sisaArr":'.json_encode($sisaArr).'}';
			}else{
				echo '{"status":"gagal"}';
			}

		break;

	#update validasi status sisa poin ==================
		case 'updateSisaP':
			if(isset($_GET['sisaStatus'])){
				$status=($_GET['sisaStatus']=='valid')?'invalid':'valid';
				$sql='UPDATE dsn  set sisaStatus="'.$status.'" where iddsn='.$_GET['iddsn'];
				// print_r($sql);exit();
				$exe=mysqli_query($con,$sql);
				if($exe){
					echo '{"status":"sukses","sisaStatus":"'.$status.'"}';
				}else{
					echo '{"status":"gagal"}';
				}
			}
		break;
		
	#ubah =============================================================================================
		case 'ubah':
			$jumRow = count($_POST['idH']);
			$sukses	= false;
			$jumStatus = 0;
			for($i=0;$i<$jumRow;$i++){
				if(!isset($_POST['statusTB'][$i])){
					$statusTB = 'invalid';
				}else{
					$statusTB = 'valid';
				}
				$sql = "update bukeg set 	status 		= '$statusTB',
											keterangan 	= '".trim(mysqli_real_escape_string($con,$_POST['keteranganTB'][$i]))."'
						where idbukeg = '".$_POST['idH'][$i]."'";
				$exe = mysqli_query($con,$sql);
				if($exe){
					$sukses=true;
					if($statusTB=='valid'){
						$jumStatus=$jumStatus+1;
					}
				}
			}
			
			if($sukses==true){
				if($jumStatus<$jumRow){
					$statusx ='checked';
				}else{
					$statusx ='valid';
				}
				$sql2 	= "update dtk set status = '$statusx' where iddtk='$_GET[iddtk]'";
				$exe2	= mysqli_query($con,$sql2);
				if($exe2){
					echo '{"status":"sukses","statusx":"'.$statusx.'"}';
				}
			}else{
				echo'{"status":"gagal"}';
			}
		break;
		
	#listDosen  ============================================================================================
		case 'tampil' :
			$where=($_SESSION['levelx']=='adminf')?" AND a.idadmin=".$_SESSION['idadmin']:"";
			$sql ="SELECT
						sum(ifnull(tbpoin.poinx, 0)) poinx,
						IFNULL(tbds.poin, 0) availRemainx,
						IFNULL(tbds.remain, 0) usedRemainx,
						(
							sum(ifnull(tbpoin.poinx, 0)) + IFNULL(tbds.remain, 0)
						) AS totpoinx,
						IFNULL(SUM(k.poin), 0) AS totpoin,
						h.tgltmp,
						tbhj.jum,
						d.iddsn,
						d.nip,
						d.baru,
						CONCAT(
							d.gelard,
							' ',
							d.namad,
							' ',
							d.namab,
							' ',
							d.gelarb
						) AS nama,
						g.gol,
						j.jab,
						(YEAR(CURDATE()) - YEAR(tgll)) AS umur,
						d.sisaStatus
					FROM
						dsn d
					JOIN prodi p ON p.idprodi = d.idprodi
					JOIN jur jr ON jr.idjur = p.idjur
					JOIN fak f ON f.idfak = jr.idfak
					JOIN admin a ON a.idfak = f.idfak
					LEFT JOIN histjab h ON h.iddsn = d.iddsn
					LEFT JOIN dtk t ON t.idhistjab = h.idhistjab
					LEFT JOIN kegiatan k ON t.idkeg = k.idkeg
					LEFT JOIN gol g ON g.id_gol = h.id_gol
					LEFT JOIN jab j ON j.id_jab = g.id_jab
					LEFT JOIN (
						SELECT
							dtk.iddtk,
							CASE dtk.sisa
						WHEN 0 THEN
							kegiatan.poin
						ELSE
							dtk.sisa
						END AS poinx
						FROM
							dtk
						JOIN kegiatan ON kegiatan.idkeg = dtk.idkeg
						WHERE
							dtk. STATUS != 'done'
						OR (
							dtk. STATUS = 'done'
							AND dtk.sisa != 0
						)
					) tbpoin ON tbpoin.iddtk = t.iddtk
					LEFT JOIN (
						SELECT
							iddsn,
							COUNT(*) jum
						FROM
							histjab
						GROUP BY
							iddsn
					) tbhj ON tbhj.iddsn = d.iddsn
					LEFT JOIN (
						SELECT
							dtksisa.idhistjab,
							SUM(dtksisa.poin) AS poin,
							SUM(dtksisa.remain) AS remain
						FROM
							dtksisa
						GROUP BY
							dtksisa.idhistjab
					) tbds ON tbds.idhistjab = h.idhistjab
					WHERE
						h.`status` = 1 ".$where."
					GROUP BY
						d.iddsn
					ORDER BY
						d.baru DESC,
						totpoin DESC,
						d.sisaStatus DESC,
						g.gol DESC";
			// print_r($sql);exit();
			if(isset($_GET['starting'])){ //nilai awal halaman
				$starting=$_GET['starting'];
			}else{
				$starting=0;
			}
			//var_dump($sql);exit();
			
			//record per halaman
			$recpage= 10;//jumlah data per halaman
			$obj 	= new pagination_class($menu,$sql,$starting,$recpage);
			$result = $obj->result;
			#end of paging	 
			$jum = mysqli_num_rows($result);
			// var_dump($jum);exit();
			$tb = '';
			#ada data
			if($jum!=0){
				$nox 	= $starting+1;
				while($res = mysqli_fetch_array($result)){	
					#info : row baru(update)
					if($res['baru']==1){
						$trclr='class="warning"';$info='baru';
					}else{
						$trclr='';$info='';
					}

					#info : row sisa poin (pengajuan sebelumnya)
					if($res['sisaStatus']=='valid'){//punya sisa poin , sudah validasi
						if($res['availRemainx']!=$res['usedRemainx']){ //sisa habis terpakai
							$icon =' icon-lock';
							$toolt ='sisa poin : terpakai';
							$status='terpakai';
						}else{ //masih ada
							$icon =' icon-ok';
							$toolt ='sisa poin : valid';
							$status='valid';
						}

						$btnSisa='<a onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top"
									title="'.$toolt.'" class="btn" href="javascript:viewSisaPoin('.$res['iddsn'].',\''.$status.'\');" 
									role="button"><i class="'.$icon.'"></i></a>';
					}elseif($res['sisaStatus']=='invalid'){ // punya sisa poin , belum valid
						$btnSisa='<a onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="sisa poin : belum valid" class="btn" 
									href="javascript:viewSisaPoin('.$res['iddsn'].',\'invalid\');" 
									role="button"><i class="icon-warning-sign"></i></a>';
					}else{//tidak punya sisa poin
						$btnSisa='<span   onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="sisa poin Kosong"  ><button disabled="disabled" class="btn"><i class="icon-ban-circle"></i></button></span>';
					}

					#status berapa kali naik pangkat (menggunakan aplikasi)
					$statusNaik='';
					// var_dump($res['jum']);exit();
					if($res['jum']==1){
						$statusNaik.= '<i class="icon-star-empty"></i>';
						$infoStatus = 'belum naik pangkat';
					}else{
						for($i=1; $i<=$res['jum']; $i++){
							if($i==$res['jum'])
								$statusNaik.= '<i class="icon-star-empty"></i>';
							else
								$statusNaik.= '<i class="icon-star"></i>';
						}
						$infoStatus= ($res['jum']-1).' x naik pangkat';
					}

					$btn 	='<td>
								 <a onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="detail Dosen"  class="btn" href="javascript:viewDosenDtl('.$res['iddsn'].');" 
									 role="button"><i class="icon-user"></i></a>
									 '.$btnSisa.'
							 </td>';
					$tb.='<tr onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" '.$trclr.' title="'.$info.'">
							<td ><label onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="'.$infoStatus.'" >'.$statusNaik.'</label></td>
							<td>'.$res['nip'].'</td>
							<td>'.$res['nama'].'</td>
							<td>'.$res['gol'].'</td>
							<td>'.$res['jab'].'</td>
							<td>'.$res['umur'].' th</td>
							<td>'.$res['totpoinx'].'</td>
							'.$btn.'
						</tr>';
                	$nox++;
				}
			}else{#kosong
				$tb.="<tr class='warning' align='center'><td  colspan='8' ><span class='label label-important'>
						... data masih kosong...</span></td></tr>";
			}
			#link paging
			$tb.="<tr class='info'><td colspan=8>".$obj->anchors."</td></tr>";
			$tb.= "<tr class='info'><td colspan=8>".$obj->total."</td></tr>";
			echo $tb;
			// print_r($tb);exit();
	break;

	#ambilview==============================================================================================
		case 'ambilview':
			switch($menu){
				# view bukti kegiatan ============
				case 'bukeg':
					$exe1 = mysqli_query($con,"UPDATE dtk set status='checked' where iddtk = '$_GET[iddtk]'");
					
					$sql = 'SELECT * 
							from dtk join kegiatan on dtk.idkeg = kegiatan.idkeg 
							where dtk.iddtk='.$_GET['iddtk'];
					$exe = mysqli_query($con,$sql);
					$res = mysqli_fetch_assoc($exe);

					$sql2 = 'SELECT * from bukeg where iddtk='.$_GET['iddtk'];
					$exe2 = mysqli_query($con,$sql2);
					$dataArr = array();
					while($res2=mysqli_fetch_assoc($exe2)){
						$dataArr[]=$res2;	
					}
					// print_r($dataArr);exit();
					if($exe2){
						if($dataArr!=NULL){
							echo '{
								"nakeg":"'.$res['nakeg'].'",
								"batut":"'.$res['batut'].'",
								"bukeg":"'.$res['bukeg'].'",
								"dataArr":'.json_encode($dataArr).'
							}';
							// echo json_encode($dataArr);
						}else{
							echo '{"status":"data_kosong"}';
						}
					}else{
						echo '{"status":"gagal_kueri"}';
					}
	 			break;
				# end of view bukti kegiatan ============

				#view kegiatan ================
				case 'keg' :
					$sqlkt ='SELECT
								ktg.idkatkeg,
								ktg.katkeg,
								ktg.subTotTgt,
								ktg.tipe,
								tbdtk.sisa AS subsisa,
								tbdtk.remain AS subremain
							FROM 
								katkeg ktg
								LEFT JOIN subkatkeg s ON s.id_katkeg= ktg.idkatkeg
								LEFT JOIN kegiatan k ON s.id_subkatkeg= k.id_subkatkeg
								LEFT JOIN dtk t ON t.idkeg = k.idkeg
								LEFT JOIN histjab h ON h.idhistjab = t.idhistjab
								LEFT JOIN dsn ON dsn.iddsn = h.iddsn
								LEFT JOIN (
									SELECT
										katkeg.idkatkeg,
										tbds.poin as sisa,
										tbds.remain
									FROM katkeg
										LEFT JOIN (
											SELECT 
												ds.iddtksisa,
												ds.idkatkeg,
												ds.poin,
												ds.remain
											FROM 
												dsn d
												LEFT JOIN histjab h ON h.iddsn= d.iddsn
												LEFT JOIN dtksisa ds ON ds.idhistjab= h.idhistjab
											WHERE
												d.sisaStatus="valid" AND
												d.iddsn='.$_GET['iddsn'].'
										)
										tbds ON tbds.idkatkeg = katkeg.idkatkeg
								)tbdtk ON tbdtk.idkatkeg = ktg.idkatkeg
							GROUP BY 
								ktg.idkatkeg';

					// print_r($sqlkt);exit();
					$exekt 	= mysqli_query($con,$sqlkt);
					$jumkt 	= mysqli_num_rows($exekt);
					$katArr	= array();
					$itemKrg= array();
					$gtotDt = 0;
					
					$sql = 'UPDATE dsn set baru=0 where iddsn ='.$_GET['iddsn'];
					mysqli_query($con,$sql);
				
					#kategori : kosong --------------------------------
					if($jumkt==0){
						//<div class="label label-important">kategori kegiatan kosong</div>
						$arrx[]=array('status'=>'kategori_kosong');
					}#end of kategori : kosong -------------------------
					
					#kategori : ada ------------------------------------
					else{
						#loop kategori ---------------------------------
						while($reskt= mysqli_fetch_assoc($exekt)){
							$subsisa = ($reskt['subremain']==null)?0:$reskt['subremain'];

							$kegArr = array();
							$sqlkeg ='SELECT
											t.iddtk,
											kt.idkatkeg,
											k.nakeg, 
											k.poin,
											t.sisa,
											t.status
										from 
											histjab h 
											left join dtk t on t.idhistjab = h.idhistjab
											join kegiatan k on k.idkeg = t.idkeg
											join katkeg kt on kt.idkatkeg = k.katkeg
										where 
											h.iddsn ='.$_GET['iddsn'].' and 
											kt.idkatkeg = '.$reskt['idkatkeg'].' and 
											(
												t.status!="done" or 
												(
													t.status="done" and t.sisa!=0
												)
											)';
							print_r($sqlkeg);exit();
							$exekeg = mysqli_query($con,$sqlkeg);
							$jum 	= mysqli_num_rows($exekeg);

							$subtotDt	= 0;

							//loop item kegiatan (per kategori)------------------
							while($reskeg=mysqli_fetch_assoc($exekeg)){
								if($reskeg['sisa']>0){
									$poinAwl = $reskeg['poin'];
									$poin=$reskeg['sisa'];
								}else{
									$poinAwl = $reskeg['poin'];
									$poin=$poinAwl;
								}
								$subtotDt += $poin;
								$kegArr[]=array(
											'iddtk'	=>$reskeg['iddtk'],
											'nakeg'	=>$reskeg['nakeg'],
											'status'=>$reskeg['status'],
											'poin'	=>$poin,
											'poinAwl'=>$poinAwl
								);
							}
							$subtotDt 	= $subtotDt + $subsisa;
							$gtotDt		= $gtotDt+$subtotDt;
							//end of loop item kegiatan (per kategori)-----------

							//hitung rule min & max subtot per kategori----------
							$tipe			= $reskt['tipe'];
							$pointgt		= $_GET['pointgt'];
							$subTotTgtPerc	= $reskt['subTotTgt'];
							$subTotTgtNum 	= $subTotTgtPerc / 100 * $pointgt;	

							//kurangan point subtot per kategori --------------- 
							$valKrg		=abs($subTotTgtNum - $subtotDt);
							if($subtotDt==0){//kosong
								$itemKrg[]	=$reskt['katkeg'].' masih kosong';
							}else{ //ada
								if($tipe=='mn'){ //min
									if($subtotDt<$subTotTgtNum){
										$itemKrg[]	=$reskt['katkeg'].' kurang '.$valKrg.' poin';
									}
								}else{ //max
									if($subtotDt>$subTotTgtNum){
										// $itemKrg[]	=$reskt['katkeg'].' lebih  '.$valKrg.' poin';
									}
								}
							}
							//end of kurangan point subtot per kategori ---
							$katArr[]=array(
											'idkatkeg' 		=>$reskt['idkatkeg'] ,
											'katkeg' 		=>$reskt['katkeg'] ,
											'tipe'			=>$tipe,
											'subTotTgtPerc'	=>$subTotTgtPerc,
											'subTotTgtNum'	=>$subTotTgtNum,
											'subtot'		=>$subtotDt,
											'subsisa'		=>$subsisa,
											'kegArr' 		=>$kegArr
									);
						}#end of loop kategori --------------------------
					}#end of kategori : ada -----------------------------

					// print_r($katArr);exit();
					if($gtotDt<$pointgt){
						$valKrg		= $pointgt - $gtotDt;
						$itemKrg[]	='Grand Total krg '.$valKrg.' poin';
					}

					echo '{
							"gtot":'.$gtotDt.',
							"kurangan":'.json_encode($itemKrg).',
							"katArr":'.json_encode($katArr).'
					}';
					
				break;
				#end of view kegiatan ================
	
				case 'bio':
					$sql	= 	'SELECT
								d.*,(YEAR(curdate()) - YEAR(d.tgll)) AS umur,
								g.id_gol,g.gol,g.urutan,
								j.jab,j.id_jab,
								p.pt,
								h.tgljabs,h.tglgols,
								YEAR (CURDATE()) - YEAR (h.tgljabs) AS masajabs,
								YEAR (CURDATE()) - YEAR (h.tglgols) AS masagols
							FROM
								dsn d
								left JOIN histjab h ON h.iddsn = d.iddsn
								left  JOIN gol g ON g.id_gol=h.id_gol
								left  JOIN jab j ON j.id_jab=h.id_jab
								left JOIN pt p ON h.id_pt= p.id_pt
								LEFT JOIN dtk t ON t.idhistjab = h.idhistjab
								LEFT JOIN kegiatan k ON k.idkeg = t.idkeg
							WHERE
								d.iddsn= '.$_GET['iddsn'].' and h.status=1';
					// var_dump($sql);exit();
					$exe	= mysqli_query($con,$sql);
					$res	= mysqli_fetch_assoc($exe);
					// print_r($sql);exit();
	
					if(isset($res['gtot'])==''){$gtot=0;}else{$gtot=$res['gtot'];}
					if($exe){
						if($res['tglgols']=='0000-00-00' and $res['tgljabs']=='0000-00-00'){
							$tglgols = '-';$tgljabs = '-';
							$masagols =0;$masajabs =0;
						}else{
							$tglgols = tgl_indo5($res['tglgols']);$tgljabs= tgl_indo5($res['tgljabs']);
							$masagols= $res['masagols'];$masajabs= $res['masajabs'];
						}

						echo '{
								"kondisi":"sukses",
								"nip":"'.$res['nip'].'",
								"karpeg":"'.$res['karpeg'].'",
								"gelard":"'.$res['gelard'].'",
								"gelarb":"'.$res['gelarb'].'",
								"namad":"'.$res['namad'].'",
								"namab":"'.$res['namab'].'",
								"jk":"'.$res['jk'].'",
								"agama":"'.$res['agama'].'",
								"tl":"'.$res['tl'].'",
								"tgll":"'.tgl_indo($res['tgll']).'",
								"pt":"'.$res['pt'].'",
								"gols":"'.$res['gol'].'",
								"urutgols":"'.$res['urutan'].'",
								"jabs":"'.$res['jab'].'",
								"tgljabs":"'.$tgljabs.'",
								"tglgols":"'.$tglgols.'",
								"masajabs":"'.$masajabs.'",
								"masagols":"'.$masagols.'"
							}';
								// "gtot":"'.$gtot.'"
					}else{
						echo '{"status":"gagal_kueri_dsn"}';	
					}
				break;
			}
		break;
	} 
?>			
