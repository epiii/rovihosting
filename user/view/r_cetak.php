<style>
	.fieldx{
		text-transform:capitalize;
	}
</style>

<?php
	session_start();
 	require_once '../../lib/koneksi.php';
	require_once '../../lib/tglindo.php';
	require_once '../../lib/mpdf/mpdf.php';
	
	//sudah login ---------------------------------------------------------------------------------
	if(isset($_SESSION['login'])!=0){
		//tipe : pdf ------------------------------------------------------------------------------
	 	if(isset($_GET['tipe']) AND $_GET['tipe']=='pdf'){
			$id=isset($_GET['iddsn'])?'d.iddsn='.$_GET['iddsn']:'d.iduser='.$_SESSION['iduser'];
			$sql 	='SELECT
						kajur.kjab,
						kajur.knip,
						kajur.kgol,
						kajur.knama,
						kajur.kpangkat,
						u.username,u.level,
						d.iddsn,d.gelard,d.gelarb,d.namad,d.namab,d.agama,d.jk,d.tl,d.tgll,
						d.nip,d.karpeg,pt.id_pt,pt.pt,j.id_jab,j.jab,g.id_gol,g.gol,g.pangkat,h.tgljabs,h.tglgols,h.tgltmp,g.gol,j.jab,
						f.fak,jr.jur,
						YEAR (CURDATE()) - YEAR (h.tglgols) AS masagols,
						YEAR (CURDATE()) - YEAR (h.tgljabs) AS masajabs,
						(YEAR(CURDATE()) - YEAR(tgll)) AS umur

					FROM
						dsn d
						JOIN USER u ON u.iduser = d.iduser
						JOIN prodi p ON p.idprodi = d.idprodi
						JOIN jur jr ON jr.idjur = p.idjur
						JOIN (
							SELECT 
								CONCAT(dn.gelard," ",dn.namad," ",dn.namab," ",dn.gelarb)as knama,
								jrr.idjur,
								dn.nip as knip,
								gl.gol as kgol,
								gl.pangkat as kpangkat,
								jb.jab as kjab
							from jur jrr 
								join dsn dn on dn.iddsn = jrr.iddsn
								JOIN histjab hj ON hj.iddsn= dn.iddsn
								JOIN gol gl ON gl.id_gol= hj.id_gol
								JOIN jab jb ON jb.id_jab = hj.id_jab
						)kajur ON kajur.idjur = jr.idjur
						JOIN fak f ON f.idfak = jr.idfak
						JOIN histjab h ON h.iddsn = d.iddsn
						JOIN pt ON pt.id_pt = h.id_pt
						JOIN gol g ON g.id_gol = h.id_gol
						JOIN jab j ON j.id_jab = g.id_jab

					WHERE
						h.`status` = 1
					AND '.$id;
			$exe	= mysql_query($sql);
			$res 	= mysql_fetch_assoc($exe);
			// print_r($res);exit();
			
			$sqlsu 	= 'SELECT 
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
								'.$id.' AND	(
									dk.`status`!="done" or(
										dk.status="done" AND dk.sisa!=0
									)
								) ';
			ob_start(); // digunakan untuk convert php ke html

	        $tb='<table width="100%" border="0">
                <tr>
                    <td width="81%" align="right" valign="top">
                        LAMPIRAN II
                    </td>
                    <td width="19%">: SURAT KEPUTUSAN BERSAMA MENTERI PENDIDIKAN DAN KEBUDAYAAN DAN  KEPALA BADAN KEPEGAWAIAN NEGARA<br />NOMOR  : 61409/MPK/KP/99
                        <br />NOMOR  : 181 TAHUN 1999
                        <br />TANGGAL: 13 OKTOBER 1999<br />________________________________________________
                    </td>
                </tr>
            </table>'; 
            
//kategori : A ------------------------------------------------------------------------
		 	if (isset($_GET['kat']) AND $_GET['kat']=='A'){ 
            // var_dump($res['knama']);
				$ruwet	= base64_encode($_SESSION['idsesi'].$_SESSION['iduser'].$_GET['kat'].$_SESSION['idsesi']);
				//enkripsi "ruwet"------------------------------------------------------------------------
		 		if(isset($_GET['ruwet']) AND $_GET['ruwet']==$ruwet){	
					// <td class="fieldx" width="75%">: <b>'.$res['gelard'].' '.$res['namad'].' '.$res['namab'].' '.$res['gelarb'].'</b></td>
								// <p>SURAT PERNYATAAN<br />MELAKUKAN KEGIATAN PENDIDIKAN DAN PENGAJARAN</p>
					$tb.='<h3 align="center">
								SURAT PERNYATAAN<br/>MELAKUKAN KEGIATAN PENDIDIKAN DAN PENGAJARAN
						</h3>
						<table width="100%" border="0">
							<tr>
								<td colspan="4">Yang bertanda tangan dibawah ini :</span></td>
							</tr>
							<tr>
								<td width="25%">Nama </td>
								<td class="fieldx" width="75%">: <b>'.$res['knama'].'</b></td>
							</tr>
							<tr>
								<td  >NIP / NIDN</td>
								<td class="fieldx">: '.$res['knip'].' / </td>
							</tr>
							<tr>
								<td  > Pangkat / Golongan Ruang</span></td>
								<td class="fieldx">: '.$res['kpangkat'].' / '.$res['kgol'].'</td>
							</tr>
							<tr>
								<td  >  Jabatan Fungsional </span></td>
								<td class="fieldx">: '.$res['kjab'].'</td>
							</tr>
							<tr>
								<td  >  Unit Kerja</span></td>
								<td class="fieldx">: '.$res['fak'].' Universitas Negeri Surabaya</td>
							</tr>
							<tr>
								<td  > menyatakan bahwa</span></td>
								<td class="fieldx">: </td>
							</tr>
							<tr>
								<td width="25%">Nama </td>
								<td class="fieldx" width="75%">: <b>'.$res['gelard'].' '.$res['namad'].' '.$res['namab'].' '.$res['gelarb'].'</b></td>
							</tr>
							<tr>
								<td  > NIP/NIDN</span></td>
								<td class="fieldx">: '.$res['nip'].' / </td>
							</tr>
							<tr>
								<td  >  Pangkat/Golongan Ruang</span></td>
								<td class="fieldx">: '.$res['pangkat'].' / '.$res['gol'].'</td>
							</tr>
							<tr>
								<td  > Jabatan Fungsional</span></td>
								<td class="fieldx">: '.$res['jab'].'</td>
							</tr>
							<tr>
								<td  >  Unit Kerja </span></td>
								<td class="fieldx">: '.$res['fak'].' Universitas Negeri Surabaya</td>
							</tr>
						</table>
						<br>';
					
					$tb.='<table width="100%" bgcolor="#999999" border="0">
							<tr align="center" bgcolor="#A2A2A2" >
							  <td align="center">NO.</td>
							  <td  align="center" colspan="2"><p>KEGIATAN PENDIDIKAN DAN PENGAJARAN/KODE</p></td>
							  <td  align="center">TEMPAT / INSTANSI / PRODI</td>
							  <td  align="center">SEMESTER / TANGGAL</td>
							  <td  align="center">JUMLAH ANGKA KREDIT</td>
							  <td  align="center">KETERANGAN / BUKTI FISIK</td>
							</tr>';
						
	               	$sqlsu.='	AND s.id_katkeg =(
											SELECT idkatkeg
											from katkeg where cum="'.$_GET['kat'].'"
										)
								ORDER BY 
									dk.tglinput desc
								)tbpoinC
							GROUP BY 
								tbpoinC.idkeg';
					// print_r($sqlsu);exit();
					
					$exesu = mysql_query($sqlsu);
					$no =1;$tot=0;
					#loop sub unsur ====================================
					while ($ressu = mysql_fetch_assoc($exesu)){
						$tb.='<tr bgcolor="white">
								<td align="right">'.$no.'.</td>
								<td colspan="6">'.$ressu['nakeg'].'</td>
							</tr>';
						$sqldk = 'SELECT
									dk.iddtk,
									s.id_katkeg,
									k.idkeg,
									dk.ket,
									k.poin,
									dk.sks,
									dk.tempat,
									dk.waktu,
									dk.jumAnggota,
									/*poin skrg*/
									CASE
										WHEN dk.sisa > 0 THEN
											dk.sisa
										ELSE
											CASE
												WHEN isGroup = "y" THEN
													/*grup : kelompok*/
													CASE
												WHEN isLeader = "y" THEN
													/*ketua*/
													(k.poin * 60 / 100)
												ELSE
													/*anggota*/
													(
														(k.poin * 40 / 100) / dk.jumAnggota
													)
											END
										WHEN isGroup = "n" THEN
											/*grup : individu*/
											k.poin
										ELSE
											CASE
												WHEN dk.sks != 0 THEN
													/*penjar : kuliah*/
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
									h.`status` = 1
								AND '.$id.'
								AND (
									dk.`status` != "done"
									OR (
										dk. STATUS = "done"
										AND dk.sisa != 0
									)
								)
								AND k.idkeg = '.$ressu['idkeg'].'
								ORDER BY
									dk.tglinput DESC';
						$exedk = mysql_query($sqldk);
						$no2=1;
					
						#loop detail kegiatan ==========================
						while ($resdk=mysql_fetch_assoc($exedk)) {
							$tb.='<tr bgcolor="white">
									<td align="right"></td>
									<td valign="top" align="right">'.$no2.'.</td>
									<td align="left">'.$resdk['ket'].'</td>
									<td align="center">'.$resdk['tempat'].'</td>
									<td align="center">'.$resdk['waktu'].'</td>
									<td align="center">'.$resdk['poinCur'].'</td>
									<td align="center">Ba('.$ressu['idkeg'].')</td>
								</tr>';		
							$no2++;
						}#end of loop detail kegaitan ==================
						
						#sub jumlah ====================================
						$tb.='<tr bgcolor="#CCCCCC">
								<td colspan="5" align="right">Sub Jumlah :</td>
                                    <td align="center">'.$ressu['poinCur'].'</td>
                                    <td></td>
                                </tr>';
						#end of sub jumlah =============================
						$no++;
						$tot+=$ressu['poinCur'];
					}#end of loop sub unsur ============================
					#grand total =======================================
					$tb.='<tr bgcolor="#CCCCCC">
                                <td colspan="5" align="right">JUMLAH BIDANG A :</td>
                                <td align="center">'.$tot.'</td>
                                <td></td>
                            </tr>
					</table><br />';
					#end of grand total =================================
					//echo $tb;	
                    
					$tb.='Demikian pernyataan ini dibuat dapat dipergunakan sebagai mana aslinya
                    <table width="100%" border="0">
                        <tr>
                            <td width="4%" valign="top">&nbsp;</td>
                            <td width="62%">&nbsp;</td>
                            <td width="34%">Surabaya, '.tgl_indo(date(Y.'-'.m.'-'.d)).' <br />
                            Ketua Jurusan '.$res['jur'].',</td>
                        </tr>
                        <tr>
                            <td rowspan="2" valign="top">Catatan:</td>
                            <td rowspan="2"><ol>
                                    <li>Dibuat per semester</li>
                                    <li>Ditanda tangani oleh Ketua Jurusan pada universitas / institut / Sekolah Tinggi / Akademi / Politeknik</li>
                                    <li>Dilampirkan surat penugasan tersebut di atas</li>
                                </ol>
                            </td>
                            <td><!--<img width="50" src="../../img/ANIMAL FOOTPRINT.PNG">--></td>
                        </tr>
                        <tr>
                        	<td>'.$res['knama'].'</td>
                        </tr>
                    </table>
                    <p>&nbsp;</p>';
					echo $tb;
				}//end of "ruwet" -----------------------------------------------------------------
			}//end of kategori : A ----------------------------------------------------------------
			
