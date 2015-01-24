	var dir	='server/p_kegiatan.php';
	var dir2='server/p_upload.php';

	function tabpilih(x){
		$('#id_katkegH').val(x);
		loadData('tampil',x,'');
	}
	
	function tabkat(){
		$.ajax({
			url:dir,
			data:'aksi=tabkatkeg',
			type:'get',
			dataType:'json',
			success:function(data){
				var li='';
				var nox=1;

				$.each(data,function(id,item){
					if(nox==1){
						li+='<li class="active">';
            		}else{
						li+='<li>';
            		}
            		var notif;
            		if (item.jum!=0) {
                		notif='<b onmouseover="return tooltipx(this);" data-toggle="tooltip" title="'+item.jum+' kegiatan tidak valid" data-placement="top">( '
                				+item.cum
							 +' )</b> <p class="badge badge-important">'+item.jum+'</p>';
            		} else{
                		notif='<b> ( '+item.cum+' ) </b>';
            		}

            		// li+='<a  title="'+item.katkeg+'" href="#panel'+nox+'" data-toggle="tab" onclick="return loadData(\'tampil\','+item.idkatkeg+',\'\',\'\');"  style="color:#080">'
            		li+='<a  title="'+item.katkeg+'" href="#panel'+nox+'" data-toggle="tab" onclick="return tabpilih('+item.idkatkeg+');"  style="color:#080">'
                			+notif
                		+'</a>'
            		+'</li>';
            		nox++;
				});
				$('#nav-tabs').html(li);
			}
		});
	}

// panggil fungsi2 di ready function ==============================================================
	$(document).ready(function(){
		tabkat();
		loadData('tampil',1,''); //run fungsi loadData 

		//fungsi ketika tombol sisa poin dikklik
		$('#opsiBC').click(function(){
			dtkSisaView();
			$('#popMe').modal('show');
		});

		$('#statusTS').on('change',function(){
			loadData('tampil',$('#id_katkegH').val(),'');
			// tabpilih();
		});

		$('#kegiatanTS').on('keyup',function(){
			loadData('tampil','1','');
		});

		$('#subunsurTS').on('keyup',function(){
			loadData('tampil','1','');
		});

		$('#poinTS').on('keyup',function(){
			loadData('tampil','1','');
		});

		//fungis ketika tombol add di klik 
		$('#addBC').click(function(){
			$('#i_kegPN').toggle(1000);
			$('#v_kegPN').toggle();
			$(this).toggle();
			$('#viewBC').toggle();
			$('#loadarea').html('<h3>ADD KEGIATAN</h3>').fadeIn();
			kosongkan();
			bukegFC('add','');
		});

		//fungsi ketika tombol view diklik
		$('#viewBC').click(function(){
			$('#i_kegPN').toggle(1000);
			$('#v_kegPN').toggle();
			$('#addBC').toggle();
			$(this).toggle();
			kosongkan();
			loadData('tampil',$('#id_katkegH').val(),'');	
		});
		
		$('form').on('submit', submitForm); //run fungsi submitForm ketika men-submit form

		//event click tombol + (bukeg)
		$('#bukegBC').click(function(){bukegFC('add','');});
		
		//action
			//combo dinamis 1
			$('#id_katkegTB').change(function(){
				//         idform, idkatkeg, idkeg
				combokatkeg('',$(this).val(),'');
			});
			//end of combo dinamis 1
			
			//combo dinamis 2
			$('#id_kegTB').change(function(){
				combokeg('',$(this).val());
			});
			//end of combo dinamis 2
		//end of action
	});	

