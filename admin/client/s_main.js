var dir ='server/p_main.php';
	$(document).ready(function(){
		var menuH = $('#menuTB').val('berita');
		function cari(){
			var cari 	= $('#cariTB').val();
			loadData('tampil',menuH,'',cari);
		}
		$("#cariBC").click(function(){
			cari();
		});

		$('#panel1').click(function(){
			//alert();
			$('#menuTB').replace('berita');
		});
		$('#panel2').click(function(){
			$('#menuTB').val('download');
		});
		
	});	
	loadData('tampil','berita','','');
	
	//function loadData(aksix,menux,idx,carix){
	function loadData(aksix,menux,idx){
		$('#isi').html('<img src="../img/loader.gif"><br>loading..').fadeIn();
		var datax = 'aksi='+aksix+'&menu='+menux+'&id='+idx;
		//var datax = 'aksi='+aksix+'&menu='+menux+'&id='+idx+'&cari='+carix;
		
		$.ajax({
			url	: dir,
			type: "GET",
			data: datax,
			success:function(data){
				$("#loadtabel").fadeOut(1000);
				$('#isi').hide().html(data).fadeIn(1000);
			}
		});
	}
		
	//function pagination(page,aksix,menux,carix){
	function pagination(page,aksix,menux){
		$('#isi').html('<img src="../img/loader.gif"><br>loading..').fadeIn();
			//var datax = 'starting='+page+'&aksi='+aksix+'&menu='+menux+'&id='+idx+'&cari='+carix;
			var datax = 'starting='+page+'&aksi='+aksix+'&menu='+menux;
		$.ajax({
			url:dir,
			type:"GET",
			data: datax,
			success:function(data){
				$("#loadtabel").fadeOut();
				$('#isi').hide().html(data).fadeIn(1000);
			}
		});
	}
