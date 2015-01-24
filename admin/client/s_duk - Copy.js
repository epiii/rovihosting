var dir ='server/p_duk.php';
// panggil fungsi2 di ready function ----------------
	$(document).ready(function(){
		listDosen();
		$("[data-toggle=tooltip]").tooltip({ 
			//placement: 'right'
		});

		$('#po').click(function(){
			$(this).popover();
		});

		$('#viewBC').click(function(){
			kosongkan();
			listDosen();	
			$('#v_kegPN').toggle();
			$('#i_kegPN').toggle(1000);
		});
	});	

//fungsi tooltip ------------------------------------
	function tooltipx(event){
		$("[data-toggle=tooltip]").tooltip({ 
			//placement: 'right'
		});
	}

	function po(event){
		// $('[data-toggle=popover]').popover();
		$('.po').popover();
	}
//function loadData list-dosen ----------------------
	function listDosen(){
		$('#isi').html('<img src="../img/loader.gif"> ').fadeIn();
		
		$.ajax({
			url	: dir,
			type: "GET",
			data: 'aksi=tampil',
			success:function(data){
				$('#loadarea').html('<h3><i class="icon-th-list"></i>DAFTAR DOSEN</h3>');
				$('#isi').hide().html(data).fadeIn(1000);
			},error: function(jqXHR, textStatus, errorThrown){
				alert('ERRORS: ' + textStatus);
			}

		});
	}
		
//fungsi paging halaman list dosen ------------------
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

//fungsi view sisa poin terdahulu @dosen
	function viewSisaPoin(id,status){
		$.ajax({
			url:dir,
			dataType:'json',
			data:'aksi=viewSisa&iddsn='+id,
			success:function(data){
				var cb,no=1,total=0,info,tampil;
				
				if(status=='valid'){
					cb='<label><input value="valid" id="validCB" onChange="return updateSisaP('+id+');" type="checkbox" checked="checked"> valid</label>';
					info='<label class="label label-success"> VALID</label> <i class="icon-ok"></i>';
				}else if(status=='invalid'){
					cb='<label><input value="invalid" id="validCB"  type="checkbox" onChange="return updateSisaP('+id+');"> valid</label>';
					info='<label class="label label-warning"> BELUM VALID</label> <i class="icon-warning-sign"></i>';
				}else{ //status = terpakai
					cb='<label><input  id="validCB" checked="checked" disabled="disabled" type="checkbox" onChange="return updateSisaP('+id+');"> valid</label>';
					info='<label class="label label-info"> TERPAKAI</label> <i class="icon-lock"></i>';
				}
					
				var tb 	='<h4>Sisa Poin Pengajuan Sebelumnya '+info+'</h4>'
						+'<h6 style="text-transform:capitalize;"><i class="icon-user"></i> '+data.nama+'</h6>'
						+'<table class="table table-striped table-hover">'
							+'<tr class="info"><td>Kategori</td><td>sisa poin</td></tr>';
				$.each(data.sisaArr,function(id,item){
					// alert(item.sisa);
					total=parseFloat(total)+parseFloat(item.sisa);	
					tb+='<tr>'
						+'<td>'+item.katkeg+'</td>'
						+'<td>:<b> '+item.sisa+'</b></td>'
					+'</tr>';
					no++;
				});

					tb+='<tr class="info">'
							+'<td><b class="pull-right">Total</b></td>'
							+'<td>:<b> '+total+'</b></td>'
						+'</tr>'
					+'</table>'
					+cb
					+'<div id="infoSisa"></div>'
				$('#popMeDV').html(tb);
				$('#popMe').modal('show');
			}
		});
	}

