<?php 
	session_start();
	// error_reporting(0);
    // var_dump($_SESSION['levelx']);exit();
	if((isset($_SESSION['levelx'])=='adminf') or (isset($_SESSION['levelx'])=='adminu')){
		header('location:admin');
    }elseif(isset($_SESSION['levelx'])=='user'){
        header('location:user');
	}else{
?>

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
    <style type="text/css">
	.icon{
		opacity:0.8;
		background-color:red;
	}.icon:hover{
		opacity:1;
	}
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
			background: -ms-linear-gradient(left, black,#000055);
			background: -o-linear-gradient(left, black,#000055);
			background: -webkit-linear-gradient(left, black,#000055);
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
	}
	</style>
	</head>
	
    <body style="overflow:hidden">
        <div id="header" align="center" class="top-header" style="background-color:#005;">
            <img src="img/logoooo.png" />
            <h2 style="color:#F60">Aplikasi Kenaikan Pangkat Dosen</h2>
        </div>    
        <div id="content" class="bod">
        	
                        <form name="form-login" class="form-login" action="p_login.php" method="post">
                            <h2 style="color:#EEE" class="form-signin-heading" align="center">Masuk</h2>
                            <!--<span id="info"><?php 
								//if($info =='error') 
								//{ echo 'username atau password salah';}?></span>-->
                            <input name="username" required type="text" class="input-block-level" placeholder="Username" >
                            <input type="password" class="input-block-level" placeholder="Password" name="password" required>
                            <button class="btn" name="login" type="submit"><b style="color:#F60;">Login</b></button>
                    	</form>
                    	<p align="center" class="muted"><b>Belum punya akun? <a href="daftar" style="color:#F60"> Daftar</a></b></p>
                   
			</div>
            <div id="footerx">copyright UNESA @ 2013</div>
  		</body>
  
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
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
</html>
<?php 
	}
?>