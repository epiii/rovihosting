<?php 
	session_start();
	if($_SESSION['levelx']=='user'){
		require_once "../lib/koneksi.php";
    require_once "../lib/tglindo.php";
    require_once "../lib/filter.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Unesa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="../lib/paging.css"rel="stylesheet">
    <link href="../assets/css/bootstrap.css"rel="stylesheet">
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet" media="screen">

	<script src="../assets/js/jquery.js"></script>
	<script src="../assets/js/bootstrap-tooltip.js"></script>
	<script src="../js/base64.js"></script>
		    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
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
			background:#000099;
			padding: 10px 0;
			background: -moz-linear-gradient(left, black,#000055);
			background: -webkit-linear-gradient(left, black,#000055);
			background: -o-linear-gradient(left, black,#000055);
			background: -ms-linear-gradient(left, black,#000055);
			background: -moz-linear-gradient(left, black,#000055);
			bottom: 0;
			position: fixed;
			width: 100%;
			font-size: 18px;
	}.blinker{
		text-decoration:blink;
	}
    </style>
</head>

<body>
  <div class="container-fluid" style="background-color:#005">
    <div class="span2" align="left" style="padding-top:5px;">
      <img src="../img/logoooo.png" />   </div>   
     <div class="span8"> <h3 align="center" style="color:#F60">Aplikasi Kenaikan Pangkat Dosen</h3>
    </div>
    <div class="span2">.</div>
  </div>
  
  <div class="container-fluid" style="background-color:#005">
      <div class="container-fluid" >
         
        
              <div class="navbar">
                  <div class="navbar-inner">
                      <div class="container-fluid">
                           <a data-target=".navbar-responsive-collapse" data-toggle="collapse" class="btn btn-navbar">
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                          </a> 
                          <a href="./" style="color:#000" class="brand">
                              <?php 
                                  echo '<i class="icon-user lg"></i> '.$_SESSION['namad'];
                              ?>
                          </a>
                          <div class="nav-collapse collapse navbar-responsive-collapse">
                              <ul class="nav pull-right">
                                  <li>
                                      <a href="main" style="color:#000" ><b>Beranda</b></a>
                                  </li>
                                  <li>
                                      <a href="profil" style="color:#000"><b>Profil</b></a>
                                  </li>
                                  <li>
                                      <a href="kegiatan" style="color:#000"><b>Kegiatan</b></a>
                                  </li>
                                  <li>
                                      <a href="rekapitulasi" style="color:#000"><b>Rekapitulasi</b></a>
                                  </li>
                                  <li>
                                      <a href="../logout.php"style="color:#000"><b>Keluar</b></a>
                                  </li>
                              </ul>
                              
                          </div>
                          
                      </div>
                  </div>
                  
              </div>
          
      </div>
  </div>
  <!-- content -->
  <div align="center" class="container">
     
          <!--<h3>HALAMAN <?php //echo $username; ?> </h3>-->
          <?php
              if (isset($_GET['menu'])) {
                switch ($_GET['menu']){
                    case 'vprof':
                        require 'view/v_profil.php';
                    break;
                    case 'vkeg':
                        require 'view/v_kegiatan.php';
                    break;
                    case 'vrekap':
                        require 'view/v_rekapitulasi.php';
                    break;
                    case 'vmain':
                        require 'view/v_main.php';
                    break;
                    // case 'rcetak':
                    //     require 'view/r_cetak.php';
                    // break;
                }
              }else{
                require 'view/v_main.php';
              }
          ?>
          
          <!--<divx class="span2"></div>-->
      </div>
  <div id="footerx">copyright UNESA @ 2013</div>
<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/bootstrap-transition.js"></script>
    <script src="../assets/js/bootstrap-alert.js"></script>
    <script src="../assets/js/bootstrap-modal.js"></script>
    <script src="../assets/js/bootstrap-dropdown.js"></script>
    <script src="../assets/js/bootstrap-scrollspy.js"></script>
    <script src="../assets/js/bootstrap-tab.js"></script>
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