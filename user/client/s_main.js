var dir='server/p_main.php';  //  direktori file server 

//fungsi yg di-run SETELAH (v_main.php  & element2 nya) selesai di-load 
$(document).ready(function(){
	loadData('tampil','berita','',''); 
});//end of fungsi yg di-run SETELAH (v_main.php + element2 nya) selesai di-load 

//fungsi untuk me-load tabel dr database (p_main.php)
function loadData(aksix,menux,idx){
	$('#isi').html('<img src="../img/loader.gif"><br>loading..').fadeIn();
	var datax = 'aksi='+aksix+'&menu='+menux+'&id='+idx;
	$.ajax({
		url	: dir,
		type: "GET",
		data: datax,
		success:function(data){
			$('#isi').hide().html(data).fadeIn(1000); //element yg ber ID :'isi' di v_main.php menampung tabel hasil dari p_main.php 
		}
	});
}//end of fungsi untuk me-load tabel dr database (p_main.php)
	
//fungsi paging (halaman) 
function pagination(page,aksix,menux){
	var datax = 'starting='+page+'&aksi='+aksix+'&menu='+menux;
	$('#isi').html('<img src="../img/loader.gif">').fadeIn();
	$.ajax({
		url:dir,
		type:"GET",
		data: datax,
		success:function(data){
			$('#isi').hide().html(data).fadeIn(1000);
		}
	});
}//end of fungsi paging (halaman) 