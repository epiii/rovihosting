var dir = 'server/p_rekapitulasi.php';

$(document).ready(function(){
	loadrekap();
	// loadBio();
	$('#infoBC').on('click',function(){
		loadInfo();
	});
	// $('#dupakBC').on('click',function(){
	// 	'<a href="view/r_cetak.php?tipe=pdf&kat='+cumy+'&ruwet='+item.ruwet+'" target="_blank" class="btn btn-secondary pull-right">cetak <i class="icon-print"></i></a><br>'

	// });
});
	
//load biodata dosen
	function loadrekap(){
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=view',
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
				var masaJaby= parseInt(data.masaJab);
				var masaGoly= parseFloat(data.masaGol);
				var jabFungy= data.jabFung;
				var golFungy= data.golFung;
				var urutGoly= data.urutGol;
				var pointgt = data.pointgt;
				var gtotNumDty =parseFloat(data.gtotNumDt);
				// alert(pointgt);
				//-----------

				//  (urutGoly);
				$('#_nip').html(nipy);
				$('#_nm').html(nmy);
				$('#_pt').html(pty);
				$('#_agm').html(agmy);
				$('#_jk').html(jky);
				$('#_ttl').html(tly+' / '+tglly);
				$('#_fungJG').html(jabFungy+' / '+golFungy);
				$('#_tmtJG').html(tmtJaby+' / '+tmtGoly);
				$('#_masaJG').html(masaJaby+'th / '+masaGoly+' th');
				
					var TB='';
					var subtotNumDtArr={};

					// var gtotNumDt =0;
					//loop kategori ---------------------------------------------
					$.each(data.katArr, function(id,item){
						// var subtotDty=0;
						//tampung katArr -> var ------------ 
						var id_katkegy		= item.idkatkeg;
						var katkegy			= item.katkeg;
						var subTotTgtPerc	= item.subTotTgtPerc;
						var subTotTgtNum	= item.subTotTgtNum;
						var subtotNumDty 	= parseFloat(item.subtotNumDt);
						var tipey			= item.tipe;
						var cumy			= item.cum;
						// var sisay			= item.subsisa;
						
						//subtotal poin sekarang 
						if(item.subtotNumDt==null){
							var subtotNumDty=0;
						}else{
							var subtotNumDty=(parseFloat(item.subtotNumDt)).toFixed(2);
						}
						//subtotal sisa 
						if(item.remain==0){
							var sisay=0;
						}else{
							var sisay=(parseFloat(item.remain)).toFixed(2);
						}
						
						// alert(subtotNumDty);return false;

						var kegArrx 		= new Array();
							kegArrx 		= item.kegArr;
						var nox				= 1;
						subtotNumDtArr[id_katkegy]=subtotNumDty;
						
						//header tabel kegiatan per kategori --------------------
						TB+='<div class="badge badge-inverse pull-left">'+katkegy+'</div>'
							// +'<a href="view/r_cetak.php?tipe=pdf&kat='+cumy+'&ruwet='+item.ruwet+'" target="_blank" class="btn btn-secondary pull-right">cetak <i class="icon-print"></i></a>'
							+'<br>'
						 	+'<table class="table table-hover table-bordered table-striped" width="100%" border="0">'
								+'<tr class="info">'
									+'<td width="5%"><b>no.</b></td>'
									+'<td width="78%"><label class="text-center control-label"><b>Sub Unsur</b></label></td>'
									+'<td width="5%"><b>Poin</b></td>'
								+'</tr>'
			
						//kegiatan kosong ----------------------------------------	
						if(kegArrx==''){
							TB+='<tr>'
									+'<td colspan="2"><label class="label label-important">data kosong</label></td>'
									+'<td><p class="pull-right">0</p></td>'
								+'</tr>';						
						}//end of kegiatan kosong --------------------------------

						//kegiatan ada -------------------------------------------
						else{
							//loop kegiatan --------------------------------------
							$.each(kegArrx, function(id,item){
								var poinAwly= item.poinAwl;
								// var poinCury = item.poinCur;
								if(item.poinCur==0){
									var poinCury=0;
								}else{
									var poinCury=(parseFloat(item.poinCur)).toFixed(2);
								}								

								var nakegy 	= item.nakeg;
								var statusy	= item.status;
								// var poinCury= 0;
								var tr;
								if(statusy=='done'){
									tr ='<tr class="warning" onmouseover="return tooltipx(this);" data-toggle="tooltip" title="poin awal : '+poinAwly+'" data-placement="bottom" >';
								}else{
									tr='<tr>';
								}
								//record tabel -----------------------------------
								TB+=tr
									+'<td  width="5%">'+nox+'</label></td>'
									+'<td  width="78%"><label class="control-label">'+nakegy+'</label></td>'
									+'<td  width="5%"><label class="pull-right control-label">'+poinCury+'</label></td>'
							 	+'</tr>'
							 	//end of record tabel ----------------------------
								nox++;
								// subtotNumDt=subtotNumDt+parseFloat(poinCury);
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
								+'<td colspan="2" align="right">'
									+'<span class="pull-right">'
										+'<b>Sisa Point <i>(sebelumnya)</i></b>'
									+'</span>'
								+'</td>'
								+'<td align="right">'
									+'<span class="pull-right" >'+sisay+'</span>'
								+'</td>'
							+'</tr>'
						//record subtotal -----------------------------------------
						TB+='<tr class="info" onmouseover="return tooltipx(this);" data-toggle="tooltip" title="'+infosub+'" data-placement="bottom">'
								+'<td colspan="2" align="right">'
									+'<span class="pull-right">'
										// +'<b>Sub Total : </b><label '+clrsubpro+'>'+subtotPercDt+' ok %</label>&nbsp;'+iconsub+'</i>'
										+'<b>Sub Total : </b>'
										// +'<label '+clrsubpro+'> x %</label>&nbsp;'+iconsub+'</i>'
									+'</span>'
								+'</td>'
								+'<td '+clrsub+'>'
									+'<span class="pull-right" ><b>'+subtotNumDty+'</b></span>'
								+'</td>'
							+'</tr>'
						//end of record subtotal ------------------------------
						+'</table>'
					+'</div>';
				});//end of loop kategori -------------------------------------
			// }//end of kategori : ada --------------------------------------------
			// target(subtotNumDtArr);
				// alert(subtotNumDtArr);return false;
				target(gtotNumDty,pty,urutGoly,subtotNumDtArr);
	
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
				// gtotNumDt= gtotNumDt+
				TB+='<table class="table table-striped ">'
						+'<tr class="info" data-toggle="tooltip" title="'+info+'" data-placement="bottom">'
							+'<td width="81%"><span class="pull-right"><b>Grand Total : '+icon+'</b></span></td>'
							+'<td width="7%" '+colortot+'><span class="pull-right"><b>'+gtotNumDty.toFixed(2)+'</b></span></td>'
						+'</tr>'
					+'</table>';
				//end of grand total -------------------------------------------------------
				$('#isi').html(TB);
			}
		});
	}
