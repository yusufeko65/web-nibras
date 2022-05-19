<div class="col-lg-12 main-content">
    <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
    <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
			<div class="widget-content nopadding">
				<form class="form-horizontal" method="POST" name="frmdata" id="frmdata" target="upload-frame" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		     
					<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
					<input type="hidden" id="id_slide" name="id_slide" value="<?php echo $iddata ?>">
					<input type="hidden" id="nama_slide_lama" name="nama_slide_lama" value="<?php echo $slide_nama ?>">
					<div id="hasil" style="display: none;"></div>
			  
					<div class="form-group">
						<label class="col-sm-2 control-label">Nama Slide</label>
						<div class="col-sm-4">
							<input type="text" id="nama_slide" name="nama_slide" class="form-control" value="<?php echo $slide_nama ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">File Gambar</label>
						<div class="col-sm-4">
							<input name="gbr_slide" type="file" id="gbr_slide">
							<input type="hidden" value="<?php echo $slide_gbr ?>" id="filelama" name="filelama">
							<br>
							<?php if($slide_gbr != '') { ?>
							<img src="<?php echo URL_IMAGE.'_other/other_'.$slide_gbr ?>" style="max-width:30%">
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Ukuran Slide</label>
						<div class="col-sm-2">
							<label>P</label> <input type="text" id="panjang" name="panjang" class="form-control" value="<?php echo $panjang ?>"> 
						</div>
						<div class="col-sm-2">
							<label>x L</label> <input type="text" id="lebar" name="lebar" class="form-control" value="<?php echo $lebar ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Link Slide</label>
						<div class="col-sm-4">
							<input type="text" id="link_slide" name="link_slide" class="form-control" value="<?php echo $url_link ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Urutan</label>
						<div class="col-sm-4">
							<input type="text" id="urutan" name="urutan" class="form-control" value="<?php echo $urutan ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-4">
							<select id="sts_slide" name="sts_slide" class="form-control">
								<option value="1" <?php if($slide_status=='1') echo "selected" ?>> Aktif </option>
								<option value="0" <?php if($slide_status=='0') echo "selected" ?>> Tidak Aktif </option>
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
<script type="text/javascript">
$(function(){
	$("#frmdata").submit(function(event){
		event.preventDefault();
		simpandata();
	});
});

function simpandata(){
	var action = $('#frmdata').prop("action");
	var rv = true;
	$('#btnsimpan').button("loading");
	$('#hasil').removeClass();
	
	$.ajax({
		url: action,
		method: "POST",
		data: new FormData(frmdata),
		processData:false,
		contentType:false,
		dataType: 'json',
		success: function(json){
			
			if(json['status'] == 'error') {
				
				$('#hasil').addClass('alert alert-danger');
				$('#btnsimpan').button("loading");
				
			} else {
				
				$('#hasil').addClass('alert alert-success');
				
				if($('#aksi').val() == 'tambah') {
					$('#frmdata')[0].reset();
				}
			}
			$('#hasil').show();
			$('#hasil').html(json['result']);
			$('html, body').animate({ scrollTop: 0 }, 'slow');
			$('#btnsimpan').button("reset");
		}
	});
	return rv;
}
</script>