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

		$('#rekomBC').click(function(){
			loadrekap($('#iddsnH').val());
			$('#popHeader').html('Rekomendasi Kenaikan Pangkat');
			$('#popMe').modal('show');
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
							+'<td>:<b> '+total.toFixed(2)+'</b></td>'
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
	function viewDosenDtl(iddsn){
		loadrekap(iddsn);
		$('#iddsnH').val(iddsn);

		// viewDosenBio(id);
		$('#loadarea').html('<h3><i class="icon-search"></i> DETAIL REKAPITULASI</h3>').fadeIn();
		$('#i_kegPN').toggle(1000);
		$('#v_kegPN').toggle();
	}
//end of view / detail dosen (rekap) ----------------------

//load rekap dosen
	function loadrekap(iddsn){
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=view&iddsn='+iddsn,
			dataType:'json',
			success:function(data){
				var nipy	= data.nip;
				var nmy 	= data.nm;
				var agmy	= data.agm;
				var jky		= data.jk;
				var tly		= data.tl;
				var tglly	= data.tgll;
				var tmtJaby	= data.tmtJab;
				var tmtGoly	= data.tmtGol;
				// var gtoty 	= data.gtot;
				//------------
				var pty 	= data.pt;
				var id_pty 	= data.id_pt;
				var masaJaby= parseInt(data.masaJab);
				var masaGoly= parseFloat(data.masaGol);
				var jabFungy= data.jabFung;
				var golFungy= data.golFung;
				var urutGoly= data.urutGol;
				var pointgt = data.pointgt;
				var gtotNumDty = data.gtotNumDt;
				var gtotnoty = data.gtotnot;
				//-----------

				$('#ptDtH').val(pty);
				$('#goltgtH').val(golFungy);
				$('#jabtgtH').val(jabFungy);
				$('#pointgtH').val(pointgt);
				$('#gtotDtH').val(gtotNumDty);
				$('#urutGolDtH').val(urutGoly);
				$('#idptDtH').val(id_pty);

				$('#nipTD').html(nipy);
				$('#namaTD').html(nmy);
				$('#ptTD').html(pty);
				$('#agamaTD').html(agmy);
				$('#jkTD').html(jky);
				$('#ttlTD').html(tly+' / '+tglly);
				$('#fungJGTD').html(jabFungy+' / '+golFungy);
				$('#tmtJGTD').html(tmtJaby+' / '+tmtGoly);
				$('#masaJGTD').html(masaJaby+'th / '+masaGoly+' th');
				

					// $.each(data.subnotarr,function(id,item){
					// 	subnotarr[item.id_katkeg]=item.subnot;
					// });

					var TB='';
					var subnotarr={};
					var subtotNumDtArr={};
				//loop kategori kegiatan ------------------------------------------------
					$.each(data.katArr, function(id,item){
						//tampung katArr -> var ------------ 
						var id_katkegy		= item.idkatkeg;
						var katkegy			= item.katkeg;
						var subTotTgtPerc	= item.subTotTgtPerc;
						var subTotTgtNum	= item.subTotTgtNum;
						var tipey			= item.tipe;
						var cumy			= item.cum;

						//subtotal data (semua) 
						if(item.subtotNumDt==null){
							var subtotNumDty=0;
						}else{
							var subtotNumDty=(parseFloat(item.subtotNumDt)).toFixed(2);
						}

						//subtotal data (valid tok)
						if(item.subnot==null){
							var subnoty=0;
						}else{
							var subnoty=(parseFloat(item.subnot)).toFixed(2);
						}
						
						// alert(subtotNumDty);
						subtotNumDtArr[id_katkegy]=subtotNumDty; // untuk tampil
						subnotarr[id_katkegy]=item.subnot; // untuk target perhitungan 
						
						//subtotal sisa poin 
						if(item.remain==0){
							var sisay=0;
						}else{
							var sisay=(parseFloat(item.remain)).toFixed(2);
						}
						// var sisay			= item.subsisa;
						var kegArrx 		= new Array();
							kegArrx 		= item.kegArr;
						var nox				= 1;
						
						//header tabel kegiatan per kategori --------------------
						TB+='<div class="badge badge-inverse pull-left">'+katkegy+'</div>'
							// cetak PDF @kategori keg
 							+'<a class="btn pull-right pdfKat" style="display:none;" href="../user/view/r_cetak.php?iddsn='+iddsn+'&tipe=pdf&kat='+cumy+'&ruwet='+item.ruwet+'" target="_blank" class="btn btn-secondary pull-right">cetak <i class="icon-print"></i></a>'
 							+'<br>'
						 	+'<table class="table table-hover table-bordered table-striped" width="100%" border="0">'
								+'<tr class="info">'
									+'<td width="5%"><b>no.</b></td>'
									+'<td width="60%"><label class="text-center control-label"><b>Kegiatan</b></label></td>'
									+'<td width="25%"><label class="text-center control-label"><b>Sub Unsur</b></label></td>'
									+'<td width="5%"><b>Poin</b></td>'
									+'<td width="5%"><b>Detail</b></td>'
								+'</tr>'
			
						//kegiatan kosong ----------------------------------------	
						if(kegArrx==''){
							TB+='<tr>'
									+'<td colspan="3"><label class="label label-important">data kosong</label></td>'
									+'<td><p class="pull-right">0</p></td>'
									+'<td><button disabled class="btn"><i class="icon-search"></i></button></td>'
								+'</tr>';						
						}//end of kegiatan kosong --------------------------------

						//kegiatan ada -------------------------------------------
						else{
							//loop kegiatan --------------------------------------
							$.each(kegArrx, function(id,item){
								var poinAwly= item.poinAwl;
								if(item.poinCur==0){
									var poinCury=0;
								}else{
									var poinCury=(parseFloat(item.poinCur)).toFixed(2);
								}
								
								var kety 	= item.ket;
								var nakegy 	= item.nakeg;
								var statusy	= item.status;
								var iddtky	= item.iddtk;
								var idkegy	= item.idkeg;

								if(statusy=='new'){	
									kegClr = 'warning';
									titlex = 'baru';
									btn ='<a href="javascript:viewBukeg('+iddtky+');" class="btn"  onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="Bukti Kegiatan" ><i class="icon-search"></i></a>';
								}else if(statusy=='valid'){
									kegClr = 'success';
									titlex = 'valid';
									btn ='<a href="javascript:viewBukeg('+iddtky+');" class="btn" onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="Bukti Kegiatan"><i class="icon-search" ></i></a>';
								}else if(statusy=='checked'){
									kegClr = '';
									titlex = 'tidak valid';
									btn ='<a href="javascript:viewBukeg('+iddtky+');" class="btn" onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="top" title="Bukti Kegiatan"><i class="icon-search" ></i></a>';
								}else{
									kegClr = '';
									titlex = 'poin awal '+poinAwly;
									btn ='<i class="icon-ok"></i> Terpakai';
								}

								// record tabel -----------------------------------
								TB+='<tr onmouseover="return tooltipx(this);" data-toggle="tooltip" data-placement="left" title="'+titlex+'" class="'+kegClr+'" id="kegTR_'+iddtky+'">'									
									+'<td>'+nox+'</label></td>'
									+'<td><label class="control-label">'+kety+'</label></td>'
									+'<td><label class="control-label">'+nakegy+'</label></td>'
									+'<td><label class="pull-right control-label">'+poinCury+'</label></td>'
									+'<td>'+btn+'</td>'
							 	+'</tr>'
							 	//end of record tabel ----------------------------
								nox++;
							});//end of loop kegiatan ----------------------------
						}//end of kegiatan : ada ----------------------------------
					
						//data : marking min - max subtotal ------------------
						var clrsub,clrsubpro,iconsub,infosub;
						if(tipey=='mn'){//min 
							infosub		= 'minimal '+subTotTgtPerc+'% ('+subTotTgtNum+' poin)';
							if(subtotNumDty<subTotTgtNum){ //min : kurang
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
							if(subtotNumDty>subTotTgtNum){ //max: lebih
								clrsub		= ' style="background-color:red;color:white;"';	
								clrsubpro	= ' class="badge badge-important"';	
								iconsub		= '<i class="icon-arrow-down">';
							}else if(subtotNumDty==0){ //max : 0 nol
								clrsub		= ' style="background-color:orange;color:white;"';	
								clrsubpro	= ' class="badge badge-warning"';	
								iconsub		= '<i class="icon-arrow-up">';
							}else{ //max : pas/kurang
								clrsub		= ' style="background-color:green;color:white;"';	
								clrsubpro	= ' class="badge badge-success"';	
								iconsub		= '<i class="icon-ok">';
							}//end of data : marking min - max subtotal -----------
						}			
						TB+='<tr>'
								+'<td colspan="3" align="right">'
									+'<span class="pull-right">'
										+'<b>Sisa Poin <i>(sebelumnya)</i></b>'
									+'</span>'
								+'</td>'
								+'<td align="right">'
									+'<span class="pull-right" >'+sisay+'</span>'
								+'</td>'
								+'<td ><span class="pull-right" ></span></td>'
							+'</tr>'
						//record subtotal -----------------------------------------
						TB+='<tr class="info" onmouseover="return tooltipx(this);" data-toggle="tooltip" title="'+infosub+'" data-placement="bottom">'
								+'<td colspan="3" align="right">'
									+'<span class="pull-right">'
										// +'<b>Sub Total : </b><label '+clrsubpro+'>'+subtotPercDt+' ok %</label>&nbsp;'+iconsub+'</i>'
										+'<b>Sub Total : </b>'
										// +'<label '+clrsubpro+'> x %</label>&nbsp;'+iconsub+'</i>'
									+'</span>'
								+'</td>'
								+'<td '+clrsub+'>'
									+'<span class="pull-right" ><b>'+subtotNumDty+'</b></span>'
								+'</td>'
								+'<td></td>'
							+'</tr>'
						//end of record subtotal ------------------------------
						+'</table>'
					+'</div>';
				});//end of loop kategori -------------------------------------
				// alert(subtotNumDtArr);return false;
				target(subtotNumDtArr,gtotnoty,subnotarr);
			//grand total -------------------------------------------------------
				var info,icon,colortot	;
				if(gtotNumDty<pointgt){
					info		= 'minimal '+pointgt+' poin';
					icon		= '<i class="icon-arrow-up"></i>';
					colortot	= ' style="background-color:orange;color:white;cursor:pointer;"';	
				}else{
					info		= 'poin memenuhi syarat ';
					icon		= '<i class="icon-ok"></i>';
					colortot	= ' style="background-color:green;color:white;cursor:pointer;"';	
				}

				 TB+='<table class="table table-hover table-bordered table-striped" width="100%" border="0">'
						+'<tr class="info" data-toggle="tooltip" title="'+info+'" data-placement="bottom">'
							+'<td colspan="3" width="80%"><span class="pull-right"><b>Grand Total : '+icon+'</b></span></td>'
							+'<td width="5%"'+colortot+'><span class="pull-right"><b>'+gtotNumDty.toFixed(2)+'</b></span></td>'
							+'<td width="5%"></td>'
						+'</tr>'
					+'</table>';
				//end of grand total -------------------------------------------------------

				$('#kegDV').html(TB);
				$('#loadarea').html('<h3><i class="icon-search"></i> DETAIL REKAPITULASI</h3>').fadeIn();
			}//end of success function ---------------
		}); // end of ajax ---------------------------
	}//end of loadrekap___________________________________________________________________________________

//target 
	function target(subtotNumDtArr,gtotnot,subnotarr){
		// alert(subnotarr);return false;
		var gtotNumDt 	= $('#gtotDtH').val();
		var ptDt 		= $('#ptDtH').val();
		var urutGolDt 	= $('#urutGolDtH').val();
		var subtotNumDt	= $('#subtotNumDtH').val();
		// var idptDt 		= $('#idptDtH').val();


		var subtotNumDtArr 	= JSON.stringify(subtotNumDtArr);
		var subnotarr 		= JSON.stringify(subnotarr);
		// var jsonfied = {
		//     subtotNumDtArr: subtotNumDt.replace( /,$/, "" ).split(',').map(function(subtotNumDt) {
		//         return {subtotNumDtArr: subtotNumDt};
		//     })
		// };
		// var ok = JSON.stringify(jsonfied);
		var datax = 'aksi=target'
				 + '&ptDt='+ptDt
				 + '&urutGolDt='+urutGolDt
				 + '&gtotNumDt='+gtotNumDt
				 + '&subtotNumDtArr='+subtotNumDtArr
				 + '&gtotnot='+gtotnot
				 + '&subnotarr='+subnotarr;
		$.ajax({
			url:dir,
			type:'get',
			data:datax,
			// data:'aksi=target&gtotNumDt='+gtotNumDt+'&ptDt='+ptDt+'&urutGolDt='+urutGolDt+'&subtotNumDtArr='+subtotNumDtArr,
			dataType:'json',
			success:function(data){
				var iddsn = $('#iddsnH').val();

				// $('#naikTempTH').html('<a href="javascript:naikTemp('+id+',\''+idgoltgt+'\',\''+idjabtgt+'\',\''+goltgt+'\',\''+jabtgt+'\','+pointgt+');" id="naikTempBC" class="btn "><i class="icon-ok" ></i> &nbsp;rekomendasikan</a>');

				var info='';
				info+='<div style="padding-left:20px; padding-top:20px;" class="tabbable" id="tabs-104268">'
						+'<ul class="nav nav-tabs">';
				var i=x=1;
				//---notif untuk kurangan persyaratan---------------------- 
				//link tab ===
				var kur ='';
				$.each(data,function(id,item){
					if(i==1){
						// info+='<li class="active"><a href="#panel'+i+'" data-toggle="tab" style="color:#080"><b>'+item.goltgt+'</b></a></li>';
						info+='<li class="active"><a href="#panel'+i+'" data-toggle="tab" style="color:#080"><b>'+item.pangkatTgt+'</b></a></li>';
					}else{
						// info+='<li><a href="#panel'+i+'" data-toggle="tab" style="color:#080"><b>'+item.goltgt+'</b></a></li>';
						info+='<li><a href="#panel'+i+'" data-toggle="tab" style="color:#080"><b>'+item.pangkatTgt+'</b></a></li>';
					}
					i++;
					if(item.jumKrg==0){
						info+='';
					}
				});
				//end of link tab ===
					info+='</ul>'
					+'<div class="tab-content">';

				//content tab ===
				$.each(data,function(id,item){

					if(x==1){
						info+='<div align="justify" class="tab-pane active" id="panel'+x+'">';
					}else{
						info+='<div align="justify" class="tab-pane" id="panel'+x+'">';
					}

					// naik
					if(item.jumKrg==0){
						//cetak DUPAK button;
							var sesi = $('#idsesiH').val();
							var ruwet= encode64(sesi+iddsn+sesi);

							$('.pdfKat').removeAttr('style');
							$('#dupakBC').removeAttr('style');
							$('#dupakBC').attr("href","../user/view/r_dupak.php?tipe=pdf&ruwet="+ruwet+"&idsesi="+sesi+"&iddsn="+iddsn);

						info+='<div class="alert alert-success">Direkomendasikan untuk naik ke golongan <label class="label label-success">'+item.goltgt+'</label> jabatan <label class="label label-success">'+item.jabtgt+'</label>'
								+'<p><a href="javascript:naikTemp('+item.pointgt+','+item.idgoltgt+','+item.idjabtgt+','+item.id_pt+');" class="btn"><i class="icon-ok"></i> Setujui</a></p>'
							+'</div>';
					}else{// belum naik 
						info+='<ul class="alert alert-warning">Kekurangan untuk naik ke golongan : <label class="label label-warning">'+item.goltgt+'</label> jabatan : <label class="label label-warning">'+item.jabtgt+'</label>';
						$.each(item.kurangan,function(id,item){
							info+='<li style="list-style:none;"><i class="icon-ok"></i> '+item+'</li>';						
						});
						info+='</ul>';
					}

					info+='</div>';
					$('#popMeDV').html(info);
					x++;
				});//end of content tab ===
				//----end of notif untuk kurangan persyaratan---------------------- 
			}//end of ajax-success---- 
		});//end of ajax ------------
	}//end of function ---------

//buka bukti kegiatan untuk per kegiatan	
	// function viewBukeg(id,nama){
	function viewBukeg(id){
		// alert(id);
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
				$('#popHeader').html('Bukti - Kegiatan <p>(scan/foto sertifikat/ijazah dll)</p>');
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
		// kosongkan();
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
					// alert(a);return false;
					loadrekap(a);
					// viewDosenBio(a);
				}
			}
		});
		return false;
	}