//image =================================================================================
    //tampilkan gambar setelah browse (sebelum upload)
	function PreviewImage(par,x){
		var typex 	= par.files[0].type;
		var sizex	= par.files[0].size;
		var namex	= par.files[0].name;
		
		if(typex =='image/png'||typex =='image/jpg'||typex =='image/jpeg'|| typex =='image/gif'){ //validasi format
			if(sizex>(900*900)){ //validasi size
				$('#viewimg_'+x).html('<span class="label label-important">ukuran max 1 MB</span>');
				$('#bukegTB_'+x).val('');
				return false;	
			}else{ 
				$('#viewimg_'+x).html('<img src="../img/loader.gif">');
				var reader = new FileReader();
				reader.readAsDataURL(par.files[0]);
	
				reader.onload = function (oFREvent){
					var urlx  = oFREvent.target.result;
					var itemx = '<img class="vwimg" src="'+urlx+'">';
					$('#viewimg_'+x).html(itemx);
				};
			}
		}else{ // format salah
			$('#viewimg_'+x).html('<img src="../img/loader.gif">');
			$('#viewimg_'+x).html('<span class="label label-important">hanya file gambar(.jpg,.jpeg,.png,.gif)</span>');
			$('#bukegTB_'+x).val('');
			return false;
		}
	};
	
	//hapus gambar yang telah terpilih
	function hapusTR(tipe,id){
		if(tipe=='add'){
				$('#imgTR_'+id).fadeOut('slow',function(){$('#imgTR_'+id).remove();});
		}else{
			if(confirm('melanjutkan menghapus '+id+'?')){
				$('#imgTR_'+id).hide('slow');
			}
		}
	}
	
	//funggsi ketika submit form 
	var filesDel = new Array();
	function submitForm(event){
		event.stopPropagation(); // mencegah reload / refresh halaman saat submit form
		event.preventDefault(); // mencegah reload / refresh halaman saat submit form
			
			$(".vwimg:hidden", "#imgTB").each(function() {
				filesDel.push($(this).attr('idx')); // array menampung gambar yang telah di del (temporary)
			});
			var jumDel = filesDel.length; // hitung jumlah element array
			
		//add imagew
			var files =new Array();
			$("input:file").each(function() {
				files.push($(this).get(0).files[0]); //array menampung gambar yang telah di add (temporary)  
			});
			var jumAdd=files.length; //hitung jumlah element array 
			
	        // Create a formdata object and add the files
			var filesAdd = new FormData();
			$.each(files, function(key, value){
				filesAdd.append(key, value); //variabel object form : menampung gambar yg telah di add / del (temporary)  
			});

			imgInfo(); //run fungsi imgInfo 

			//fungsi  untuk validasi + meneruskan submit ke fungsi selanjutnya
			function imgInfo(){
				var jumImg = $('.imgTR:visible','#imgTB').length; //hitung jumlah gambar bkeg bukeg  dalam form 
				if(jumImg==0){// ksong
					$('#imgInfo').fadeIn(function(){
						$('#imgInfo').html('minimal unggah 1 bukti kegiatan(scan/gambar)'); //notif eror =>harus browse gambar 
					});

					//efek untuk menghilangkan notif secara perlahan dalam interval 3000 milisecond = 3 detik
					setTimeout(function(){
						$('#imgInfo').fadeOut(1000,function(){
							$('#imgInfo').html(''); 
						});
					},3000);
				}else{ // ada gambar
					$('#imgInfo').html('');
					//tipe submit (add or edit )
					var iddtk = $('#idformTB').val();
					if(iddtk>0){ //edit data
						if(jumDel==0 && jumAdd==0){ 			// 0 0
							uploadFiles('edit','','',iddtk); //panggil fungsi uploadFiles
							console.log('edit : 0 0');
						}else if (jumAdd>0 && jumDel==0){ 		// + 0
							uploadFiles('edit',filesAdd,'',iddtk);
							console.log('edit : + 0');
						}else if(jumAdd==0 && jumDel>0){ 		// 0 -
							uploadFiles('edit','',filesDel,iddtk);
							console.log('edit : 0 -');
						}else{ 									// + -
							uploadFiles('edit',filesAdd,filesDel,iddtk);
							console.log('edit : + -');
						}
					}else{ // add data
						uploadFiles('add',filesAdd,'','');
						console.log('add : + 0');				// + 0
					}
				}
			}
		}
	//end of submit form 
	
	// fungsi untuk upload gambar (bukeg) 
	function uploadFiles(tipe,dataAdd,dataDel,id){
		$('#loadarea').html('<img src="../img/loader.gif"> ').fadeIn();
		
		if(dataAdd!=''){ // add / edit : +
			$.ajax({
				url: dir2+'?files&tipe='+tipe+'&iddtk='+id,
				type: 'POST',
				data: dataAdd,
				cache: false,
				dataType: 'json',
				processData: false,// Don't process the files
				contentType: false,//Set content type to false as jq 'll tell the server its a query string request
				success: function(data, textStatus, jqXHR){
					if(typeof data.error === 'undefined'){ //gak error
						saveData(tipe,data,dataDel,id); //run fungsi saveData untuk menyimpan data kegiatn + nama file(gambar) ke dalam db 
					}else{ //eerror
						console.log('ERRORS upl: ' + data.error);
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					console.log('ERRORS: ' + textStatus);
					$('#loadarea').html('<img src="../img/loader.gif"> ').fadeOut();
				}
			});
		}else{ // add / edit : 0
			saveData(tipe,'',dataDel,id); //run fungsi saveData 
		}
    }
	
	// simpan data ke database
	function saveData(typ,add,del,idx){
		var datax='';
		
		var formData = $('form').serialize();
		if(add!=''){ // ada upload file nya
			if(del!=''){ 		// edit : + -
				$.each(add.files, function(key, value){
					formData +='&fileadd[]=' + value ;
				});
				$.each(del, function(key, value){
					formData +='&filedel[]=' + value ;
				});
			}else{ 				// edit : + 0
				$.each(add.files, function(key, value){
					formData = formData + '&fileadd[]=' + value;
				});
			}
		}else{ // tanpa upload file nya
			if(del!=''){ 		// edit : 0 -
				$.each(del, function(key, value){
					formData =formData + '&filedel[]=' + value ;
				});
			}else{ 				// edit : 0 0
				formData  = formData;
			}
		}
		
		//proses simpan data (nama file upload & data form)
		var idkatx ;//= 2; 
		$.ajax({
			url: dir2,
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'json',
            success: function(data, textStatus, jqXHR){
            	if(typeof data.error === 'undefined'){
            		// Success so call function to process the form
            		idkatx = data.formData.id_katkegTB;
					console.log('SUCCESS: ' + data.success);
            	}else{
					// Handle errors here
            		console.log('ERRORS submt: ' + data.error);
            	}
            },

            error: function(jqXHR, textStatus, errorThrown){
            	// Handle errors here
            	console.log('ERRORS: ' + textStatus);
            },
            complete: function(){
   				// STOP LOADING SPINNER
            	$('#loadarea').html('<img src="../img/loader.gif"> ').fadeOut();
				alert('data tersimpan');
				$('#i_kegPN').toggle(1000);
				$('#v_kegPN').toggle();
				$('#addBC').toggle();
				$('#viewBC').toggle();
				loadData('tampil',idkatx,'');
            	$('#loadarea').html('VIEW KEGIATAN').fadeIn();
				kosongkan();
			}
		});
	}

	//hapus record kegiatan
	function hapusKeg(id,kat){
		if(confirm('melanjutkan untuk mnghapus data?'))
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=hapus&menu=dtk&iddtk='+id,
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
	
	//fungsi untuk menampilkan list-data combobox "kegiatan" berdasarkan "kategori-kegiatan"
	function combokatkeg(idform,idkatkeg,idkeg){
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=combo&menu=katkeg&id_katkeg='+idkatkeg+'&idform='+idform,
			dataType:'json',
			success:function(data){
				if(data.status=='gagal'){ //eror
					// $('#id_kegTB').html('<option value="">anda telah mengambil semua kegiatan ini</option>');
					$('#id_kegTB').html('<option value="">gagal menampilkan data kegiatan</option>');
				}else{ // sukses
					var optiony ='';
					$.each(data, function (id,item){
						optiony+='<optgroup label="'+item.subkatkeg+' '+item.dsubkatkeg+' ">';
						$.each(item.keg, function(id,item){
							if(item.idkeg==idkeg)
								optiony+='<option selected="selected" value='+item.idkeg+'>'+item.nakeg+' </option>';
							else
								optiony+='<option value='+item.idkeg+'>'+item.nakeg+' </option>';
						});
						optiony+='</optgroup>';
					});
					$('#id_kegTB').html('<option value="">pilih Kegiatan ..</option>'+optiony);
					// combokeg(idform,idkeg);
				}
			}
		});
	}
	
	//centang individu / kelompok
	function centangStatus(poin,x){
		if(x=='kelompok'){ //kelompok
			$('#anggotaRB').removeAttr('checked');
			$('#subKelompok').attr('style','display:visible');
			$('input[name=status2RB]').attr('required','required');
			//rumus pembagian poin ketua = (60% * poin) , anggota = (40% * poin) / n
			var poinx = 60/100 * parseFloat(poin);
			$('#poinTB').val(poinx.toFixed(2));
			$('#ketuaRB').attr('checked','checked');
		}else{ //individu
			$('#subKelompok').attr('style','display:none');
			$('#jumlahTB').attr('style','display:none');//.removeAttr('required');
			$('#poinTB').val(poin);
			$('#ketuaRB').attr('checked','checked');
		}
	}

	//centang ketua / anggota 
	function centangStatus2(x,p){
		var poinx; 
		if(x=='anggota'){ // anggota 
			$('#jumlahTB').val(1);
			var n = $('#jumlahTB').val();
			$('#jumlahTB').attr('style','display:visible').attr('required','required');
			poinx = (40/100 * parseFloat(p))/n;
		}else{ // ketua
			$('#jumlahTB').attr('style','display:none').removeAttr('required').val('');
			poinx = 60/100 * parseFloat(p);
		}
		$('#poinTB').val(poinx.toFixed(2));
	}

	//fungsi unrtuk validasi hanya angka 
	function angkaValid2(input,info,poin){
		var n = $('#'+input).val();

		if($('#'+input).val()!=$('#'+input).val().replace(/[^0-9.]/g,'')){
			$('#'+input).val($('#'+input).val().replace(/[^0-9.]/g,''));

			$('#'+info).html('<span class="label label-important"> hanya angka</span>').fadeIn();
			setTimeout(function(){
				$('#'+info).fadeOut();
			},1000);
		}else{
			if(n<1 || n>5){
				$('#'+input).val('');
				$('#'+info).html('<span class="label label-important"> antara 1 - 5</span>').fadeIn();
				setTimeout(function(){
					$('#'+info).fadeOut();
				},1000);
			}else{
				//rumus pembagian poin ketua = (60% * poin) , anggota = (40% * poin) / n
				var poinx = (40/100 * parseFloat(poin) )/parseInt(n);
				$('#poinTB').val(poinx.toFixed(2));
			}
		}
	}

	//fungsi unrtuk validasi hanya angka 
	function angkaValid3(input1,input2,info1,info2,poin){
		var sks = $('#'+input1).val(); // jumlah sks 
		var dosen = $('#'+input2).val(); // jumlah dosen pengajar

		if($('#'+input1).val()!=$('#'+input1).val().replace(/[^0-9]/g,'')){
			$('#'+input1).val($('#'+input1).val().replace(/[^0-9]/g,''));

			$('#'+info1).html('<span class="label label-important"> hanya angka</span>').fadeIn();
			setTimeout(function(){
				$('#'+info1).fadeOut();
			},1000);
		}else if($('#'+input2).val()!=$('#'+input2).val().replace(/[^0-9]/g,'')){
			$('#'+input2).val($('#'+input2).val().replace(/[^0-9]/g,''));

			$('#'+info2).html('<span class="label label-important"> hanya angka</span>').fadeIn();
			setTimeout(function(){
				$('#'+info2).fadeOut();
			},1000);
		}else{
			if(sks<1){
				$('#'+input1).val('');
				$('#'+info1).html('<span class="label label-important">minimal 1 sks</span>').fadeIn();
				setTimeout(function(){
					$('#'+info1).fadeOut();
				},1000);
			}else if(dosen<1){
				$('#'+input2).val('');
				$('#'+info2).html('<span class="label label-important">minimal 1 dosen</span>').fadeIn();
				setTimeout(function(){
					$('#'+info2).fadeOut();
				},1000);
			}else{
				//rumus nilai sks = (poin * sks) / dosen
				var poinx = (parseFloat(poin) * parseInt(sks) )/parseInt(dosen);
				$('#poinTB').val(poinx.toFixed(2));
			}
		}
	}

	//fungsi untuk menampilkan data bukeg,batut,poin kegiatan setelah   combobox "kegiatan" dipilih
	function combokeg(id,idkeg){
		var datax='aksi=combo&menu=nakeg'; 
		if(id!=''){ // edit mode
			datax+='&iddtk='+id; 
		}else{ // add mode
			datax+='&idkeg='+idkeg;
		}

		$.ajax({
			url:dir,
			type:'get',
			data: datax,
			dataType:'json',
			success:function(data){
				$('#batutTB').val(data.batut);
				$('#poinTB').val(data.poin); //poin awal (sebelum dibagi dll)
				$('#bukegTA').val(data.bukeg);


				var opsiDV,opsiDV2;
			//tipe : grup (ketua & anggota) 
				if(data.subkatkeg=='grup'){
					opsiDV 	='<label class="control-label">Status :</label>'
							+'<div class="controls">'
								+'<table class="table table-hover">'
									//individu
										+'<tr>'
											+'<td colspan="2">'
												+'<label><input id="individuRB" name="statusRB" value="individu"  checked="checked" required onChange="centangStatus(\''+data.poin+'\',\'individu\');" type="radio"> individu</label>'
											+'</td>'
										+'</tr>'
									//kelompok
										+'<tr>'
											+'<td colspan="2">'
												+'<label><input  id="kelompokRB" name="statusRB" value="kelompok"  required onChange="centangStatus(\''+data.poin+'\',\'kelompok\');" type="radio" > kelompok</label>'
											+'</td>'
										+'</tr>'
										+'<tr id="subKelompok" style="display:none;">'									
											+'<td class="span1" valign="center">sebagai:</td>'
											+'<td>'
											//ketua
												+'<label><input checked="checked" id="ketuaRB"  name="status2RB" value="ketua"  onChange="centangStatus2(\'ketua\','+data.poin+');" type="radio" > Ketua</label>'
											//anggota
												+'<label><input id="anggotaRB" name="status2RB" value="anggota"  onChange="centangStatus2(\'anggota\','+data.poin+');" type="radio" > Anggota</label>'
													+'<input id="jumlahTB" style="display:none;" name="jumlahTB" value="1" class="span1" size="1" type="text" maxlength="1" min="1" max="5" onkeyup="angkaValid2(\'jumlahTB\',\'jumlahInfo\','+data.poin+');" placeholder="jumlah anggota max 5">'
													+'<div style="color:red;" id="jumlahInfo"></div>'
											+'</td>'
										+'</tr>'
								+'</table>'
							+'</div>';
				}
			//tipe : kuliah
				else if(data.subkatkeg=='kuliah'){
					opsiDV	='<label class="control-label">SKS</label>'
							+'<div class="controls">'
								+'<input id="sksTB" value="'+data.sks+'" name="sksTB" type="text" class="span5"' 
									+'onkeyup="angkaValid3(\'sksTB\',\'jumlahTB\',\'sksInfo\',\'jumlahInfo\','+data.poin+');" ' 
									+'placeholder="masukkan jumlah sks" required >'
								+'<div style="color:red;" id="sksInfo"></div>'
							+'</div><br>'

							+'<label class="control-label">Jumlah Dosen</label>'
							+'<div class="controls">'
								+'<input id="jumlahTB"  value="'+data.jumAnggota+'" name="jumlahTB" class="span5" onkeyup="angkaValid3(\'sksTB\',\'jumlahTB\',\'sksInfo\',\'jumlahInfo\','+data.poin+');" size="1" type="text" maxlength="1" min="1" required  placeholder="jumlah dosen" >'
								+'<div style="color:red;" id="jumlahInfo"></div>'
							+'</div>';
				}
			//tipe : NULL / none
				else{
					opsiDV ='';
				}

			//waktu & tempat 
				if(data.cum!='B'){ 
					//penjar, pengab, penunj
					opsiDV2 ='<p><label class="control-label">Tempat / Instansi :</label>'
							+'<div class="controls">'
								+'<input class="span5" type="text" placeholder="tempat / instansi " required id="tempatTB" name="tempatTB">'
							+'</div><br>'

							+'<label class="control-label">Waktu :</label>'
							+'<div class="controls">'
								+'<input class="span5" type="text" placeholder="waktu" required id="waktuTB" name="waktuTB">'
							+'</div></p>';

					 //penunj (tambahan)
					if(data.cum=='D'){ 
						opsiDV2 +='<br><label class="control-label">Kedudukan/Tingkat :</label>'
								+'<div class="controls">'
									+'<input value="'+data.jabatan+'" class="span5" type="text" placeholder="kedudukan/Tingkat dalam kepanitian" required id="jabatanTB" name="jabatanTB">'
								+'</div></p>';
					}
				}
				//penel
				else{
					opsiDV2 ='';
				}

				$('#opsionalDV').html(opsiDV+opsiDV2).fadeIn(1000).css('display','visible');

				// edit mode
				if(id!=''){
					$('#tempatTB').val(data.tempat);
					$('#waktuTB').val(data.waktu);
					$('#poinTB').val(data.poinCur);

					// if(data.isGroup==null){ // mode : kuliah,none/null 
						// if(data.subkatkeg=='kuliah'){ // penjar : kuliah
							// $('#jumlahTB').val(data.jumAnggota);  //TB jumlah dosen pengajar (set value) 
							// $('#sksTB').val(data.sks);  //TB jumlah dosen pengajar (set value) 
							// alert(data.sks);return false;
						// }
					// }else{ // mode : group 
					if(data.isGroup!=null){	
						if(data.isGroup=='y'){ // kelompok 
							$('#kelompokRB').attr('checked','checked'); // RB kelompok (centang)
							$('#subKelompok').removeAttr('style'); // RB ketua & anggota (tampilkan)
							
							if(data.isLeader=='n'){ // anggota  
								$('#anggotaRB').attr('checked','checked'); // RB anggota (dicentang )
								$('#jumlahTB').attr('style','display:visible').val(data.jumAnggota);  //TB jumlah anggota (set value) 
							}else{ // ketua
								$('#ketuaRB').attr('checked','checked'); // RB ketua (dicentang )
							}
						}else if(data.isGroup=='n'){ // individu 
							$('#individuRB').attr('checked','checked');
						}
					}
				} //end of edit 

			}
		});	
	}
	
	//edit record kegiatan
	function editKeg(id,kat){
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=ambiledit&iddtk='+id,
			dataType:'json',
			success:function(data){
				var kondisiy	= data.kondisi;
				var id_katkegy	= data.id_katkeg;
				var idkegy 		= data.idkeg;
				var batuty		= data.batut;
				var bukegy		= data.bukeg;
				var poiny 		= data.poin;
				var poinCury	= data.poinCur;
				var sksy		= data.sks;
				var kety		= data.ket;
				var waktuy		= data.waktu;
				var tempaty		= data.tempat;
				
				$('#idformTB').val(id); // add = 0 | edit = xxx
				$('#ketTB').val(kety); 

				$('#poinTB').val(poinCury); 
				$('#bukegTA').val(bukegy); 
				$('#id_katkegTB').val(id_katkegy).attr('selected',true); // nampilkan kategori terpilih
				$('#batutTB').val(batuty);
				combokatkeg(id,id_katkegy,idkegy); // nampilkan list kegiatan sesuai kategori  
				combokeg(id,idkegy);

				var bukegarx = new Array();
				bukegarx = data.bukegArr;
				bukegFC('edit',bukegarx);
		
				$('#loadarea').html('<h3>EDIT KEGIATAN</h3>').fadeIn();
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
	
	var i = 0;
	bukegFC('add',''); //menampilkan 1 input FILE bukeg

	//fungs untuk menampilkan bukti kegiatan (bukeg) / gambar
	function bukegFC(tipe,arr){
		i+= 1;
		if(tipe=='add'){ // ketika add data
			$('#imgTB').slideDown(1000,function(){ // efek slide , munculnya element perlahan dari atas kebawah
				$('#imgTB').prepend( // prepend menambahakan element baru di awal tag
					'<tr class="imgTR" id="imgTR_add'+i+'">' 
						+'<td width="70%">'
								+'<input onchange="PreviewImage(this,'+i+');" id="bukegTB_'+i+'"'
									+'class="imgz" name="bukegTB[]" type="file" required="required"/>'
						+'</td>'
						+'<td width="25%"id="viewimg_'+i+'">'
							
						+'</td>'
						+'<td width="5%">'
							+'<a class="hpsBC btn btn-secondary" '
							+'href="javascript:hapusTR(&quot;add&quot;,&quot;add'+i+'&quot;);">X</a>'
						+'</td>'
					+'</tr>'
				);
			});
		}else{ //ketika edit data
			var imgTR ='';
			//untuk menampilkan info status bukeg sudah divalidasi oleh admin atau belum
			$.each(arr, function(id,item){
				console.log(arr);
				var info,color;
				if(item.status=='valid'){
					info="data valid";
					color='label-info';
				}else if(item.status=='invalid'){
					info=item.keterangan;
					color='label-important';
				}else{
					info='pending';
					color='label-inverse';
				}

				imgTR+= 
					"<tr class='imgTR' id='imgTR_edit"+item.idbukeg+"'>" 
						+"<td width='70%' ><label class='label "+color+"'>"+info+"</label></td>"
						+"<td width='25%'id='viewimg_"+item.idbukeg+"'>"
							+"<a href='../upload/bukeg/"+item.file+"' target='_blank'>"
								+"<img idx='"+item.idbukeg+"' class='vwimg' src='../upload/bukeg/"+item.file+"'>"
							+"</a>"
						+"</td>"
						+"<td width='5%'>"
							+"<a class='btn btn-secondary' "
							+"href='javascript:hapusTR(&quot;edit&quot;,&quot;edit"+item.idbukeg+"&quot;);'>X</a>"
						+"</td>"
					+"</tr>";
			});
			$('#imgTB').append(imgTR); // menampilkan list bukeg ke dalam element ber-ID : imgTB
		}
	}
	
	//kosongkan form
	function kosongkan(){
		$('#opsionalDV').html('');
		$('#idformTB').val('');
		$('#ketTB').val('');
		$('#bukegTA').val('');
		$('#id_katkegTB').val('');
		$('#id_kegTB').html('<option value="">pilih kategori dahulu..</option>');
		$('#batutTB').val('');
		$('#poinTB').val('');
		$('#bukegTB').val('');
		$('#imgTB').html('');
		filesDel.length=0;
	}
	//end of kosongkan form

	function tooltipx(event){
		$("[data-toggle=tooltip]").tooltip({ 
			//placement: 'right'
		});
	}

	// fungsi untuk menampilkan sisa poin sebelumnya 
	function dtkSisaView(){
		$.ajax({
			url:dir,
			dataType:'json',
			data:'aksi=viewSisa',
			success:function(data){
				var func, info, tb, no=1,total=0;
				var sisaStatus = data.sisaStatus;

				if(sisaStatus=='valid'){ //valid
					if(data.punya=='nn' || data.punya=='yn'){ // tidak punya sisa dan belum naik pangkat
						info='<label class="label label-success"> VALID</label> <i class="icon-ok"></i>';
						info2='<p style="color:blue;">* sisa poin hanya berlaku 1 x pengajuan </p>';
					}else{
						info='';
						info2='';
					}
				}else{ //invalid
					if(data.punya=='nn' || data.punya=='yn'){ // tidak punya sisa dan belum naik pangkat
						info='<label class="label label-warning"> BELUM VALID</label> <i class="icon-warning-sign"></i>';
						info2='';
						// info2='<p style="color:red;">* sisa poin anda belum dapat digunakan sebelum validasi (berkas) ke administrator </p>';
					}else{ // ny , yy
						info='';
						info2='';
					}
				}

				tb 	='<h4>Sisa Poin Pengajuan Sebelumnya '+info+'</h4>'
						+'<table class="table table-striped table-hover">'
							+'<tr class="info"><td>Kategori</td><td>sisa poin</td></tr>';
				$.each(data.sisaArr,function(id,item){
					var sisax,remainTool,remainClr;
					total=parseFloat(total)+parseFloat(item.sisa1);	
					//fungsi add / edit
						if(item.sisa2==null){ //add
							func='dtkSisaAdd('+no+','+item.idkatkeg+')';
						}else{ //edit
							func='dtkSisaEdit('+no+','+item.iddtksisa+','+item.sisa1+')';
						}

					//valid / invalid / no
						if(sisaStatus=='valid'){ //valid
							sisax =item.sisa1;
						}else if(sisaStatus=='invalid' || sisaStatus=='no'){ //invalid
							if(data.punya=='nn' || data.punya=='yn'){ // tidak punya sisa dan belum naik pangkat
								sisax ='<a href="javascript:'+func+'"> '+item.sisa1+'</a>';
							}else{ // ny, yy
								sisax =item.sisa1;
							}
						}
						// alert(sisax);return false;
					//tooltip terpakai / masih ada
						// if(item.remain==0){
						// 	remainIcon='<i class="icon-ok"></i>';
						// 	remainTool=' onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="bottom" title="habis terpakai" ';
						// 	remainClr=' class="success"';
						// }else{
							remainIcon='<i class="icon-asterisk"></i>';
							remainTool='';
							remainClr='';
						// }

					tb+='<tr '+remainClr+' '+remainTool+'>'
						+'<td>'+remainIcon+' '+item.katkeg+'</td>'
						+'<td id="sisaTD_'+no+'">:<b>'+sisax+'</b></td>'
					+'</tr>';
					no++;
				});
				tb+='<tr class="info"><td ><b class="pull-right">Total</b></td><td>:<b> '+total.toFixed(2)+'</b></td></tr>'
					+'</table>'
					+'<div id="infoSisa"></div>'
					+info2
				$('#popMeDV').html(tb);
			}
		});
	}
	
	//fungsi unrtuk validasi hanya angka 
	function angkaValid(input,info){
		var x = $('#'+input).val();

		if($('#'+input).val()!=$('#'+input).val().replace(/[^0-9.]/g,'')){
			$('#'+input).val($('#'+input).val().replace(/[^0-9.]/g,''));

			$('#'+info).html('<span class="label label-important"> hanya angka</span>').fadeIn();
			setTimeout(function(){
				$('#'+info).fadeOut();
			},1000);
		}
	}

	// fungsi untuk edit sisa poin sebelumya
	function dtkSisaEdit(no,iddtksisa,sisa){
		$('#sisaTD_'+no).html('<form onSubmit="return dtkSisaSave('+no+');" id="sisaFR_'+no+'">'
								+'<input type="hidden"  name="iddtksisaTB" value="'+iddtksisa+'">'
								+'<input class="span1" name="sisaTB" id="sisaTB_'+no+'" onkeyup="return angkaValid(\'sisaTB_'+no+'\',\'sisaInfo_'+no+'\');" required  placeholder="wajib diisi"  type="text" maxlength="5" size="5" value="'+sisa+'" >'
								// +'<input name="sisaTB" id="sisaTB_'+no+'" onkeyup="return angkaValid(\'sisaTB_'+no+'\',\'sisaInfo_'+no+'\');" required  placeholder="wajib diisi"  type="text" maxlength="4" size="5" value="'+sisa+'" >'
								+'<span id="sisaInfo_'+no+'"> </span>'
							+'</form>');
	}

	// fungsi untuk tambah sisa poin sebelumya
	function dtkSisaAdd(no,idkatkeg){
		$('#sisaTD_'+no).html('<form onSubmit="return dtkSisaSave('+no+');" id="sisaFR_'+no+'">'
								+'<input type="hidden" name="idkatkegTB" value="'+idkatkeg+'">'
								+'<input  class="span1"  required placeholder="wajib diisi" onkeyup="return angkaValid(this);" maxlength="5" size="5" name="sisaTB"  type="text" id="sisaTB_'+no+'" value="0">'
								+'<span id="sisaInfo_'+no+'"> </span>'
								+'</form>');
		// $('#sisaTD_'+no).html('<form onSubmit="return dtkSisaSave('+no+');" id="sisaFR_'+no+'"><input type="hidden" name="idkatkegTB" value="'+idkatkeg+'"><input required placeholder="wajib diisi" onkeyup="return angkaValid(this);" maxlength="5" name="sisaTB"  type="text" id="sisaTB_'+no+'" value="0"></form>');
	}

	// fungsi untuk menyimpan sisa poin sebelumya
	function dtkSisaSave(no){
		var datax = $('form#sisaFR_'+no).serialize();
		$.ajax({
			url:dir+'?aksi=saveSisa',
			dataType:'json',
			type:'post',
			cache:false,
			data:datax,
			success:function(data){
				if(data.status=='sukses'){
					var info='<p class="label label-success">'+data.status+'</p>';
					$('#infoSisa').html(info);
					setTimeout(function(){
						dtkSisaView();
					},1000);
				}else{
					var info='<p class="label label-important">'+data.status+'</p>';
					$('#infoSisa').html(info);
				}
			}
		});
		return false;
	}
	
	function loadData(aksix,menux,idx){
		$('#isi').html('<img src="../img/loader.gif"> ').fadeIn();
		var datax = 'aksi='+aksix+'&menu='+menux+'&id='+idx;
		var cari = '&statusS='+$('#statusTS').val()+'&kegiatanS='+$('#kegiatanTS').val()+'&subunsurS='+$('#subunsurTS').val()+'&poinS='+$('#poinTS').val();
		// alert(cari);return false;

		$.ajax({
			url	: dir,
			type: "GET",
			data: datax+cari,
			success:function(data){
				$('#loadarea').html('<h3>VIEW KEGIATAN</h3>');
				$('#isi').hide().html(data).fadeIn(1000);
				// tabkat();
			}
		});
	}
		
	function pagination(page,aksix,menux){
		$('#isi').html('<img src="../img/loader.gif"> ').fadeIn();
		var datax = 'starting='+page+'&aksi='+aksix+'&menu='+menux;
		var cari = '&statusS='+$('#statusTS').val()+'&kegiatanS='+$('#kegiatanTS').val()+'&subunsurS='+$('#subunsurTS').val()+'&poinS='+$('#poinTS').val();
		// alert(cari);return false;

		$.ajax({
			url:dir,
			type:"GET",
			data: datax+cari,
			success:function(data){
				$("#loadtabel").fadeOut();
				$('#isi').hide().html(data).fadeIn(1000);
				// tabkat();

			}
		});
	}