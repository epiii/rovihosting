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
	}.upperizer{
		text-transform:uppercase;
	}.capitizer{
		text-transform:capitalize;
	}

</style>
<script src="client/s_rule.js"></script>
<h3><div id="loadarea"><i class="icon-th-list"></i> DAFTAR ATURAN</div></h3>
<ol class="breadcrumb">
  <li class="active">Aturan / </li>
  <li ><a href="golongan">Golongan / </a></li>
  <li><a href="jabatan">Jabatan</a></li>
</ol>

<div>
	<button id="addBC" class="btn btn-primary"><i class='icon-plus-sign'></i> Tambah</button>
	<button style="display:none;" id="viewBC" class="btn btn-primary"><i class='icon-th-list'></i> Lihat Semua</button>
	<!-- <a href="golongan"id="golBC" class="btn btn-secondary">Golongan <i class='icon-arrow-right'></i> </a> -->
</div>
	

<!--panel 1-->
<div class="span8"id="i_kegPN" style="display:none;"><br>
	<div class="span8">
		<form autocomplete="off" method="post" name="form-daftar" class="form-horizontal" >
		<input type="hidden" id="idformTB" name="idformTB"/>
		<div class="control-group">
			<label class="control-label">Jabatan</label>
			<div class="controls" >
				<select name="id_jabTB" id="id_jabTB" required>
					<option value=''>pilih jabatan ...</option>
				</select>
			</div>
			<span id="golInfo"></span>
		</div>
		
		<div class="control-group">
			<label class="control-label">Golongan</label>
			<div class="controls" >
				<select name="id_golTB" id="id_golTB" required placeholder="Golongan">
					<option value="">pilih jabatan dulu</option>
				</select>
			</div>
			<span id="golInfo"></span>
		</div>

		<div class="control-group">
			<label class="control-label">Poin</label>
			<div class="controls" >
				<input type="number" id="pointTB" name="pointTB" required placeholder="Point (hanya angka)">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Masa (tahun)</label>
			<div class="controls" >
				<input type="text" id="masaTB" name="masaTB" required placeholder="masa (tahun)" maxlength="2">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Pendidikan Terakhir</label>
			<div class="controls" >
				<select   id="id_ptTB" name="id_ptTB" required >
					<option value="">pilih pend terakhir ..</option>
					<?php
						$sql = "select * from pt order by pt asc";
						$exe = mysql_query($sql);
						if(mysql_num_rows($exe)==0){
							echo "<option value=''>pend. terakhir  masih kosong</option>";
						}else{
							while($res = mysql_fetch_assoc($exe)){
								echo "<option value='$res[id_pt]'>$res[pt]</option>";
							}
						}
					?>
				</select>
			</div>
		</div>

		<button  id="simpanBC"class="btn btn-primary" >Simpan</button>
		<div >.</div>
		<div >.</div>
		</form>
	</div>
</div>
<divX id="loadtabel"></divX>

<div class="span8"id="v_kegPN"><br>
	<table class="table table-hover table-striped table-bordered" width="100%" border="0">
	<tr class="info">
		<td><b>No.</b></td>
		<td><b>Jabatan</b></td>
		<td><b>Golongan</b></td>
		<td><b>Masa Jabatan</b></td>
		<td><b>Masa Golongan</b></td>
		<td><b>Poin</b></td>
		<td><b>Gelar</b></td>
		<td colspan="2"><b>Aksi</b>
		</td>
	</tr>

	<tbody id="isi">

	</tbody>
	</table>
	<div class="row" id="isi"></div>
</div>
