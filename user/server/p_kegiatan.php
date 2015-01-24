	<?php
		session_start();
		require_once '../../lib/koneksi.php';
		require_once '../../lib/pagination_class.php';
		require_once '../../lib/tglindo.php'; 
		require_once '../../lib/filter.php'; 
		
	 	$aksi 	=  isset($_GET['aksi'])?$_GET['aksi']:'';
		$menu	=  isset($_GET['menu'])?$_GET['menu']:'';

		$dir	= '../../upload/bukeg/';	
		switch ($aksi){
			case 'tabkatkeg':
				// $sql = 'SELECT * from katkeg order by cum asc';
				$sql = 'SELECT 
							kk.idkatkeg,
							kk.cum,
							kk.katkeg, 
							IFNULL(tbdk.jum, 0)jum
					
						FROM katkeg kk LEFT JOIN (
							SELECT
								s.id_katkeg,COUNT(*)jum
							FROM
								dtk dk
							JOIN kegiatan k ON k.idkeg = dk.idkeg
							JOIN subkatkeg s ON s.id_subkatkeg = k.id_subkatkeg
							JOIN histjab h ON h.idhistjab = dk.idhistjab
							JOIN dsn d ON d.iddsn = h.iddsn
							WHERE
								h.`status` = 1 AND 
								d.iduser = '.$_SESSION['iduser'].' AND 
								dk.`status` = "checked"
							GROUP BY s.id_katkeg
						)tbdk on tbdk.id_katkeg = kk.idkatkeg';
				$exe = mysql_query($sql);
				$data= array();
				
				while ($res=mysql_fetch_assoc($exe)) {
					$data[]=$res;
				}

				if (!$exe) {
					$out='{"status":"error db"}';
				} else {
					if ($data!=NULL) {
						$out=json_encode($data);
					} else {
						$out='{"status":"kosong"}';
					}
				}
				echo $out;
			break;

	#save : sisa poin sebelumnya ============================================================================================================
			case 'saveSisa':
				if(isset($_POST['sisaTB'])){
					if(isset($_POST['iddtksisaTB'])){ //edit
						$sql='UPDATE ';
						$sql2=' WHERE iddtksisa ='.$_POST['iddtksisaTB']; 
					}else{ //add
						$sql='INSERT into '; 	
						$sql2=' ,idkatkeg='.$_POST['idkatkegTB'].',
								idhistjab= (
									SELECT
										h.idhistjab
									FROM
										histjab h join dsn d on d.iddsn = h.iddsn
									WHERE
										d.iduser = '.$_SESSION['iduser'].'
								)';
					}

					$sisa = trim(mysql_real_escape_string($_POST['sisaTB']));
					$sql.=' dtksisa set poin='.$sisa.', remain='.$sisa.$sql2;
					// print_r($sql);exit();
					$exe=mysql_query($sql);
					
					if($exe){
						$sql2 = 'UPDATE dsn set sisaStatus="invalid" where iduser='.$_SESSION['iduser'];
						$exe2 = mysql_query($sql2);
						if($exe2)
							echo '{"status":"sukses"}';
					}else{
						echo '{"status":"gagal"}';
					}
				}
			break;
	#end of save : sisa poin sebelumnya ============================================================================================================

	#view : sisa poin sebelumnya ============================================================================================================
			case 'viewSisa':
				$sq = 'SELECT
							count(*)jum
						FROM
							(
								SELECT
									h.idhistjab
								FROM
									dtksisa ds
									JOIN histjab h ON h.idhistjab = ds.idhistjab
									JOIN dsn d ON d.iddsn = h.iddsn
								WHERE
									d.iduser = '.$_SESSION['iduser'].'
								GROUP BY
									h.idhistjab
							) tbdh ';
				$re = mysql_fetch_assoc(mysql_query($sq));

				$sq2 = 'SELECT
							COUNT(*)jum2
						FROM
							histjab h
							JOIN dsn d ON d.iddsn = h.iddsn
						WHERE
							d.iduser = '.$_SESSION['iduser'];
				$re2 = mysql_fetch_assoc(mysql_query($sq2));

				if($re['jum']<1){ // tidak pernah  punya sisa dan ... 
					if ($re2['jum2']>1) { // pernah naik pngkat
						$punya = 'ny';
					}else{ // belum naik pangkat
						$punya = 'nn';
					}
				}else{ // pernah punya sisa dan ...
					if($re2['jum2']>1){ // pernah naik pangkat
						$punya = 'yy';
					}else{ // belum naik pangkat
						$punya = 'yn';
					}
				}

				// print_r($punya);exit();
				$sql = 'SELECT sisaStatus from dsn where iduser='.$_SESSION['iduser'];
				$exe = mysql_query($sql);
				$res = mysql_fetch_assoc($exe);
				// print_r($res);exit();

				$sql2 = 'SELECT 
							tbds.iddtksisa,
							katkeg.idkatkeg,
							katkeg.katkeg,
							tbds.poin,
							tbds.remain
						FROM
							katkeg
						LEFT JOIN (
							SELECT
								ds.iddtksisa,
								ds.idkatkeg,
								ds.poin,
								ds.remain
							FROM
								dsn d
							LEFT JOIN histjab h ON h.iddsn = d.iddsn
							LEFT JOIN dtksisa ds ON h.idhistjab = ds.idhistjab
							WHERE
								d.iduser = '.$_SESSION['iduser'].' AND
								h.status=1
						) tbds ON tbds.idkatkeg = katkeg.idkatkeg';
				// print_r($sql2);exit();
				$exe2 = mysql_query($sql2);
				$sisaArr =array();
				while ($res2=mysql_fetch_assoc($exe2)) {
					$sisa1 = ($res2['poin']==null)?0:$res2['poin'];
					$sisa2 = $res2['poin'];
					$sisaArr[]=array(
								'iddtksisa'=>$res2['iddtksisa'],
								'idkatkeg'=>$res2['idkatkeg'],
								'katkeg'=>$res2['katkeg'],
								'remain'=>$res2['remain'],
								'sisa1'=>$sisa1,
								'sisa2'=>$sisa2
							);
				}

				if($sisaArr!=NULL){
					echo '{
						"punya":"'.$punya.'",
						"sisaStatus":"'.$res['sisaStatus'].'",
						"sisaArr":'.json_encode($sisaArr).'
					}';
				}else{
					echo '{"status":"gagal"}';
				}
			break;
	#end of view : sisa poin sebelumnya ============================================================================================================

	#ambiledit : kegiatan ============================================================================================================
			case 'ambiledit':
				$sql	=  'SELECT
								dk.iddtk,
								s.id_katkeg,
								k.idkeg,
								dk.ket,
								k.poin,
								k.batut,
								k.bukeg,
								dk.sisa,
								dk.sks,
								dk.jumAnggota,
								dk.status,
								dk.tglinput,
								dk.waktu,
								dk.tempat,
								/*poin skrg*/
								CASE
									WHEN dk.sisa > 0 THEN
										dk.sisa
									ELSE
										CASE
											WHEN isGroup = "y" THEN /*grup : kelompok*/
												CASE
											WHEN isLeader = "y" THEN /*ketua*/
												(k.poin * 60 / 100)
											ELSE/*anggota*/
												(
													(k.poin * 40 / 100) / dk.jumAnggota
												)
										END
									WHEN isGroup = "n" THEN /*grup : individu*/
										k.poin
									ELSE
										CASE
											WHEN dk.sks != 0 THEN	/*penjar : kuliah*/
												(
													k.poin * dk.sks / dk.jumAnggota
												)
											ELSE
												/*penjar : none, pengab, penunj*/
												k.poin
											END
										END
								END AS poinCur /*poin skrg*/
							FROM
								dtk dk
								JOIN kegiatan k ON k.idkeg = dk.idkeg
								JOIN subkatkeg s ON s.id_subkatkeg = k.id_subkatkeg
								JOIN histjab h ON h.idhistjab = dk.idhistjab
								JOIN dsn d ON d.iddsn = h.iddsn
							WHERE
								dk.iddtk = '.$_GET['iddtk'].'
							ORDER BY
								dk.tglinput DESC';
				// print_r($sql);exit();
				$exe	= mysql_query($sql);
				$res	= mysql_fetch_assoc($exe);

				if($exe){
					$bukegArr = array();
					$sql2 = 'SELECT * from bukeg where iddtk = '.$_GET['iddtk'].' order by  idbukeg desc';
					// print_r($sql2);exit();
					$exe2 = mysql_query($sql2);
					while($res2 = mysql_fetch_assoc($exe2)){
						$bukegArr[] = $res2;
					}

					if($bukegArr!=NULL){
						echo '{"kondisi":"sukses",
							"idkeg":"'.$res['idkeg'].'",
							"id_katkeg":"'.$res['id_katkeg'].'",
							"poin":"'.$res['poin'].'",
							"poinCur":"'.$res['poinCur'].'",
							"batut":"'.$res['batut'].'",
							"ket":"'.filterx($res['ket']).'",
							"bukeg":"'.$res['bukeg'].'",
							"waktu":"'.$res['waktu'].'",
							"tempat":"'.$res['tempat'].'",
							"bukegArr":'.json_encode($bukegArr).'}';
							// "ket":"'.mysql_real_escape_string(stripslashes(htmlspecialchars($res['ket'],ENT_QUOTES))).'",

					}else{
						echo '{"status":"bukeg_kosong"}';
					}
				}else{
					echo '{"status":"kegiatan_kosong"}';	
				}
			break;
	#end of ambiledit : kegiatan  ============================================================================================================

	#hapus =====================================================================================================================
			case 'hapus':
				switch($menu){
			#hapus : kegiatan ----------------------------------------------------------------
					case 'dtk':
						$sqlz	= 'SELECT * from bukeg where iddtk='.$_GET['iddtk'];
						$exez	= mysql_query($sqlz);
						$jumz	= mysql_num_rows($exez);

						if($jumz>0){
							while($resz=mysql_fetch_assoc($exez)){
								$linkx = $dir.$resz['file'];
								unlink($linkx);
							}
						}
						
						$sql1 	= 'DELETE from dtk where iddtk ='.$_GET['iddtk'];
						$exe1	= mysql_query($sql1);
						if($exe1){
							echo '{"status":"sukses","iddtk":"'.$_GET['iddtk'].'"}';
						}else{
							echo '{"status":"gagal"}';	
						}
					break;
			#end of hapus : kegiatan ----------------------------------------------------------------
			
			#hapus : bukeg ----------------------------------------------------------------
					case 'bukeg':
						$sql1 	= 'DELETE from bukeg where file ="'.$_GET['file'].'"';
						$exe1	= mysql_query($sql1);

						if($exe1){
							$linkx = $dir.$_GET['file'];
							unlink($linkx);
							echo '{"status":"sukses"}';
						}else{
							echo '{"status":"gagal"}';	
						}
					break;
			#hapus : bukeg ----------------------------------------------------------------
				}
			break;
	#end of hapus ==============================================================================================================
				
	#combo ======================================================================================================================
			case 'combo':
				switch($menu){
			#combo : katkeg ---------------------------------------------------------------
					case 'katkeg':
						// $sqlU = 'SELECT 
						// 			k.idkeg, 
						// 			k.nakeg
						// 		from 
						// 			katkeg t, kegiatan k 
						// 		where 
						// 			t.idkatkeg 	= k.katkeg and 
						// 			k.katkeg 	= '.$_GET['idkatkeg'].' and 
						// 			k.idkeg not in (
						// 				SELECT d.idkeg 
						// 				from 
						// 					dtk d, dsn s, user u,histjab h
						// 				where 
						// 					h.idhistjab  = d.idhistjab and
						// 					s.iduser = u.iduser and 
						// 					u.iduser = '.$_SESSION['iduser'].' and
						// 					h.iddsn	 = s.iddsn
						// 				group by d.idkeg
						// 			)';
						// if($_GET['idform']==''){ // add
						// 	$sql = $sqlU;
						// }else{ //edit
						// 	$sql = $sqlU.' UNION 
						// 					select d.idkeg, k.nakeg
						// 					from dtk d, kegiatan k
						// 					where 
						// 						d.iddtk = '.$_GET['idform'].' and
						// 						d.idkeg = k.idkeg';
						// }

						$sql = 'SELECT
									subkatkeg.id_subkatkeg,
									IFNULL(subkatkeg.subkatkeg, "-") as subkatkeg,
									IFNULL(subkatkeg.dsubkatkeg, "-") as dsubkatkeg
								FROM
									katkeg
								LEFT JOIN subkatkeg ON subkatkeg.id_katkeg = katkeg.idkatkeg
								WHERE
									katkeg.idkatkeg ='.$_GET['id_katkeg'];
						// if($_GET['idform']==''){ // add
						// 	$sql = $sqlU;
						// }else{ //edit
						// 	$sql = $sqlU.' UNION 
						// 					select d.idkeg, k.nakeg
						// 					from dtk d, kegiatan k
						// 					where 
						// 						d.iddtk = '.$_GET['idform'].' and
						// 						d.idkeg = k.idkeg';
						// }
						// print_r($sql);exit();
						$exe	= mysql_query($sql);
						$subkatkeg	= array();
						
						while($res=mysql_fetch_assoc($exe)){
							$keg=array();
							$sql2 = 'SELECT 
										kegiatan.idkeg, kegiatan.nakeg
									from 
										kegiatan 
										JOIN subkatkeg on subkatkeg.id_subkatkeg = kegiatan.id_subkatkeg
									where subkatkeg.id_subkatkeg = '.$res['id_subkatkeg'];
							$exe2 = mysql_query($sql2);
							while ($res2=mysql_fetch_assoc($exe2)) {
								$keg[]=$res2;
							}
							$subkatkeg[]=array(
									'id_subkatkeg'=>$res['id_subkatkeg'],
									'subkatkeg'=>$res['subkatkeg'],
									'dsubkatkeg'=>$res['dsubkatkeg'],
									'keg'=>$keg
								);
						}
						// print_r($subkatkeg);exit();

						if($subkatkeg!=NULL){
							echo json_encode($subkatkeg);
						}else{
							echo '{"status":"gagal"}';
						}
					break;
			#end  of combo : katkeg ---------------------------------------------------------------
					
			#combo : nakeg ------------------------------------------------------------------------
					case 'nakeg':
						//edit mode
						if(isset($_GET['iddtk']) and  !empty($_GET['iddtk'])){ 
							$sql = 'SELECT
									kt.cum,
									k.idkeg,
									s.subkatkeg,
									k.batut,
									k.bukeg,
									dk.jumAnggota,
									dk.isGroup,
									dk.isLeader,
									dk.sisa,
									dk.waktu,
									dk.tempat,
									dk.jabatan,
									dk.sks,
									k.poin,
										CASE
											WHEN dk.sisa > 0 THEN dk.sisa
											ELSE
												CASE
													WHEN isGroup = "y" THEN /*grup : kelompok*/
														CASE
															WHEN isLeader = "y" THEN (k.poin * 60 / 100) /*ketua*/
															ELSE((k.poin * 40 / 100) / dk.jumAnggota) /*anggota*/
														END
													WHEN isGroup = "n" THEN k.poin /*grup : individu*/ 
													ELSE
														CASE
															WHEN dk.sks != 0 THEN	(k.poin * dk.sks / dk.jumAnggota) /*penjar : kuliah*/
															ELSE k.poin /*penjar : none, pengab, penunj*/
														END
													END
												END AS poinCur /*poin skrg*/
								FROM
									dtk dk
										JOIN kegiatan k ON k.idkeg = dk.idkeg
										JOIN subkatkeg s ON s.id_subkatkeg = k.id_subkatkeg
										JOIN katkeg kt ON kt.idkatkeg = s.id_katkeg
								WHERE
									dk.iddtk  ='.$_GET['iddtk'];
						}
						//add mode
						else{
							$sql = 'SELECT
										kt.cum,
										k.idkeg,
										s.subkatkeg,
										k.batut,
										k.bukeg,
										k.poin
								FROM
									 kegiatan k 
									 JOIN subkatkeg s on s.id_subkatkeg = k.id_subkatkeg 
									 JOIN katkeg kt on kt.idkatkeg =  s.id_katkeg
								where 
									k.idkeg  ='.$_GET['idkeg'];
						}
						// print_r($res);exit();	
						
						$exe	= mysql_query($sql);
						$res 	= mysql_fetch_assoc($exe);
							$isGroup = isset($res['isGroup'])?$res['isGroup']:'';
							$isLeader = isset($res['isLeader'])?$res['isLeader']:'';
							$jumAnggota = isset($res['jumAnggota'])?$res['jumAnggota']:'';
							$waktu = isset($res['waktu'])?$res['waktu']:'';
							$tempat = isset($res['tempat'])?$res['tempat']:'';
							$poinCur = isset($res['poinCur'])?$res['poinCur']:'';
							$sks = isset($res['sks'])?$res['sks']:'';
							$jabatan = isset($res['jabatan'])?$res['jabatan']:'';

						if($exe){
							echo '{
								"cum":"'.$res['cum'].'",
								"subkatkeg":"'.$res['subkatkeg'].'",
								"waktu":"'.$waktu.'",
								"tempat":"'.$tempat.'",
								"batut":"'.$res['batut'].'",
								"bukeg":"'.$res['bukeg'].'",
								"isGroup":"'.$isGroup.'",
								"isLeader":"'.$isLeader.'",
								"jabatan":"'.$jabatan.'",
								"jumAnggota":"'.$jumAnggota.'",
								"poin":"'.$res['poin'].'",
								"sks":"'.$sks.'",
								"poinCur":"'.$poinCur.'"
							}';
						}else{
							echo '{"status":"gagal"}';
						}
					break;
			#end of combo : nakeg ------------------------------------------------------------------------
				}
			break;
	#end of combo ======================================================================================================================
			
	#view kegiatan ======================================================================================================================
			case 'tampil' :
				$status =($_GET['statusS']!='')?' AND dk.status ="'.$_GET['statusS'].'"':'';
				$kegiatan =(isset($_GET['kegiatanS'])!='')?' AND dk.ket like "%'.$_GET['kegiatanS'].'%"':'';
				$subunsur =(isset($_GET['subunsurS'])!='')?' AND k.nakeg like "%'.$_GET['subunsurS'].'%"':'';
				$poin 	  =(isset($_GET['poinS'])!='')?' poinCur like "%'.$_GET['poinS'].'%"':'';

				$sql = 'SELECT * FROM (
						SELECT
							dk.iddtk,
							s.id_katkeg,
							k.nakeg,
							dk.ket,
							k.poin,
							dk.sisa,
							dk.sks,
							dk.jumAnggota,
							dk.status,
							dk.tglinput,
							
						/*poin skrg*/
							round(CASE
								WHEN dk.sisa > 0 THEN
									dk.sisa
								ELSE
									CASE
										WHEN isGroup="y" then  /*grup : kelompok*/
											CASE
												WHEN isLeader="y" then /*ketua*/
													(k.poin * 60 /100)
												ELSE 									/*anggota*/
													((k.poin * 40 / 100)/dk.jumAnggota)
											END
										WHEN isGroup="n" THEN  /*grup : individu*/
											k.poin
										ELSE
											CASE
												WHEN dk.sks!=0 THEN /*penjar : kuliah*/
													(k.poin * dk.sks / dk.jumAnggota)
												ELSE /*penjar : none, pengab, penunj*/
													k.poin
											END
									END
							END,2) AS poinCur
						/*poin skrg*/

						FROM
							dtk dk
							JOIN kegiatan k ON k.idkeg = dk.idkeg
							JOIN subkatkeg s ON s.id_subkatkeg = k.id_subkatkeg
							JOIN histjab h ON h.idhistjab = dk.idhistjab
							JOIN dsn d ON d.iddsn = h.iddsn
						WHERE
							h.`status` = 1 AND 
							d.iduser = '.$_SESSION['iduser'].' AND	(
								dk.`status`!="done" or(
									dk.status="done" AND dk.sisa!=0
								)
							) AND
							s.id_katkeg ='.$_GET['menu'].' '.$status.' '.$subunsur.' '.$kegiatan.'						
						ORDER BY 
							dk.status asc, 
							dk.tglinput desc) tb 
					WHERE '.$poin;
				// print_r($sql);exit();
				if(isset($_GET['starting'])){ //nilai awal halaman
					$starting=$_GET['starting'];
				}else{
					$starting=0;
				}
				
				//record per halaman
				$recpage= 10;//jumlah data per halaman
				$obj 	= new pagination_class($_GET['menu'],$sql,$starting,$recpage);
				$result =$obj->result;
				#end of paging	 
				
				#ada data
				if(mysql_num_rows($result)!=0){
					$nox 	= $starting+1;
					while($res = mysql_fetch_array($result)){
						if($res['status']=='new'){ //belum divalidasi
							$info	= 'pending';
							$kegTR	= 'infox success';
							$statusx= '<i class="icon-time"></i>';
							$btn 	='<td>
										<a class="btn" href="javascript:hapusKeg('.$res['iddtk'].',\''.$res['id_katkeg'].'\');" 
										 role="button"><i class="icon-remove"></i></a>
									 </td>
									 <td>
										 <a class="btn" href="javascript:editKeg('.$res['iddtk'].',\''.$res['id_katkeg'].'\');" 
										 role="button"><i class="icon-pencil"></i></a>
									 </td>';
						}elseif($res['status']=='checked'){ //data tidak valid
							$info	= 'tidak valid';
							$kegTR	= 'infox warning';
							$statusx= '<i class="icon-warning-sign"></i>';
							$btn ='<td>
									<a class="btn" href="javascript:hapusKeg('.$res['iddtk'].',\''.$res['id_katkeg'].'\');" 
									 role="button"><i class="icon-remove"></i></a>
								 </td>
								 <td>
									 <a class="btn" href="javascript:editKeg('.$res['iddtk'].',\''.$res['id_katkeg'].'\');" 
									 role="button"><i class="icon-pencil"></i></a>
								 </td>';
						}elseif($res["status"]=="valid"){ //sudah divalidasi 
							$info	='valid';
							$kegTR	='infox info';
							$statusx='<i class="icon-ok"></i>';
							$btn ='<td>
									<a class="btn" href="javascript:hapusKeg('.$res['iddtk'].',\''.$res['id_katkeg'].'\');" 
									 role="button"><i class="icon-remove"></i></a>
								 </td>
								 <td>
									 <a class="btn" href="javascript:editKeg('.$res['iddtk'].',\''.$res['id_katkeg'].'\');" 
									 role="button"><i class="icon-pencil"></i></a>
								 </td>';
						}
						else{ // sudah di acc admin / naik pangkat
							$info	='lolos untuk kenaikan pangkat';
							$kegTR	='infox info';
							$statusx='<i class="icon-lock "></i>';
							$btn ='
								 <td>
									 <a class="btn" href="javascript:editKeg('.$res['iddtk'].',\''.$res['id_katkeg'].'\');" 
									 role="button"><i class="icon-search"></i></a>
								 </td>';
						}
						
						echo '<tr class="'.$kegTR.'" onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="left" title="'.$info.'">
								<td><label class="control-label">'.$statusx.'</label></td>
								<td><label class="control-label">'.filterx($res['ket']).'</label></td>
								<td><label class="control-label">'.filterx($res['nakeg']).'</label></td>
								<td><label class="control-label">'.filterx($res['poinCur']).'</label></td>
								<!--<td><label class="control-label">'.tgl_indo($res['tglinput']).'</label></td>-->
								'.$btn.'
							</tr>';
	                	$nox++;
					}
				}else{ #kosong
					echo '<tr>
							<td  colspan="7">
								<span  class="label label-important">... data masih kosong...</span>
							</td>
						</tr>';
				}
				#link paging
				echo '<tr class="info"><td colspan="7">'.$obj->anchors.'</td></tr>';
				echo '<tr class="info"><td colspan="7">'.$obj->total.'</td></tr>';
			break;
	#end of view kegiatan ======================================================================================================================
		}	
	?>			