//kosongkan form
	function kosongkan(){
		$('#iddsnH').val('');
		$('#kuranganH').val('');
		$('#popMeDV').html('');
		// $('#naikTempTH').html('');
	}
	//end of kosongkan form

//naik temporary
	// function naikTemp(id,idgol,idjab,gol,jab,pointgt){
	function naikTemp(pointgt,idgoltgt,idjabtgt,ptDt){
		var urutGolDt 	= $('#urutGolDtH').val();
		var gtotNumDt 	= $('#gtotDtH').val();
		var id_ptDt		= $('#idptDtH').val();
		var iddsn 		= $('#iddsnH').val();

		if(confirm('setujui untuk naik pangkat ?'))
		// alert('fungsi naik temporary');		
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=naikTemp&iddsn='+iddsn+'&pointgt='+pointgt+'&idgoltgt='+idgoltgt+'&idjabtgt='+idjabtgt+'&id_ptDt='+id_ptDt,
			// data:'aksi=naikTemp&iddsn='+iddsn+'&pointgt='+pointgt+'&idgoltgt='+idgoltgt+'&idjabtgt='+idjab,
			// data:'aksi=naikTemp&iddsn='+id+'&pointgt='+pointgt+'&idgoltgt='+idgol+'&idjabtgt='+idjab,
			dataType:'json',
			success:function(data){
				alert(data.status);
				var a 	= $('#iddsnH').val();
				loadrekap(a)
				// viewDosenBio(a);
				// kosongkan();
			}
		});
	}