//update validasi 
	function updateSisaP(iddsn){
		var state = $('#validCB').val();
		// alert(state);return false;
		$.ajax({
			url:dir+'?aksi=updateSisaP',
			dataType:'json',
			type:'get',
			cache:false,
			data:'iddsn='+iddsn+'&sisaStatus='+state,
			success:function(data){
				if(data.status=='sukses'){
					var info='<p class="label label-success">'+data.status+'</p>';
					$('#infoSisa').html(info);
					setTimeout(function(){
						viewSisaPoin(iddsn,data.sisaStatus);
						listDosen();
					},1000);
				}else{
					var info='<p class="label label-important">'+data.status+'</p>';
					$('#infoSisa').html(info);
				}
			}
		});
		return false;
	}
//view / detail dosen (rekap) ----------------------
	function viewDosenDtl(id){
		viewDosenBio(id);
		$('#loadarea').html('<h3><i class="icon-search"></i> DETAIL REKAPITULASI</h3>').fadeIn();
		$('#i_kegPN').toggle(1000);
		$('#v_kegPN').toggle();
	}

//view biodata dosen 
	function viewDosenBio(id){
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=ambilview&menu=bio&iddsn='+id,
			dataType:'json',
			error: function(jqXHR, textStatus, errorThrown){
				console.log('ERRORS: ' + errorThrown);
			},
			success:function(data){
				var nipy		= data.nip;
				var karpegy		= data.karpeg;
				var gelardy 	= data.gelard;
				var gelarby 	= data.gelarb;
				var namady 		= data.namad;
				var namaby		= data.namab;
				var jky			= data.jk;
				var agamay		= data.agama;
				var tly			= data.tl;
				var tglly		= data.tgll;
				//----
				var pty			= data.pt;
				var masagolsy	= data.masagols;
				var masajabsy	= data.masajabs;
				var tglgolsy	= data.tglgols;
				var tgljabsy	= data.tgljabs;
				var urutgolsy	= data.urutgols;
				var golsy		= data.gols;
				var jabsy		= data.jabs;
				var masajabsy	= data.masajabs;
				// var gtoty 		= data.gtot;
				//----		
				var katArrx		= new Array();
					katArrx  	= data.katArr;
	
				//set field 
				$('#nipTD').html(nipy);
				$('#namaTD').html(gelardy+' '+namady+' '+namaby+' '+gelarby);
				$('#agamaTD').html(agamay);
				$('#jkTD').html(jky);
				$('#ttlTD').html(tly+', '+tglly);
				//----
				$('#ptTD').html(pty);
				$('#fungJGTD').html(jabsy+' / '+golsy);
				$('#tmtJGTD').html(tgljabsy+' / '+tglgolsy);
				$('#masaJGTD').html(masajabsy+' th / '+masagolsy+' th');
				
				targetJG(id,masajabsy,masagolsy,pty,urutgolsy,katArrx);
				// targetJG(id,masajabsy,masagolsy,gtoty,pty,urutgolsy,katArrx);
			}
		});
	}
//end of view / detail dosen (rekap) ----------------------

//target jab/gol ----------------
	// function targetJG(id,masaJabDt,masaGolDt,gtotDt,ptDt,urutGol,katArrDt){
	function targetJG(id,masaJabDt,masaGolDt,ptDt,urutGol,katArrDt){
		$.ajax({
			url:'../user/server/p_rekapitulasi.php',
			type:'get',
			// data:'aksi=target&masaJabDt='+masaJabDt+'&masaGolDt='+masaGolDt+'&gtotDt='+gtotDt+'&ptDt='+ptDt+'&urutGol='+urutGol,
			data:'aksi=target&masaJabDt='+masaJabDt+'&masaGolDt='+masaGolDt+'&ptDt='+ptDt+'&urutGol='+urutGol,
			dataType:'json',
			success:function(data){
				var idjabtgty	= data.idjabtgt;
				var idgoltgty	= data.idgoltgt;
				var jabtgty		= data.jabtgt;
				var goltgty		= data.goltgt;
				var pointgty	= data.pointgt;
				var kurangany	= new Array();
					kurangany	= data.kurangan;
				$('#tgtJGTD').html(jabtgty+' / '+goltgty); //target jabatan / golongan yang dituju
				$('#iddsnH').val(id);
				$('#idgoltgtH').val(idgoltgty);
				$('#idjabtgtH').val(idjabtgty);
				$('#goltgtH').val(goltgty);
				$('#jabtgtH').val(jabtgty);
				$('#pointgtH').val(pointgty);
				// $('#gtotDtH').val(gtotDt);
				$('#kuranganH').val(kurangany);

					// alert(kurangany);return false;
				viewKeg(id,goltgty,jabtgty,pointgty,kurangany);
			},error: function(jqXHR, textStatus, errorThrown){
				console.log('ERRORS: ' + errorThrown);
			}
		});	
	}//end of targetJG ----------

