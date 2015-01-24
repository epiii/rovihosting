var dir ='server/p_news.php';
var dir2 ='server/p_upNews.php';
	//tampil data 
	function loadData(aksix,menux,idx){
		$('#isi').html('<img src="../img/loader.gif"> ').fadeIn();
		var datax = 'aksi='+aksix+'&menu='+menux+'&id='+idx;
		
		$.ajax({
			url	: dir,
			type: "GET",
			data: datax,
			success:function(data){
				$('#loadarea').html('<h3><i class="icon-th-list"></i> INFORMASI</h3>');
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

	function submitForm(event){
		event.stopPropagation();
		event.preventDefault();

		//add image
		var files =new Array();
		$("input:file").each(function() {
			files.push($(this).get(0).files[0]); 
		});
		
		//cek ada ada upload gambar / tidak
			var isUpload;
			if($("input:file").val()==''){
				isUpload = 0;
				console.log(isUpload);
			}else{
				isUpload = 1;
				console.log(isUpload);
			}
		//end of cek ada ada upload gambar / tidak
		 
		// Create a formdata object and add the files
		var filesAdd = new FormData();
		$.each(files, function(key, value){
			filesAdd.append(key, value);
		});
		//console.log(filesAdd);return false;
		
		//tipe submit (add or edit )
		var idnews= $('#idformTB').val();
		if(idnews!=''){ //edit data
			if(isUpload==0){ 			// 0 0
				uploadFiles('edit','',idnews);
				console.log('edit : 0 0');
			}else{ 									// + -
				uploadFiles('edit',filesAdd,idnews);
				console.log('edit : + -');
			}
		}else{ // add data
			uploadFiles('add',filesAdd,'');
			console.log('add : + 0');				// + 0
		}
		//return false;
	}

	function uploadFiles(tipe,dataAdd,id){
		$('#loadarea').html('<img src="../img/loader.gif"> ').fadeIn();
		if(dataAdd!=''){ // add / edit : +
			$.ajax({
				url: dir2+'?files&tipe='+tipe+'&idnews='+id,
				type: 'POST',
				data: dataAdd,
				cache: false,
				dataType: 'json',
				processData: false,// Don't process the files
				contentType: false,//Set content type to false as jq 'll tell the server its a query string request
				success: function(data, textStatus, jqXHR){
					if(typeof data.error === 'undefined'){ //gak error
						saveData(tipe,data,id);
						console.log('sukses upload');
					}else{ //eerror
						console.log('ERRORS upload : ' + data.error);
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					console.log('ERRORS upload2: ' + textStatus);
					$('#loadarea').html('<img src="../img/loader.gif"> ').fadeOut();
				}
			});
		}else{ // add / edit : 0
			saveData(tipe,'',id);
		}
    }
	
	// simpan data ke database
	function saveData(typ,add,idx){
		var datax='';
		
		var formData = $('form').serialize();
		if(add!=''){ // ada upload file nya
			$.each(add.files, function(key, value){
				formData +='&fileadd[]=' + value ;
			});
		}else{ // tanpa upload file nya
			formData  += formData;
		}
		//console.log('@savedata :'+formData);
		//return false;
		
		//proses simpan data (nama file upload & data form)
		var idkatx='' ;//= 2; 
		$.ajax({
			url: dir2,
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'json',
            success: function(data, textStatus, jqXHR){
            	if(typeof data.error === 'undefined'){
            		// Success so call function to process the form
            		idkatx += formData.kategoriTB;
					console.log('SUCCESS savedata1: ' + data.success);
            	}else{
					// Handle errors here
            		console.log('ERRORS savedata1: ' + data.error);
            	}
            },

            error: function(jqXHR, textStatus, errorThrown){
            	// Handle errors here
            	console.log('ERRORS savedata2: ' + textStatus);
            },
            complete: function(){
   				// STOP LOADING SPINNER
            	$('#loadarea').html('<img src="../img/loader.gif"> ').fadeOut();
				alert('data tersimpan');
				$('#i_kegPN').toggle(1000);
				$('#v_kegPN').toggle();
				$('#addBC').toggle();
				$('#viewBC').toggle();
				console.log(idkatx);
				//return false;
				//loadData('tampil',idkatx,'');
				loadData('tampil','berita','');
            	$('#loadarea').html('>>DAFTAR INFORMASI').fadeIn();
				kosongkan();
			}
		});
	}

	
	//hapus record kegiatan
	function hapusNews(id,kat){
		if(confirm('melanjutkan untuk menghapus data?'))
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=hapus&idnews='+id,
			dataType:'json',
			success:function(data){
				if(data.status=='gagal'){
					alert('gagal menghapus data');
				}else{
					loadData('tampil',kat,'','');
				}
			}
		});
	}
	
	//edit record kegiatan
	function editNews(id,kat){
		//$('#loadarea').html('<img src="../img/loader.gif"> ').fadeIn();
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=ambiledit&idnews='+id,
			dataType:'json',
			success:function(data){
				kosongkan();
				var tittley		= data.tittle;
				var deskripsiy	= data.deskripsi;
				var filey		= data.file;
				var kategoriy	= data.kategori;
				
				$('#fileTB').attr('required',false);
				$('#idformTB').val(id); // add = 0 | edit = xxx
				$('#kategoriTB').val(kategoriy).attr('selected',true); // nampilkan kategori terpilih
				$('#tittleTB').val(tittley);
				$('#deskripsiTB').val(deskripsiy);
					
				$('#loadarea').html('<h3><i class="icon-edit"></i> UBAH INFORMASI</h3>').fadeIn();
				$('#i_kegPN').toggle(1000);
				$('#v_kegPN').toggle();
				$('#viewBC').toggle();
				$('#addBC').toggle();
			},
			error: function(jqXHR, textStatus, errorThrown){
				console.log('ERRORS: ' + errorThrown);
			}
		});
	}
	
	//kosongkan form
	function kosongkan(){
		$('#idformTB').val('');
		$('#kategoriTB').val('');
		$('#tittleTB').val('');
		$('#deskripsiTB').val('');
		$('#fileTB').val('');
	}
	//end of kosongkan form
	
	
// panggil fungsi2 di ready function ==============================================================
	$(document).ready(function(){
		loadData('tampil','berita','');
		$('form').on('submit', submitForm);
		$('#addBC').click(function(){
			$('#fileTB').attr('required',true);
			$('#i_kegPN').toggle(1000);
			$('#v_kegPN').toggle();
			$(this).toggle();
			$('#viewBC').toggle();
			$('#loadarea').html('<h3><i class="icon-plus"></i> TAMBAH INFORMASI</h3>').fadeIn();
			kosongkan();
		});
		$('#viewBC').click(function(){
			$('#i_kegPN').toggle(1000);
			$('#v_kegPN').toggle();
			$('#addBC').toggle();
			$(this).toggle();
			kosongkan();
			loadData('tampil','berita','');	
		});
	});	
	