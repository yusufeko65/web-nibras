<script src="<?php echo URL_PROGRAM_ADMIN_VIEW.folder."/validasi.js" ?>"></script>
<div class="col-lg-12 main-content">
     <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
     <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
		  <div class="widget-content nopadding">
	        <form class="form-horizontal" method="POST" name="frmdata" id="frmdata" target="upload-frame" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		      <iframe name="upload-frame" id="upload-frame" style="display:none"></iframe> 
			  <input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
		      <input type="hidden" id="iddata" name="iddata" value="<?php echo $iddata ?>">
		      <div id="hasil" style="display: none;"></div>
			  
			  <div class="form-group">
			     <label class="col-sm-2 control-label">Nama Bank</label>
				 <div class="col-sm-4">
				   <input type="text" id="bank" name="bank" class="form-control" value="<?php echo $bank_nama ?>" size="50">
				 </div>
		      </div>
			  <div class="form-group">
			     <label class="col-sm-2 control-label">File</label>
				 <div class="col-sm-4">
				   <input name="filelogo" type="file" id="filelogo">
				   <input type="hidden" value="<?php echo $bank_logo ?>" id="filelama" name="filelama">
				   <br>
				   <?php if($bank_logo != '') { ?>
					<img src="<?php echo URL_IMAGE.'_other/other_'.$bank_logo ?>">
					<?php } ?>
				 </div>
		      </div>
			  <div class="form-group">
			     <label class="col-sm-2 control-label">Status</label>
				 <div class="col-sm-4">
				   <select id="status" name="status" class="form-control">
						<option value="1" <?php if($bank_status=='1') echo "selected" ?>> Aktif </option>
						<option value="0" <?php if($bank_status=='0') echo "selected" ?>> Tidak Aktif </option>
					</select>
				 </div>
		      </div>
		      <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
		          <button type="submit" class="btn btn-sm btn-primary" id="btnsimpan">Simpan</button>
		          <a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
		        </div>
			  </div>
			  <div class="clearfix"></div>
	        </form>
		  </div>
	    </div> 
	 </div>
  </div>
  