//kategori : B ------------------------------------------------------------------------
		 	if (isset($_GET['kat']) AND $_GET['kat']=='B'){ 
				$ruwet	= base64_encode($_SESSION['idsesi'].$_SESSION['iduser'].$_GET['kat'].$_SESSION['idsesi']);
				//enkripsi "ruwet"------------------------------------------------------------------------
		 		if(isset($_GET['ruwet']) AND $_GET['ruwet']==$ruwet){	
					$tb.='<h3 align="center">
								DAFTAR KEGIATAN PENELITAN
						</h3>
						<table width="100%" border="0">
							<tr>
								<td colspan="4">Pegawai Negeri Sipil yang dinilai:</span></td>
							</tr>
							<tr>
								<td width="25%">Nama </td>
								<td class="fieldx" width="75%">: <b>'.$res['gelard'].' '.$res['namad'].' '.$res['namab'].' '.$res['gelarb'].'</b></td>
							</tr>
							<tr>
								<td  >NIP / NIDN</td>
								<td class="fieldx">: '.$res['nip'].' / </td>
							</tr>
							<tr>
								<td  > Pangkat / Golongan Ruang</span></td>
								<td class="fieldx">: '.' / '.$res['gol'].'</td>
							</tr>
							<tr>
								<td  >  Jabatan Fungsional </span></td>
								<td class="fieldx">: '.$res['jab'].'</td>
							</tr>
							<tr>
								<td  >  Unit Kerja</span></td>
								<td class="fieldx">: '.$res['fak'].' Universitas Negeri Surabaya</td>
							</tr>
						</table><br>';
					
					$tb.='<table width="100%" bgcolor="#999999" border="0">
							<tr align="center" bgcolor="#A2A2A2" >
							  <td rowspan="2" align="center">NO.</td>
							  <td rowspan="2" align="center" ><p>NAMA JUDUL KARYA ILMIAH (UNSUR)</p></td>
							  <td align="center">SUB UNSUR</td>
							  <td colspan="2" align="center">ANGKA KREDIT MENURUT</td>
							  <td rowspan="2" align="center">KETERANGAN</td>
							</tr>
							<tr align="center" bgcolor="#A2A2A2" >
								<td align="center">NILAI ANGKA KREDIT</td>
								<td align="center">REKTOR UNESA</td>
								<td align="center">PANITIA PENILAI PUSAT</td>
							</tr>
							';
							
					$sqlds = 'SELECT
								id_subkatkeg,
								IFNULL(dsubkatkeg, "lainnya") AS dsubkatkeg
							FROM
								subkatkeg
							WHERE
								id_katkeg = (
									SELECT
										idkatkeg
									FROM
										katkeg
									WHERE
										cum = "'.$_GET['kat'].'"
								)';

					$exeds = mysql_query($sqlds);
					$tot=0;
					#loop sub unsur ====================================
					while ($resds = mysql_fetch_assoc($exeds)){
						$tb.='<tr bgcolor="white">
								<td colspan="6" style="text-transform:capitalize;font-weight:bold;">'.$resds['dsubkatkeg'].'</td>
							</tr>';
						$sqldk = 'SELECT
									dk.iddtk,
									s.id_katkeg,
									k.idkeg,
									dk.ket,
									k.poin,
									dk.sks,
									dk.tempat,
									dk.waktu,
									dk.jumAnggota,
									/*poin skrg*/
									CASE
										WHEN dk.sisa > 0 THEN
											dk.sisa
										ELSE
											CASE
												WHEN isGroup = "y" THEN
													/*grup : kelompok*/
													CASE
												WHEN isLeader = "y" THEN
													/*ketua*/
													(k.poin * 60 / 100)
												ELSE
													/*anggota*/
													(
														(k.poin * 40 / 100) / dk.jumAnggota
													)
											END
										WHEN isGroup = "n" THEN
											/*grup : individu*/
											k.poin
										ELSE
											CASE
												WHEN dk.sks != 0 THEN
													/*penjar : kuliah*/
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
									h.`status` = 1
									AND d.iduser = '.$_SESSION['iduser'].'
									AND (
										dk.`status` != "done"
										OR (
											dk. STATUS = "done"
											AND dk.sisa != 0
										)
									)
									AND s.id_subkatkeg='.$resds['id_subkatkeg'].'
								ORDER BY
									dk.tglinput DESC';

						// print_r($sqldk);
						$exedk = mysql_query($sqldk);
						$no2=1;
						if(mysql_num_rows($exedk)==0){
							$tb.='<tr bgcolor="white"><td colspan="7" align="center" bgcolor="grey">kosong </td></tr>';
						}else{
							#loop detail kegiatan ==========================
							while ($resdk=mysql_fetch_assoc($exedk)) {
								//print_r($resdk);exit();
								$tb.='<tr bgcolor="white">
										<td valign="top" align="right">'.$no2.'</td>
										<td valign="top" >'.$resdk['ket'].'</td>
										<td valign="top" align="center">'.$resdk['poin'].'</td>
										<td valign="top" align="center">'.$resdk['poinCur'].'</td>
										<td valign="top"  align="center"></td>
										<td valign="top" align="center">Ba('.$resdk['idkeg'].')</td>
									</tr>';		
								$no2++;
								$tot+=$resdk['poinCur'];
							}#end of loop detail kegaitan ==================
							//$no++;
						}
					}#end of loop sub unsur ============================
					#grand total =======================================
					$tb.='<tr bgcolor="#CCCCCC">
                                <td colspan="3" align="right">JUMLAH BIDANG B :</td>
                                <td align="center">'.$tot.'</td>
                                <td></td>
                                <td></td>
                           </tr>
					</table><br />';
					#end of grand total =================================
					 
					$tb.='Demikian pernyataan ini dibuat dapat dipergunakan sebagai mana aslinya
                    <table width="100%" border="0">
                        <tr>
                            <td width="4%" valign="top">&nbsp;</td>
                            <td width="62%">&nbsp;</td>
                            <td width="34%">Surabaya, '.tgl_indo(date(Y.'-'.m.'-'.d)).' <br />
                            Ketua Jurusan '.$res['jur'].',</td>
                        </tr>
                        <tr>
                            <td rowspan="2" valign="top">Catatan:</td>
                            <td rowspan="2"><ol>
                                    <li>Dibuat per semester</li>
                                    <li>Ditanda tangani oleh Ketua Jurusan pada universitas / institut / Sekolah Tinggi / Akademi / Politeknik</li>
                                    <li>Dilampirkan surat penugasan tersebut di atas</li>
                                </ol>
                            </td>
                            <td><!--<img width="50" src="../../img/ANIMAL FOOTPRINT.PNG">--></td>
                        </tr>
                        <tr>
                        	<td>'.$res['knama'].'</td>
                        </tr>
                    </table>
                    <p>&nbsp;</p>';
					echo $tb;
				}//end of "ruwet" -----------------------------------------------------------------
			}//end of kategori : B ----------------------------------------------------------------
			
