<style>
	.capitizer{
		text-transform:capitalize;
	}
	#loadarea{
		height:45px;
/*	}#pagination{
		color:white;list-style:none;
	}
*/	.vwimg{
		height:100px;
		opacity:0.8;
	}.vwimg:hover{
		opacity:1;
	}.error-info{
		/*padding: .2em .6em .3em;*/
		padding: .4em;
		font-size: 75%;
		font-weight: bold;
		line-height: 1;
		color: #ffffff;
		border-radius: .25em;
		background-color:red;
  	}.trtable:hover{
		background-color:#3FC;
	}
	</style>

<script src="client/s_duk.js"></script>
<h3><div id="loadarea"></div></h3>
 	<input type="hidden" placeholder="iddsn" id="iddsnH"/>
	<input type="hidden" placeholder="id_gol" id="idgoltgtH"/>
	<input type="hidden" placeholder="id_jab" id="idjabtgtH"/>
	<input type="hidden" placeholder="jabtgt" id="jabtgtH"/>
	<input type="hidden" placeholder="pointgt" id="pointgtH"/>
	<input type="hidden" placeholder="subtotNumDt" id="subtotNumDtH"/>
	<input type="hidden" placeholder="id_ptDt" id="idptDtH"/>
	<input type="hidden" placeholder="ptDt" id="ptDtH"/>
	<input type="hidden" placeholder="gtotDt"id="gtotDtH"/>
	<input type="hidden" placeholder="urutGolDt"id="urutGolDtH"/>
	<input type="hidden" placeholder="kuranganDt" id="kuranganH"/>
	<input type="hidden" placeholder="idsesi" id="idsesiH" value="<?php echo $_SESSION['idsesi']; ?>" />
  <!--panel 1-->
<div class="container"id="i_kegPN" style="display:none;">
	<button id="viewBC" class="btn btn-primary"><i class='icon-list'></i> Lihat Semua</button>
	
	<h4 align="center">Biodata</h4>
	<input type="hidden" placeholder="goltgt" id="goltgtH"/>
	<div class="navigasi" style="margin-bottom:20px;">
	<p class="pull-right">
		<!-- <span data-toggle="tooltip" data-placement="top" title="setujui dosen untuk naik pangkat" id="rekomBC"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
		<button class="btn" data-toggle="tooltip" data-placement="top" title="setujui dosen untuk naik pangkat" id="rekomBC">rekomendasi</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a id="dupakBC" style="display:none;" target="_blank" class="btn btn-secondary pull-right">
            cetak DUPAK<i class="icon-print"></i>
        </a> 
	</p>
	<br>
		<table  class="tabel-responsive" xcellpadding="3" width="100%">
			<input type="hidden" id="iduser" name="iduser">
			<tbody>
				<tr>
					<td xwidth="24%" nowrap="nowrap"  class="capitalizer"><strong>NIP</strong></td>
					<td xwidth="3%" nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
					<td xwidth="21%" nowrap="nowrap" class="capitalizer" id="nipTD"></td>
				
					<td xwidth="33%" nowrap="nowrap"  class="capitalizer"><strong>Pendidikan Terakhir</strong></td>
					<td xwidth="3%" nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
					<td xwidth="16%" nowrap="nowrap"  class="capitalizer" id="ptTD"></td>
				</tr>
				<tr>
					<td nowrap="nowrap"  class="capitalizer"><strong>Nama Lengkap</strong></td>
					<td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
					<td nowrap="nowrap" class="capitalizer" id="namaTD"></td>
					
					<td nowrap="nowrap"  class="capitalizer"><strong>Fungsional Jab/Gol</strong></td>
					<td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
					<td nowrap="nowrap"  class="capitalizer" id="fungJGTD"></td>
				</tr>
				<tr>
					<td nowrap="nowrap"  class="capitalizer"><strong>Agama</strong></td>
					<td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
					<td nowrap="nowrap"  class="capitalizer" id="agamaTD"></td>
					
					<td nowrap="nowrap"  class="capitalizer"><strong>TMT Jab/Gol </strong></td>
					<td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
					<td nowrap="nowrap"  class="capitalizer" id="tmtJGTD"></td>
				</tr>
				<tr>
	                <td nowrap="nowrap"  class="capitalizer"><strong>Jenis Kelamin</strong></td>
	                <td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
	                <td nowrap="nowrap"  class="capitalizer" id="jkTD"></td>
				
	                <td nowrap="nowrap"  class="capitalizer"><strong>Masa Jab/Gol </strong></td>
	                <td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
	                <td nowrap="nowrap"  class="capitalizer"id="masaJGTD"></td>
	            </tr>
	            <tr>
	                <td nowrap="nowrap"  class="capitalizer"><strong>Tempat, Tgl Lahir</strong></td>
	                <td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
	                <td nowrap="nowrap"  class="capitalizer" id="ttlTD"></td>
					
					<!-- <td nowrap="nowrap"  class="capitalizer"><strong>Target Jab/Gol </strong></td> -->
	                <!-- <td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td> -->
	                <!-- <td nowrap="nowrap"  class="capitalizer" id="tgtJGTD"></td> -->
				</tr>            
				
			</tbody>
		</table>
	</div>

	<h4 class="capitalizer" align="center">Kegiatan</h4>
		<div id="kegDV">
			<!--list tabel kegiatan per kategori-->
		</div>

	<div>.</div>
	<div>.</div>
	<div>.</div>
	
	<!--<div class="alert">
		<button type="button" class="close" data-dismiss="alert">&times</button>
		hallllosss
	</div>-->
	
<!-- <div id="popMe" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<a  type="button" class="close" data-dismiss="modal" aria-hidden="true">X</a>
	<div class="modal-body">
		<div class="span3" id="popMeDV">
			
		</div>
	</div>
</div>
 -->
 	<div>.</div>
	<div>.</div>
	<div>.</div>
</div>
<!--end of panel 1-->

<!--panel 2-->
<div class="container" id="v_kegPN">
	<!--table (header)--> 
        <table class="table table-responsive table-bordered table-striped table-hover" width="100%" border="0">
            
            <tr class="info">
                <td rowspan="2"><b >Status</b></td>
                <td rowspan="2"><b>NIP</b></td>
                <td rowspan="2"><b style="text-align:center" >Nama</b></td>
                <td rowspan="2"><b>Golongan</b></td>
                <td rowspan="2"><b>Jabatan</b></td>
                <td rowspan="2"><b>Usia</b></td>
                <td align="center" colspan="2"><b style="text-align:center">Poin</b></td>
                <td rowspan="2"><b>Aksi</b></td>
            </tr>
            <tr class="info">
                <td><b>Sekarang</b></td>
                <td><b>Kumulatif</b></td>
            </tr>

            <tbody id="isi">
            
            </tbody>
        </table>
        <div>.</div>
        <div>.</div>
        <div>.</div>
	<!--end of table (header)--> 
    
    <!--table (content)--> 
    	<!--<div class="row" id="isi"></div>-->
    <!--end of table (content)--> 
</div>
<!--end of panel2-->
    <div id="popMe" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<!-- <a  type="button" class="close" data-dismiss="modal" aria-hidden="true">X</a> -->

		<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 id="popHeader" align="center"></h3>
        </div>

		<div class="modal-body">
			<div id="popMeDV">
				
			</div>
		</div>

        <div id="popFooter" class="modal-footer">
            <!-- <a href="#" class="btn">Tutup</a> -->
        </div>

	</div>
	
