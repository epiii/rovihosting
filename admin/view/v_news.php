  <style>
	#loadarea{
		height:45px;
	}#pagination{
		color:white;list-style:none;
	}.vwimg{
		height:80px;
		opacity:0.8;
	}.vwimg:hover{
		opacity:1;
	}.error-info{
		/*padding: .2em .6em .3em;*/
		padding: .4em;
		font-size: 75%;
		font-weight: bold;
		line-height: 1;
		color: #ffffff;
		border-radius: .25em;
		background-color:red;
  	}.trtable:hover{
		background-color:#3FC;
	}
	</style>

<script src="client/s_news.js"></script>
<h3><div id="loadarea"><i class="icon-th-list"></i> INFORMASI</div></h3>
 
<div>
    <button id="viewBC" style="display:none;"class="btn btn-primary"><i class='icon-list'></i> Lihat Semua</button>
    <!-- <button id="addBC" class="btn btn-primary"><i class='icon-plus-sign'></i> Tambah</button> -->
</div>
<!--panel 1-->
<div class="span8"id="i_kegPN" style="display:none;">
    <div class="span8">
        <form autocomplete="off" method="post" enctype="multipart/form-data" name="form-daftar" class="form-horizontal" >
        <input type="hidden" id="idformTB" name="idformTB"/>
            <div class="control-group">
                <label class="control-label">Kategori :</label>
                <div class="controls">
                	<select name="kategoriTB" id="kategoriTB" required >
						<option value=''>pilih kategori..</option>
						<option value='berita'>Berita</option>
						<option value='download'>Unduhan</option>
					</select>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Judul</label>
                <div class="controls">
					<input type="text" name="tittleTB" id="tittleTB" placeholder="judul" required >
				</div><span id="tittleInfo"></span>
			</div>
			
            <div class="control-group">
                <label class="control-label">Deskripsi</label>
                <div class="controls" >
					<textarea id="deskripsiTB" placeholder="deskripsi" name="deskripsiTB" ></textarea>
				</div>
			</div>
			
            <div class="control-group">
                <label class="control-label">File / Gambar</label>
                <div class="controls" >
					<input required  type="file" id="fileTB" name="fileTB" >
				</div>
			</div>
			
        	<button  id="simpanBC"class="btn btn-primary" >Simpan</button>
			<div >.</div>
			<div >.</div>
		</form>
    </div>
</div>
<!--end of panel 1-->

<!--panel 2-->
<div class="span8" id="v_kegPN">
	<divX id="loadtabel"></divX>

    <!--tab menu--> 
        <div style="padding-left:20px; padding-top:20px;" class="tabbable" id="tabs-104268">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#panel1" data-toggle="tab" onclick="return loadData('tampil','berita','','');" style="color:#080"><b>Berita</b></a>
                </li>
                <li >
                    <a href="#panel2" data-toggle="tab" onclick="return loadData('tampil','download','','');" style="color:#080"><b>Unduhan</b></a>
                </li>
                <li class="pull-right">
                    <button id="addBC" class="btn btn-primary"><i class='icon-plus-sign'></i> Tambah</button>
                </li>		
            </ul>
        </div>
    <!--end of tab menu-->
	
	<!--table (header)--> 
        <table class="table table-hover table-striped table-bordered" width="100%" border="0">
            <tr class="info">
                <td><b>no.</b></td>
                <td><b>Judul</b></td>
                <td><b>Deskripsi</b></td>
                <td><b>Tanggal</b></td>
                <td colspan="2"><b>Aksi</b>
                </td>
            </tr>
            
            <tbody id="isi">
            
            </tbody>
        </table>
	<!--end of table (header)--> 
    
    <!--table (content)--> 
    	<div class="row" id="isi"></div>
    <!--end of table (content)--> 
</div>
<!--end of panel2-->