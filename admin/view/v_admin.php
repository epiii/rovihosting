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
<script src="client/s_admin.js"></script>
<h3><div id="loadarea"><i class="icon-th-list"></i> DAFTAR PENGURUS</div></h3>

<div>
	<button id="addBC" class="btn btn-primary"><i class='icon-plus-sign'></i> Tambah</button>
	<button style="display:none;" id="viewBC" class="btn btn-primary"><i class='icon-th-list'></i> Lihat Semua</button>
</div>
<div>&nbsp;</div>
<!--panel 1-->
<div class="span8"id="i_kegPN" style="display:none;">
	<div class="span8">
		<form autocomplete="off" method="post" name="form-daftar" class="form-horizontal" >
		<input type="hidden" id="idformTB" name="idformTB"/>
		
		<div class="control-group">
			<label class="control-label">Level</label>
			<div class="controls" >
				<select   id="levelTB" name="levelTB" required >
					<option value="">pilih level ..</option>
					<option value="adminf">Adm. Fakultas</option>
					<option value="adminu">Adm. Universitas</option>
				</select>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">Nama Pengguna</label>
			<div class="controls" >
				<input type="text" name="usernameTB" id="usernameTB" required placeholder="username (max 20 karakter)" maxlength="20">
			</div>
			<span id="usernameInfo"></span>
		</div>

		<div class="control-group" id="gantipassDV">
		
		</div>
		
		<div class="control-group" id="passwordDV">
			<label class="control-label" >Kata Sandi</label>
			<div class="controls" >
				<span id="passwordSP"></span>
				<input type="password" name="passwordTB" id="passwordTB" required placeholder="password (max 20 karakter)" maxlength="20">
			</div>
			<span id="passwordInfo"></span>
		</div>
		
		<button  id="simpanBC"class="btn btn-primary" >Simpan</button>
		<div >.</div>
		<div >.</div>
		</form>
	</div>
</div>
<divX id="loadtabel"></divX>

<div class="span8"id="v_kegPN">
	<table class="table table-bordered table-hover table-striped" width="100%" border="0">
		<tr class="info">
			<td><b>No.</b></td>
			<td><b>Nama Pengguna</b></td>
			<td><b>Level</b></td>
			<td colspan="2"><b>Aksi</b>
			</td>
		</tr>

		<tbody id="isi">
			<!-- tampilkan list-data / tabel disini -->
		</tbody>
	</table>
	<div>.</div>	
	<div>.</div>	
	<div>.</div>	
</div>