//fungsi detail kategori + kegiatan dosen x ------------------------------------
	function viewKeg(id,goltgt,jabtgt,pointgt,kuranganDt){
		var kuranganDtx	= JSON.stringify(kuranganDt);
		// alert(kuranganDtx);return false;
		var idgoltgt=$('#idgoltgtH').val();
		var idjabtgt=$('#idjabtgtH').val();

		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=ambilview&menu=keg&iddsn='+id+'&pointgt='+pointgt+'&kuranganDt='+kuranganDtx,
			dataType:'json',
			success:function(data){ //ajax-success-function ---------------------
				// var kuranganx 	= data.kurangan;
				var tb=''; // var untuk cetak tabel 
				var gtotNumDt	= data.gtot;
				var katArrx 	= new Array();
				 	katArrx 	= data.katArr;

 				var kuranganDtx	= new Array();
					kuranganDtx	= kuranganDt;
				var kuranganx 	= new Array();
					kuranganx 	= data.kurangan;

				//cek bisa diajukan naik/belum ----------------------------------
				if(kuranganDtx=='' && kuranganx==''){
					console.log(kuranganx);
					console.log(kuranganDtx);
					$('#naikTempTH').html('<a href="javascript:naikTemp('+id+',\''+idgoltgt+'\',\''+idjabtgt+'\',\''+goltgt+'\',\''+jabtgt+'\','+pointgt+');" id="naikTempBC" class="btn "><i class="icon-ok" ></i> &nbsp;setujui</a>');
				}else{
					console.log(kuranganx);
					console.log(kuranganDtx);
				}
				//end of cek bisa diajukan naik/belum ---------------------------
				
				//kategori : kosong ---------------------------------------------
				if(katArrx==''){
					tb+='kategori kegiatan masih kosong';
					console.log('kategori kosong');
				}//end of kategori :  kosong ------------------------------------
				//kategori : ada ------------------------------------------------
				else{
					//loop kategori ---------------------------------------------
					$.each(katArrx, function(id,item){
						//tampung katArr -> var ===
						var katkegy			= item.katkeg;
						var subTotTgtPerc 	= item.subTotTgtPerc;
						var subTotTgtNum 	= item.subTotTgtNum;
						var tipey			= item.tipe;
						var sisay			= item.subsisa;
						var kegArrx			= new Array();
							kegArrx			= item.kegArr;
						var nox				= 1;
						var subtotNumDt;

						if(item.subtot==null){
							subtotNumDt=0;
						}else{
							subtotNumDt	=item.subtot;
						}
						var	subtotPercDt=(parseFloat(subtotNumDt) /  parseFloat(pointgt) * 100).toFixed(2);

						//header tabel kegiatan per kategori ---------
						tb+='<div class="badge badge-inverse pull-left">'+katkegy+'</div><br>'	
							+'<table class="table table-hover table-bordered table-striped" width="100%" border="0">'
								+'<tr class="info">'	
									+'<td width="5%"><b>no.</b></td>'
									+'<td width="78%"><label class="text-center control-label"><b>Kegiatan</b></label></td>'
									+'<td width="5%"><b>Point</b></td>'
									+'<td width="12%"><b>Detail</b></td>'
								+'</tr>	';
						//end of header tabel kegiatan per kategori --
						
						// kegiatan kosong -----------------------
						if(kegArrx==''){
							tb+='<tr>'
									+'<td colspan="2">'
										+'<label class="label label-important">'
											+'data kosong'
										+'</label>'
									+'</td>'
									+'<td><p class="pull-right">0</p></td><td></td>'
								+'</tr>';						
						}// end of kegiaatan kossong --------------
						// kegiatan ada --------------------------
						else{
							// var i =1;
							//loop kegiatan ----------------------
							$.each(kegArrx, function(id,item){
								var poinAwly= item.poinAwl;
								var poiny 	= item.poin;
								var nakegy 	= item.nakeg;
								var statusy	= item.status;
								var iddtky	= item.iddtk;
								var idkegy	= item.idkeg;

								if(statusy=='new'){
									kegClr = 'warning';
									titlex = 'baru';
									btn ='<a href="javascript:viewBukeg('+iddtky+');" class="btn"><i class="icon-search"></i>detail</a>';
								}else if(statusy=='valid'){
									kegClr = 'success';
									titlex = 'valid';
									btn ='<a href="javascript:viewBukeg('+iddtky+');" class="btn"><i class="icon-search"></i>detail</a>';
								}else if(statusy=='checked'){
									kegClr = '';
									titlex = 'tidak valid';
									btn ='<a href="javascript:viewBukeg('+iddtky+');" class="btn"><i class="icon-search"></i>detail</a>';
								}else{
									kegClr = '';
									titlex = 'poin awal '+poinAwly;
									btn ='<i class="icon-ok"></i> Terpakai';
								}
								
								tb+='<tr onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="bottom" title="'+titlex+'" class="'+kegClr+'" id="kegTR_'+iddtky+'">'
								// tb+=tr
									+'<td  width="5%">'+nox+'</label></td>'
									+'<td  width="78%"><label class="control-label">'+nakegy+'</label></td>'
									+'<td  width="5%"><label class="pull-right control-label">'+poiny+'</label></td>'
									+'<td  width="12%"><label class="pull-right control-label">'+btn+'</label></td>'
								+'</tr>	';
								nox++;
							});//end of loop kegiatan ------------------------
						}// end of kegiatan ada ------------------------------
						
						//data : marking min - max subtotal ------------------
						var clrsub,clrsubpro,iconsub,infosub;
						if(tipey=='mn'){//min 
							infosub		= 'minimal '+subTotTgtPerc+'% ('+subTotTgtNum+' poin)';
							if(subtotNumDt<subTotTgtNum){ //min : kurang
								clrsub		= ' style="background-color:orange;color:white;"';	
								clrsubpro	= ' class="badge badge-warning"';	
								iconsub		= '<i class="icon-arrow-up">';
							}else{ //min : pas/lebih
								clrsub		= ' style="background-color:green;color:white;"';	
								clrsubpro	= ' class="badge badge-success"';	
								iconsub		= '<i class="icon-ok">';
							}//end of data : marking min - max subtotal -----------
						}else if(tipey=='mx'){ // max						
							infosub		= 'maximal '+subTotTgtPerc+'% ('+subTotTgtNum+' poin)';
							if(subtotNumDt>subTotTgtNum){ //max: lebih
								clrsub		= ' style="background-color:red;color:white;"';	
								clrsubpro	= ' class="badge badge-important"';	
								iconsub		= '<i class="icon-arrow-down">';
								// infosub		= 'minimal '+jumMinPercy+'% ('+jumMinNumy+' poin)';
							}else if(subtotNumDt==0){ //max : 0 nol
								clrsub		= ' style="background-color:orange;color:white;"';	
								clrsubpro	= ' class="badge badge-warning"';	
								iconsub		= '<i class="icon-arrow-up">';
								// infosub		= 'memenuhi syarat '+jumMinPercy+'% - '+jumMaxPercy+'% ('+jumMinNumy+' - '+item.jumMaxNum+' poin)';
							}else{ //max : pas/kurang
								clrsub		= ' style="background-color:green;color:white;"';	
								clrsubpro	= ' class="badge badge-success"';	
								iconsub		= '<i class="icon-ok">';
								// infosub		= 'memenuhi syarat '+jumMinPercy+'% - '+jumMaxPercy+'% ('+jumMinNumy+' - '+item.jumMaxNum+' poin)';
							}//end of data : marking min - max subtotal -----------
						}			

							tb+='<tr>'
									+'<td colspan="2" align="right">'
										+'<span class="pull-right">'
											+'<b>Sisa Point <i>(sebelumnya)</i></b>'
										+'</span>'
									+'</td>'
									+'<td align="right">'
										+'<span class="pull-right" >'+sisay+'</span>'
									+'</td>'
									+'<td >'
									+'</td>'
								+'</tr>';
						//subtotal ----------------------------------------------
								tb+='<tr class="info" onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="bottom" title="'+infosub+'" >'
									+'<td colspan="2" align="right">'
										+'<span class="pull-right">'
											+'<b>Sub Total : </b>'
											+'<label '+clrsubpro+'>'+subtotPercDt+' %</label>&nbsp;'
											+iconsub+'</i>'
										+'</span>'
									+'</td>'
									+'<td '+clrsub+'><span class="pull-right" ><b>'+subtotNumDt+'</b></span></td>'
									+'<td '+clrsub+'><span class="pull-right" ></span></td>'
								+'</tr>'
						//end of subtotal ---------------------------------------
							+'</table>'	
						+'</div>'; 
					});//end of loop kategori -----------------------------------
				}//end of kategori : ada ----------------------------------------
				
				//grand total : marking kurang/memenuhi syarat -------------------
				var info,icon,colortot;
				if(gtotNumDt<pointgt){
					info		= 'minimal '+pointgt+' poin';
					icon		= '<i class="icon-arrow-up"></i>';
					colortot	= ' style="background-color:orange;color:white;cursor:pointer;"';	
				}else{
					info		= 'poin memenuhi syarat ';
					icon		= '<i class="icon-ok"></i>';
					colortot	= ' style="background-color:green;color:white;cursor:pointer;"';	
				}//end of grand total : marking kurang/memnuhi syarat -----------
				
				//grand total : cetak tr grand total ----------------------------
				tb+='<table class="table table-striped ">'
						+'<tr class="info" data-toggle="tooltip" title="'+info+'" data-placement="bottom">'
							+'<td width="81%"><span class="pull-right"><b>Grand Total : '+icon+'</b></span></td>'
							+'<td width="7%" '+colortot+'><span class="pull-right"><b>'+gtotNumDt+'</b></span></td>'
							+'<td '+colortot+'></td>'
						+'</tr>'
					+'</table>';
				//end of grand total : cetak tr grand total ----------------------
				$('#kegDV').html(tb);
				
			}//end of ajax-success ------------------------------------------------
		});//end of ajax ----------------------------------------------------------
	}//end of fungsi detail kategori + kegiatan dosen x ---------------------------