//kategori : C ------------------------------------------------------------------------
		 	if (isset($_GET['kat']) AND $_GET['kat']=='C'){ 
				$ruwet	= base64_encode($_SESSION['idsesi'].$_SESSION['iduser'].$_GET['kat'].$_SESSION['idsesi']);
				//enkripsi "ruwet"------------------------------------------------------------------------
		 		if(isset($_GET['ruwet']) AND $_GET['ruwet']==$ruwet){	
					$tb.='<h3 align="center">
								SURAT PERNYATAAN<br />MELAKUKAN KEGIATAN PENGABDIAN PADA MASYARAKAT<br>
						</h3>
						<table width="100%" border="0">
							<tr>
								<td colspan="4">Yang bertanda tangan dibawah ini :</span></td>
							</tr>
							<tr>
								<td width="25%">Nama </td>
								<td class="fieldx" width="75%">: <b>'.$res['knama'].'</b></td>
							</tr>
							<tr>
								<td  >NIP / NIDN</td>
								<td class="fieldx">: '.$res['knip'].' / </td>
							</tr>
							<tr>
								<td  > Pangkat / Golongan Ruang</span></td>
								<td class="fieldx">: '.$res['kpangkat'].' / '.$res['kgol'].'</td>
							</tr>
							<tr>
								<td  >  Jabatan Fungsional </span></td>
								<td class="fieldx">: '.$res['kjab'].'</td>
							</tr>
							<tr>
								<td  >  Unit Kerja</span></td>
								<td class="fieldx">: '.$res['fak'].' Universitas Negeri Surabaya</td>
							</tr>
							<tr>
								<td  > menyatakan bahwa</span></td>
								<td class="fieldx">: </td>
							</tr>
							<tr>
								<td width="25%">Nama </td>
								<td class="fieldx" width="75%">: <b>'.$res['gelard'].' '.$res['namad'].' '.$res['namab'].' '.$res['gelarb'].'</b></td>
							</tr>
							<tr>
								<td  > NIP/NIDN</span></td>
								<td class="fieldx">:  '.$res['nip'].'/ </td>
							</tr>
							<tr>
								<td  >  Pangkat/Golongan Ruang</span></td>
								<td class="fieldx">: '.$res['pangkat'].' / '.$res['gol'].'</td>
							</tr>
							<tr>
								<td  > Jabatan Fungsional</span></td>
								<td class="fieldx">: '.$res['jab'].'</td>
							</tr>
							<tr>
								<td  >  Unit Kerja </span></td>
								<td class="fieldx">: '.$res['fak'].' Universitas Negeri Surabaya</td>
							</tr>
						</table>
						<br>';
					
					$tb.='<table width="100%" bgcolor="#999999" border="0">
							<tr align="center" bgcolor="#A2A2A2" >
							  <td align="center">NO.</td>
							  <td align="center" colspan="2"><p>KEGIATAN PENGABDIAN MASYARAKAT</p></td>
							  <td align="center">BENTUK</td>
							  <td align="center">TEMPAT / INSTANSI / PRODI</td>
							  <td align="center">SEMESTER / TANGGAL</td>
							  <td align="center">JUMLAH ANGKA KREDIT</td>
							  <td align="center">KET.</td>
							</tr>';
						
	               	$sqlsu.='	AND s.id_katkeg =(
											SELECT idkatkeg
											from katkeg where cum="'.$_GET['kat'].'"
										)
								ORDER BY 
									dk.tglinput desc
								)tbpoinC
							GROUP BY 
								tbpoinC.idkeg';
					//print_r($sqlsu);exit();
					
					$exesu = mysql_query($sqlsu);
					$no =1;$tot=0;
					#loop sub unsur ====================================
					while ($ressu = mysql_fetch_assoc($exesu)){
						$tb.='<tr bgcolor="white">
								<td align="right">'.$no.'.</td>
								<td colspan="7">'.$ressu['nakeg'].'</td>
							</tr>';
						$sqldk = 'SELECT
									dk.iddtk,
									s.id_katkeg,
									k.idkeg,
									dk.ket,
									k.poin,
									dk.sks,
									dk.tempat,
									dk.waktu,
									dk.jumAnggota,
									/*poin skrg*/
									CASE
										WHEN dk.sisa > 0 THEN
											dk.sisa
										ELSE
											CASE
												WHEN isGroup = "y" THEN
													/*grup : kelompok*/
													CASE
												WHEN isLeader = "y" THEN
													/*ketua*/
													(k.poin * 60 / 100)
												ELSE
													/*anggota*/
													(
														(k.poin * 40 / 100) / dk.jumAnggota
													)
											END
										WHEN isGroup = "n" THEN
											/*grup : individu*/
											k.poin
										ELSE
											CASE
												WHEN dk.sks != 0 THEN
													/*penjar : kuliah*/
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
									h.`status` = 1
								AND '.$id.'
								AND (
									dk.`status` != "done"
									OR (
										dk. STATUS = "done"
										AND dk.sisa != 0
									)
								)
								AND k.idkeg = '.$ressu['idkeg'].'
								ORDER BY
									dk.tglinput DESC';
						$exedk = mysql_query($sqldk);
						$no2=1;
					
						#loop detail kegiatan ==========================
						while ($resdk=mysql_fetch_assoc($exedk)) {
							$tb.='<tr bgcolor="white">
									<td align="right"></td>
									<td valign="top" align="right">'.$no2.'</td>
									<td valign="top" align="justify">'.$resdk['ket'].'</td>
									<td valign="top" align="center"></td>
									<td valign="top" align="center">'.$resdk['tempat'].'</td>
									<td valign="top" align="center">'.$resdk['waktu'].'</td>
									<td valign="top" align="center">'.$resdk['poinCur'].'</td>
									<td valign="top" align="center">Ba('.$ressu['idkeg'].')</td>
								</tr>';		
							$no2++;
						}#end of loop detail kegaitan ==================
						
						#sub jumlah ====================================
						$tb.='<tr bgcolor="#CCCCCC">
								<td colspan="6" align="right">Sub Jumlah :</td>
                                    <td align="center">'.$ressu['poinCur'].'</td>
                                    <td></td>
                                </tr>';
						#end of sub jumlah =============================
						$no++;
						$tot+=$ressu['poinCur'];
					}#end of loop sub unsur ============================
					#grand total =======================================
					$tb.='<tr bgcolor="#CCCCCC">
                                <td colspan="6" align="right">JUMLAH BIDANG A :</td>
                                <td align="center">'.$tot.'</td>
                                <td></td>
                            </tr>
					</table><br />';
					#end of grand total =================================
					//echo $tb;	
                    
					$tb.='Demikian pernyataan ini dibuat dapat dipergunakan sebagai mana aslinya
                    <table width="100%" border="0">
                        <tr>
                            <td width="4%" valign="top">&nbsp;</td>
                            <td width="62%">&nbsp;</td>
                            <td width="34%">Surabaya, '.tgl_indo(date(Y.'-'.m.'-'.d)).' <br />
                            Ketua Jurusan '.$res['jur'].',</td>
                        </tr>
                        <tr>
                            <td rowspan="2" valign="top">Catatan:</td>
                            <td rowspan="2"><ol>
                                    <li>Dibuat per semester</li>
                                    <li>Ditanda tangani oleh Ketua Jurusan pada universitas / institut / Sekolah Tinggi / Akademi / Politeknik</li>
                                    <li>Dilampirkan surat penugasan tersebut di atas</li>
                                </ol>
                            </td>
                            <td><!--<img width="50" src="../../img/ANIMAL FOOTPRINT.PNG">--></td>
                        </tr>
                        <tr>
                        	<td>'.$res['knama'].'</td>
                        </tr>
                    </table>
                    <p>&nbsp;</p>';
					echo $tb;
				}//end of "ruwet" -----------------------------------------------------------------
			}//end of kategori : C ----------------------------------------------------------------
			
