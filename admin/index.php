<?php
	session_start();
	if($_SESSION['levelx']=='adminf' or $_SESSION['levelx']=='adminu' ){
		include "../lib/koneksi.php";
		$res = mysqli_fetch_assoc(mysqli_query($con,'SELECT * FROM admin join fak on fak.idfak = admin.idfak WHERE admin.iduser='.$_SESSION['iduser']));
		$_SESSION['idadmin']=$res['idadmin'];
		$_SESSION['fak']=$res['fak'];
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Unesa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="../assets/js/jquery.js"></script>
    <link href="../lib/paging.css"rel="stylesheet">
    <link href="../assets/css/bootstrap.css"rel="stylesheet">
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet" media="screen">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" >
    <link rel="apple-touch-icon-precomposed" sizes="114x114" >
    <link rel="apple-touch-icon-precomposed" sizes="72x72" >
    <link rel="apple-touch-icon-precomposed" >
    <link rel="shortcut icon">
	<style>
		#footerx{
			color:#FFFFFF;
			text-align:center;
			/*background:#000099;*/
			background:orange;
			padding: 10px 0;
			/*background: -webkit-linear-gradient(left, #ccc, #000099); /*#999*/
			background: -moz-linear-gradient(left, red,orange);
			background: -ms-linear-gradient(left, red,orange);
			background: -webkit-linear-gradient(left, red,orange);
			background: -o-linear-gradient(left, red,orange);
			bottom: 0;
			position: fixed;
			width: 100%;
			font-size: 18px;
	}
    </style>
</head>

<body>

<div class="container-fluid" style="background-color:#F90">
    <div class="span2" align="left" style="padding-top:5px;">
      <img src="../img/LOOGG.png" />   </div>   
     <div class="span8"> <h3 align="center" style="color:#005">Aplikasi Kenaikan Pangkat Dosen</h3>
    </div>
    <div class="span2">.</div>
  </div>

    <div class="container-fluid" style="background-color:#F90">
    
    <div class="container-fluid">
	
			<div class="navbar">
				<div class="navbar-inner">
					<div class="container-fluid">
						 <a data-target=".navbar-responsive-collapse" data-toggle="collapse" class="btn btn-navbar"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></a> <a href="?menu=vmain" style="color:#000" class="brand">
						 <?php 
							if($_SESSION['levelx']=='adminf'){
								$levelx	= 'Admin Fakultas ('.$_SESSION['fak'].')';
							}else{
								$levelx	= 'Admin Universitas';
							}
							echo '<i class="icon-user lg"></i> '.$levelx;
							//echo "Administrator";
						?></a>
						<div class="nav-collapse collapse navbar-responsive-collapse">
							<ul class="nav pull-right">
								<li>
									<a href="beranda" style="color:#000"><b>Beranda</b></a>
								</li>
                            	<?php if($_SESSION['levelx']=='adminu'){; ?>
                                <li>
									<a href="pengurus" style="color:#000"><b>Pengurus</b></a>
								</li>
                                <?php } ?>
<!-- 							<li>
									<a href="aturan" style="color:#000"><b>Aturan</b></a>
								</li>
 -->                                <li>
									<a href="daftar-dosen" style="color:#000"><b>DUK</b></a>
								</li>
                                <li>
									<a href="angka-kredit" style="color:#000"><b>Kegiatan</b></a>
								</li>
                                <li>
									<a href="informasi" style="color:#000"><b>Informasi</b></a>
								</li>
                                <li>
									<a href="../logout.php" style="color:#000"><b>Keluar</b></a>
								</li>
							</ul>
							
						</div>
						
					</div>
				</div>
				
			</div>
            
		
	</div>
</div>

<!-- content -->
<div class="container">
    
        <!--<h3>HALAMAN <?php //echo $username; ?> </h3>-->
    	<?php
			if (isset($_GET['menu'])) {
				switch ($_GET['menu']){
					case 'vadmin':
						require 'view/v_admin.php';
					break;
					case 'vmain':
						require 'view/v_main.php';
					break;
					#---------
					case 'vrule':
						require 'view/v_rule.php';
					break;
					case 'vjab':
						require 'view/v_jab.php';
					break;
					case 'vgol':
						require 'view/v_gol.php';
					break;
					
					#---------
					case 'vduk':
						require 'view/v_duk.php';
					break;
						
					case 'vak';
						require 'view/v_kegiatan.php';
					break;
					
					case 'vnews';
						require 'view/v_news.php';
					break;
					
					case 'rekap':
						require 'view/v_rekapitulasi.php';
					break;
				}
			}else{
				require 'view/v_main.php';
			}
		?>
   
</div>

<div id="footerx">copyright UNESA @ 2013</div>


<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
	<script src="../js/base64.js"></script>
	<script src="../assets/js/bootstrap-transition.js"></script>
    <script src="../assets/js/bootstrap-alert.js"></script>
    <script src="../assets/js/bootstrap-modal.js"></script>
    <script src="../assets/js/bootstrap-dropdown.js"></script>
    <script src="../assets/js/bootstrap-scrollspy.js"></script>
    <script src="../assets/js/bootstrap-tab.js"></script>
    <script src="../assets/js/bootstrap-tooltip.js"></script>
    <script src="../assets/js/bootstrap-popover.js"></script>
    <script src="../assets/js/bootstrap-button.js"></script>
    <script src="../assets/js/bootstrap-collapse.js"></script>
    <script src="../assets/js/bootstrap-carousel.js"></script>
    <script src="../assets/js/bootstrap-typeahead.js"></script>
</body>
</html>

<?php
	}else{
		header('location:../');
	}
?>