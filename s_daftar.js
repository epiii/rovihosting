var pros = 'p_daftar.php';
function comboFak(fak){
	//console.log(fak);return false;
	$.ajax({
		url:pros,
		type:'post',
		data:'aksi=combo&kategori=fak&fak='+fak,
		dataType:'json',
		success:function(data){
			if(data.status=='gagal'){
				$('#_jur').html('<option value="">kosong</option>');
			}else{
				var opt='<option value="silahkan pilih jurusan"></option>';
				$.each(data,function(id,item){
					opt+='<option value="'+item.idjur+'">'+item.jur+'</option>';
				})
				$('#_jur').html(opt);
			}
		}
	})
}

function comboJur(jur){
	$.ajax({
		url:pros,
		type:'post',
		data:'aksi=combo&kategori=jur&jur='+jur,
		dataType:'json',
		success:function(data){
			if(data.status=='gagal'){
				$('#_prodi').html('<option value="">kosong</option>');
			}else{
				var opt='<option value="silahkan pilih prodi"></option>';
				$.each(data,function(id,item){
					opt+='<option value="'+item.idprodi+'">'+item.prodi+'</option>';
				})
				$('#_prodi').html(opt);
			}
		}
	})
}

// function cekNama(event){
// 	if( $(this).val() != $(this).val().replace(/[^a-zA-Z ]/g, '')){
// 		$(this).val($(this).val().replace(/[^a-zA-Z ]/g, ''));
// 	}
// }

function cekNip(nip){
	var digit = $('#_nip').val().length;
	if( $('#_nip').val() != $('#_nip').val().replace(/[^0-9]/g, '')){ // cek hanya angka 
		$('#_nip').val($('#_nip').val().replace(/[^0-9]/g, ''));
	}else if(digit<18){ // cek 18 digit
		$('#nipinfo').html('<span class="label label-important">harus 18 digit</span>');
	}else{ //sudah 18 digit -> cek k db
		$.ajax({
			url:'p_daftar.php',
			type:'post',
			data:'aksi=cek&kategori=nip&nip='+nip,
			dataType:'json',
			success:function(data){
				if(nip==''){
					$('#nipinfo').html('<span class="label label-important">harus diisi (hanya angka)</span>');
				}else if(data.status=='ada'){
					$('#nipinfo').html('<span class="label label-important">"'+nip+'" telah digunakan</span>');
					$('#_nip').val('');
					return false;
				}else{
					$('#nipinfo').html('<span class="label label-success"><i class="icon-ok"></i></span>');
				}
			}
		});
	}
}

function cekKarpeg(karpeg){
	var digit = $('#_kg').val().length;
	if($('#_kg').val()!=$('#_kg').val().replace(/[^a-z.A-Z0-9]/g,'')){
		$('#_kg').val($('#_kg').val().replace(/[^a-z.A-Z0-9]/g,''));
	}else if(digit<8){ // cek 18 digit
		$('#nipinfo').html('<span class="label label-important">harus 18 digit</span>');
	}else{ //sudah 18 digit -> cek k db
		$.ajax({
			url:'p_daftar.php',
			type:'post',
			data:'aksi=cek&kategori=karpeg&karpeg='+karpeg,
			dataType:'json',
			success:function(data){
				if(karpeg==''){
					$('#kginfo').html('<span class="label label-important">harus diisi(hanya a-z 0-9 . )</span>');
				}else if(data.status=='ada'){
					$('#kginfo').html('<span class="label label-important">"'+karpeg+'" telah digunakan</span>');
					$('#_kg').val('');
					return false;
				}else if(data.status=='kosong'){
					$('#kginfo').html('<span class="label label-success"><i class="icon-ok"></i></span>');
				}
			}
		});
	}
}

function cekUser(user){
	var x = $('#_un').val();
	if(x!=x.replace(/[^a-z_A-Z0-9]/g,'')){
		$('#_un').val(x.replace(/[^a-z_A-Z0-9]/g,''));
	}else{
		$.ajax({
			url:'p_daftar.php',
			type:'post',
			data:'aksi=cek&kategori=username&username='+user,
			dataType:'json',
			success:function(data){
				if(user==''){
					$('#userinfo').html('<span class="label label-important">harus diisi (hanya mengandung a-z 0-9 _)</span>');
				}else if(data.status=='ada'){
					$('#userinfo').html('<span class="label label-important">"'+user+'" telah digunakan </span>');
					$('#_un').val('');
					return false;
				}else{
					$('#userinfo').html('<span class="label label-success"><i class="icon-ok"></i></span>');
				}
			}
		});
	}
}

//function cekRps(x){
function cekRps(ps,rps){
	//console.log('rps :'+rps+', ps:'+ps);
	if(ps==''){
		$('#psinfo').html('<span class="label label-important">harus diisi</span>');
	}if(rps==''){
		$('#rpsinfo').html('<span class="label label-important">harus diisi</span>');
	}else if(rps==ps){
		$('#rpsinfo').html('<span class="label label-success" ><i class="icon-ok"></i></span>');
		$('#psinfo').html('');
	}else if(rps!=ps){
		$('#rpsinfo').html('<span class="label label-important" >password tidak sesuai</span>');
	}
}

$(document).ready(function(){
	$('#_fak').on('change',function(){
		comboFak($(this).val());
	});
	$('#_jur').on('change',function(){
		comboJur($(this).val());
	});
	$('form').on('submit',tambah);
	//validation  - action
		//username
		$('#_un').on('keyup paste input blur',function(){
			cekUser($(this).val());
		});
		//nip
		$('#_nip').on('change blur paste input',function(){
			cekNip();
		});
		//password
		$('#_ps').on('change keyup blur input paste', function(){
			cekRps($('#_ps').val(),$('#_rps').val());
		});
		//repassword
		$('#_rps').on('change keyup blur input paste', function(){
			cekRps($('#_ps').val(),$('#_rps').val());
		});
		
	$("#_tgll").datepicker(function(){
		format:"yyyy/mm/dd"
	});
	$("#_tglgol").datepicker(function(){
		format:"yyyy/mm/dd"
	});
	$("#_tgljab").datepicker(function(){
		format:"yyyy/mm/dd"
	});
	
	function tambah(event){
		event.stopPropagation(); 
        event.preventDefault();
         
		var ps 	= $('#_ps').val();
		var rps = $('#_rps').val();
		var nip	= $('#_nip').val();
		
		//cek password sama
		if(ps!=rps){
			return false;
			$('#_ps').focus();
		}
		//cek nip valid
		else if($('#_nip').val().length<18){
			return false;
			$('#_nip').focus();
		}
		//sudah valid=> simpan ke db
		else{
			var datax = $(this).serialize();
			$.ajax({
				url:'p_daftar.php',
				type:'post',
				data:'aksi=tambah&'+datax,
				dataType:'json',
				success:function(data){
					if(data.status=='gagal'){
						alert('gagal menyimpan data');
					}else{
						alert('berhasil simpan data');
						window.location='index.php';//window.open('index.php');
					}
				}
			});
		}
	}
});