<?php
	// session_start();
	require_once"../../lib/sesi.php";
	require_once"../../lib/koneksi.php";
	require_once"../../lib/pagination_class.php";
	require_once"../../lib/tglindo.php"; 
	require_once"./f_hitung.php";
	$itemKrg= array();
	
	$aksi 	=  isset($_GET['aksi'])?$_GET['aksi']:'';
	$menu	=  isset($_GET['menu'])?$_GET['menu']:'';
	

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
								r.id_gol 	= g.id_gol AND
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

				#cek pend. terakhir
				if($_GET['ptDt']<$res['pt']){
					$valKrg		= $res['pt'];
					$itemKrgx[]	= 'pendidikan terakhir minimal <b style="color:red;">'.$valKrg.'</b>';
				}

				//loop kategori kegiatan 
				$x=json_decode($_GET['subnotarr'],true);
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
				if($_GET['gtotnot']<$gtotKum){
					$valKrg		= round(abs($gtotKum-$_GET['gtotnot']),2);
					$itemKrgx[]	= 'Grand Total poin kurang <b style="color:red;">'.$valKrg.'</b>';
				}


				$jumKrg=count($itemKrgx);
				$notif[]=array(
						'idgoltgt'	=>$res['id_gol'],
						'idjabtgt'	=>$res['id_jab'],
						'id_pt'		=>$res['id_pt'],
						'pangkatTgt'=>$res['pangkat'],
						'goltgt'	=>$res['gol'],
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
						p.pt,p.id_pt,
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
						d.iddsn= '.$_GET['iddsn'].' and 
						h.status=1';
			// print_r($resdsn);exit();
			$exedsn	= mysqli_query($con,$sqldsn);
			$resdsn	= mysqli_fetch_assoc($exedsn);

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
		//update status belum  dicek / sudah
			$sqlst = 'UPDATE dsn set baru =0 WHERE iddsn='.$_GET['iddsn'];
			$exest = mysqli_query($con,$sqlst);

		#loop kat u/ notif ---------------------
			// $snot ='SELECT 
			// 			tbpoinC.id_katkeg,
			// 			round(sum(tbpoinC.poinCur) + tbsisa.remain,2 )subnot

			// 		FROM (
			// 				SELECT
			// 					s.id_katkeg,
			// 					CASE
			// 						WHEN dk.sisa > 0 THEN
			// 							dk.sisa
			// 						ELSE
			// 							CASE
			// 								WHEN isGroup="y" then  
			// 									CASE
			// 										WHEN isLeader="y" then 
			// 											(k.poin * 60 /100)
			// 										ELSE 									
			// 											((k.poin * 40 / 100)/dk.jumAnggota)
			// 									END
			// 								WHEN isGroup="n" THEN  
			// 									k.poin
			// 								ELSE
			// 									CASE
			// 										WHEN dk.sks!=0 THEN 
			// 											(k.poin * dk.sks / dk.jumAnggota)
			// 										ELSE 
			// 											k.poin
			// 									END
			// 							END
			// 					END AS poinCur

			// 				FROM
			// 					dtk dk
			// 					JOIN kegiatan k ON k.idkeg = dk.idkeg
			// 					JOIN subkatkeg s ON s.id_subkatkeg = k.id_subkatkeg
			// 					JOIN histjab h ON h.idhistjab = dk.idhistjab
			// 					JOIN dsn d ON d.iddsn = h.iddsn
			// 				WHERE
			// 					h.`status` = 1 AND 
			// 					d.iddsn=35 AND	(
			// 						dk.`status`="valid" or(
			// 							dk.status="done" AND dk.sisa!=0
			// 						)
			// 					) 
			// 			)tbpoinC 
			// 			join (
			// 			SELECT
			// 				katkeg.idkatkeg,
			// 				tbds.poin as sisa,
			// 				IFNULL(tbds.remain,0)remain
			// 			FROM katkeg
			// 				LEFT JOIN (
			// 					SELECT 
			// 						ds.iddtksisa,
			// 						ds.idkatkeg,
			// 						ds.poin,
			// 						ds.remain
			// 					FROM 
			// 						dsn d
			// 					LEFT JOIN histjab h on h.iddsn = d.iddsn
			// 					LEFT JOIN dtksisa ds ON h.idhistjab = ds.idhistjab
			// 					WHERE
			// 						d.sisaStatus="valid" AND
			// 						d.iddsn='.$_GET['iddsn'].'
			// 				)
			// 				tbds ON tbds.idkatkeg 	= katkeg.idkatkeg
			// 			)tbsisa on tbsisa.idkatkeg 	= tbpoinC.id_katkeg
			// 			GROUP BY tbpoinC.id_katkeg';
			// $enot  		= mysqli_query($con,$snot);
			// $subnot 	= array();
			// $gtotnot 	= 0;
			
			// #loop item kegiatan (per kategori)------------------
			// while($rnot=mysqli_fetch_assoc($enot)){
			// 	$subnot[]=$rnot;
			// 	$gtotnot+=$rnot['subnot'];
			// }#end of loop item kegiatan (per kategori)------------------
			
			// print_r($subkegnot);exit();
		#end of loop kat u/ notif ---------------------

		#loop kat u/ tampil
			$sqlkt='SELECT
						ktg.idkatkeg,
						ktg.katkeg,
						ktg.subTotTgt as subTotTgtPerc,
						ktg.tipe,
						ktg.cum,
						tbdtk.sisa AS subsisa,
						round(tbdtk.remain,2)AS remain,
						tbpoin.poinCur,
						round((tbnot.poinValid + tbdtk.remain),2)as subnot,
						round((tbdtk.remain+tbpoin.poinCur),2)as subtotNumDt
					FROM 
						katkeg ktg
						LEFT JOIN subkatkeg s ON s.id_katkeg= ktg.idkatkeg
						LEFT JOIN kegiatan k ON k.id_subkatkeg= s.id_subkatkeg
						LEFT JOIN dtk t ON t.idkeg = k.idkeg
						LEFT JOIN histjab h ON h.idhistjab = t.idhistjab
						LEFT JOIN dsn ON dsn.iddsn = h.iddsn 
						LEFT JOIN (
							SELECT
								s.id_katkeg,
								sum(CASE
									WHEN dk.sisa > 0 THEN
										dk.sisa
									ELSE
										CASE
											WHEN isGroup="y" then  
												CASE
													WHEN isLeader="y" then 
														(k.poin * 60 /100)
													ELSE 									
														((k.poin * 40 / 100)/dk.jumAnggota)
												END
											WHEN isGroup="n" THEN  
												k.poin
											ELSE
												CASE
													WHEN dk.sks!=0 THEN 
														(k.poin * dk.sks / dk.jumAnggota)
													ELSE 
														k.poin
												END
										END
								END) AS poinValid
							FROM
								dtk dk
								JOIN kegiatan k ON k.idkeg = dk.idkeg
								JOIN subkatkeg s ON s.id_subkatkeg = k.id_subkatkeg
								JOIN histjab h ON h.idhistjab = dk.idhistjab
								JOIN dsn d ON d.iddsn = h.iddsn
							WHERE
								h.`status` = 1 AND 
								d.iddsn='.$_GET['iddsn'].' AND	(
									dk.`status`="valid" or(
										dk.status="done" AND dk.sisa!=0
									)
								)
							GROUP BY
								s.id_katkeg
						)tbnot on tbnot.id_katkeg = ktg.idkatkeg
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
										d.iddsn ='.$_GET['iddsn'].' AND	(
											dk.`status`!="done" or(
											/*dk.`status`="valid" or(*/
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
											d.iddsn='.$_GET['iddsn'].'
								)tbds ON tbds.idkatkeg = katkeg.idkatkeg
						)tbdtk ON tbdtk.idkatkeg = ktg.idkatkeg
					GROUP BY 
						ktg.idkatkeg';
			$exekt 	= mysqli_query($con,$sqlkt);
			$jumkt 	= mysqli_num_rows($exekt);
			// print_r($exekt);exit();
			
			$katArr    = array();
			$gtotNumDt = 0;
			$gtotnot   = 0;
			
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
								tbpoinC.iddtk,
								tbpoinC.idkeg,
								tbpoinC.ket,
								tbpoinC.nakeg,
								tbpoinC.poin,
								tbpoinC.sisa,
								tbpoinC.status,
								tbpoinC.poinCur

							FROM (
								SELECT
									s.id_katkeg,
									dk.iddtk,
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
									d.iddsn='.$_GET['iddsn'].' AND	(
										dk.`status`!="done" or(
										/*dk.`status`="valid" or(*/
											dk.status="done" AND dk.sisa!=0
										)
									) AND
									s.id_katkeg ='.$reskt['idkatkeg'].'
									
								ORDER BY 
									dk.tglinput desc
							)tbpoinC';
					// print_r($sqlkeg);exit();
					$exekeg = mysqli_query($con,$sqlkeg);
					$jum 	= mysqli_num_rows($exekeg);
					$kegArr = array();
					$subtotDt	= 0;
					
					#loop item kegiatan (per kategori)------------------
					while($reskeg=mysqli_fetch_assoc($exekeg)){
						$kegArr[]=$reskeg;
					}#end of loop item kegiatan (per kategori)------------------

					$ruwet = base64_encode($_SESSION['idsesi'].$_SESSION['iduser'].$reskt['cum'].$_SESSION['idsesi']);
					$katArr[]=array(
							'idkatkeg'		=>$reskt['idkatkeg'],
							'katkeg'		=>$reskt['katkeg'],
							'subTotTgtPerc'	=>$reskt['subTotTgtPerc'],
							'tipe'			=>$reskt['tipe'],
							'cum'			=>$reskt['cum'],
							'subsisa'		=>$reskt['subsisa'],
							'remain'		=>$reskt['remain'],
							'ruwet'			=>$ruwet,
							'subtotNumDt'	=>$reskt['subtotNumDt'],
							'subnot' 		=>$reskt['subnot'],
							'kegArr'		=>$kegArr
						);
						// 'subTotTgtNum'=>$reskt['subTotTgtNum'],
					$gtotNumDt+=$reskt['subtotNumDt'];
					$gtotnot+=$reskt['subnot'];
				}#end of loop kategori ---------------------------------
				// print_r($katArr);exit();
			}#kategori : ada ------------------------------------

			#end of loop kategori kegiatan ---------------------
			echo '{
					"nip":"'.$resdsn['nip'].'",
					"id_pt":"'.$resdsn['id_pt'].'",
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
					"gtotnot":'.$gtotnot.',
					"katArr":'.json_encode($katArr).'
				}';
					// "subnot":"'.json_encode($subnot).'",

		break;

		#ubah pangkat ==========================================================================================
		case 'naikTemp':
			// $pointgt	= isset($_GET['pointgt'])?$_GET['pointgt']:'';
			// $iddsn		= isset($_GET['iddsn'])?$_GET['iddsn']:'';

			#loop kategori kegiatan, subtotal poin, subtotal target, subtotal sisa poin sebelumnya----
			$sqlkat = 'SELECT
					ktg.idkatkeg,
					ktg.katkeg,
					ktg.subTotTgt as subTotTgtPerc,
					('.$_GET['pointgt'].' * ktg.subTotTgt / 100 ) as subTotTgtNum,
					ktg.tipe,
					ktg.cum,
					tbdtk.sisa AS subsisa,
					tbdtk.remain,
					tbpoin.poinCur,
					(tbdtk.remain+tbpoin.poinCur)as subtotNumDt,
					(tbdtk.remain+tbpoin.poinCur)-('.$_GET['pointgt'].' * ktg.subTotTgt / 100 )as selisih
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
									d.iddsn ='.$_GET['iddsn'].'  AND	(
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
								d.iddsn='.$_GET['iddsn'].' 
						)
						tbds ON tbds.idkatkeg = katkeg.idkatkeg
				)tbdtk ON tbdtk.idkatkeg = ktg.idkatkeg
				GROUP BY 
					ktg.idkatkeg desc';
			// print_r($sqlkat);exit();
			$exekat 	= mysqli_query($con,$sqlkat);
			
	    	//pilih history jabtan dosen x
	    	$sqlh1 ='SELECT *
					FROM histjab hh
					WHERE 
						hh.STATUS=1 AND 
						hh.iddsn='.$_GET['iddsn'];
			$exeh1 = mysqli_query($con,$sqlh1);
			$resh1 = mysqli_fetch_assoc($exeh1);

			//insert baru history jabatan status = 1
			$sqlh2 ='INSERT into histjab 
					SET 
						status 	=1,
						id_gol	='.$_GET['idgoltgt'].',
						id_jab	='.$_GET['idjabtgt'].',
						tgltmp	= NOW(),
						id_pt 	='.$resh1['id_pt'].', 
						iddsn 	='.$_GET['iddsn'];
			// print_r($sqlh2);exit();
			$exeh2 = mysqli_query($con,$sqlh2);
			$idhj 	= mysqli_insert_id($con);

			//ubah status 1 jadi 0
			$sql2 ='UPDATE 
						histjab h 
					SET 
						h.status=0
					WHERE
						h.idhistjab='.$resh1['idhistjab'];
			$exe2 = mysqli_query($con,$sql2);
			// var_dump($idhj);exit();
			#loop kategori ---------------------------
			$selisih = 0;
			while($reskat=mysqli_fetch_assoc($exekat)){
				// insert histjab baru

				// $selisih += $reskat['selisih'];
				$sqls = 'INSERT into dtksisa set idhistjab ='.$idhj.',idkatkeg='.$reskat['idkatkeg'].',poin=';
				if($reskat['tipe']=='mx'){ //max = C,D
					if($reskat['selisih'] < 0){ // minus
						$selisih = $selisih - abs($reskat['selisih']);
						$sqls.='0, remain=0';
					}else{ // bukan minus
						if($reskat['selisih']>0){ // plus
							$selisih = abs($reskat['selisih']) - $selisih;
							$sqls.=$selisih.', remain='.$selisih;
						}else{ // sama dg.
							$sqls.='0, remain=0';
						}
					}
				}else{ //min = A,B
					$x = $selisih - $reskat['selisih'];
					$sqls.=$x.', remain='.$x;
					// $sqls.=$selisih.', remain='.$selisih;
				}

				$exes = mysqli_query($con,$sqls);
				if($exes)
					$state='berhasil';
				else
					$state='gagal';

		   	}#end of loop kategori -----------------
		   	// var_dump($selisih);exit();

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
							LEFT JOIN histjab h ON h.iddsn= d.iddsn
							LEFT JOIN dtksisa ds ON ds.idhistjab= h.idhistjab
							WHERE 
								d.iddsn='.$_GET['iddsn'].' and h.`status`=1
						)
						tbds ON tbds.idkatkeg = katkeg.idkatkeg';
			// print_r($sql2);exit();
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
				$sql = 'UPDATE bukeg set 	status 		= "'.$statusTB.'",
											keterangan 	= "'.trim(mysqli_real_escape_string($con,$_POST['keteranganTB'][$i])).'"
						WHERE  idbukeg = '.$_POST['idH'][$i];
				// print_r($sql);exit();
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
			$sql ='	SELECT
						ifnull(tbpoin.poinCur, 0)poinx,
						IFNULL(tbds.poin, 0) availRemainx,
						IFNULL(tbds.remain, 0) usedRemainx,round(
							(ifnull(tbpoin.poinCur, 0)) + IFNULL(tbds.remain, 0)
						,2) AS totpoinx,
						IFNULL(SUM(k.poin), 0) AS totpoin,
						h.tgltmp,
						tbhj.jum,
						d.iddsn,
						d.nip,
						d.baru,
						CONCAT(d.gelard," ",d.namad," ",d.namab," ",d.gelarb) AS nama,
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
								d.iddsn,
								k.idkeg,
								k.nakeg,
								k.poin,
								dk.sisa,
								dk.status,
								
							/*poin skrg*/
								sum(CASE
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
								END )AS poinCur
							/*poin skrg*/

							FROM
								dtk dk
								JOIN kegiatan k ON k.idkeg = dk.idkeg
								JOIN subkatkeg s ON s.id_subkatkeg = k.id_subkatkeg
								JOIN histjab h ON h.idhistjab = dk.idhistjab
								JOIN dsn d ON d.iddsn = h.iddsn
							WHERE
								h.`status` = 1 AND (
									dk.`status`!="done" or(
										dk.status="done" AND dk.sisa!=0
									)
								) 
							GROUP BY
								d.iddsn
							ORDER BY 
								dk.tglinput desc	
						) tbpoin ON tbpoin.iddsn= d.iddsn
						LEFT JOIN (
							SELECT iddsn, COUNT(*) jum
							FROM histjab
							GROUP BY iddsn
						) tbhj ON tbhj.iddsn = d.iddsn
						LEFT JOIN (
							SELECT dtksisa.idhistjab,SUM(dtksisa.poin) AS poin,SUM(dtksisa.remain) AS remain
							FROM dtksisa
							GROUP BY dtksisa.idhistjab
						) tbds ON tbds.idhistjab = h.idhistjab
						WHERE
						h.`status` = 1 '.$where.'
					GROUP BY
						d.iddsn
					ORDER BY
						d.baru DESC,
						totpoin DESC,
						d.sisaStatus DESC,
						g.gol DESC';
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

					// <a onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="detail Dosen"  class="btn" href="javascript:viewDosenDtl('.$res['iddsn'].');" 
					$btn 	='<td>
								 <a onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="detail Dosen"  class="btn" href="javascript:viewDosenDtl('.$res['iddsn'].');" 
									 role="button"><i class="icon-user"></i></a>
									 '.$btnSisa.'
							 </td>';
							 // <p class="badge badge-important">2</p>
					$tb.='<tr onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" '.$trclr.' title="'.$info.'">
							<td ><label onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="'.$infoStatus.'" >'.$statusNaik.'</label></td>
							<td>'.$res['nip'].'</td>
							<td>'.$res['nama'].'</td>
							<td>'.$res['gol'].'</td>
							<td>'.$res['jab'].'</td>
							<td>'.$res['umur'].' th</td>
							<td>'.$res['totpoinx'].'</td>
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
			$tb.='<tr class="info"><td colspan="9">'.$obj->anchors.'</td></tr>';
			$tb.= '<tr class="info"><td colspan="9">'.$obj->total.'</td></tr>';
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
							// print_r($sqlkeg);exit();
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
