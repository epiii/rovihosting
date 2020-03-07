<?php
	session_start();
	require_once '../../lib/koneksi.php';
	require_once '../../lib/pagination_class.php';
	require_once '../../lib/tglindo.php'; 
	//$itemKrg= array();
			
 	$aksi 	= isset($_GET['aksi'])?$_GET['aksi']:'';
	switch ($aksi){
		#target naik  -----------------------------------------
		case 'target' :
			$sql	=  'SELECT 	g.gol,
								g.pangkat,
								g.urutan as urutGol,
								r.id_gol,
								j.id_jab,
								r.point,
								r.id_pt,
								r.masa as masaGol,
								r.masaJab as masaJab,
								r.id_rule,
								p.pt,
								j.jab,
								j.urutan as urutJab
							FROM
								rule r,
								gol g,
								pt p,
								jab j
							WHERE
								r.id_gol     = g.id_gol AND
								r.id_pt 	= p.id_pt AND
								g.id_jab	= j.id_jab AND
								g.id_gol>'.$_GET['urutGolDt'].'
							ORDER BY
								g.urutan ASC';
			// print_r($sqltgt);exit();
			$gtotKum=0;
			$exe= mysqli_query($con,$sql);
			while ($res=mysqli_fetch_assoc($exe)) {
				$gtotKum+=$res['point'];
				$itemKrgx=array();

				#cek pend. tertinggi
				if($_GET['ptDt']<$res['pt']){
					$valKrg		= $res['pt'];
					$itemKrgx[]	= 'pendidikan terakhir minimal <b style="color:red;">'.$valKrg.'</b>';
				}

				//loop kategori kegiatan 
				$x=json_decode($_GET['subtotNumDtArr'],true);
				$sqlsub	= 'SELECT 	tipe,
									katkeg,
									idkatkeg,
									('.$gtotKum.' * subTotTgt  / 100 )as subTotTgtNum 
							FROM 
								katkeg 
							ORDER BY 
								idkatkeg ASC';

				$exesub = mysqli_query($con,$sqlsub);
				#cek subtotal per kategori
				while ($ressub=mysqli_fetch_assoc($exesub)) {
					if($x[$ressub['idkatkeg']]==0){ // kosong (0)
						if($ressub['tipe']=='mx'){ // tipe maximum 
							$valKrg		= abs($ressub['subTotTgtNum']-$x[$ressub['idkatkeg']]);
							$itemKrgx[]=$ressub['katkeg'].' minimal <b style="color:red;">1</b> poin maximal <b style="color:red;">'.$ressub['subTotTgtNum'].'</b> poin';
						}else{ // tipe : minimum
							$valKrg		= abs($ressub['subTotTgtNum']-$x[$ressub['idkatkeg']]);
							$itemKrgx[]=$ressub['katkeg'].' kurang <b style="color:red;">'.$valKrg.'</b> poin';
						}
					}else{// lebih dari 0 
						if($ressub['tipe']=='mn'){// jika tipe mn = minimal 
							if($x[$ressub['idkatkeg']]<$ressub['subTotTgtNum']){ // jika subtotal_data  kurang dari subtotal_target 
								$valKrg		= abs($ressub['subTotTgtNum']-$x[$ressub['idkatkeg']]);
								$itemKrgx[]=$ressub['katkeg'].' kurang <b style="color:red;">'.$valKrg.'</b> poin';
							}
						}
					}
				}

				#cek grand total 
				if($_GET['gtotNumDt']<$gtotKum){
					$valKrg		= round(abs($gtotKum-$_GET['gtotNumDt']),2);
					$itemKrgx[]	= 'grand total poin kurang <b style="color:red;">'.$valKrg.'</b>';
				}

				$jumKrg=count($itemKrgx);
				$notif[]=array(
						'idgoltgt'	=>$res['id_gol'],
						'idjabtgt'	=>$res['id_jab'],
						'goltgt'	=>$res['gol'],
						'pangkatTgt'=>$res['pangkat'],
						'jabtgt'	=>$res['jab'],
						'pttgt'		=>$res['pt'],
						'pointgt'	=>$res['point'],
						'masaGoltgt'=>$res['masaGol'],
						// 'masaJabtgt'=>$restgt['masaJab'],
						'jumKrg'	=>$jumKrg,
						'kurangan'	=>$itemKrgx
					);
			}
			echo json_encode($notif);
		break;

		#view rekap -----------------------------------------
		case 'view' :
			$sqldsn='SELECT
						d.*,
						(YEAR(curdate()) - YEAR(d.tgll)) AS umur,
						g.id_gol as id_golFung,g.gol as golFung ,g.urutan as urutGol,
						j.jab as jabFung,j.id_jab as id_jabFung,
						p.pt,
						h.tgljabs as tmtJab,
						h.tglgols as tmtGol,
					 	(YEAR (CURDATE()) - YEAR (h.tgljabs)) AS masaJab,
						(YEAR (CURDATE()) - YEAR (h.tglgols)) AS masaGol,
						sum(k.poin) AS gtot
					FROM
						dsn d
						left JOIN histjab h ON h.iddsn = d.iddsn
						left JOIN gol g ON g.id_gol= h.id_gol
						left JOIN jab j ON j.id_jab= h.id_jab
						left JOIN pt p ON h.id_pt= p.id_pt
						LEFT JOIN (
							select * from dtk where status="valid"
						)as t ON t.idhistjab = h.idhistjab
						LEFT JOIN kegiatan k ON k.idkeg = t.idkeg
					WHERE
						d.iduser= '.$_SESSION['iduser'].' and 
						h.status=1';
			$exedsn	= mysqli_query($con,$sqldsn);
			$resdsn	= mysqli_fetch_assoc($exedsn);
			// print_r($resdsn);exit();

		// 	//end of proses untuk biodata dosen ----
			// if($resdsn['tmtGol']=='0000-00-00' and $resdsn['tmtJab']=='0000-00-00'){
			if($resdsn['tmtGol']==null and $resdsn['tmtJab']==null){
				$tmtGol='-';$tmtJab='-';
				$masaGol=0;$masaJab=0;
			}else{
				$tmtGol=tgl_indo5($resdsn['tmtGol']);
				$tmtJab=tgl_indo5($resdsn['tmtJab']);
				$masaGol=$resdsn['masaGol'];
				$masaJab=$resdsn['masaJab'];
			}

		#loop kategori kegiatan ---------------------
    	$sqlkt = 'SELECT
					ktg.idkatkeg,
					ktg.katkeg,
					ktg.subTotTgt as subTotTgtPerc,
					ktg.tipe,
					ktg.cum,
					tbdtk.sisa AS subsisa,
					tbdtk.remain,
					tbpoin.poinCur,
					(tbdtk.remain+tbpoin.poinCur)as subtotNumDt
				FROM 
					katkeg ktg
					LEFT JOIN subkatkeg s ON s.id_katkeg= ktg.idkatkeg
					LEFT JOIN kegiatan k ON k.id_subkatkeg= s.id_subkatkeg
					LEFT JOIN dtk t ON t.idkeg = k.idkeg
					LEFT JOIN histjab h ON h.idhistjab = t.idhistjab
					LEFT JOIN dsn ON dsn.iddsn = h.iddsn 
					LEFT JOIN (

						SELECT 
							SUM(tbpoinx.poinCur)poinCur,
							tbpoinx.id_katkeg
						from (
								SELECT
									s.id_katkeg,
									k.idkeg,
									k.nakeg,
									dk.ket,
									k.poin,
									dk.sisa,
									dk.status,
									dk.sks,
									dk.jumAnggota,
									
								/*poin skrg*/
									CASE
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
									END AS poinCur
								/*poin skrg*/

								FROM
									dtk dk
									JOIN kegiatan k ON k.idkeg = dk.idkeg
									JOIN subkatkeg s ON s.id_subkatkeg = k.id_subkatkeg
									JOIN histjab h ON h.idhistjab = dk.idhistjab
									JOIN dsn d ON d.iddsn = h.iddsn
								WHERE
									h.`status` = 1 AND 
									d.iduser ='.$_SESSION['iduser'].' AND	(
										/*dk.`status`!="done" or(*/
										dk.`status`="valid" or(
											dk.status="done" AND dk.sisa!=0
										)
									) 
								ORDER BY
									dk.tglinput desc
							)tbpoinx
						GROUP BY 
						tbpoinx.id_katkeg
					)tbpoin on tbpoin.id_katkeg=ktg.idkatkeg 
					LEFT JOIN (
						SELECT
							katkeg.idkatkeg,
							tbds.poin as sisa,
							IFNULL(tbds.remain,0)remain
						FROM katkeg
							LEFT JOIN (
								SELECT 
									ds.iddtksisa,
									ds.idkatkeg,
									ds.poin,
									ds.remain
								FROM 
									dsn d
								LEFT JOIN histjab h on h.iddsn = d.iddsn
								LEFT JOIN dtksisa ds ON h.idhistjab = ds.idhistjab
								WHERE
									d.sisaStatus="valid" AND
									d.iduser='.$_SESSION['iduser'].'
							)
							tbds ON tbds.idkatkeg = katkeg.idkatkeg
					)tbdtk ON tbdtk.idkatkeg = ktg.idkatkeg
				GROUP BY 
					ktg.idkatkeg';
			// print_r($sqlkt);exit();
			$exekt 	= mysqli_query($con,$sqlkt);
			$jumkt 	= mysqli_num_rows($exekt);
			$katArr	= array();
			$gtotNumDt = 0;
			
			#kategori : kosong --------------------------------
			if($jumkt==0){
				//<div class="label label-important">kategori kegiatan kosong</div>
				$arrx[]=array('status'=>'kategori_kosong');
			}#end of kategori : kosong -------------------------
			#kategori : ada ------------------------------------
			else{
				#loop kategori ---------------------------------
				while($reskt= mysqli_fetch_assoc($exekt)){
					#$subsisa = ($reskt['subsisa']==null)?0:$reskt['subsisa'];
					$sqlkeg ='SELECT 
								tbpoinC.idkeg,
								tbpoinC.nakeg,
								tbpoinC.poin,
								tbpoinC.sisa,
								tbpoinC.status,
								sum(tbpoinC.poinCur)poinCur

							FROM (
								SELECT
									s.id_katkeg,
									k.idkeg,
									k.nakeg,
									dk.ket,
									k.poin,
									dk.sisa,
									dk.status,
									dk.sks,
									dk.jumAnggota,
									
								/*poin skrg*/
									CASE
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
									END AS poinCur
								/*poin skrg*/

								FROM
									dtk dk
									JOIN kegiatan k ON k.idkeg = dk.idkeg
									JOIN subkatkeg s ON s.id_subkatkeg = k.id_subkatkeg
									JOIN histjab h ON h.idhistjab = dk.idhistjab
									JOIN dsn d ON d.iddsn = h.iddsn
								WHERE
									h.`status` = 1 AND 
									d.iduser ='.$_SESSION['iduser'].' AND	(
										/*dk.`status`!="done" or(*/
										dk.`status`="valid" or(
											dk.status="done" AND dk.sisa!=0
										)
									) AND
									s.id_katkeg ='.$reskt['idkatkeg'].'
									
								ORDER BY 
									dk.tglinput desc
							)tbpoinC

							GROUP BY 
								tbpoinC.idkeg';
					// print_r($sqlkeg);exit(); 	 	
					$exekeg = mysqli_query($con,$sqlkeg);
					$jum 	= mysqli_num_rows($exekeg);
					$kegArr = array();
					$subtotDt	= 0;
					
					#loop item kegiatan (per kategori)------------------
					while($reskeg=mysqli_fetch_assoc($exekeg)){
						$kegArr[]=$reskeg;
					}#end of loop item kegiatan (per kategori)------------------
					// $subtotNumDt=($)	
					$ruwet = base64_encode($_SESSION['idsesi'].$_SESSION['iduser'].$reskt['cum'].$_SESSION['idsesi']);
					$katArr[]=array(
							'idkatkeg'=>$reskt['idkatkeg'],
							'katkeg'=>$reskt['katkeg'],
							'subTotTgtPerc'=>$reskt['subTotTgtPerc'],
							// 'subTotTgtNum'=>$reskt['subTotTgtNum'],
							'tipe'=>$reskt['tipe'],
							'cum'=>$reskt['cum'],
							'subsisa'=>$reskt['subsisa'],
							'remain'=>$reskt['remain'],
							'ruwet'=>$ruwet,
							'subtotNumDt'=>$reskt['subtotNumDt'],
							'kegArr'=>$kegArr
						);
					$gtotNumDt=$gtotNumDt+$reskt['subtotNumDt'];
				}#end of loop kategori ---------------------------------
			}#kategori : ada ------------------------------------
			// print_r($katArr);exit();

		#end of loop kategori kegiatan ---------------------
			echo '{
					"nip":"'.$resdsn['nip'].'",
					"pt":"'.$resdsn['pt'].'",
					"nm":"'.$resdsn['gelard'].' '.$resdsn['namad'].' '.$resdsn['namab'].' '.$resdsn['gelarb'].'",
					"agm":"'.$resdsn['agama'].'",
					"jk":"'.$resdsn['jk'].'",
					"tl":"'.$resdsn['tl'].'",
					"tgll":"'.tgl_indo($resdsn['tgll']).'",
					"golFung":"'.$resdsn['golFung'].'",
					"jabFung":"'.$resdsn['jabFung'].'",
					"tmtGol":"'.$tmtGol.'",
					"tmtJab":"'.$tmtJab.'",
					"masaGol":"'.$masaGol.'",
					"masaJab":"'.$masaJab.'",
					"urutGol":"'.$resdsn['urutGol'].'",
					"gtotNumDt":'.$gtotNumDt.',
					"katArr":'.json_encode($katArr).'
				}';
				// "gtot":"'.$resdsn['gtot'].'"
		// 	//end of target terpilih -----------------------
		break;
		// #end of bio =======================================================================================

	}
?>