  <div class="col-lg-12 main-content">
     <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
     <div class="widget-box col-md-offset-4" style="width:500px">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
		  <div class="widget-content nopadding">
	        <form method="POST" name="frmdata" id="frmdata" onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		      <input type="hidden" id="aksi" value="<?php echo $modul ?>">
		      <input type="hidden" id="iddata" value="<?php echo $iddata ?>">
		      <div id="hasil" style="display: none;"></div> 
		      <div class="form-group">
			     <label>Negara</label>
			     <input type="text" id="nama" class="form-control" value="<?php echo $negara_nama ?>" placeholder="Negara" size="50">
		      </div>
		      <a onclick="simpandata()" class="btn btn-sm btn-primary">Simpan</a>
		      <a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
		  
	        </form>
		  </div>
	    </div> 
	 </div>
	 	  
  </div>
	  
<script src="<?php echo URL_PROGRAM_ADMIN_VIEW.folder."/validasi.js" ?>"></script>