//end of loadbio___________________________________________________________________________________

//target 
	function target(gtotNumDt,ptDt,urutGolDt,subtotNumDtArr){
		var subtotNumDtArr = JSON.stringify(subtotNumDtArr);
		$.ajax({
			url:dir,
			type:'get',
			data:'aksi=target&gtotNumDt='+gtotNumDt+'&ptDt='+ptDt+'&urutGolDt='+urutGolDt+'&subtotNumDtArr='+subtotNumDtArr,
			dataType:'json',
			success:function(data){
				var info='';
				// if(data.length=0){
				// 	alert('naik');
				// }else{
				// 	alert('gak naik');
				// }

				info+='<div style="padding-left:20px; padding-top:20px;" class="tabbable" id="tabs-104268">'
						+'<ul class="nav nav-tabs">';
				var i =1;
				//notif untuk kurangan persyaratan---------------------- 
				//link tab ===

				$.each(data,function(id,item){
					// alert(item.jumKrg);
					// if(i==1){
					if(i==1){
						info+='<li class="active"><a href="#panel'+i+'" data-toggle="tab" style="color:#080"><b>'+item.pangkatTgt+'</b></a></li>';
					}else{
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
				
				var x=1;

				//content tab ===
				$.each(data,function(id,item){
					var rekom='';
					// if(item.jumKrg==0){
					// 	rekom+='selamat anda direkomendasikan untuk naik ke golongan : ';
					// }

					if(x==1){
						info+='<div align="justify" class="tab-pane active" id="panel'+x+'">';
					}else{
						info+='<div align="justify" class="tab-pane" id="panel'+x+'">';
					}

					if(item.jumKrg==0){
						var sesi = $('#idsesi').val();
						var user = $('#iduser').val();
						var ruwet= encode64(sesi+user+sesi);
						// //cetak DUPAK button;
							$('#dupakBC').removeAttr('style');
							$('#dupakBC').attr("href","view/r_dupak.php?tipe=pdf&ruwet="+ruwet+"&idsesi="+sesi+"&iduser="+user);
						//cetak PAK button;
							$('#pakBC').removeAttr('style');
							$('#pakBC').attr("href","view/r_pak.php?tipe=pdf&ruwet="+ruwet+"&idsesi="+sesi+"&id="+user);

						info+='<div class="alert alert-success">Selamat, Anda Direkomendasi naik golongan <label class="label label-success">'+item.goltgt+'</label> jabatan <label class="label label-success">'+item.jabtgt+'</label>'
								+'<p>NB : Silahkan cetak berkas dan bukti kegiatan untuk validasi ke Fakultas</p>'
							+'</div>';
						
						// $('.modal-footer').html('<p align="center">Silahkan cetak berkas dan bukti kegiatan untuk validasi ke Fakultas </p>');
					}else{
						info+='<ul class="alert alert-warning">Untuk naik ke golongan <label class="label label-warning">'+item.goltgt+'</label>, jabatan <label class="label label-warning">'+item.jabtgt+'</label> silahkan lengkapi :';
						$.each(item.kurangan,function(id,item){
							info+='<li style="list-style:none;"><i class="icon-ok"></i> '+item+'</li>';						
						});
						info+='</ul>';
					}

					info+='</div>';
					$('#popMeDV').html(info);
					x++;
				});
				//end of content tab ===
				//end of notif untuk kurangan persyaratan---------------------- 
				loadInfo();
			}
		});
	}
//end of target 


//fungsi pop up info : naik pangkat atau belum_________________________________
	function loadInfo(){
		$('#popMe').modal('show');
	}
//end of pop up info___________________________________________________________ 

//fungsi tooltip_______________________________________________________________
	function tooltipx(event){
		$("[data-toggle=tooltip]").tooltip({ 
			//placement: 'right'
		});
	}
//end of fungsi tooltip________________________________________________________
	