SELECT
					ktg.idkatkeg,
					ktg.katkeg,
					ktg.subTotTgt as subTotTgtPerc,
					(100 * ktg.subTotTgt / 100 ) as subTotTgtNum,
					ktg.tipe,
					ktg.cum,
					tbdtk.sisa AS subsisa,
					tbdtk.remain,
					tbpoin.poinCur,
					(tbdtk.remain+tbpoin.poinCur)as subtotNumDt,
					(tbdtk.remain+tbpoin.poinCur)-(100 * ktg.subTotTgt / 100 )as selisih
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
									d.iddsn =35  AND	(
										dk.`status`!="done" or(
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
								d.iddsn=35 
						)
						tbds ON tbds.idkatkeg = katkeg.idkatkeg
				)tbdtk ON tbdtk.idkatkeg = ktg.idkatkeg
				GROUP BY 
					ktg.idkatkeg desc