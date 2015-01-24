<script language="javascript" type="text/javascript" src="../js/plugins/bootstrap-datepicker.js"></script>
<script src="client/s_profil.js"></script>
<style>
#loadarea{
	height:15px;}
.capitizer{
	text-transform:capitalize;
}</style>

<!-- content -->
<h3></h3>
<div id="loadarea" ></div> 
<div class="container">
    	<div style="padding-left:20px; padding-top:20px;" class="tabbable" id="tabs-104268">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#panel1" data-toggle="tab" style="color:#080"><b>Data Login</b></a>
				</li>
				<li>                    
					<a href="#panel2" data-toggle="tab" style="color:#080"><b>Data Pribadi</b></a>
				</li>
				<!-- <li>
					<a href="#panel3" data-toggle="tab" style="color:#080"><b>Data Jabatan</b></a>
				</li> -->
			
				<li class="pull-right">
					<button onclick="return hapusAkun();" class="btn btn-secondary"><i class="icon-trash"></i> hapus akun</button>
				</li>
			</ul>
			
			<form class="form-horizontal" >
				<div class="tab-content">
					<!-- login data -->
					<div align="center" class="tab-pane active" id="panel1">
						<div class="control-group">
							<table class="table table-striped" width="100%" border="0">
								<tr id="usernameTR">
									<td width="25%"><label class="control-label">Username</label></td>
									<td width="5%"><label class="control-label"> :</label></td>
									<td width="70%" id="usernameTD"></td>
								</tr>
                                
								<tr id="gantiDV">
									<!--password-->
								</tr>
								
								<tr id="pass1" style="display:none;">
									 <td width="25%"><label class="control-label">password lama</label></td>
									 <td width="5%"><label class="control-label">:</label></td>
									 <td width="70%"><input type="password" id="passLTB"></td>
								 </tr>
								 <tr id="pass2"  style="display:none;">
									 <td width="25%"><label class="control-label">password baru</label></td>
									 <td width="5%"><label class="control-label">:</label></td>
									 <td width="70%"><input type="password" id="passBTB1"></td>
								 </tr>
								 <tr id="pass3"  style="display:none;">
									 <td width="25%"><label class="control-label">password baru (ketik ulang)</label></td>
									 <td width="5%"><label class="control-label">:</label></td>
									 <td width="70%"><input type="password" id="passBTB2" name="passBTB2"><span id="passinfo"></span></td>
								 </tr>
							</table>
						</div>
                            
						<div class="form-actions">
						</div>
					</div>
					<!-- end of login data -->

					<!-- bio data -->
					<div  align="center"class="tab-pane" id="panel2">
						<table class="table table-striped" width="100%" border="0">
							<tr>
								<td width="25%"><label class="control-label">NIP</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%" id="nipTD"></td>
							</tr>
	                        <tr>
								<td width="25%"><label class="control-label">Karpeg</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%" class="capitizer"  id="karpegTD"></td>
	 						</tr>
	  						<tr id="namalTR">
								<td width="25%"><label class="control-label">Nama Lengkap</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%" class="capitizer"  id="namalTD"></td>
	 						</tr>
	                        <tr id="gelardTR" style="display:none;">
								<td width="25%"><label class="control-label">Gelar Depan</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%" class="capitizer"  id="gelardTD"></td>
	 						</tr>
	                        <tr id="namadTR" style="display:none;">
								<td width="25%"><label class="control-label">Nama Depan</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%" class="capitizer"  id="namadTD"></td>
	 						</tr>
	                        <tr id="namabTR" style="display:none;">
								<td width="25%"><label class="control-label">Nama Belakang</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%" class="capitizer"  id="namabTD"></td>
	 						</tr>
	 						<tr id="gelarbTR" style="display:none;">
								<td width="25%"><label class="control-label">Gelar Belakang</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%" class="capitizer"  id="gelarbTD"></td>
	 						</tr>
	                        <tr>
								<td width="25%"><label class="control-label">Agama</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%"  class="capitizer" id="agamaTD"></td>
	 						</tr>
	                        <tr>
								<td width="25%"><label class="control-label">Jenis Kelamin</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%" class="capitizer" id="jkTD"></td>
	 						</tr>
	                        <tr>
								<td width="25%"><label class="control-label">Tempat Lahir</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%" class="capitizer"  id="tlTD"></td>
	 						</tr>
	                        <tr>
								<td width="25%"><label class="control-label">Tanggal Lahir</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%"class="capitizer"  id="tgllTD"></td>
	 						</tr>
	                        <tr id="umurTR">
								<td width="25%"><label class="control-label">Umur</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%" id="umurTD"></td>
	 						</tr>

	 						<!-- jabatan data -->
	                        <tr>
								<td width="25%"><label class="control-label">Pendidikan Terakhir</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%" class="capitizer" id="ptTD"></td>
	 						</tr>
							<tr>
								<td width="25%"><label class="control-label">Golongan Sekarang </label></td>
								<td width="5%"><label class="control-label"> :</label><input type="hidden" id="golsTB" name="golsTB"></td>
								<td width="70%"class="capitizer" id="golsTD"></td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label" id="TMTgolsLB">TMT Golongan</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%"  id="TMTgolsTD"></td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label" >Jabatan Sekarang</label></td>
								<td width="5%"><label class="control-label"> :</label><input type="hidden" id="jabsTB" name="jabsTB"></td>
								<td width="70%" class="capitizer"  id="jabsTD"></td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label" id="TMTjabsLB">TMT Jabatan </label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%"  id="TMTjabsTD"></td>
							</tr>
<!-- 							<tr>
								<td width="25%"><label class="control-label">Point</label></td>
								<td width="5%"><label class="control-label"> :</label></td>
								<td width="70%" id="`tTD"></td>
							</tr>
 -->
						</table>
					</div>
					<!-- end of bio data -->
			</div>
			<input type="submit" value="Simpan"class="btn btn-primary" style="display:none;">
			<a href="profil" style="display:none;" class="btn btn-primary" id='cancelBC'>Batal</a>
			<a class="btn btn-primary" id='editBC'>Ubah</a>
			<div>.</div>
			<div>.</div>
		</form>
	</div>
</div>