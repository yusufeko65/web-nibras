<div class="col-lg-12 main-content">
     <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
     <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
		  <div class="widget-content nopadding">
	        <form class="form-horizontal" method="POST" name="frmdata" id="frmdata" onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		      <input type="hidden" id="aksi" value="<?php echo $modul ?>">
		      <input type="hidden" id="iddata" value="<?php echo $iddata ?>">
		      <div id="hasil" style="display: none;"></div>
			  
			  <div class="form-group">
			     <label class="col-sm-2 control-label">No. Rekening</label>
				 <div class="col-sm-4">
				   <input type="text" id="norek" class="form-control" value="<?php echo $rekening_no ?>" size="40">
				 </div>
		      </div>
			  <div class="form-group">
			     <label class="col-sm-2 control-label">Cabang</label>
				 <div class="col-sm-4">
				   <input type="text" id="cabang" class="form-control" value="<?php echo $rekening_cabang ?>" size="40">
				 </div>
		      </div>
			  <div class="form-group">
			     <label class="col-sm-2 control-label">Atas Nama</label>
				 <div class="col-sm-4">
				   <input type="text" id="atasnama" class="form-control" value="<?php echo $rekening_atasnama ?>" size="40">
				 </div>
		      </div>
			  <div class="form-group">
			     <label class="col-sm-2 control-label">Bank</label>
				 <div class="col-sm-4">
				   <select id="bank" class="form-control">
				     <option value="0">- Pilih Bank -</option>
				   <?php foreach($databank as $bank) {?>
				     <option value="<?php echo $bank['ids'] ?>" <?php if($bank['ids'] == $bank_id) echo "selected" ?>><?php echo $bank['nms'] ?></option>
				   <?php } ?>
				   </select>
				 </div>
		      </div>
			  
			  <div class="form-group">
			     <label class="col-sm-2 control-label">Status</label>
				 <div class="col-sm-4">
				   <select id="status" class="form-control">
				     <option value="1" <?php if($rekening_status == '1') echo "selected" ?>>Aktif</option>
					 <option value="0" <?php if($rekening_status == '0') echo "selected" ?>>Tidak Aktif</option>
				   </select>
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