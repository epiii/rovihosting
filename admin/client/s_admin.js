var dir = 'server/p_admin.php';

// panggil fungsi2 di ready function ==============================================================
	$(document).ready(function(){
		loadData();//load data saat refresh halaman
		$('form').on('submit', submitForm); // run fungsi submit form  ketika men-submit form
		
		//masuk halaman "ADD DATA"
		$('#addBC').click(function(){
			$(this).toggle();
			kosongkan();
			$('#passwordTB').attr('required',true);
			$('#gantiPassDV').html('');
			$('#passwordDV').css('display','visible');
			$('#i_kegPN').toggle(1000);
			$('#v_kegPN').toggle();
			$('#viewBC').toggle();
			$('#loadarea').html('<i class="icon-plus"></i> TAMBAH PENGURUS').fadeIn();
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
	
	//tampil data
	function loadData(){
		$('#isi').html('<img src="../img/loader.gif"> ').fadeIn();
		$.ajax({
			url	: dir,
			type: 'GET',
			data: 'aksi=tampil',
			success:function(data){
				$('#loadarea').html('<i class="icon-th-list"></i> DAFTAR PENGURUS');
				$('#isi').hide().html(data).fadeIn(1000);
			}
		});
	}
	
	//submit form
	function submitForm(event){
		event.stopPropagation();
		event.preventDefault();
		
		var iduser	= $('#idformTB').val();
		var urlx =dir+'?';
		if($('#idformTB').val()==''){ //add
			urlx += 'aksi=tambah';
		}else{ //edit
			urlx += 'aksi=ubah&iduser='+iduser;
		}
		
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
	function hapusAdmin(id){
		if(confirm('melanjutkan untuk menghapus data?'))
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=hapus&iduser='+id,
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
	function editAdmin(id){
		//alert('id: '+id);
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=ambiledit&iduser='+id,
			dataType:'json',
			success:function(data){
				if(data.status=='gagal'){
					alert('database error');
				}else{
					kosongkan();
					var usernamey	= data.username;
					var levely		= data.level;
					
					$('#idformTB').val(id); 
					$('#usernameTB').val(usernamey);
					$('#levelTB').val(levely);
					$('#passwordTB').attr('required',false);
					$('#passwordDV').css('display','none');
					$('#gantipassDV').html('<label class="control-label">Ganti Kata Sandi</label>'
											+'<div class="controls" >'
												+'<input type="checkbox" id="passwordCB" onclick="cekPass(this);"> '
											+'</div>');
									
					$('#loadarea').html('<i class="icon-edit"></i> UBAH PENGURUS').fadeIn();
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
	function cekPass(){
		$('#passwordDV').toggle(1000);
		$('#passwordCB').val('');
	}
	//kosongkan form
	function kosongkan(){
		$('#idformTB').val('');
		$('#usernameTB').val('');
		$('#passwordTB').val('').attr('disabled',false);
		$('#passwordSP').html('');
		$('#levelTB').val('');
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
	
	