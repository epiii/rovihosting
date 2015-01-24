<?php require_once 'lib/koneksi.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Unesa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Le styles -->
    <link href="assets/css/bootstrap.css"rel="stylesheet">
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link rel="apple-touch-icon-precomposed" sizes="144x144">
    <link rel="apple-touch-icon-precomposed" sizes="114x114">
    <link rel="apple-touch-icon-precomposed" sizes="72x72">
    <link rel="apple-touch-icon-precomposed">
    <link rel="shortcut icon">
	<script src="assets/js/jquery.js"></script>
    <script src="js/plugins/bootstrap-datepicker.js"></script>
    <script src="s_daftar.js"></script>

    <style type="text/css">
	.form-login {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color:#F60;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-login .form-signin-heading,
      .form-login .checkbox {
        margin-bottom: 10px;
      }
      .form-login input[type="text"],
      .form-login input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
	  .bod{
		  padding-top:120px;
	  }
	  #info{
		  color: #FFFFFF;
		  font-weight:bold;
		  
	  }#footerx{
			color:#FFFFFF;
			text-align:center;
			background:#000099;
			padding: 10px 0;
			background: -moz-linear-gradient(left, black,#000055);
			background: -webkit-linear-gradient(left, black,#000055);
			background: -ms-linear-gradient(left, black,#000055);
			background: -o-linear-gradient(left, black,#000055);
			background: -linear-gradient(left, black,#000055);
			bottom: 0;
			position: fixed;
			width: 100%;
			font-size: 18px;
	}
	#footerx a{
		text-decoration: none;
		font-weight: bold;
		color: #000;
	}.lowerizer{
		text-transform:lowercase;
	}.upperizer{
		text-transform:uppercase;
	}
	</style>
	</head>
	
    <body style="overflow:scroll;">
        <div id="header" align="center" class="top-header" style="background-color:#005;">
            <img src="img/logoooo.png" />
            <h2 style="color:#F60">Aplikasi Kenaikan Pangkat Dosen</h2>
        </div>    

    <div class="container">
    <a  href="./" class="pull-right btn btn-secondary"><i class="icon-home"></i> Kembali</a>
    <h2 align="center"><legend>Form Pendaftaran</legend></h2>
		<div class="container-fluid">
			<div class="span2"></div>
			<div class="span8">
				<form name="form-daftar" class="form-horizontal" autocomplete="off" action="p_daftar.php" method="post">
					<div class="control-group">
						<label class="control-label">Username :</label>
						<div class="controls">
							<input id="_un" name="_un" class="lowerizer" required type="text" placeholder="max 20 karakter (a-z 0-9  _)" maxlength="20">
							<div id="userinfo"></div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Password :</label>
						<div class="controls">
							<input id="_ps"name="_ps" required type="password" placeholder="Password (max 20 karakter)" maxlength="20">
							<div id="psinfo"></div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Re-Password :</label>
						<div class="controls">
							<input id="_rps" name="_rps" required type="password" placeholder="Re-Password (max 20 karakter)" maxlength="20">
                        <div id="rpsinfo"></div>    
						</div>
					</div>
        <!--end of tb user-->
		
					<div class="control-group">
						<label class="control-label">NIP :</label>
						<div class="controls">
							<input name="_nip" id="_nip" required type="text" placeholder="NIP (hanya angka )" min="18" maxlength="18">
								<div id="nipinfo"></div>
							</div>
					</div>
					<div class="control-group">
						<label class="control-label">Gelar (depan):</label>
						<div class="controls">
							<input id="_gd"name="_gd"  type="text" placeholder="Gelar (depan)" maxlength="20" >
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Nama Depan :</label>
						<div class="controls">
							<input id="_nd"name="_nd"  required type="text" placeholder="Nama Depan" maxlength="20" >
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Nama Belakang :</label>
						<div class="controls">
							<input id="_nb" name="_nb"  type="text" placeholder="Nama Belakang" maxlength="50">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Gelar (belakang):</label>
						<div class="controls">
							<input id="_gb"name="_gb"  type="text" placeholder="Gelar (balakang)" maxlength="20" >
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">No Seri KARPEG :</label>
						<div class="controls">
							<input  name="_kg" id="_kg" class="upperizer" type="text" placeholder="Nomor Seri Kartu pegawai" maxlength="8" minlength="8">
							<div id="kginfo"></div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Tempat Lahir :</label>
						<div class="controls">
							<input class="capitizer" name="_tl" required type="text" placeholder="Tempat Lahir" maxlength="50" >
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Tanggal Lahir :</label>
						<div class="controls">
							<input id="_tgll" name="_tgll" required  placeholder="mm/dd/yyyy" type="text" >
							<div id="tgllinfo"><p class="label label-info">mm/dd/yyyy</p></div>
						</div>	
					</div>
					<div class="control-group">
						<label class="control-label">Agama :</label>
						<div class="controls">
							<select required  name="_agm" id="_agm">
								<option value="">pilih agama ...</option>
								<option value="islam">Islam</option>
								<option value="kristen">Kristen</option>
								<option value="katolik">Katolik</option>
								<option value="hindu">Hindu</option>
								<option value="budha">Budha</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Jenis Kelamin :</label>
						<div class="controls">
							<label >
								<input required type="radio" class="radio"name="_jk" value="L" />Laki - laki 
							</label>
							<label >
								<input required class="radio" type="radio" name="_jk" value="P"/>Perempuan
							</label>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Fakultas</label>
						<div class="controls">
							<select required name="_fak" id="_fak"/>
								<option value="">silahkan pilih fakultas</option>
							<?php
								$sql="select * from fak order by fak asc";
								$exe=mysql_query($sql);
								while($res=mysql_fetch_assoc($exe)){
									echo '<option value="'.$res['idfak'].'">'.$res['fak'].'</option>';
								}

							?></select>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Jurusan</label>
						<div class="controls">
							<select required name="_jur" id="_jur"/>
								<option value="">silahkan pilih fakultas dulu</option>
							</select>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Program Studi</label>
						<div class="controls">
							<select required name="_prodi" id="_prodi"/></select>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Pendidikan Tertinggi :</label>
						<div class="controls">
							<select required id="_pt"name="_pt">
								<option value="">pilih pend tertinggi ..</option>
								<?php
									$sqlpt 	= 'select * from pt  order by id_pt asc';
									$exept 	= mysql_query($sqlpt);
									while($respt=mysql_fetch_assoc($exept)){
										echo '<option value="'.$respt[id_pt].'">'.$respt[pt].'</option>';
									}
								?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Golongan :</label>
						<div class="controls">
							<select required name="_gol" id="_gol" >
								<option value="">pilih golongan ..</option>
								<?php
									$sql 	= 'select * from gol order by id_gol asc';
									$exe 	= mysql_query($sql);
									while($res=mysql_fetch_assoc($exe)){
										echo '<option value="'.$res[id_gol].'">'.$res[gol].'</option>';
									}
								?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">TMT Golongan:</label>
						<div class="controls">
							<input type="text" id="_tglgol"name="_tglgol" required placeholder="tanggal dilantik mm/dd/yyyy">
							<div id="tglgolinfo"><p class="label label-info">mm/dd/yyyy</p></div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Jabatan :</label>
						<div class="controls">
							<select required name="_jab" id="_jab" >
								<option value="">pilih jabatan ..</option>
								<?php
									$sql 	= 'select * from jab order by id_jab asc';
									$exe 	= mysql_query($sql);
									while($res=mysql_fetch_assoc($exe)){
										echo '<option value="'.$res[id_jab].'">'.$res[jab].'</option>';
									}
								?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">TMT Jabatan:</label>
						<div class="controls">
							<input type="text" id="_tgljab"name="_tgljab" required placeholder="tanggal dilantik mm/dd/yyyy">
							<div id="tgljabinfo"><p class="label label-info">mm/dd/yyyy</p></div>
						</div>
					</div>
					
					<div class="form-actions">
						<button type="submit" class="btn btn-primary" value="daftar">Daftar</button>
						<button type="submit" class="btn" value="cancel" onClick="window.history.back()">Cancel</button>
					</div>
				</form>
                <div>.</div>
                <div>.</div>
    		</div>
	    </div>
    </div> <!-- /container -->

	
    <!-- Le java3333ipt
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script src="assets/js/bootstrap-transition.js"></script>
    <script src="assets/js/bootstrap-alert.js"></script>
    <script src="assets/js/bootstrap-modal.js"></script>
    <script src="assets/js/bootstrap-dropdown.js"></script>
    <script src="assets/js/bootstrap-scrollspy.js"></script>
    <script src="assets/js/bootstrap-tab.js"></script>
    <script src="assets/js/bootstrap-tooltip.js"></script>
    <script src="assets/js/bootstrap-popover.js"></script>
    <script src="assets/js/bootstrap-button.js"></script>
    <script src="assets/js/bootstrap-collapse.js"></script>
    <script src="assets/js/bootstrap-carousel.js"></script>
    <script src="assets/js/bootstrap-typeahead.js"></script>

	</body>
	<div id="footerx">copyright UNESA @ 2013</div>
</html>
