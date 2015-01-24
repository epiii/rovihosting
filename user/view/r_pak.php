<?php
  session_start();
  require_once '../../lib/koneksi.php';
  require_once '../../lib/tglindo.php';
  require_once '../../lib/mpdf/mpdf.php';
  require_once '../server/f_pak.php';
  
  //sudah login ---
  if(isset($_SESSION['login'])!=0){
    //tipe file pdf ---
    if(isset($_GET['tipe']) AND $_GET['tipe']=='pdf'){
      $ruwet  = base64_encode($_SESSION['idsesi'].$_SESSION['iduser'].$_SESSION['idsesi']);
      //enkripsi ruwet ---
      if(isset($_GET['ruwet']) AND $_GET['ruwet']==$ruwet){ 
          $id=isset($_GET['iddsn'])?'d.iddsn='.$_GET['iddsn']:'d.iduser='.$_SESSION['iduser'];
          ob_start(); // digunakan untuk convert php ke html
          $sql='SELECT
                  u.username,u.level,
                  concat(d.gelard," ",d.namad," ",d.namab," ",d.gelarb)nmLengkap,d.iddsn,
                  /*d.gelard,d.gelarb,d.namad,d.namab,*/
                  d.agama,d.jk,concat(d.tl,", ",d.tgll)ttl,
                  d.nip,d.karpeg,pt.id_pt,pt.pt,
                  j.id_jab,j.jab,
                  g.id_gol,g.gol,g.pangkat,
                  h.tgljabs,h.tglgols,
                  h.tgltmp,g.gol,j.jab,
                  f.fak,jr.jur,
                  YEAR (CURDATE()) - YEAR (h.tglgols) AS masagols,
                  YEAR (CURDATE()) - YEAR (h.tgljabs) AS masajabs,
                  (YEAR(CURDATE()) - YEAR(tgll)) AS umur

                FROM
                  dsn d
                  JOIN USER u ON u.iduser = d.iduser
                  JOIN prodi p ON p.idprodi = d.idprodi
                  JOIN jur jr ON jr.idjur = p.idjur
                  JOIN fak f ON f.idfak = jr.idfak
                  JOIN histjab h ON h.iddsn = d.iddsn
                  JOIN pt ON pt.id_pt = h.id_pt
                  JOIN gol g ON g.id_gol = h.id_gol
                  JOIN jab j ON j.id_jab = g.id_jab

                WHERE
                  h.`status` = 1 and '.$id;
          $exe = mysql_query($sql);
          $res = mysql_fetch_assoc($exe);
          $jk=($res['jk']=="P")?"Perempuan":"Laki-laki";
          $subOld = qpak($id,"A","remain") + qpak($id,"B","remain") + qpak($id,"C","remain") ;
          $subNew = qpak($id,"A","poinCur") + qpak($id,"B","poinCur") + qpak($id,"C","poinCur") ;
          $subUsed= qpak($id,"A","subtotNumDt") + qpak($id,"B","subtotNumDt") + qpak($id,"C","subtotNumDt") ;
          // var_dump($subOld);exit();
          $out='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
              <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>Untitled Document</title>
                </head>

                <body>
                  <table width="100%" border="0">
                    <tr>
                        <td width="81%" align="right" valign="top">
                            LAMPIRAN VI
                        </td>
                        <td width="19%">: SURAT KEPUTUSAN BERSAMA MENTERI PENDIDIKAN DAN KEBUDAYAAN DAN  KEPALA BADAN KEPEGAWAIAN NEGARA<br />NOMOR  : 61409/MPK/KP/99
                            <br />NOMOR  : 181 TAHUN 1999
                            <br />TANGGAL: 13 OKTOBER 1999<br />________________________________________________
                        </td>
                    </tr>
                  </table>

              <p align="center"><b>PENETAPAN ANGKA KREDIT JABATAN FUNGSIONAL DOSEN</b><br>
              Nomor : ...........................................<br>
              Masa Penilaian Tanggal : sampai dengan ..............</p>
            

                <table class="isi" width="100%" border="0">
                  <tr class="head">
                    <td align="center" width="3%">|</td>
                    <td align="center" colspan="9">KETERANGAN PERORANGAN</td>
                  </tr>
                  <tr>
                    <td align="center">1</td>
                    <td width="40%" colspan="3">Nama</td>
                    <td colspan="6"><b>'.$res['nmLengkap'].'</b></td>
                  </tr>
                  <tr>
                    <td align="center">2</td>
                    <td colspan="3">NIP / NIDN</td>
                    <td colspan="6">'.$res['nip'].'</td>
                  </tr>
                  <tr>
                    <td align="center">3</td>
                    <td colspan="3">Nomor Seri Karpeg</td>
                    <td colspan="6">'.$res['karpeg'].'</td>
                  </tr>
                  <tr>
                    <td align="center">4</td>
                    <td colspan="3">Tempat dan Tanggal Lahir</td>
                    <td colspan="6">'.$res['ttl'].'</td>
                  </tr>
                  <tr>
                    <td align="center">5</td>
                    <td colspan="3">Jenis Kelamin</td>
                    <td colspan="6">'.$jk.'</td>
                  </tr>
                  <tr>
                    <td align="center">6</td>
                    <td colspan="3">Pendidikan Tertinggi</td>
                    <td colspan="6">'.$res['pt'].'</td>
                  </tr>
                  <tr>
                    <td align="center">7</td>
                    <td colspan="3">Pangkat/Golongan Ruang/TMT</td>
                    <td colspan="6">'.$res['pangkat'].' / '.$res['gol'].' / '.tgl_indo($res['tglgols']).'</td>
                  </tr>
                  <tr>
                    <td align="center">8</td>
                    <td colspan="3">Jabatan Fungsional/TMT</td>
                    <td colspan="6">'.$res['jab'].' / '.tgl_indo($res['tgljabs']).'</td>
                  </tr>
                  <tr>
                    <td align="center">9</td>
                    <td colspan="3">Fakultas / Jurusan</td>
                    <td colspan="6">'.$res['fak'].' / '.$res['jur'].'</td>
                  </tr>
                  <tr>
                    <td align="center" rowspan="2">10</td>
                    <td width="5" colspan="2" rowspan="2">Masa Kerja</td>
                    <td> LAMA</td>
                    <td colspan="6"> tahun  bulan</td>
                  </tr>
                  <tr>
                    <td> BARU</td>
                    <td colspan="6"> tahun  bulan</td>
                  </tr>
                  <tr>
                    <td align="center">11</td>
                    <td colspan="3">Unit Kerja</td>
                    <td colspan="6">UNIVERSITAS NEGERI SURABAYA</td>
                  </tr>
                  <tr class="head">
                    <td rowspan="2">||</td>
                    <td colspan="3" rowspan="2">PENETAPAN ANGKA KREDIT</td>
                    <td align="center" rowspan="2">LAMA</td>
                    <td align="center" rowspan="2">BARU</td>    
                    <td align="center" colspan="2">JUMLAH</td>
                  </tr>
                  <tr class="head">
                    <td align="center">DIGUNAKAN</td>
                    <td align="center">LEBIHAN</td>
                  </tr>
                  <tr>
                    <td align="center">1.</td>
                    <td colspan="3">UNSUR UTAMA</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr>
                   <tr>
                    <td align="center">&nbsp;</td>
                    <td align="left">A</td>
                    <td colspan="2">PENDIDIKAN</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr>
                   <tr>
                    <td align="center">&nbsp;</td>
                    <td align="left">B</td>
                    <td colspan="2">TRIDHARMA PERGURUAN TINGGI</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr> <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center"></td>
                    <td colspan="2">a. Melaksanakan pendidikan dan pengajaran</td>
                    <td align="center">'.qpak($id,"A","remain").'</td>
                    <td align="center">'.qpak($id,"A","poinCur").'</td>
                    <td align="center">'.qpak($id,"A","subtotNumDt").'</td>
                    <td align="center">&nbsp;</td>
                  </tr> <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center"></td>
                    <td colspan="2">b. Melaksanakan penelitian</td>
                    <td align="center">'.qpak($id,"B","remain").'</td>
                    <td align="center">'.qpak($id,"B","poinCur").'</td>
                    <td align="center">'.qpak($id,"B","subtotNumDt").'</td>
                    <td align="center">&nbsp;</td>
                  </tr> <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center"></td>
                    <td colspan="2">c. Melaksanakan pengabdian pada masyarakat</td>
                    <td align="center">'.qpak($id,"C","remain").'</td>
                    <td align="center">'.qpak($id,"C","poinCur").'</td>
                    <td align="center">'.qpak($id,"C","subtotNumDt").'</td>
                    <td align="center">&nbsp;</td>
                  </tr> <tr>
                    <td align="center">&nbsp;</td>
                    <td colspan="3" align="right">JUMLAH</td>
                    <td align="center"><b>'.$subOld.'</b></td>
                    <td align="center"><b>'.$subNew.'</b></td>
                    <td align="center"><b>'.$subUsed.'</b></td>
                    <td align="center">&nbsp;</td>
                  </tr> <tr>
                    <td align="center" valign="top">2.</td>
                    <td colspan="3">UNSUR PENUNJANG<p>Melaksanakan Penunjang Tugas Pokok Dosen</p>
                    </pre></td>
                    <td align="center">'.qpak($id,"D","remain").'</td>
                    <td align="center">'.qpak($id,"D","poinCur").'</td>
                    <td align="center">'.qpak($id,"D","subtotNumDt").'</td>
                    <td align="center">&nbsp;</td>
                  </tr> <tr>
                    <td align="center">&nbsp;</td>
                    <td colspan="3" align="right">JUMLAH</td>
                    <td align="center"><b>'.qpak($id,"D","remain").'</b></td>
                    <td align="center"><b>'.qpak($id,"D","poinCur").'</b></td>
                    <td align="center"><b>'.qpak($id,"D","subtotNumDt").'</b></td>
                    <td align="center">&nbsp;</td>
                  </tr> <tr class="head">
                    <td colspan="4" align="right">JUMLAH UNSUR UTAMA DAN UNSUR PENUNJANG</td>
                    <td align="center"><b>'.qpakTot($id,"remain").'</b></td>
                    <td align="center"><b>'.qpakTot($id,"poinCur").'</b></td>
                    <td align="center"><b>'.qpakTot($id,"subtotNumDt").'</b></td>
                    <td align="center">&nbsp;</td>
                  </tr> <tr>
                    <td align="center" valign="top">3.</td>
                    <td colspan="7">Dapat diangkat dalam jabatan : ............................................................ (tmt. ............................................ dalam mata kuliah : .................................................................. dan dapat dinaikkan pangkatnya menjadi : ......................)</td>
                    </tr>

                  </table>
                  <!--<table width="100%" border="0">
                  <tr>
                    <td>&nbsp;</td>
                    <td width="40%"><br /><img src="file:///C|/xampp/htdocs/bel/img/M.JPG" /></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                <pre style="font-family:Arial, Helvetica, sans-serif; font-size:13px">
                Kepada : <b>'.$res['nmLengkap'].'</b>
                NIP/NIDN : Alamat : FT Universitas Negeri Surabaya
                TEMBUSAN, disampaikan dengan hormat kepada :
                </pre>
                </td>
                    </tr>
                </table>-->
                    <table border="0" width="100%" style="float:right;">
                        <tr>
                            <td valign="top" width="55%">&nbsp;</td>
                            <td>Ditetapkan di<p>Pada Tanggal</p> </td>
                            <td>: Surabaya <p>: ...</p></td>
                        </tr>
                        <tr>
                          <td width="55%"></td>  
                          <td colspan="2">
                            ________________________________
                            Rektor Universitas Negeri Surabaya<br><br><br>
                            <p><b>Prof. Dr. MUCHLAS SAMANI</b></p>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="3">Kepada : <b>'.$res['nmLengkap'].'</b></td>
                        </tr>
                        <tr>
                          <td colspan="3">NIP/NIDN :'.$res['nip'].' /,  Alamat :<b> FT Universitas Negeri Surabaya</b></td>
                        </tr>
                        <tr>
                          <td colspan="3"><b>TEMBUSAN</b>, disampaikan dengan hormat kepada :</td>
                        </tr>
                        <tr>
                          <td colspan="3">
                            <ol>
                              <li>Pimpinan Unit Kerja Tenaga Pengajar yang bersangkutan;</li>
                              <li>Kepala Badan Administrasi Kepegawaian Negara;</li>
                              <li>Sekretaris Tim Penilai yang bersangkutan;</li>
                              <li>Pertinggal pada Pejabat yang menetapkan angka kredit.</li>
                            </ol>
                          </td>
                        </tr>
                    </table>';
          echo $out;
  
        #generate html -> PDF ------------
          $out2 = ob_get_contents();
          ob_end_clean(); 
          $mpdf=new mPDF('c','A4','');   
          $mpdf->SetDisplayMode('fullpage');   
          $stylesheet = file_get_contents('../../lib/mpdf/r_cetak.css');
          $mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this is css/style only and no body/html/text
          $mpdf->WriteHTML($out);
          $mpdf->Output();
        #end of generate html -> PDF ------------
      } //end of enkripsi ruwet -- 
      else{ // ruwet  =salah 
          echo 'kode enkripsi (url) tidak sesuai ';
      } // end of ruwet =salah     
    } //end of file pdf --
    else{ // tipe file bukan pdf ---
      echo 'bukan tipe pdf ';
    } // end of tipe file bukan pdf ---
  } // end of sudah login --
  else{ //belum login ---
    echo '<script>alert("anda belum login");window.location="../";</script>';
  } //end of belum login ---
// echo $out;
