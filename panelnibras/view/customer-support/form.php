<div class="col-lg-12 main-content">
    <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
    <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
		  <div class="widget-content nopadding">
	        <form class="form-horizontal" method="POST" name="frmdata" id="frmdata" action="<?php echo $_SERVER['PHP_SELF'] ?>">
				<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
				<input type="hidden" id="idsupport" name="idsupport" value="<?php echo $iddata ?>">
				<input type="hidden" id="cs_jsupport_lama" name="cs_jsupport_lama" value="<?php echo $cs_jsupport ?>">
				<input type="hidden" id="cs_akun_lama" name="cs_akun_lama" value="<?php echo $cs_akun ?>">
				<div id="hasil" style="display: none;"></div>
			  
				<div class="form-group">
					<label class="col-sm-2 control-label">Nama</label>
					<div class="col-sm-4">
						<input type="text" id="cs_nama" name="cs_nama" class="form-control" value="<?php echo $cs_nama ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Jenis Support</label>
					<div class="col-sm-4">
						<select id="cs_jsupport" name="cs_jsupport" class="form-control">
							<?php foreach($jenissupport as $jsupport) { ?>
							<option value="<?php echo $jsupport['idp'] ?>" <?php echo $cs_jsupport == $jsupport['idp'] ? 'selected':'' ?>><?php echo $jsupport['nmp'] ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Akun</label>
					<div class="col-sm-4">
						<input type="text" id="cs_akun" name="cs_akun" class="form-control" value="<?php echo $cs_akun ?>">
					</div>
				</div>
			 
				<div class="form-group">
					<label class="col-sm-2 control-label">Aktif</label>
					<div class="col-sm-2">
						<select id="status" name="cs_status" id="cs_status" class="form-control">
							<option value="1" <?php if($cs_status=='1') echo "selected" ?>> Ya</option>
							<option value="0" <?php if($cs_status=='0') echo "selected" ?>> Tidak </option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button id="btnsimpan" type="submit" class="btn btn-sm btn-primary">Simpan</button>
						<a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
					</div>
				</div>
				<div class="clearfix"></div>
	        </form>
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
		data: $('#frmdata').serialize(),
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
  
