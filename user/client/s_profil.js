var dir='server/p_profil.php';

$(document).ready(function(){
	loadData('tampil');
    $('form').on('submit',simpanData);

	$('#editBC').click(function(){
		loadData('ambiledit');
		$('input:submit').toggle();
		$('#cancelBC').toggle();
		$(this).toggle();
		$('#gantiDV').append('<td width="25%" colspan="3">'
								+'<label class="control-label">'
								+'<input type="checkbox" id="gantiTB" onclick="cekganti(gantiTB);"/>'
								+'Ganti Password</label></td>');
	});
});	

//fungsi untuk hapus akun user(dosen) secara kesluruhan (profil + kegiatan + bukeg )
function hapusAkun(){
	if(confirm('Anda yakin akan menghapus akun secara permanen?')){
		$.ajax({
			url:dir,
			dataType:'json',
			data:'aksi=hapusAkun',
			success:function(data){
				alert(data.status); // notif sebelum logout
				location.href='../logout.php'; //otomatis akan logout ketika berhasil hapus akun 
			}
		});
	}
}

//fungsi untuk memilih tanggal 
function tglinput(par){ 
	$(par).datepicker();
}

//fungsi untuk  mengecek ulang  password baru (sama/tidak) 
function cekpass(){
	var p2 = $('#passBTB2').val();
	var p1 = $('#passBTB1').val();
	if(p2==p1){ // notif ketika sama
		$('#passinfo').html('<span class="label label-success">password sesuai</span>'); 
	}else{ //notif ketika beda/salah
		$('#passinfo').html('<span class="label label-important">password harus sama</span>');
	}
}

//fungsi ketika checkbox dicentang (saat edit profil) => menampilkan textbox2 ganti  password  
function cekganti(x){
	$('#pass1').toggle(1000);
	$('#pass2').toggle(1000);
	$('#pass3').toggle(1000);
	$('#passLTB').val('');
	$('#passBTB1').val('');
	$('#passBTB2').val('');

	//fungsi ketika event keyUp  pada textbox password1
	$('#passBTB1').keyup(function(){
		cekpass();
	});
	//fungsi ketika event keyUp  pada textbox password2
	$('#passBTB2').keyup(function(){
		cekpass();
	});
}
	