//buka bukti kegiatan untuk per kegiatan	
	// function viewBukeg(id,nama){
	function viewBukeg(id){
		$('#kegTR_'+id).removeClass('warning');
		$('#popMeDV').html('<img src="../img/loader.gif">').fadeIn;
		$.ajax({
			url:dir,
			data:'aksi=ambilview&menu=bukeg&iddtk='+id,
			type:'get',
			dataType:'json',
			error: function(jqXHR, textStatus, errorThrown){
				alert('ERRORS: ' + textStatus);
				console.log(jqXHR+' - '+textStatus+' - '+errorThrown);
			},
			success:function(data){
				var bukegTB = '<center>'
						+'<div class="modal-header active">'
        					+'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'
        					+'<h4 class="modal-title">Bukti - Kegiatan (scan/foto sertifikat/ijazah dll)</h4>'
      					+'</div>'
						+'<div><button onclick="po(this);" type="button" class="po btn" xdata-toggle="popover"data-placement="bottom" data-html="true" title="Info" data-content="'
							+'<b>Kegiatan :</b><p>- '+data.nakeg+'</p>'
							+'<b>Batas Kepatutan :</b><p>- '+data.batut+'</p>'
							+'<b>Bukti Kegiatan :</b><p>- '+data.bukeg+'</p>'
						+'"><i class="icon-question-sign"></i> Keterangan</button>'
					+'<form onSubmit="return updateBukeg('+id+');" method="post" autocomplete="off">'
				$.each(data.dataArr,function(id,item){
					var statusCek,ketVis
					var ketVal='';
					if(item.status=='valid'){
						statusCek	= "checked='checked'";
						ketVis		="style='display:none;'";
						ketVal		='';
					}else{
						statusCek 	='';
						ketVis		='';
						if(item.keterangan==''){
							ketVal +='gambar tidak valid';
							console.log(item.keterangan);
						}else{
							ketVal +=item.keterangan;
							console.log(item.keterangan);
						}
					}
					//console.log(item.idbukeg);
					bukegTB+='<div class="form-row control-group row-fluid">'
						+'<a target="_blank" href="../upload/bukeg/'+item.file+'">'
							+'<img width="200" class="vwimg" src="../upload/bukeg/'+item.file+'" id="namaTB" name="namaTB" />'
						+'</a>'
						+'<input name="idH[]" type="hidden" id="idH" value="'+item.idbukeg+'">'
						+'<label><input value="'+item.status+'" id="statusTB_'+item.idbukeg+'" name="statusTB[]"  type="checkbox" '+statusCek+' onchange="centangBukeg('+item.idbukeg+');"class="span12"  />valid</label>'
						+'<span ><input value="'+ketVal+'" id="keteranganTB_'+item.idbukeg+'" name="keteranganTB[]" type="text" '+ketVis+'  placeholder="pesan (memo)"  class="span12"/>'
					+'</div>';			
				});
					bukegTB+='<div class="form-actions row-fluid">'
								+'<div class="span7 offset3">'
									+'<button type="submit" class="btn btn-secondary" id="simpanBC" >simpan</button>'
								+'</div>'
							+'</div>'
					+'</form></center>';

				$('#popMeDV').html(bukegTB).fadeIn;
				$('#popMe').modal('show');
				
			}
		});
	}

