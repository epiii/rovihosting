<?php 
	session_start();
	// error_reporting(0);
	//include "../lib/timeout.php";
	require_once "../../lib/tglindo.php";
	require_once "../../lib/fpdf17/fpdf.php";
	require_once "../../lib/koneksi.php";

	//sesikosong -----------------------------------------------------------------------------------------
	if (empty($_SESSION['usernamex']) AND empty($_SESSION['passwordx']) AND $_SESSION['login']==0)
	{
		echo "<script>window.location='../../logout.php'</script>";
	}//end of sesi kosong --------------------------------------------------------------------------------
	
	// sesi ada (username &  passuser = ada , sesi login = 1) --------------------------------------------
	else
	{
		//ada get par ruwet ------------------------------------------------------------------------------
		if(isset($_GET['ruwet']))
		{
			$ruwet	= base64_encode($_SESSION['idsesi'].$_SESSION['iduser'].$_SESSION['idsesi']);
			//par ruwet cocok ----------------------------------------------------------------------------
			if($_GET['ruwet']==$ruwet)
			{
				#biodata ---------------------------------------------------------------------------------
				$sqldos	= "SELECT
								d.gelard,
								d.gelarb,
								d.namad,
								d.namab,
								p.pt,
								g.gol,
								j.jab,
								d.nip,
								d.karpeg,
								d.agama,
								d.jk,
								d.tl,
								d.tgll,
								(
									YEAR (curdate()) - YEAR (d.tgll)
								) AS umur,
								h.tgljabs,
								h.tglgols,
								YEAR (CURDATE()) - YEAR (h.tgljabs) AS masajabs,
								YEAR (CURDATE()) - YEAR (h.tglgols) AS masagols,
								sum(k.poin) AS jumpoin
							FROM
								dsn d
							LEFT JOIN histjab h ON h.iddsn = d.iddsn
							LEFT JOIN dtk t ON t.idhistjab = h.idhistjab
							LEFT JOIN kegiatan k ON t.idkeg = k.idkeg
							LEFT JOIN USER u ON u.iduser = d.iduser
							LEFT JOIN gol g on g.id_gol = h.id_gol
							LEFT JOIN jab j ON j.id_jab = h.id_jab
							LEFT JOIN pt p on p.id_pt = h.id_pt
							WHERE
								d.iduser = u.iduser
							AND d.iduser = '$_SESSION[iduser]'";
				// var_dump($sqldos);exit();
				$exedos		= mysql_query ($sqldos);
				// var_dump($exedos);exit();
				$resdos		= mysqli_fetch_assoc($exedos);
				// print_r($resdos);exit();
			
				#panggil library FPDF
				//layout setting
				$pdf=new FPDF('p','mm','A4');
				$pdf->AddPage();
				$border = 0;
				// $pdf->SetAutoPageBreak(true,60);
				$pdf->SetAutoPageBreak(true,25);
				$left = 25;
				$pdf->SetMargins(2,10,10);
			
				//header
				$pdf->SetFont('Arial','B','12');
				$pdf->SetTextColor(204);
				$pdf->MultiCell(0, 2, 'Universitas Negeri Surabaya (UNESA)');
				$pdf->Image('../../img/logo.jpg',170,2,30,'','','www.unesa.ac.id');
				$pdf->Cell(0, 1, " ", "B"); 
				$pdf->Ln(3);
				
				//title
				$pdf->SetFont("", "B", 12);
				$pdf->SetTextColor(0);
				$pdf->Cell(0, 6, 'LAPORAN REKAPITULASI KENAIKAN PANGKAT DOSEN ', 0, 1,'C');
	
				//BIODATA -------------------------------------------------------------------------
					//sub title
					$pdf->SetFont("", "B", 10);
					$pdf->Cell(0, 6, 'Biodata', 0, 1,'C');
				
					//content's setting
					$pdf->SetFont('Arial','','10');
					$pdf->SetFillColor(249, 246, 244);
					$pdf->SetTextColor(78);
					$pdf->SetDrawColor(249, 246, 244);
					$f1 = true;
					$f0	= false;
					
					#$ww1=50; 
					#$ww2=50;
					#$hh1= 6;
					$ww1=40; 
					$ww2=60;
					$hh1= 6;
			
					//content's view
					//-------------------------------------------------------------------
					$pdf->Cell($ww1, 	$hh1,	'NIP', 		1, '0', 'L', $f1);
					$pdf->Cell($ww2, 	$hh1,	': '.$resdos['nip'], 	1, '0', 'L', $f1);
					$pdf->Cell($ww1, 	$hh1,	'Pendidikan Terakhir', 	1, '0', 'L', $f1);
					$pdf->Cell($ww2, 	$hh1,	': '.$resdos['pt'], 	1, '0', 'L', $f1);
					$pdf->Ln();
					//-------------------------------------------------------------------
					$pdf->Cell($ww1, 	$hh1,	'nama lengkap', 	1, '0', 'L', $f1);
					$pdf->Cell($ww2, 	$hh1,	': '.$resdos['gelard'].' '.$resdos['namad'].' '.$resdos['namab'].' '.$resdos['gelarb'], 	1, '0', 'L', $f1);
					$pdf->Cell($ww1, 	$hh1,	'Fungsional jab/Gol', 	1, '0', 'L', $f1);
					$pdf->Cell($ww2, 	$hh1,	': '.$resdos['jab'].' / '.$resdos['gol'], 	1, '0', 'L', $f1);
					$pdf->Ln();
					//-------------------------------------------------------------------
					$pdf->Cell($ww1, 	$hh1,	'Agama', 	1, '0', 'L', $f1);
					$pdf->Cell($ww2, 	$hh1,	': '.$resdos['agama'], 	1, '0', 'L', $f1);
					$pdf->Cell($ww1, 	$hh1,	'TMT Jab/Gol', 	1, '0', 'L', $f1);
					$pdf->Cell($ww2, 	$hh1,	': '.tgl_indo5($resdos['tgljabs']).' / '.tgl_indo5($resdos['tglgols']), 	1, '0', 'L', $f1);
					$pdf->Ln();
					//-------------------------------------------------------------------
					$pdf->Cell($ww1, 	$hh1,	'Jenis Kelamin', 	1, '0', 'L', $f1);
					$pdf->Cell($ww2, 	$hh1,	': '.$resdos['jk'], 	1, '0', 'L', $f1);
					$pdf->Cell($ww1, 	$hh1,	'Masa Jab/Gol', 	1, '0', 'L', $f1);
					$pdf->Cell($ww2, 	$hh1,	': '.$resdos['masagols'].' th / '.$resdos['masajabs'].' th', 	1, '0', 'L', $f1);
					$pdf->Ln();
					//-------------------------------------------------------------------
					$pdf->Cell($ww1, 	$hh1,	'Tempat,Tgl Lahir', 	1, '0', 'L', $f1);
					$pdf->Cell($ww2, 	$hh1,	': '.$resdos['tl'].', '.tgl_indo($resdos['tgll']), 	1, '0', 'L', $f1);
					$pdf->Cell($ww1, 	$hh1,	'Target Jab/Gol', 	1, '0', 'L', $f1);
					$pdf->Cell($ww2, 	$hh1,	': '.$_GET['jabtgt'].' / '.$_GET['goltgt'], 	1, '1', 'L', $f1);
					$pdf->Ln();
					//-------------------------------------------------------------------
					//end of content's view
				//end of BIODATA ------------------------------------------------------------------
	
				//KEGIATAN ------------------------------------------------------------------------
					// sub title ------------------------------------------------------------------ 
					$pdf->SetFont("", "B", 10);
					$pdf->Cell(0, 6, 'Kegiatan', 0, 1,'C');
					$pdf->Ln(1);
		
					// KATEGORI KEGIATAN --------------------------------------------------------------
						// $sqlkat 	= "select * from katkeg order by katkeg";
					$sqlkat = 'SELECT
							ktg.idkatkeg,
							ktg.katkeg,
							ktg.subTotTgt,
							ktg.tipe,
							tbdtk.sisa AS subsisa,
							tbdtk.remain
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
										LEFT JOIN dtksisa ds ON d.iddsn = ds.iddsn
										WHERE
											d.sisaStatus="valid" AND
										 	d.iduser='.$_SESSION['iduser'].'
									)
									tbds ON tbds.idkatkeg = katkeg.idkatkeg
							)tbdtk ON tbdtk.idkatkeg = ktg.idkatkeg
						GROUP BY 
							ktg.idkatkeg';
						$exekat 	= mysqli_query($con,$sqlkat)or die(mysqli_error($con));
						$tot		= 0;
						//loop kategori kegiatan ------------------------------------------------------
						while($reskat=mysqli_fetch_assoc($exekat))
						{
							// var_dump($reskat);exit();
							//kategori kegiatan's view ------------------------------------------------
							$ww3=10; 
							$ww4=175; 
							$ww5=15; 
							$pdf->SetFont('','B',10);
							$pdf->Cell($ww4	, 8, $reskat['katkeg'], 2, 0, 'L', $f0);
							$pdf->Ln();
							
							$pdf->SetFillColor(204, 204, 204);
							$pdf->SetTextColor(0);
							$pdf->SetFont('');
							
							//table header 
							$pdf->Cell($ww3, 8, 'no.', 1, '0', 'C', true);
							$pdf->Cell($ww4, 8, 'Kegiatan', 1, '0', 'C', true);
							$pdf->Cell($ww5, 8, 'Poin', 1, '0', 'C', true);
							$pdf->Ln();

							// NAMA KEGIATAN---------------------------------------------------------
								$sqlkeg = 'SELECT t.status,t.iddtk,t.idkeg,k.nakeg,k.poin,t.sisa
											FROM dtk t
												left join histjab h on h.idhistjab=t.idhistjab
												left join dsn d on d.iddsn=h.iddsn
												left join kegiatan k on k.idkeg=t.idkeg
											where 
												d.iduser='.$_SESSION['iduser'].' and
												k.katkeg='.$reskat['idkatkeg'].'  and
												(t.status="valid" or 
													(t.status="done" and t.sisa!=0)
												)';
									// var_dump($sqlkeg);exit();
								$exekeg= mysqli_query($con,$sqlkeg);
								$i = 1;
								$sub=0;
								// loop nama kegiatan  ----------------------------------------------------
								while($reskeg=mysqli_fetch_assoc($exekeg)){
									$poinx = ($reskeg['sisa']==0)?$reskeg['poin']:$reskeg['sisa'];
									$pdf->SetFillColor(249, 246, 244);
									$pdf->SetDrawColor(204, 204, 204);
									$pdf->SetTextColor(0);
									$pdf->SetFont('');

									$pdf->Cell($ww3, 8, $i, 1, '0', 'C', $f1);
									$pdf->Cell($ww4, 8, strlen($reskeg['nakeg'])>105?substr($reskeg['nakeg'],0,100).' ...':$reskeg['nakeg'], 1, '0', 'L', $f1);
									$pdf->Cell($ww5, 8, $poinx, 1, '0', 'R', $f1);
									$pdf->Ln();
									$i++;
									$f1= !$f1;
									$sub=$sub + $poinx;
								}// end of loop nama kegiatan  -------------------------------------------
							// end of NAMA KEGIATAN ------------------------------------------------------
							
							// SUBTOTAL per kategori -----------------------------------------------------	
								$sub =$sub+$reskat['subsisa'];
								$pdf->SetFillColor(204, 204, 204);
								$pdf->SetDrawColor(249, 246, 244);
								$pdf->SetTextColor(0);
								$pdf->SetFont('');
									$pdf->Cell(185, 8, 'Sisa Poin (Sebelumnya)', 0, '0', 'R', true);
									$pdf->Cell($ww5, 8, $reskat['subsisa'], 1, '0', 'R', true);
								$pdf->Ln();
									$pdf->Cell(185, 8, 'Sub Total', 1, '0', 'R', true);
									$pdf->Cell($ww5, 8, $sub, 1, '0', 'R', true);
								$pdf->Ln();
							// end of SUBTOTAL per kategori ------------------------------------------

							$tot = $tot+$sub;
							$pdf->Ln(3);
						}//end of loop kategori kegiatan ----------------------------------------------
					// end of KATEGORI KEGIATAN -------------------------------------------------------
					#grand total per kategori ---------------------------------------------------------	
						$pdf->Cell(185, 8, 'Grand Total', 1, '0', 'R', true);
						$pdf->Cell($ww5, 8, $tot, 1, '0', 'R', true);
						$pdf->Ln();
					#end of grand total per kategori --------------------------------------------------
				// end of KEGIATAN 
				
				// NOTICE -----------------------------------------------------------------------------
					#$pdf->Cell(185, 8, 'Grand Total', 1, '0', 'R', true);
					#$pdf->Cell($ww5, 8, $tot, 1, '0', 'R', true);
					#$pdf->Ln();
				
				// end of NOTICE -----------------------------------------------------------------------
				$pdf->Output();
			}//end of par ruwet cocok -----------------------------------------------------------------
			
			// par ruwet gak cocok --------------------------------------------------------------------
			else{ 
				echo 'maaf url tidak sesuai(dilarang merubah url)<br>';
				echo '<a href="../rekapitulasi">kembali</a>';
			}// end of par ruwet gak cocok ------------------------------------------------------------
		}//end of ada get par ruwet -------------------------------------------------------------------
	}// end of sesi ada (username &  passuser = ada , sesi login = 1)
?>