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

<h3 align="center">BERANDA</h3>

<div class="container">
	<div style="padding-top:20px;" class="tabbable" id="tabs-104268">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#panel1" data-toggle="tab" onclick="return loadData('tampil','berita','','');" style="color:#080"><b>Berita</b></a>
			</li>
			<li >
				<a href="#panel2" data-toggle="tab" onclick="return loadData('tampil','download','','');" style="color:#080"><b>Unduhan</b></a>
			</li>
		</ul>
	</div>
</div>

<div class="row" id="isi">
	<!-- area untuk meload tabel dr database -->
</div>
