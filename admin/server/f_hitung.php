<?php
	require_once '../../lib/koneksi.php';
	function hitungPangkat($iddsn,$idkatkeg,$target){ # fungsi --- 
		// $sqlkeg = "	SELECT
		// 				t.iddtk,
		// 				k.poin,
		// 				t.sisa,
		// 				t.status
		// 			FROM kegiatan k
		// 				INNER JOIN dtk t ON t.idkeg = k.idkeg
		// 				INNER JOIN histjab h ON h.idhistjab = t.idhistjab
		// 				INNER JOIN dsn d ON d.iddsn = h.iddsn
		// 			WHERE 
		// 				d.iddsn	=$iddsn AND 
		// 				k.katkeg=$idkatkeg AND (
		// 					t.status='valid' OR(
		// 						t.status='done' AND t.sisa!=0
		// 					)
		// 				)
		// 			ORDER BY
		// 				t.status desc,
		// 				k.poin desc";
		$sqlkeg		= 'SELECT 
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
									d.iddsn='.$iddsn.' AND	(
										dk.`status`!="done" or(
											dk.status="done" AND dk.sisa!=0
										)
									) AND
									s.id_katkeg ='.$idkatkeg.'
									
								ORDER BY 
									dk.tglinput desc
							)tbpoinC';
		$exekeg 	= mysql_query($sqlkeg);
		$subNumDt	= 0;
		$kegArr = array();

		#loop kegiatan (db) -------------------
		$subSisa2 = 0;
		while ($reskeg=mysql_fetch_assoc($exekeg)){
			$sisa 	= $reskeg['sisa'];
			$iddtk 	= $reskeg['iddtk'];
			$target	= $target-$sisa;
			
			if($sisa!=0){ //punya sisa poin @kegiatan
				if($target>0){ // target > sisa (habis terpakai @dtk)
					$sql = 'UPDATE dtk set sisa=0 WHERE iddtk='.$iddtk;
					$exe = mysql_query($sql);
					if($exe){
						$state = 'false';
						continue;
					}
				}else{ //target < sisa
					if($target==0){ // = 0 
						$sql = 'UPDATE dtk set sisa=0 WHERE iddtk='.$iddtk;
					}else{ // < 0
						$sql = 'UPDATE dtk set sisa='.abs($target).' WHERE iddtk='.$iddtk;
					}

					$exe = mysql_query($sql);
					$state='true'; // exit from loop & skip perhitungan poin reguler => result
					break;
				}
			}else{#tidak punya sisa poin @kegiatan---
				$kegArr[]=array(
							'iddtk'=>$reskeg['iddtk'],
							'poin'=>$reskeg['poin']
						);			
				$state = 'false';
			}#end of tidak punya sisa poin @kegiatan---
		}#end of loop kegiatan (db) -------------------

		// var_dump($state);exit();
		if($state=='true'){#state true ----------------
			return $state;
		}else{#state false ----------------------------
			#loop kegiatan (metode) -------------------
			$tmp = $counter = 0;
		    foreach ($kegArr as $key => $value){
		    	$poin=$value['poin'];
		    	$tmp=$tmp + $poin;
		        if ( $tmp < $target) { //akumulasi  kurang 
		            $counter += $poin;
					$sql="UPDATE dtk set status='done' where iddtk=".$value['iddtk'];
					mysql_query($sql);
					continue;
		        }elseif($tmp==$target){ //akumulasi pas
		            // $counter += $poin;
					$sql="UPDATE dtk set status='done' where iddtk=".$value['iddtk'];
					mysql_query($sql);
					$state='true';	
		            break;
		        }else{ //akumulasi lebih 
		    		$sql="UPDATE dtk set status='done',sisa=".abs($tmp-$target)." where iddtk=".$value['iddtk'];
					mysql_query($sql);
					$state='true';
					break;
		        }
		    }#end of loop kegiatan (metode)--
		    return $state;
		}#end of state false ----------------
	}#end of fungsi -------------------------
?>