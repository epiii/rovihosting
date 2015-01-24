var dir = 'server/p_rule.php';
	function combopt(id_pt){
		//console.log(id_gol);
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=combo&menu=pt&id_jab='+id_pt,
			dataType:'json',
			success:function(data){
				if(data.status=='gagal'){
					//$('#id_kegTB').html('<option value="">anda telah mengambil semua kegiatan ini</option>');
				}else{
					var optiony ='';
					$.each(data, function (id,item){
						if(item.id_pt==id_pt){
							optiony+='<option selected="selected" value='+item.id_pt+'>'+item.pt+' </option>';
						}else{
							optiony+='<option value='+item.id_pt+'>'+item.pt+' </option>';
						}
					});
					$('#id_ptTB').html('<option value="">pilih pend terakhir..</option>'+optiony);
				}
			}
		});
	}

	function combojab(id_jab,id_gol){
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=combo&menu=jab&id_jab='+id_jab,
			dataType:'json',
			success:function(data){
				if(data.status=='gagal'){
					//$('#id_kegTB').html('<option value="">anda telah mengambil semua kegiatan ini</option>');
				}else{
					var optiony ='';
					$.each(data, function (id,item){
						if(item.id_jab==id_jab){
							optiony+='<option selected="selected" value='+item.id_jab+'>'+item.jab+' </option>';
						}else{
							optiony+='<option value='+item.id_jab+'>'+item.jab+' </option>';
						}
					});
					$('#id_jabTB').html('<option value="">pilih jabatan..</option>'+optiony);
					if(id_gol!=''){
						combogol(id_jab,id_gol);	
					}
				}
			}
		});
	}

	function combogol(id_jab,id_gol){
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=combo&menu=gol&id_jab='+id_jab+'&id_gol='+id_gol,
			dataType:'json',
			success:function(data){
				if(data.status=='gagal'){
					//$('#id_kegTB').html('<option value="">anda telah mengambil semua kegiatan ini</option>');
				}else{
					var optiony ='';
					$.each(data, function (id,item){
						if(item.id_gol==id_gol){
							optiony+='<option selected="selected" value='+item.id_gol+'>'+item.gol+' </option>';
						}else{
							optiony+='<option value='+item.id_gol+'>'+item.gol+' </option>';
						}
					});
					$('#id_golTB').html('<option value="">pilih golongan..</option>'+optiony);
				}
			}
		});
	}

	function loadData(){
		$('#isi').html('<img src="../img/loader.gif"> ').fadeIn();
		$.ajax({
			url	: dir,
			type: 'GET',
			data: 'aksi=tampil',
			success:function(data){
				$('#loadarea').html('<i class="icon-th-list"></i> DAFTAR ATURAN');
				$('#isi').hide().html(data).fadeIn(1000);
			}
		});
	}
	
	//validasi poin (harus angka)
	function cekPoin(poin){
		if( $('#pointTB').val() != $('#pointTB').val().replace(/[^0-9]/g, '')){ // cek hanya angka 
			$('#pointTB').val($('#pointTB').val().replace(/[^0-9]/g, ''));
		}
	}
	
	//submit form
	function submitForm(event){
		event.stopPropagation();
		event.preventDefault();
		
		var id_rule = +$('#idformTB').val()
		var urlx =dir+'?';
		if($('#idformTB').val()==''){ //add
			urlx += 'aksi=tambah';
		}else{ //edit
			urlx += 'aksi=ubah&id_rule='+id_rule;
		}
		//console.log(urlx);
		//return false;
		$.ajax({
			url:urlx,
			type:'post',
			dataType:'json',
			data:$('form').serialize(),
			success:function(data){
				if(data.status=='sukses'){
					kosongkan();
					$('#i_kegPN').toggle();
					$('#v_kegPN').toggle();
					$('#addBC').toggle();
					$('#viewBC').toggle();
					loadData();
				}else{
					alert(data.status);
				}
			}
		});
	}
	
	//hapus record kegiatan
	function hapusRule(id){
		if(confirm('melanjutkan untuk menghapus data?'))
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=hapus&id_rule='+id,
			dataType:'json',
			success:function(data){
				if(data.status=='gagal'){
					alert('gagal menghapus data');
				}else{
					loadData();
				}
			}
		});
	}
	//end of hapus record kegiatan
	
	//edit record kegiatan
	function editRule(id){
		//alert('id: '+id);
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=ambiledit&id_rule='+id,
			dataType:'json',
			success:function(data){
				if(data.status=='gagal'){
					alert('database error');
				}else{
					kosongkan();
					var id_jaby		= data.id_jab;
					var id_goly		= data.id_gol;
					var id_pty		= data.id_pt;
					var masay		= data.masa;
					var pointy		= data.point;
					
					$('#idformTB').val(id); 
					$('#id_jabTB').val(id_jaby);
					$('#id_golTB').val(id_goly);
					$('#id_ptTB').val(id_pty);
					$('#masaTB').val(masay);
					$('#pointTB').val(pointy);
					
					combojab(id_jaby,id_goly);
					
					$('#loadarea').html('<i class="icon-edit"></i> UBAH ATURAN').fadeIn();
					$('#i_kegPN').toggle(1000);
					$('#v_kegPN').toggle();
					$('#viewBC').toggle();
					$('#addBC').toggle();
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				console.log('ERRORS: ' + errorThrown);
			}
		});
	}
	
	//kosongkan form
	function kosongkan(){
		$('#idformTB').val('');
		$('#id_jabTB').val('');
		$('#id_golTB').val('');
		$('#id_ptTB').val('');
		$('#pointTB').val('');
		$('#masaTB').val('');
	}
	//end of kosongkan form

		
	//function pagination(page,aksix,menux,carix){
	function pagination(page,aksix,menux){
		$('#isi').html('<img src="../img/loader.gif"> ').fadeIn();
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
	
	// panggil fungsi2 di ready function ==============================================================
	$(document).ready(function(){
		//load data saat refresh halaman
		loadData();
		
		//panggil fungsi submit form 
		$('#id_jabTB').on('change', function(){
			var idjab 	= $(this).val();
			combogol(idjab,'');
		});
		
		$('form').on('submit', submitForm);
	
		//panggil fungsi cekPoin (validasi)
		$('#pointTB').on('input paste',function(){
			cekPoin($(this).val());
		});
		
		//masuk halaman "ADD DATA"
		$('#addBC').click(function(){
			$(this).toggle();
			kosongkan();
			combojab('','');
			$('#i_kegPN').toggle(1000);
			$('#v_kegPN').toggle();
			$('#viewBC').toggle();
			$('#loadarea').html('<i class="icon-plus"></i> TAMBAH ATURAN').fadeIn();
		});
		
		//masuk halaman "VIEW DATA"
		$('#viewBC').click(function(){
			kosongkan();
			$(this).toggle();
			$('#i_kegPN').toggle(1000);
			$('#v_kegPN').toggle();
			$('#addBC').toggle();
			loadData();	
		});
		
	});	
	