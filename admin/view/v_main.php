<!--<link href="../assets/css/justified-nav.css" rel="stylesheet">-->
<!--<script type="text/javaescript"  src="../js/jsUserMain.js"></script>-->
<style>
	#pagination{
		color:white;list-style:none;
	}.vwimg{
		height:80px;
		opacity:0.8;
	}.vwimg:hover{
		opacity:1;
	}
</style>

<script src="client/s_main.js"></script>
<!--<link href="../assets/css/articlex.css" rel="stylesheet">-->
<h3 id="loadarea"><i class="icon-home"></i> BERANDA</H3>
<!-- Example row of columns -->

<div class="container">
	<div style="padding-top:20px;" class="tabbable" id="tabs-104268">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#panel1" data-toggle="tab" onclick="return loadData('tampil','berita','','');" style="color:#080"><b>Berita</b></a>
			</li>
			<li >
				<a href="#panel2" data-toggle="tab" onclick="return loadData('tampil','download','','');" style="color:#080"><b>Download</b></a>
			</li>
			<!--<li>
				<input type="text" id="cariTB" placeholder="pencarian">
				<input type="button" id="cariBC" value="cari">
			</li>-->
		</ul>
	</div>
 </div>
 <div>.</div>
		<!--<input type="text" id="menuTB">-->
<div id="loadtabel"></div>
<div class="row" id="isi"></div>