//kategori : D ------------------------------------------------------------------------
		 	if (isset($_GET['kat']) AND $_GET['kat']=='D'){ 
				$ruwet	= base64_encode($_SESSION['idsesi'].$_SESSION['iduser'].$_GET['kat'].$_SESSION['idsesi']);
				//enkripsi "ruwet"------------------------------------------------------------------------
		 		if(isset($_GET['ruwet']) AND $_GET['ruwet']==$ruwet){	
					$tb.='<h3 align="center">
								SURAT PERNYATAAN<br />MELAKUKAN KEGIATAN PENUNJANG TRI DHARMA PERGURUAN TINGGI
						</h3>
						<table width="100%" border="0">
							<tr>
								<td colspan="4">Yang bertanda tangan dibawah ini :</span></td>
							</tr>
							<tr>
								<td width="25%">Nama </td>
								<td class="fieldx" width="75%">: <b>'.$res['knama'].'</b></td>
							</tr>
							<tr>
								<td  >NIP / NIDN</td>
								<td class="fieldx">: '.$res['knip'].' / </td>
							</tr>
							<tr>
								<td  > Pangkat / Golongan Ruang</span></td>
								<td class="fieldx">: '.$res['kpangkat'].' / '.$res['kgol'].'</td>
							</tr>
							<tr>
								<td  >  Jabatan Fungsional </span></td>
								<td class="fieldx">: '.$res['kjab'].'</td>
							</tr>
							<tr>
								<td  >  Unit Kerja</span></td>
								<td class="fieldx">: '.$res['fak'].' Universitas Negeri Surabaya</td>
							</tr>
							<tr>
								<td  > menyatakan bahwa</span></td>
								<td class="fieldx">: </td>
							</tr>
							<tr>
								<td width="25%">Nama </td>
								<td class="fieldx" width="75%">: <b>'.$res['gelard'].' '.$res['namad'].' '.$res['namab'].' '.$res['gelarb'].'</b></td>
							</tr>
							<tr>
								<td  > NIP/NIDN</span></td>
								<td class="fieldx">: '.$res['nip'].' / </td>
							</tr>
							<tr>
								<td  >  Pangkat/Golongan Ruang</span></td>
								<td class="fieldx">: '.$res['pangkat'].' / '.$res['gol'].'</td>
							</tr>
							<tr>
								<td  > Jabatan Fungsional</span></td>
								<td class="fieldx">: '.$res['jab'].'</td>
							</tr>
							<tr>
								<td  >  Unit Kerja </span></td>
								<td class="fieldx">: '.$res['fak'].' Universitas Negeri Surabaya</td>
							</tr>
						</table>
						<br>';
					
					$tb.='<table width="100%" bgcolor="#999999" border="0">
							<tr align="center" bgcolor="#A2A2A2" >
							  <td align="center">NO.</td>
							  <td align="center" colspan="2"><p>KEGIATAN PENUNJANG TRI DHARMA PERGURUAN TINGGI </p></td>
							  <td align="center">KEDUDUKAN / TINGKAT</td>
							  <td align="center">TEMPAT / INSTANSI</td>
							  <td align="center">TANGGAL</td>
							  <td align="center">JUMLAH ANGKA KREDIT</td>
							  <td align="center">KET.</td>
							</tr>';
						
	               	$sqlsu.='	AND s.id_katkeg =(
											SELECT idkatkeg
											from katkeg where cum="'.$_GET['kat'].'"
										)
								ORDER BY 
									dk.tglinput desc
								)tbpoinC
							GROUP BY 
								tbpoinC.idkeg';
					//print_r($sqlsu);exit();
					
					$exesu = mysql_query($sqlsu);
					$no =1;$tot=0;
					#loop sub unsur ====================================
					while ($ressu = mysql_fetch_assoc($exesu)){
						$tb.='<tr bgcolor="white">
								<td align="right">'.$no.'.</td>
								<td colspan="7">'.$ressu['nakeg'].'</td>
							</tr>';
						$sqldk = 'SELECT
									dk.iddtk,
									s.id_katkeg,
									k.idkeg,
									dk.ket,
									k.poin,
									dk.sks,
									dk.tempat,
									dk.jabatan,
									dk.waktu,
									dk.jumAnggota,
									/*poin skrg*/
									CASE
										WHEN dk.sisa > 0 THEN
											dk.sisa
										ELSE
											CASE
												WHEN isGroup = "y" THEN
													/*grup : kelompok*/
													CASE
												WHEN isLeader = "y" THEN
													/*ketua*/
													(k.poin * 60 / 100)
												ELSE
													/*anggota*/
													(
														(k.poin * 40 / 100) / dk.jumAnggota
													)
											END
										WHEN isGroup = "n" THEN
											/*grup : individu*/
											k.poin
										ELSE
											CASE
												WHEN dk.sks != 0 THEN
													/*penjar : kuliah*/
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
									h.`status` = 1
								AND '.$id.'
								AND (
									dk.`status` != "done"
									OR (
										dk. STATUS = "done"
										AND dk.sisa != 0
									)
								)
								AND k.idkeg = '.$ressu['idkeg'].'
								ORDER BY
									dk.tglinput DESC';
						$exedk = mysql_query($sqldk);
						$no2=1;
					
						#loop detail kegiatan ==========================
						while ($resdk=mysql_fetch_assoc($exedk)) {
							$tb.='<tr bgcolor="white">
									<td align="right"></td>
									<td valign="top" align="right">'.$no2.'</td>
									<td valign="top" align="justify">'.$resdk['ket'].'</td>
									<td valign="top" align="center">'.$resdk['jabatan'].'</td>
									<td valign="top" align="center">'.$resdk['tempat'].'</td>
									<td valign="top" align="center">'.$resdk['waktu'].'</td>
									<td valign="top" align="center">'.$resdk['poinCur'].'</td>
									<td valign="top" align="center">Ba('.$ressu['idkeg'].')</td>
								</tr>';		
							$no2++;
						}#end of loop detail kegaitan ==================
						
						#sub jumlah ====================================
						$tb.='<tr bgcolor="#CCCCCC">
								<td colspan="6" align="right">Sub Jumlah :</td>
                                    <td align="center">'.$ressu['poinCur'].'</td>
                                    <td></td>
                                </tr>';
						#end of sub jumlah =============================
						$no++;
						$tot+=$ressu['poinCur'];
					}#end of loop sub unsur ============================
					#grand total =======================================
					$tb.='<tr bgcolor="#CCCCCC">
                                <td colspan="6" align="right">JUMLAH BIDANG A :</td>
                                <td align="center">'.$tot.'</td>
                                <td></td>
                            </tr>
					</table><br />';
					#end of grand total =================================
					//echo $tb;	
                    
					$tb.='Demikian pernyataan ini dibuat dapat dipergunakan sebagai mana aslinya
                    <table width="100%" border="0">
                        <tr>
                            <td width="4%" valign="top">&nbsp;</td>
                            <td width="62%">&nbsp;</td>
                            <td width="34%">Surabaya, '.tgl_indo(date(Y.'-'.m.'-'.d)).'<br />
                            Ketua Jurusan '.$res['jur'].',</td>
                        </tr>
                        <tr>
                            <td rowspan="2" valign="top">Catatan:</td>
                            <td rowspan="2"><ol>
                                    <li>Dibuat per semester</li>
                                    <li>Ditanda tangani oleh Ketua Jurusan pada universitas / institut / Sekolah Tinggi / Akademi / Politeknik</li>
                                    <li>Dilampirkan surat penugasan tersebut di atas</li>
                                </ol>
                            </td>
                            <td><!--<img width="50" src="../../img/ANIMAL FOOTPRINT.PNG">--></td>
                        </tr>
                        <tr>
                        	<td>'.$res['knama'].'</td>
                        </tr>
                    </table>
                    <p>&nbsp;</p>';
					echo $tb;
				}//end of "ruwet" -----------------------------------------------------------------
			}//end of kategori : D ----------------------------------------------------------------
							
			#generate html -> PDF ------------
				$out = ob_get_contents();
				ob_end_clean();	
				$mpdf=new mPDF('c','A4','');	 
				$mpdf->SetDisplayMode('fullpage');	 
				$stylesheet = file_get_contents('./pdf/mpdf.css');
				$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
				$mpdf->WriteHTML($out);
				$mpdf->Output();
			#end of generate html -> PDF ------------
			
		}//end of tipe : pdf--------------------------------------------------------------------------
		//tipe : lainnya -----------------------------------------------------------------------------
		else{
			echo 'tipe/format tidak ditemukan';
		}//end of tipe : lainnya  --------------------------------------------------------------------
	}//end of : sudah login --------------------------------------------------------------------------
	//belum login ------------------------------------------------------------------------------------
	else{
		echo 'silahkan <a href="../../"> login</a>'; 
	}//end of : belum login --------------------------------------------------------------------------
?>
