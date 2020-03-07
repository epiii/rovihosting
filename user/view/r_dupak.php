<style >  
    .tbCont{
    background-color:black;
  }
  .tbCont tr {
    background-color: white;  
  }
</style>

<?php
  session_start();
  require_once '../../lib/koneksi.php';
  require_once '../../lib/tglindo.php';
  require_once '../../lib/mpdf/mpdf.php';
  require_once '../../lib/f_report.php';
  // echo   tgl_indo('2010-11-04');exit();

  //sudah login ---------------------------------------------------------------------------------
  if(isset($_SESSION['login'])!=0){
    //tipe : pdf ------------------------------------------------------------------------------
#   if(isset($_GET['tipe']) AND $_GET['tipe']=='pdf'){
      $id     = isset($_GET['iddsn'])?$_GET['iddsn']:$_SESSION['iduser'];
      $ruwet  = base64_encode($_SESSION['idsesi'].$id.$_GET['idsesi']);
      // $ruwet  = base64_encode($_SESSION['idsesi'].$_SESSION['iduser'].$_GET['idsesi']);
      // var_dump($_GET['ruwet']);exit();
      // var_dump($ruwet);exit();
      //enkripsi "ruwet"------------------------------------------------------------------------
    if(isset($_GET['ruwet']) AND $_GET['ruwet']==$ruwet){ 
      $idwhr=isset($_GET['iddsn'])?'d.iddsn='.$_GET['iddsn']:'d.iduser='.$_SESSION['iduser'];
      // echo qsubnew(35,'perkuliahan');exit();
      // echo 'ok'.qsubnew($id,'C');exit();
			// print_r($id);exit();
		
			$sql1 	='SELECT
      						kajur.kjab,
      						kajur.knip,
      						kajur.kgol,
      						kajur.knama,
      						kajur.kpangkat,
      						u.username,u.level,
      						d.iddsn,concat(d.gelard," ",d.namad," ",d.namab," ",d.gelarb)as namaLengkap,
                  			d.agama,d.jk,d.tl,d.tgll,
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
      					AND '.$idwhr;
			$exe1	= mysqli_query($con,$sql1);
			$res1 	= mysqli_fetch_assoc($exe1);
      $jk=($res1['jk']=='P')?'Perempuan':'Laki-laki';
			// echo '<pre>';
			// 	print_r($res1);exit();
			// echo '</pre>';

ob_start(); // digunakan untuk convert php ke html
			
//biodata ---------------------------------------
// $tb='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
// <html xmlns="http://www.w3.org/1999/xhtml">
// <head>
// <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
// <title>Untitled Document</title>
// <style type="text/css">
// .tittle1{text-align:center;
// font-family:Georgia, "Times New Roman", Times, serif;
// font-size:16px;}
// .tabel{ font:Georgia, "Times New Roman", Times, serif;
// font-size:14px;}
// .tabel1{ font:Arial, Helvetica, sans-serif;
// font-size:20px;}
// </style>
// </head>

// <body>
$tb='
    <table width="100%" border="0">
      <tr>
          <td width="81%" align="right" valign="top">
              LAMPIRAN I
          </td>
          <td width="19%">: SURAT KEPUTUSAN BERSAMA MENTERI PENDIDIKAN DAN KEBUDAYAAN DAN  KEPALA BADAN KEPEGAWAIAN NEGARA<br />NOMOR  : 61409/MPK/KP/99
              <br />NOMOR  : 181 TAHUN 1999
              <br />TANGGAL: 13 OKTOBER 1999<br />________________________________________________
          </td>
      </tr>
    </table>
    <p align="center">
      <b>DAFTAR USUL PENETAPAN ANGKA KREDIT JABATAN FUNGSIONAL DOSEN</b><br>
      <b>PERGURUAN TINGGI : UNIVERSITAS NEGERI SURABAYA</b><br><br>
      Masa Penilaian Tanggal : sampai dengan ..............
    </p>


<table  class="isi" border="0">
  <tr class="head">
    <td align="center" width="3%">|</td>
    <td align="center" colspan="9">KETERANGAN PERORANGAN</td>
  </tr>
  <tr>
    <td align="center">1</td>
    <td colspan="3">Nama</td>
    <td colspan="6">'.$res1['namaLengkap'].' </td>
  </tr>
  <tr>
    <td align="center">2</td>
    <td colspan="3">NIP / NIDN</td>
    <td colspan="6">'.$res1['nip'].' / </td>
  </tr>
  <tr>
    <td align="center">3</td>
    <td colspan="3">Nomor Seri Karpeg</td>
    <td colspan="6">'.$res1['karpeg'].'</td>
  </tr>
  <tr>
    <td align="center">4</td>
    <td colspan="3">Tempat dan Tanggal Lahir</td>
    <td colspan="6">'.$res1['tl'].' / '.tgl_indo($res1['tgll']).'</td>
  </tr>
  <tr>
    <td align="center">5</td>
    <td colspan="3">Jenis Kelamin</td>
    <td colspan="6">'.$jk.'</td>
  </tr>
  <tr>
    <td align="center">6</td>
    <td colspan="3">Pendidikan Tertinggi</td>
    <td colspan="6">'.$res1['pt'].'</td>
  </tr>
  <tr>
    <td align="center">7</td>
    <td colspan="3">Pangkat/Golongan/Ruang/TMT</td>
    <td colspan="6">'.$res1['pangkat'].' / '.$res1['gol'].' / '.$res1['jab'].'</td>
  </tr>
  <tr>
    <td align="center">8</td>
    <td colspan="3">Jabatan Tenaga Pengajar/TMT</td>
    <td colspan="6">'.tgl_indo($res1['tgljabs']).'</td>
  </tr>
  <tr>
    <td align="center">9</td>
    <td colspan="3">Fakultas Jurusan</td>
    <td colspan="6">'.tgl_indo($res1['tglgols']).'</td>
  </tr>
  <tr>
    <td align="center" rowspan="2">10</td>
    <td colspan="2" rowspan="2">Masa Kerja</td>
    <td> LAMA</td>
    <td colspan="6">'.$res1['masajabs'].' tahun</td>
  </tr>
  <tr>
    <td> BARU</td>
    <td colspan="6">'.$res1['masagols'].' tahun</td>
  </tr><tr>
    <td align="center">11</td>
    <td colspan="3">Unit Kerja</td>
    <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="9">UNSUR YANG DINILAI</td>
  </tr>  <tr>
    <td align="center" rowspan="3">1.</td>
    <td colspan="3" rowspan="3">UNSUR UTAMA DAN SUB UNSUR</td>
    <td align="center" colspan="6">ANGKA KREDIT MENURUT</td>
  </tr>
  <tr>
    <td align="center" colspan="3">UNIVERSITAS NEGERI
    SURABAYA</td>
    <td align="center" colspan="3">TIM PENILAI</td>
  </tr>
  <tr>
    <td width="7%" align="center">LAMA</td>
    <td width="7%" align="center">BARU</td>
    <td width="10%" align="center">JUMLAH</td>
    <td width="5%" align="center">LAMA</td>
    <td width="4%" align="center">BARU</td>
    <td width="7%" align="center">JUMLAH</td>
  </tr>
  <tr class="head">
    <td align="center">1</td>
    <td colspan="3" align="center">2</td>
    <td align="center">3</td>
    <td align="center">4</td>
    <td align="center">5</td>
    <td align="center">6</td>
    <td align="center">7</td>
    <td align="center">8</td>
  </tr>';

  $tb.='
    <tr>
    <td>&nbsp;</td>
    <td align="center" width="3%">A</td>
    <td colspan="2">PENDIDIKAN</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" width="3%">(a)</td>
    <td>Mengikuti pendidikan sekolah dan memperoleh gelar/sebutan/ijasah/akta</td>
    <td></td>
    <td align="center">'.qnew($id,'').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">(b)</td>
    <td>Mengikuti pendidikan sekolah dan memperoleh gelar/ sebutan/ijasah/akta tambahan yang setingkat atau lebih tinggi di luar bidang ilmunya</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td  align="right">(c)</td>
    <td>Mengikuti pendidikan dan pelatihan fungsional Dosen dan memperoleh Surat Tanda Tamat Pendidikan dan Pelatihan (STTPL)</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="head">
    <td  align="center" colspan="4">JUMLAH</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>B</td>
    <td colspan="2">TRI DHARMA PERGURUAN TINGGI</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>a</td>
    <td>MELAKSANAKAN PENDIDIKAN DAN PENGAJARAN</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">1.</td>
    <td>Melaksanakan perkuliahan/tutorial dan membimbing menguji serta menyelenggarakan pendidikan di laboratorium, praktek keguruan, bengkel/studio/kebun percobaan/teknologi pengajaran dan praktek lapangan</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'perkuliahan').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">2.</td>
    <td>Membimbing seminar mahasiswa</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Membimbing seminar mahasiswa').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">3.</td>
    <td>Membimbing Kuliah Kerja Nyata (KKN), Praktek Kerja Nyata (PKN), Praktek Kerja Lapangan (PKL)</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Membimbing Kuliah Kerja Nyata (KKN), Praktek Kerja Nyata (PKN), Praktek Kerja Lapangan (PKL)').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">4.</td>
    <td>Membimbing dan ikut membimbing dalam menghasilkan laporan akhir studi/skripsi/thesis/disertasi</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'pembimbing').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">5.</td>
    <td>Bertugas sebagai penguji pada Ujian Akhir</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">6.</td>
    <td>Membina kegiatan mahasiswa di bidang akademik dan kemahasiswaan</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'pembina kegiatan mahasiswa').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">7.</td>
    <td>Mengembangkan program kuliah</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Mengembangkan program kuliah').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">8.</td>
    <td>Mengembangkan bahan pengajaran</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'untuk bahan pengajaran').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">9.</td>
    <td>Menyampaikan orasi ilmiah</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menyampaikan orasi ilmiah').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">10.</td>
    <td>Menduduki jabatan pimpinan perguruan tinggi </td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menduduki jabatan sebagai ').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">11.</td>
    <td>Membimbing dosen yang lebih rendah jabatan fungsionalnya</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'dosen yang lebih rendah jabatannya').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">12.</td>
    <td>Melaksanakan kegiatan detasering dan pencangkokan dosen</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Melaksanakan kegiatan detasering').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="head">
    <td align="center" colspan="4">JUMLAH</td>
    <td>&nbsp;</td>
    <td align="center">'.qsubnew($id,'A').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>b</td>
    <td>MELAKSANAKAN PENELITIAN</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">1.</td>
    <td>Menghasilkan karya ilmiah</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menghasilkan karya ilmiah ').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">2.</td>
    <td>Menerjemahkan/menyadur buku ilmiah</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menerjemahkan/menyadur buku ilmiah').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">3.</td>
    <td>Mengedit/menyunting karya ilmiah</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Mengedit/menyunting karya ilmiah').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">4.</td>
    <td>Membuat rancangan dan karya teknologi yang dipatenkan</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'membuat rancangan dan karya teknologi ').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">5.</td>
    <td>Membuat rancangan dan karya teknologi rancangan dan karya seni monumental/seni pertunjukan/karya sastra</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'membuat rancangan dan karya teknologi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="head">
    <td align="center" colspan="4">JUMLAH</td>
    <td>&nbsp;</td>
    <td align="center">'.qsubnew($id,'B').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>c</td>
    <td>MELAKSANAKAN PENGABDIAN PADA MASYARAKAT</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">1.</td>
    <td>Menduduki jabatan pimpinan pada lembaga pemerintahan/pejabat negara yang harus dibebaskan dari jabatan organiknya</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">2.</td>
    <td>Melaksanakan pengembangan hasil pendidikan dan penelitian yang dapat dimanfaatkan oleh masyarakat</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">3.</td>
    <td>memberi latihan/penyuluhan.penataran/ceramah pada masyarakat</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">4.</td>
    <td>memberi pelayanan kepada masyarakat atau kegiatan lain yang menunjang pelaksanaan tugas umum pemerintahan dan pembangunan</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">5.</td>
    <td>Membuat/menulis karya pengabdian pada masyarakat yang tidak dipublikasikan</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr class="head">
    <td align="center" colspan="4">JUMLAH</td>
    <td>&nbsp;</td>
    <td align="center">'.qsubnew($id,'C').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">||</td>
    <td colspan="3">UNSUR PENUNJANG</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">TUGAS POKOK DOSEN</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">1.</td>
    <td>Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">2.</td>
    <td>Menjadi anggota Panitia/Badan pada Lembaga Pendidikan</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">3.</td>
    <td>Menjadi anggota Organisasi Profesi</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">4.</td>
    <td>Mewakili Perguruan Tinggi/Lembaga pemerintah duduk dalam panitia antar lembaga</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">5.</td>
    <td>Menjadi anggota Delegasi Nasional ke pertemuan Internasional</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">6.</td>
    <td>Berperan serta aktif dalam pertemuan ilmiah</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">7.</td>
    <td>Mendapat tanda jasa/penghargaan</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">8.</td>
    <td>Menulis buku pelajaran SLTA ke bawah yang diterbitkan dan diedarkan secara Nasional</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">9.</td>
    <td>Mempunyai prestasi di bidang olahraga/Humaniora</td>
    <td>&nbsp;</td>
    <td align="center">'.qnew($id,'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr class="head">
    <td align="center" colspan="4">JUMLAH</td>
    <td>&nbsp;</td>
    <td align="center">'.qsubnew($id,'D').'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <!--keterangan bawah tabel-->
  
  <tr>
    <td align="center" width="3%" valign="top">III</td>
    <td width="3%">&nbsp;</td>
    <td colspan="8" rowspan="2">
      <p>BAHAN YANG DINILAI :</p>     
      <table border="1" width="100%">
        <tr><td>Nama</td>
          <td colspan="2">: sdfhjkshdfjshjdkfhjksdhf</td>
        </tr><tr>
          <td width="30%">NIP / NIDN</td>
          <td width="70%" colspan="2">: sdfhjkshdfjshjdkfhjksdhf</td>
        </tr><tr>
          <td width="30%">Jabatan Lama tmt</td>
          <td width="70%" colspan="2">: sdfhjkshdfjshjdkfhjksdhf</td>
        </tr><tr>
          <td width="30%">Pangkat Lama tmt</td>
          <td width="70%" colspan="2">: sdfhjkshdfjshjdkfhjksdhf</td>
        </tr><tr>
          <td width="30%">Jurusan / Program Studi</td>
          <td width="70%" colspan="2">: sdfhjkshdfjshjdkfhjksdhf</td>
        </tr><tr>
          <td width="30%">Bidang Ilmu  / Mata Kuliah yang dibina  </td>
          <td width="70%" colspan="2">: sdfhjkshdfjshjdkfhjksdhf</td>
        </tr><tr>
          <td width="30%">Keterangan Loncat </td>
          <td width="70%" colspan="2">: sdfhjkshdfjshjdkfhjksdhf</td>
        </tr><tr>
          <td width="30%">Fotocopy Ijazah</td>
          <td width="70%" colspan="2">: sdfhjkshdfjshjdkfhjksdhf</td>
        </tr><tr>
          <td width="30%"></td>
          <td width="10%"></td>
          <td width="60%">
            <i>Surabaya,</i><br> 
            Yang Mengusulkan<br>
            Ketua Jurusan '.$res1['jur'].' '.$res1['fak'].' UNESA <br><br><br>
            '.$res1['knama'].'<br>
            NIP '.$res1['knip'].'
          </td>
        </tr>
      </table>
    </td>
</table>';


// </body>
// </html>';

// echo '<pre>';
//   print_r($x);
// echo '</pre>';
echo $tb;
      #generate html -> PDF ------------
        $out = ob_get_contents();
        ob_end_clean(); 
        $mpdf=new mPDF('c','A4','');   
        $mpdf->SetDisplayMode('fullpage');   
        $stylesheet = file_get_contents('../../lib/mpdf/r_cetak.css');
        $mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this is css/style only and no body/html/text
        $mpdf->WriteHTML($out);
        $mpdf->Output();
      #end of generate html -> PDF ------------

#		}
	}
}
?>