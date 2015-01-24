    //$(document).ready(function(){	
		
		//$('.btn btn-primary').live('click', function(){
		$('.halo').click( function(){
			alert('haha');
		});
		
		function pop(){
			alert('okeh');
		}
		loadData();
		//fungai loading mode "normal"
		function loadData(){
			$('#isi').html('<img src="../img/loader.gif"><br>loading..').fadeIn();
			
			$.ajax({
				url	: "p_main.php",
				type: "GET",
				data: 'aksi=tampil',
				success:function(data){
					$("#loadtabel").fadeOut(1000);
					$('#isi').hide().html(data).fadeIn(1000);
				}
			});
		}
		
		//fungsi loading mode "paging"
		//var page;
		function pagination(page){
			$('#isi').html('<img src="../img/loader.gif"><br>loading..').fadeIn();
				dataString = 'starting='+page;//+'&random='+Math.random();
			
			$.ajax({
				url:"p_main.php",
				data: dataString+'aksi=tampil',
				type:"GET",
				success:function(data){
					$("#loadtabel").fadeOut();
					$('#isi').hide().html(data).fadeIn(1000);
				}
			});
		}
		//end of fungsi loading mode "paging"
		/*$(".halo").live({click:function(){ 
			alert('halo');
		}});
		*/
		//action klik paging
		/*$('li.nextpaging').live('click',function(){
		//$('li.nextpaging').click(function(){
			var pg = $(this).attr('pg');
			pagination(pg);
		});
		$('li.prevnext').live('click',function(){
		//$('li.prevnext').click(function(){
			var pg = $(this).attr('pg');
			pagination(pg);
		});*/
		//action klik paging
		
		//cari keteranganTB 
		$("#kategoriTB").change(function(){
			var cari = $("input#objekTB").val();
			var combo = $("select#kategoriTB").val();
			if (cari.replace(/\s/g,"") != ""){ // mengecek field text kosong atau tidak)
				loadData();
			}else if ((cari.replace(/\s/g,"") == "") && (combo != "semua") ){
			  $("input#objekTB").focus();
				loadData();
			}else{
			  loadData();
			}return false;
		});
		
		//cari objekTB
		$("#objekTB").keyup(function(){
			var cari 	= $("input#objekTB").val();
			var combo 	= $("select#kategoriTB").val();
			if (cari.replace(/\s/g,"") != ""){ // mengecek field text kosong atau tidak)
				loadData();
			}else if ((cari.replace(/\s/g,"") == "") && (combo != "semua") ){
				$("input#objekTB").focus();
				loadData();
			}else{
			  loadData();
			}return false;	
		});
		
		//klik tambah 
		$("#tambahBC").click(function(){
			$("#titlex").html('<h4><span align=center><b>tambah data</b></span><h4>');
			$("#idform").val('');
			$("#namaTB").val('');
			$("#keteranganTB").val('');
			$("#simpanBC").html('Simpan');
			$("#namaTB").focus();
		});
		//end of tambah 
		
		//kosongkan fields
		function kosongkan(){
			$("#idform").val('');
			$("#namaTB").val('');
			$("#keteranganTB").val('');
		}
		//end of kosongkan fields
		
		//simpan(tambah & edit)
		
//	});
