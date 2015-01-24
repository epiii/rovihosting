/*function hapus(){
	alert('hapus');
	$(this).parents(".trKeg").fadeOut(1000);
	$('.trKeg').fadeOut(1000).remove();
	$('.trKeg').remove();
});*/
$('.delBC').live('click',(function(){
	if(confirm('lanjutkan menghapus ?'))
		$(this).parents('.trKeg').remove();
}));
$(document).ready(function(){
	combokeg();
	function combokeg(){
		$.ajax({
			url:'p_kegiatan.php',
			type:'get',
			dataType:'json',
			data:'aksi=combo',
			success:function(data){
				var optiony ='';
					$.each(data, function (id,item){
						optiony+='<option value='+item.idkeg+'>'+item.nakeg+' || '+item.kategori+' || '+item.poin+'</option>';
					});
					$('#id_kegiatanTB').html(optiony);
			}
		});
	}
	
	$('#simpanBC').click(function(){simpan()});
	function simpan(){
		var datax = $('#kegFR').serialize();
		$.ajax({
			url:'p_kegiatan.php',
			data:'aksi=tambah&'+datax,
			type:'post',
			datType:'json',
			success:function(data){
				var statusy = data.status;
				alert('sukses :'+statusy);
			}
		});
	}
	
	$('#pilihBC').click(function(){
		pilihKeg();
	});
	
	function pilihKeg(){
		var id_kegy = $('#id_kegiatanTB').val();
		var nakegy	= $('#id_kegiatanTB').find("option:selected").text();
		tambahKeg(id_kegy,nakegy);
	}
	
	function tambahKeg(id,nama){
		//countC += 1;
		$('.infox').remove();
		$('#kegDiv').append('<tr class="trKeg">'
								//+'<td><a class="delBC btn" href="javascript:hapus();">Hapusx</a></td>'
								+'<td><a class="delBC btn" href="#">x</a></td>'
								+'<td><input type="hidden" value="'+id+'">'+nama+'</td>'
								+'<td><input name="_file[] id="_file" required type="file" placeholder="Choose File" size="70"></td>'
							+'</tr>');
		//$('#id_kegiatanTB').find("option[value]=id").attr('disabled:true');
		//$('#id_kegiatanTB').find(value)
	}
	
	//$(".delBC").click(function(){
	
	/*function simpan(){
		$.ajax({
			url:'p_kegiatan.php',
			data:'aksi=tambah',
			dataType:'json',
			success:function(data){
				if(data.status=='sukses'){
					alert('sukses deh');
				}
			}
		});
	}*/		
	//add component  
	var countk = 0;
	//tambahKeg();
	/*$("#tambahBC").click(function(){
		tambahKeg();
	});
	function tambahKeg(){
		countk += 1;
		$('#kegDiv').append(
			'<tr class="rowKeg">' 
				+'<td class="control-group">'
					+'<input name="_nkg'+countk+'" id="_nkg'+countk+'" required type="text" placeholder="Nama Kegiatan" maxlength="200">'
				+'</td>'
			
				+'<td class="control-group">'
					+'<input name="_file'+countk+'" id="_file'+countk+'" required type="file" placeholder="Choose File" size="70">'
				+'</td>'
						
				+'<td>'
					+'<button class="remove_itemKx">delete</button>'
					+'<a class="remove_itemK btn"  href="#" >Hapus</a>'
					+'<input id="rowk_' + countk + '" name="rowk[]" value="'+ countk +'" type="hidden">'
				+'</td>'
			+'</tr>'
		);
	}
	$("a.remove_itemK").live('click',function(){
		//alert('delete');
		$(this).parents(".rowKeg").fadeOut();
		$(this).parents(".rowKeg").remove();
	});*/
	//alert('halo');
});