//centang bukti kegiatan (valid/tidak)	
	function centangBukeg(id){
		$('#keteranganTB_'+id).toggle(1000);
		if($('#statusTB_'+id).val()=='valid'){
			$('#statusTB_'+id).val('invalid');
			$('#keteranganTB_'+id).val('gambar tidak valid');
			console.log('status = invalid');
			console.log('telah dimatikan');
		}else{
			$('#statusTB_'+id).val('valid');
			$('#keteranganTB_'+id).val('');
			console.log('status = valid');
			console.log('tlh dhidupkan');
		}
	}

//update status buki kegiatan (valid/tidak valid)
	function updateBukeg(id){	
		kosongkan();
		var datax = $('form').serialize();
		$.ajax({
			url: dir+'?aksi=ubah&iddtk='+id,
			type: 'POST',
			data: datax,
			cache: false,
			dataType: 'json',
			success: function(data){
				if(data.status=='gagal'){ 
					console.log('update status bukeg : gagal');
				}else{
					console.log('update status bukeg : sukses');
					$('#popMe').modal('hide');
					var a 	= $('#iddsnH').val();
					viewDosenBio(a);
				}
			}
		});
		return false;
	}

//kosongkan form
	function kosongkan(){
		$('#kuranganH').val('');
		$('#naikTempTH').html('');
	}
	//end of kosongkan form

//naik temporary
	function naikTemp(id,idgol,idjab,gol,jab,pointgt){
		if(confirm('setujui kenaikkan pangkat dosen menuju '+gol+' / '+jab+' ?'))
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=naikTemp&iddsn='+id+'&pointgt='+pointgt+'&idgoltgt='+idgol+'&idjabtgt='+idjab,
			dataType:'json',
			success:function(data){
				alert(data.status);
				var a 	= $('#iddsnH').val();
				viewDosenBio(a);
				kosongkan();
			}
		});
	}