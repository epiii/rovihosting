<style>
	#loadarea{
		height:45px;
	}#pagination{
		color:white;list-style:none;
	}.vwimg{
		height:80px;
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

<script src="client/s_kegiatan.js"></script>
<h3><div id="loadarea"><i class="icon-th-list"></i> DAFTAR KEGIATAN</div></h3>
<div>
	<button id="viewBC" style="display:none;"class="btn btn-primary"><i class='icon-list'></i> Lihat Semua</button>
	<!-- <button id="addBC" class="btn btn-primary"><i class='icon-plus-sign'></i> Tambah</button> -->
</div>
	
<!--panel 1-->
<div class="span8"id="i_kegPN" style="display:none;">
<div class="span8">
<form autocomplete="off" method="post" name="form-daftar" class="form-horizontal" >
<input type="hidden" id="idformTB" name="idformTB"/>
<div class="control-group">
	<label class="control-label">Kategori :</label>
	<div class="controls">
		<select name="id_katkegTB" id="id_katkegTB" required >
			<option value=''>pilih kategori..</option>
			<option value='1'>Pendidikan dan Pengajaran</option>
			<option value='2'>Penelitian</option>
			<option value='3'>Pengabdian</option>
			<option value='4'>Penunjang</option>
		</select>
	</div>
	<span id="katkegInfo"></span>
</div>

<div class="control-group">
	<label class="control-label">Sub Kategori :</label>
	<div class="controls">
		<select name="id_subkatkegTB" id="id_subkatkegTB" required >
			<option value=''>pilih kategori dulu..</option>
		</select>
	</div>
	<span id="katkegInfo"></span>
</div>

<div class="control-group">
	<input type="hidden" id="nakeg1TB" name="nakeg1TB">
	<label class="control-label">Nama Kegiatan</label>
	<div class="controls" >
		<input disabled type="text" name="nakegTB" id="nakegTB" required placeholder="nama kegiatan">
	</div>
	<span id="nakegInfo"></span>
</div>

<div class="control-group">
	<label class="control-label">Batas Kepatutan</label>
	<div class="controls" >
		<input type="text" id="batutTB" name="batutTB" required placeholder="batas kepatutan">
	</div>
</div>

<div class="control-group">
	<label class="control-label">Bukti Kegiatan</label>
	<div class="controls" >
		<input type="text" id="bukegTB" name="bukegTB" required placeholder="bukti kegiatan"></textarea>
	</div>
</div>

<div class="control-group">
	<label class="control-label">Point</label>
	<div class="controls">
		<input type="number" id="poinTB" name="poinTB" required placeholder="point">
	</div><span id="poinInfo"></span>
</div>

<button  id="simpanBC"class="btn btn-primary" >Simpan</button>
<div >.</div>
<div >.</div>
</form>
</div>
</div>
<!--end of panel 1-->

<!--panel 2-->
<div class="span8" id="v_kegPN">
<divX id="loadtabel"></divX>

<!--tab menu--> 
<div style="padding-left:20px; padding-top:20px;" class="tabbable" id="tabs-104268">
<ul class="nav nav-tabs">
	<li class="active">
		<a href="#panel1" data-toggle="tab" onclick="return loadData('tampil',1,'','');" style="color:#080"><b>Pendidikan dan Pengajaran</b></a>
	</li>
	<li >
		<a href="#panel2" data-toggle="tab" onclick="return loadData('tampil',2,'','');" style="color:#080"><b>Penelitian</b></a>
	</li>		
	<li >
		<a href="#panel3" data-toggle="tab" onclick="return loadData('tampil','3','','');" style="color:#080"><b>Pengabdian</b></a>
	</li>		
	<li >
		<a href="#panel4" data-toggle="tab" onclick="return loadData('tampil','4','','');" style="color:#080"><b>Penunjang</b></a>
	</li>
	<li class="pull-right">	
		<button id="addBC" class="btn btn-primary"><i class='icon-plus-sign'></i> Tambah</button>
	</li>
</ul>
</div>
<!--end of tab menu-->

<!--table (header)--> 
<table class="table table-hover table-bordered table-striped" width="100%" border="0">
	<tr class="info">
		<td>no.</td>
		<td><b>Kegiatan</b></td>
		<td><b>Point</b></td>
		<td colspan="2"><b>Aksi</b>
		</td>
	</tr>

	<tbody id="isi">

	</tbody>
</table>
	<div>.</div>
	<div>.</div>
	<div>.</div>
	<div>.</div>

<!--end of table (header)--> 

<!--table (content)--> 
<!--<div class="row" id="isi"></div>-->
<!--end of table (content)--> 
</div>
<!--end of panel2-->