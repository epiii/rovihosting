<style>
	#loadarea{
		height:40px;
	}#paginationx{
		color:white;list-style:none;
	}.vwimg{
		width:70px;
		opacity:0.7;
	}.vwimg:hover{
		opacity:1;
	}.capitalizer{
		text-transform:capitalize;}
</style>

<div id="loadarea"><h3>VIEW REKAPITULASI</h3></div>

<!--pop up info naik/belum atau kurangan poin-->
	<div id="popMe" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Notifikasi Rekomendasi Kenaikan Pangkat </h3>
        </div>
        <!-- <a  type="button" class="close" data-dismiss="modal" aria-hidden="true">x </a> -->
		
        <div class="modal-body">
			<div xclass="span4" id="popMeDV">
		        
                <img src="../img/loader.gif">	
			</div>
		</div>

        <div class="modal-footer">
            <!-- <a href="#" class="btn">Tutup</a> -->
        </div>
	</div>
<!--info naik/belum-->

<!-- panel 1 untuk menampilkan biodata + list kegiatan per kategori -->
<div class="container" id="v_kegPN"><br>
    <!--biodata dosen-->
    <h4 align="center">Biodata</h4>
    <div class="navigasi" style="margin-bottom:20px;">
    <p class="pull-right">
        <!-- <a style="display:none;" id="cetakBC" class="btn "><i class="icon-print"></i> &nbsp;cetak</a> -->
<!--          <a id="dupakBC" style="display:none;" target="_blank" class="btn btn-secondary pull-right">
            cetak DUPAK<i class="icon-print"></i>
        </a>&nbsp;&nbsp;&nbsp;
 -->        <a id="pakBC" style="display:none;" target="_blank" class="btn btn-secondary pull-right">
            cetak PAK<i class="icon-print"></i>
        </a>&nbsp;&nbsp;&nbsp;
        <button id="infoBC" class="btn "><i class="icon-search"></i> &nbsp;info</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </p>
    <table cellpadding="3" width="100%">
		<input type="hidden" id="idsesi" value="<?php echo $_SESSION['idsesi'];?>">
		<input type="hidden" id="iduser" value="<?php echo $_SESSION['iduser'];?>">
        <tbody>
            <tr>
                <td nowrap="nowrap"  class="capitalizer"><strong>NIP</strong></td>
                <td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
                <td nowrap="nowrap" class="capitalizer" id="_nip"></td>
            
                <td  nowrap="nowrap"  class="capitalizer"><strong>Pendidikan Terakhir</strong></td>
                <td  nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
                <td  nowrap="nowrap"  class="capitalizer" id="_pt">
					<label onmouseover="tooltipx(this);" data-toggle="tooltip" data-placement="top" title="pend. terakhir memenuhi" class="label label-success">

					</label>
					<i class="icon-ok"></i>
				</td>
            </tr>
            <tr>
                <td nowrap="nowrap"  class="capitalizer"><strong>Nama Lengkap</strong></td>
                <td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
                <td nowrap="nowrap" class="capitalizer" id="_nm"></td>
                
                <td nowrap="nowrap"  class="capitalizer"><strong>Fungsional Jab/Gol</strong></td>
                <td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
                <td nowrap="nowrap"  class="capitalizer" id="_fungJG"></td>
            </tr>
            <tr>
                <td nowrap="nowrap"  class="capitalizer"><strong>Agama</strong></td>
                <td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
                <td nowrap="nowrap"  class="capitalizer" id="_agm"></td>
                
                <td nowrap="nowrap"  class="capitalizer"><strong>TMT Jab/Gol</strong></td>
                <td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
                <td nowrap="nowrap"  class="capitalizer" id="_tmtJG"></td>
			</tr>
            <tr>
                <td nowrap="nowrap"  class="capitalizer"><strong>Jenis Kelamin</strong></td>
                <td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
                <td nowrap="nowrap"  class="capitalizer" id="_jk"></td>
			
                <td nowrap="nowrap"  class="capitalizer"><strong>Masa Jab/Gol</strong></td>
                <td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
                <td nowrap="nowrap"  class="capitalizer" id="_masaJG"></td>
            </tr>
            <tr>
                <td nowrap="nowrap"  class="capitalizer"><strong>Tempat, Tanggal Lahir</strong></td>
                <td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
                <td nowrap="nowrap"  class="capitalizer" id="_ttl"></td>
				
				<!-- <td nowrap="nowrap"  class="capitalizer"><strong>Target Jab/Gol</strong></td>
                <td nowrap="nowrap"  class="capitalizer"><strong>:</strong></td>
                <td nowrap="nowrap"  class="capitalizer" id="_tgtJG"></td> -->
			</tr>
        </tbody>
    </table>			
    </div>	
    <!--end of  biodata dosen-->
	
    <!-- menampilkan kegiatan per kategori -->
    <h4 class="capitalizer" align="center">kegiatan</h4>
    <div id="isi">
        <!--.....-->
    </div>
    <!-- end of  menampilkan kegiatan per kategori -->
    
    <div>&nbsp;<br><br><br><br></div>
    <div>&nbsp;</div>
</div>
<!-- end of panel 1 untuk menampilkan biodata + list kegiatan per kategori -->

<script src="client/s_rekapitulasi.js"></script>