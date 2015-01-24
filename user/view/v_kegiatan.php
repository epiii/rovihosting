  <style>
    #loadarea{
		height:45px;
	}
    .vwimg{
		height:80px;
		opacity:0.8;
	}.vwimg:hover{
		opacity:1;
	}.error-info{
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

<!-- <script src="client/s_kegiatan.js"></script> -->
<h3><div id="loadarea">VIEW KEGIATAN</div></h3>

<!--panel 1 : list/tabel kegiatan-->
<div class="container" id="v_kegPN">
	<divX id="loadtabel"></divX>
<!--     <button onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="Tambah Kegiatan" id="addBC" class="pull-right btn btn-primary"><i class='icon-plus-sign'></i> ADD</button>&nbsp;&nbsp;
    <button  onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="SISA poin pengajuan (sebelum menggunakan aplikasi) " id="opsiBC" class="pull-right btn btn-secondary"><i class='icon-cog'></i> Sisa Poin</button>
 -->
    <div id="popMe" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <a  type="button" class="close" data-dismiss="modal" aria-hidden="true">X</a>
        <div class="modal-body">
            <div id="popMeDV">
                
            </div>
        </div>
    </div>

    <!--tab menu--> 
    <!-- <a onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="Pendidikan & Pengajaran"  href="#panel1" data-toggle="tab" onclick="return loadData('tampil',1,'','');" style="color:#080"><b>A</b></a> -->
    <!-- <span class="badge badge-important">33</span> -->
        <div style="padding-left:20px; padding-top:20px;" class="tabbable" id="tabs-104268">
            <input type="hidden" id="id_katkegH" value="1">
            <ul class="nav nav-tabs" id="nav-tabs">
                <!-- <span id="idtab"> -->
                    <!-- <li class="active">
                        <a  title="Pendidikan & Pengajaran" href="#panel1" data-toggle="tab" onclick="return loadData('tampil',1,'','');" style="color:#080"><b>A</b></a>
                    </li>
                    <li >
                        <a  title="Penelitian" href="#panel2" data-toggle="tab" onclick="return loadData('tampil',2,'','');" style="color:#080"><b>B</b></a>
                    </li>       
                    <li >
                        <a title="Pengabdian" href="#panel3" data-toggle="tab" onclick="return loadData('tampil','3','','');" style="color:#080"><b>C</b></a>
                    </li>       
                    <li >
                        <a title="Penunjang" href="#panel4" data-toggle="tab" onclick="return loadData('tampil','4','','');" style="color:#080"><b>D</b></a>
                    </li> -->
                <!-- </span> -->
            </ul>
        </div>
    <!--end of tab menu-->
	
	<!--table (header)--> 
        <table class="table table-bordered table-striped  table-hover" width="100%" border="0">
            <tr>
                <td>
                    <select id="statusTS" class="span1">
                        <option value="">Semua</option>
                        <option value="new">Pending</option>
                        <option value="checked">Tidak Valid</option>
                        <option value="valid">Valid</option>
                    </select>
                </td>
                <td><input class="span4" type="text" id="kegiatanTS" placeholder="cari kegiatan .."></td>
                <td><input class="span4" type="text" id="subunsurTS" placeholder="cari subunsur .."></td>
                <td><input class="span1" type="text" id="poinTS" placeholder="cari poin .."></td>
                <td colspan="2" style="background-color:grey;">            
                    <span class="pull-right">
                        <button onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="Tambah Kegiatan" id="addBC" class="pull-right btn btn-primary"><i class='icon-plus-sign'></i>tambah</button>
                        <button  onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="SISA poin pengajuan (sebelum menggunakan aplikasi) " id="opsiBC" class="pull-right btn btn-secondary"><i class='icon-cog'></i>sisa</button>
                    </span>
                </td>
            </tr>
            <tr style="background-color:grey;color:white;"  xclass="info">
                <td><b>Status</b></td>
                <td><b>Kegiatan<!-- <img src="../img/checked.png"><i class="icon-ok"></i> --></b></td>
                <td><b>Sub Unsur</b></td>
                <td><b>Poin</b></td>
                <!-- <td><b>Tanggal</b></td> -->
                <td colspan="2"><b>Aksi</b>
                </td>
            </tr>
            
            <tbody id="isi">
            
            </tbody>
        </table>
	<!--end of table (header)--> 
        <div>.</div>
        <div>.</div>
        <div>.</div>
</div>
<!--end of panel 1 : list/tabel kegiatan-->

<!--panel 2 : form ADD n EDIT kegiatan-->
<div class="container" id="i_kegPN" style="display:none;">
    <button id="viewBC" style="display:none;"class="btn btn-primary"><i class='icon-list'></i> VIEW</button>
    <div class="span2"></div>
    <div class="span8">
       <form autocomplete="off" method="post" enctype="multipart/form-data" 
            name="form-daftar" class="form-horizontal" >
            <input type="hidden" id="idformTB" name="idformTB"/>
                <div class="control-group">
                    <label class="control-label">Cum :</label>
                    <div  class="controls">
                        <select  class="span5 pull-left" name="id_katkegTB" id="id_katkegTB" required >
                            <option value=''>pilih kategori..</option>
                            <option value='1'>(A) Pendidikan dan Pengajaran</option>
                            <option value='2'>(B) Penelitian</option>
                            <option value='3'>(C) Pengabdian</option>
                            <option value='4'>(D) Penunjang</option>
                        </select>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Sub Unsur : </label>
                    <div class="controls">
                        <select class="span5 pull-left" name="id_kegTB" id="id_kegTB" required >
                            <option value=''>pilih kategori dahulu..</option>
                        </select>
                    </div><span id="loadcombo"></span>
                </div>

                <div class="control-group" id="opsionalDV" style="display:none;">
                    <!-- area tambahan (opsional) -->
                </div>
                
                <div class="control-group">
                    <label class="control-label">Kegiatan :</label>
                    <div class="controls">
                        <textarea  class="span5 pull-left" id="ketTB" name="ketTB" placeholder="example => hand out : pengolahan air hujan untuk pemukiman " required></textarea>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Poin :</label>
                    <div class="controls">
                        <input  class="span5 pull-left" type="text" id="poinTB" name="poinTB" readonly>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Batas Kepatutan :</label>
                    <div class="controls" >
                        <textarea  class="span5 pull-left" id="batutTB" name="batutTB" readonly></textarea>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Bukti Kegiatan :</label>

                    <div class="controls">
                        <textarea  class="span5 pull-left" id="bukegTA" readonly></textarea>
                    </div><br>
                    <div class="controls">
                        <a id="bukegBC" class="btn btn-secondary">+</a>
                    </div>
                    <span class="controls">
                        <label id="imgInfo" class="label label-important"></label>
                    </span>
                </div>
                <table class="table table-hover table-striped" id="imgTB" width="80%">

                </table>            
                <button  id="simpanBC"class="btn btn-primary" >Simpan</button>
                <div >.</div>
                <div >.</div>
            </form>
   </div> 
   <div class="span2">.
   </div>
</div>
<!--end of panel 2 : form ADD n EDIT kegiatan-->
<script src="client/s_kegiatan.js"></script>