function loadData(aksix){
	$('#loadarea').html('<img src="../img/loader.gif">loading..').fadeIn();
	$.ajax({
		url	: dir,
		type: "GET",
		data: "aksi=tampil",
		dataType:"json",
		success:function(data){
			if(data.status=='kosong'){
				alert('kosong');
			}else{
				//user 
				var usernamey 	= data.username;

				//dsn
				var nipy 		= data.nip;
				var gelardy		= data.gelard;
				var gelarby		= data.gelarb;
				var namady 		= data.namad;
				var namaby 		= data.namab;
				var agamay 		= data.agama;
				var tly 		= data.tl;
				var tglly 		= data.tgll;
				var umury 		= data.umur;
				var karpegy 	= data.karpeg;
				var jky			= data.jk;	
					if(jky=='P'){ var jkx ='perempuan';}
					else {var jkx ='laki-laki'; }
				
				//histjab
				//var id_ruley	= data.id_rule;
				var id_pty		= data.id_pt;
				var id_golsy 	= data.id_gols;
				var id_jabsy 	= data.id_jabs;
				var golsy 		= data.gols;
				var jabsy 		= data.jabs;
				var tgll2y 		= data.tgll2;
				

				//histjab
				var masagolsy 	= data.masagols;
				var masajabsy	= data.masajabs;
				var tgljabsy 	= data.tgljabs;
				var tglgolsy 	= data.tglgols;
				var tgljabs2y 	= data.tgljabs2;
				var tglgols2y 	= data.tglgols2;
				var id_pty		= data.id_pt;
				var pty		 	= data.pt;
				
				//kegiatan
				var pointy 		= data.gtot;
				// alert(pointy);return false;
				//view
				if(aksix=='tampil'){
					$('a#editBC').fadeIn();
					$('input:submit').fadeOut();
		
					$('#loadarea').html('<h3>VIEW PROFIL</h3>');
					$('#usernameTD').html(usernamey);
					$('#nipTD').html(nipy);
					$('#golsTB').val(golsy);
					$('#jabsTB').val(jabsy);
					$('#golsTD').html(golsy);
					$('#jabsTD').html(jabsy);
					$('#namalTD').html(gelardy+'. '+namady+' '+namaby+' '+gelarby);
					$('#karpegTD').html(karpegy);
					$('#agamaTD').html(agamay);
					$('#jkTD').html(jkx);
					$('#tlTD').html(tly);
					$('#tgllTD').html(tglly);
					$('#ptTD').html(pty);
					$('#pointTD').html(pointy);
					$('#umurTD').html(umury+' tahun');
					$('#TMTjabsTD').html(tgljabsy);
					$('#TMTgolsTD').html(tglgolsy);
					$('#masajabsTD').html(masajabsy+' tahun');
					$('#masagolsTD').html(masagolsy+' tahun');
					$('#gantiDV').html('');
				}
				//edit
				else{
					$('#loadarea').html('<h3>EDIT PROFIL</h3>');
					
					//user
					$('#usernameTD').html('<input required  name=usernameTB id=usernameTB type=text value='+usernamey+'>');
					
					//dsn
					$('#nipTD').html('<input maxlength="18" required name="nipTB" type="text" value="'+nipy+'">');
					$('#karpegTD').html('<input maxlength="8" name="karpegTB" type="text" value="'+karpegy+'">');
					$('#umurTR').toggle();
					$('#namalTR').toggle();
					$('#gelardTR').toggle();
					$('#gelarbTR').toggle();
					$('#namadTR').toggle();
					$('#namabTR').toggle();
					$('#gelardTD').html('<input name="gelardTB" type="text" value="'+gelardy+'">');
					$('#gelarbTD').html('<input name="gelarbTB" type="text" value="'+gelarby+'">');
					$('#namadTD').html('<input class="capitizer" required name="namadTB" type="text" value="'+namady+'">');
					$('#namabTD').html('<input name="namabTB" type="text" value="'+namaby+'">');
					$('#agamaTD').html('<select required name="agamaTB" id="agamaTB" >'
										+'<option value=islam>Islam</option>'
										+'<option value=kristen>Kristen</option>'
										+'<option value=katholik>Katholik</option>'
										+'<option value=hindu>Hindu</option>'
										+'<option value=budha>Budha</option>'
										+'</select>');
					$('#agamaTB').val(agamay).attr('selected',true);
					$('#jkTD').html('<select required name=jkTB id=jkTB>'
										+'<option value=L>Laki - laki</option>'
										+'<option value=P>Perempuan</option>'
									+'</select>');
					$('#jkTB').val(jky).attr('selected',true);
					$('#tlTD').html('<input class="capitizer" required name="tlTB" type="text" value="'+tly+'">');
					$('#tgllTD').html("<input maxlength='10' onclick='tglinput(this);' required name='tgllTB' id='tgllTB' type='text' value='"+tgll2y+"'><br><p class='label label-info'>mm / dd / yyyy</p>");
					
					//histjab		
					combogol(id_golsy,id_jabsy,id_pty);
					$('#TMTjabsTD').html("<input maxlength='10' onclick='tglinput(this);' required name='TMTjabsTB' id='TMTjabsTB' type='text' value='"+tgljabs2y+"'><br><p class='label label-info'>mm / dd / yyyy</p>");
					$('#TMTgolsTD').html("<input maxlength='10' onclick='tglinput(this);' required name='TMTgolsTB' id='TMTgolsTB' type='text' value='"+tglgols2y+"'><br><p class='label label-info'>mm / dd / yyyy</p>");
					$('#masajabsLB').html('Tanggal Lantik Jabatan ');
					$('#masajabsTD').html("<input  onclick='tglinput(this);' required id='tgljabsTB' name='tgljabsTB' type='text' value='"+tgljabsy+"'><br><p class='label label-info'>mm / dd / yyyy</p>");
					
				}
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log('ERRORS: ' + textStatus);
		}
	});
}

function combogol(idgol,idjab,idpt){
	$.ajax({
		url:dir,
		type:'get',
		dataType:'json',
		cache:false,
		data:'aksi=combo&menu=gol',
		success:function(data){
			var optiony ='';
			$.each(data, function (id,item){
				if(idgol==item.id_gol){
					optiony+='<option value="'+item.id_gol+'" selected="selected">'+item.gol+' </option>';
				}else{
					optiony+='<option value="'+item.id_gol+'">'+item.gol+' </option>';
				}
			});
			$('#golsTD').html('<select id="golsTB" name="golsTB" required>'
								+'<option value="">silahkan pilih golongan</optionn>'+optiony
							+'</select>');
			combojab(idjab,idpt);
		}
	}); 
}	

function combojab(idjab,idpt){
	$.ajax({
		url:dir,
		type:'get',
		dataType:'json',
		cache:false,
		data:'aksi=combo&menu=jab',
		success:function(data){
			var optiony ='';
			$.each(data, function (id,item){
				if(idjab==item.id_jab){
					optiony+='<option value="'+item.id_jab+'" selected="selected">'+item.jab+' </option>';
				}else{
					optiony+='<option value="'+item.id_jab+'">'+item.jab+' </option>';
				}
			});
			$('#jabsTD').html('<select id="jabsTB" name="jabsTB" required>'
								+'<option value="">silahkan pilih jabatan</option>'+optiony
							+'</select>');
			combopt(idpt);	
		}
	});
}	

function combopt(idpt){
	$.ajax({
		url:dir,
		type:'get',
		dataType:'json',
		cache:false,
		data:'aksi=combo&menu=pt',
		success:function(data){
			var optiony ='';
			$.each(data, function (id,item){
				if(idpt==item.id_pt){
					optiony+='<option value="'+item.id_pt+'" selected="selected">'+item.pt+' </option>';
				}else{
					optiony+='<option value="'+item.id_pt+'">'+item.pt+' </option>';
				}
			});
			$('#ptTD').html('<select id="ptTB" name="ptTB" required>'
								+'<option value="">silahkan pilih pend. terakhir</option>'+optiony
							+'</select>');
		}
	});
}	

function simpanData(event){
    $('#loadarea').html('<img src="../img/loader.gif">loading..').fadeIn();
	event.stopPropagation(); // Stop stuff happening
    event.preventDefault(); // Totally stop stuff happening

    // var urlx = $(this).attr('action');
    // var typex= $(this).attr('method');
    var datax= $(this).serialize();
	//alert(datax);
    $.ajax({
        url:dir+'?aksi=ubah',
        type:'post',
        data:datax,
        dataType:'json',
        success:function(data){
	        if(data.status=='sukses'){
	            loadData('tampil');				
				$('#gantiTB').attr('checked',false); // menghilangkan centang setalah sukses simpan/update data
				$('#gantiTR').css('display','none'); // menyembunyikan textbox setelah sukses simpan/update data 
				$('#pass1').css('display','none'); 
				$('#pass2').css('display','none');
				$('#pass3').css('display','none');
				$('#passBTB1').val('');
				$('#passBTB2').val('');
				$('#umurTR').toggle();
				$('#namalTR').toggle();
				$('#gelardTR').toggle();
				$('#gelarbTR').toggle();
				$('#namadTR').toggle();
				$('#namabTR').toggle();
				$('#cancelBC').toggle();
			}else{
                alert('gagal menyimpan data');
            }
        }	
    });	
}

