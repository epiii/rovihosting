var dir = 'server/p_kegiatan.php';
	// cek nama kegiatan (exist/not)
	function cekNakeg(event){
		var id=$('#idformTB').val();
		var idkatkeg=$('#id_katkegTB').val();
		var nakeg	=$(this).val();
		var nakeg1	=$('#nakeg1TB').val();

		if($('#id_katkegTB').val()==''){
			$('#id_katkegTB').focus();
			$('#nakegTB').val('');
			$('#katkegInfo').html('<label  class="label label-important">silahkan pilih dulu</label>').fadeIn();
			setTimeout(function(){
				$('#katkegInfo').fadeOut(function(){
					$('#katkegInfo').html('');
				});
			},1000);
		}else{
			if(id!=''){ //hanya berlaku untuk edit
				datax = 'aksi=cek&menu=nakeg&idkatkeg='+idkatkeg+'&nakeg1='+nakeg1+'&nakeg='+nakeg;
			}else{
				datax = 'aksi=cek&menu=nakeg&idkatkeg='+idkatkeg+'&nakeg='+nakeg;
			}
			
			$.ajax({
				url:dir,
				data:datax,
				type:'get',
				dataType:'json',
				success:function(data){
					if(data.status=='terpakai'){
						//if(id!=''){ //hanya berlaku untuk edit
							$('#nakegInfo').html('<label  class="label label-important">"'+nakeg+'" telah terpakai</label>').fadeIn();
							$('#nakegTB').val('');
							setTimeout(function(){
								$('#nakegInfo').fadeOut(function(){
									$('#nakegInfo').html('');
								});
							},1000);
						//}else{
						
						//}
					}else{
						$('#nakegInfo').html('<label  class="label label-success">"'+nakeg+'" tersedia</label >').fadeIn();
						setTimeout(function(){
							$('#nakegInfo').fadeOut(function(){
								$('#nakegInfo').html('');
							});
						},1000);
					}
				}
			});
		}
	}
	// end ofcek nama kegiatan (exist/not)

	//validasi angka point
	function angkaValid(event){
		while ( ($(this).val().split(".").length - 1) > 1 ) {
			$(this).val($(this).val().slice(0, -1));
			if ( ($(this).val().split(".").length - 1) > 1 ) {
				continue;
			} else {
				return false;
			}
		}
		$(this).val($(this).val().replace(/[^0-9.]/g, ''));

		var int_num_allow = 3;
		var float_num_allow = 1;

		var iof = $(this).val().indexOf(".");
		if ( iof != -1 ) {
			if ( $(this).val().substring(0, iof).length > int_num_allow ) {
				$(this).val('');
				$(this).attr('placeholder', 'invalid number');
			}
			// ambil bilangan desimal
			$(this).val($(this).val().substring(0, iof + float_num_allow + 1));
		} else {
			$(this).val($(this).val().substring(0, int_num_allow));
		}
		return true;
	}//end of validasi angka point

	//submit form 
	function submitForm(event){
		event.stopPropagation();
		event.preventDefault();
	
		var formData = $('form').serialize();
		var datax;
		var id = $('#idformTB').val();
		if(id!=''){
			urlx	='ubah';
			datax 	=formData+'&idkeg='+id;
		}else{
			urlx	='tambah';
			datax = formData;
		}
		console.log('url :'+urlx);
		console.log('data :'+datax);
		//return false;
		saveData(urlx,datax);
	}
	//submit form 
	
	//simpan -> database
	function saveData(ur,dt){
		var idkatx = $('#id_katkegTB').val();
		$.ajax({
			url: dir+'?aksi='+ur,
            type: 'POST',
            data: dt,
            cache: false,
            dataType: 'json',
            success: function(data, textStatus, jqXHR){
            	if(data.status == 'sukses'){
					console.log('SUCCESS: ' + data.status);
            	}else{
					console.log('ERRORS submt: ' + data.status);
            	}
            },
			error: function(jqXHR, textStatus, errorThrown){
            	console.log('ERRORS: ' + textStatus);
            },
            complete: function(){
				//console.log('kategori :'+idkatx);
				alert('data tersimpan');
            	$('#loadarea').html('<i class="icon-th-list"></i> VIEW KEGIATAN');
				//$('#loadarea').html('<img src="../img/loader.gif"> ').fadeOut();
				$('#i_kegPN').toggle(1000);
				$('#v_kegPN').toggle();
				$('#addBC').toggle();
				$('#viewBC').toggle();
				loadData('tampil',idkatx,'');
				kosongkan();
			}
		});
	}	
	//end of simpan -> database
	
	//hitung point berdasarkan sks(otomatis)
	//function hitungSKS(valx){
	function hitungSKS(){
		var r = $('#id_kegTB').val();
		//var x = parseFloat($('#sksTB').val());
		var x = $('#sksTB').val();
		console.log(isNaN(x));//false (number)
		console.log('sks dr function :'+x);//alert(x);
		var x2,k,k2;
		var xx;
		Number(xx);
		if(r==13){ x2 = 5; k = 0.5; k2 = 0.25; } //asisten ahli
		else if(r==15){ x2 = 10; k = 1; k2 = 0.5;} //lektor
		
		if(x>0 && x<13){
			$('#sksINFO').html('');
			if(x<11){
				//xx=parseFloat(x*k);
				x=x*k;
				$('#poinTB').val(x);
				console.log(Number(x));
			}else{
				x=x2+((x-10)*k2);
				$('#poinTB').val(x);
				console.log(Number(xx));
			}
			//alert(x);
		}else{
			$('#sksINFO').html('sks antara 1 - 12 (angka)');
			$(valx).val('');
			return false;
		}
	}

	//hapus record kegiatan
	function hapusKeg(id,kat){
		if(confirm('melanjutkan untuk menghapus data?'))
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=hapus&idkeg='+id,
			dataType:'json',
			success:function(data){
				if(data.status=='gagal'){
					alert('gagal menghapus data');
				}else{
					loadData('tampil',kat,'');
					// loadData('tampil',kat,'','');
				}
			}
		});
	}
	//end of hapus record kegiatan
	
	//edit record kegiatan
	function editKeg(id){
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=ambiledit&idkeg='+id,
			dataType:'json',
			success:function(data){
				if(data.status=='gagal'){
					alert('database error');
				}else{
					var idkatkegy		= data.idkatkeg;
					var id_subkatkegy	= data.id_subkatkeg;
					var nakegy			= data.nakeg;
					var bukegy			= data.bukeg;
					var batuty			= data.batut;
					var poiny			= data.poin;
					var sksy			= data.sks;
					
					$('#nakegTB').attr('disabled',false);
					$('#idformTB').val(id); // add = 0 | edit = xxx
					$('#id_katkegTB').val(idkatkegy).attr('selected',true); // nampilkan kategori terpilih
					$('#nakeg1TB').val(nakegy);
					$('#nakegTB').val(nakegy);
					$('#batutTB').val(batuty);
					$('#bukegTB').val(bukegy);
					$('#poinTB').val(poiny);
					combosubkatkeg(idkatkegy,data.id_subkatkeg);
					
					$('#loadarea').html('<i class="icon-edit"></i> UBAH KEGIATAN').fadeIn();
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
		$('#id_katkegTB').val('');
		$('#nakegTB').attr('disabled',true);
		$('#nakegTB').val('');
		$('#batutTB').val('');
		$('#poinTB').val('');
		$('#bukegTB').val('');
	}
	//end of kosongkan form
	 
	//harus terisi
	function pilihKatkeg(event){
		if($(this).val()==''){
			$('#nakegTB').attr('disabled',true);
			$('#nakegTB').val('');
		}else{
			$('#nakegTB').attr('disabled',false);
		}
	}
	//end of harus terisi
	
	
	//function loadData(aksix,menux,idx,carix){
		// loadData('tampil',kat,'','');

	function loadData(aksix,menux,idx){
		$('#isi').html('<img src="../img/loader.gif"> ').fadeIn();
		var datax = 'aksi='+aksix+'&menu='+menux+'&id='+idx;
		
		$.ajax({
			url	: dir,
			type: "GET",
			data: datax,
			success:function(data){
				$('#loadarea').html('<i class="icon-th-list"></i> DAFTAR KEGIATAN</h3>');
				$('#isi').hide().html(data).fadeIn(1000);
			}
		});
	}
		
	//function pagination(page,aksix,menux,carix){
	function pagination(page,aksix,menux){
		$('#isi').html('<img src="../img/loader.gif"> ').fadeIn();
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
	
	function combosubkatkeg(id_katkeg,id_subkatkeg){
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=combo&menu=subkatkeg&id_katkeg='+id_katkeg,
			dataType:'json',
			success:function(data){
				if(data.status=='gagal'){ //eror
					$('#id_kegTB').html('<option value="">gagal menampilkan data sub kategori kegiatan</option>');
				}else{ // sukses
					var optiony ='';
					$.each(data, function (id,item){
						if(item.id_katkeg==id_katkeg)
							optiony+='<option selected="selected" value='+item.id_subkatkeg+'>'+item.subkatkeg+' ('+item.dsubkatkeg+') </option>';
						else
							optiony+='<option value='+item.id_subkatkeg+'>'+item.subkatkeg+' ('+item.dsubkatkeg+') </option>';
					});
					$('#id_subkatkegTB').html('<option value="">pilih sub kategori Kegiatan ..</option>'+optiony);
				}
			}
		});
	}

	// panggil fungsi2 di ready function ==============================================================
	$(document).ready(function(){
		loadData('tampil',1,'');
	
		$('#id_katkegTB').on('focus change',pilihKatkeg);
		
		$('#nakegTB').on('blur change', cekNakeg);
		$('#poinTB').on('keydown keypress keyup paste input', angkaValid);
	
		$('#addBC').click(function(){
			$('#i_kegPN').toggle(1000);
			$('#v_kegPN').toggle();
			$(this).toggle();
			$('#viewBC').toggle();
			$('#loadarea').html('<i class="icon-plus"></i> TAMBAH KEGIATAN</h3>').fadeIn();
			kosongkan();
		});
		
		$('#viewBC').click(function(){
			$('#i_kegPN').toggle(1000);
			$('#v_kegPN').toggle();
			$('#addBC').toggle();
			$(this).toggle();
			kosongkan();
			loadData('tampil',1,'');	
		});
		
		$('form').on('submit', submitForm);
		$('#id_katkegTB').change(function(){
			combosubkatkeg($(this).val(),'');
		});
	});	
	