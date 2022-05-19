<div class="col-lg-12 main-content">
     <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
     <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
		  <div class="widget-content nopadding">
	        <form class="form-horizontal" autocomplete="off" method="POST" name="frmdata" id="frmdata" action="<?php echo $_SERVER['PHP_SELF'] ?>">
				<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
				<input type="hidden" id="servis_id" name="servis_id" value="<?php echo isset($servis_id) ? $servis_id : 0 ?>">
				<input type="hidden" id="shipping_id" name="shipping_id" value="<?php echo isset($servis_shipping) ? $servis_shipping : 0 ?>">
				<div id="hasil" style="display: none;"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Kode Servis <br><cite>Jika ingin terintegrasi rajaongkir.com, code servis harus disamakan dengan code di <a href="https://rajaongkir.com/dokumentasi/pro#cost-request" target="_blank">rajaongkir.com</a></label>
					<div class="col-sm-2">
						<input type="text" id="servis_code" name="servis_code" class="form-control" value="<?php echo isset($servis_code) ? $servis_code : '' ?>">
						<input type="hidden" id="servis_code_lama" name="servis_code_lama" value="<?php echo isset($servis_code) ? $servis_code : '' ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Nama Servis</label>
					<div class="col-sm-4">
						<input type="text" id="servis_nama" name="servis_nama" class="form-control" value="<?php echo isset($servis_nama) ? $servis_nama : '' ?>">
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						
						<button type="submit" class="btn btn-sm btn-success">Simpan</button>
						<a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder.'?op=servis&pid='.$servis_shipping ?>'" class="btn btn-sm btn-warning">Kembali</a>
						
					</div>
				</div>
				<div class="clearfix"></div>
	        </form>
		</div>
    </div> 
</div>
 

<script src="<?php echo URL_PROGRAM_ADMIN_VIEW.folder."/validasiservis.js" ?>"></script>