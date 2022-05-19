<div class="col-lg-12 main-content">
     <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
     <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
		  <div class="widget-content nopadding">
	        <form class="form-horizontal" method="POST" name="frmdata" id="frmdata" onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		      <input type="hidden" id="aksi" value="<?php echo $modul ?>">
		      <input type="hidden" id="iddata" value="<?php echo $iddata ?>">
			  <input type="hidden" id="warnalama" class="inputbox" value="<?php echo $warna_nama ?>" size="50">
		      <div id="hasil" style="display: none;"></div>
			  
			  <div class="form-group">
			     <label class="col-sm-2 control-label">Warna</label>
				 <div class="col-sm-4">
				   <input type="text" id="warna" class="form-control" value="<?php echo $warna_nama ?>" size="40">
				 </div>
		      </div>
			  
			  <div class="form-group">
			     <label class="col-sm-2 control-label">Alias</label>
				 <div class="col-sm-4">
				   <input type="text" id="alias" class="form-control" value="<?php echo $warna_alias ?>" size="40">
				 </div>
		      </div>
			  
			  
		      <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
		          <a onclick="simpandata()" class="btn btn-sm btn-primary">Simpan</a>
		          <a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
		        </div>
			  </div>
			  <div class="clearfix"></div>
	        </form>
		  </div>
	    </div> 
	 </div>
  </div>

<script src="<?php echo URL_PROGRAM_ADMIN_VIEW.folder."/validasi.js" ?>"></script>