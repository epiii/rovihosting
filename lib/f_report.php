<?php
  function qsubnew($iddsn,$cum){
  	$sq = 'SELECT 
				ifnull(sum(tbpoinC.poinCur),0)poinCur
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
					
					CASE
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
					END AS poinCur

				FROM
					dtk dk
					JOIN kegiatan k ON k.idkeg = dk.idkeg
					JOIN subkatkeg s ON s.id_subkatkeg = k.id_subkatkeg
					JOIN histjab h ON h.idhistjab = dk.idhistjab
					JOIN dsn d ON d.iddsn = h.iddsn
				WHERE
					h.`status` = 1 AND 
					d.iddsn='.$iddsn.' AND	(
						dk.`status`="valid" or(
							dk.status="done" AND dk.sisa!=0
						)
					)and s.id_katkeg=(
	             SELECT idkatkeg from katkeg where cum="'.$cum.'"
	        )
	      ORDER BY 
					dk.tglinput desc
			)tbpoinC';
		$ex = mysql_query($sq);
		$rs = mysql_fetch_assoc($ex);
		$poin = $rs['poinCur'];
		// print_r($sq);exit();
		return $poin;

	}

  function qnew($iddsn,$nakeg){
  	// var_dump($nakeg);exit();
  	$sq = 'SELECT 
				tbpoinC.idkeg,
				tbpoinC.nakeg,
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
					
					CASE
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
					END AS poinCur

				FROM
					dtk dk
					JOIN kegiatan k ON k.idkeg = dk.idkeg
					JOIN subkatkeg s ON s.id_subkatkeg = k.id_subkatkeg
					JOIN histjab h ON h.idhistjab = dk.idhistjab
					JOIN dsn d ON d.iddsn = h.iddsn
				WHERE
					h.`status` = 1 AND 
					d.iddsn='.$iddsn.' AND	(
						dk.`status`="valid" or(
							dk.status="done" AND dk.sisa!=0
						)
					)and k.nakeg LIKE "%'.$nakeg.'%"
					
				ORDER BY 
					dk.tglinput desc
			)tbpoinC';
		// print_r($sq);exit();
		$ex = mysql_query($sq);
		$rs = mysql_fetch_assoc($ex);
		$poin = $rs['poinCur'];
		$poin = ($rs['poinCur']=='')?0:$rs['poinCur'] ;
		// var_dump($poin);exit();
		return $poin;
  	}
?>