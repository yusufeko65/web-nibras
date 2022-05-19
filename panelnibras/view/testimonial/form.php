<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	 
    <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
			<div class="widget-content nopadding">
				<form class="form-horizontal" method="POST" name="frmdata" id="frmdata" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>">
					<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
					<input type="hidden" id="testimid" name="testimid" value="<?php echo $iddata ?>">
					<div id="hasil" style="display: none;"></div>
			  
					<div class="form-group">
						<label class="col-sm-2 control-label">Nama</label>
						<div class="col-sm-10">
							<input type="text" id="testim_nama" name="testim_nama" class="form-control" value="<?php echo $nama ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10">
							<input type="email" id="testim_email" name="testim_email" class="form-control" value="<?php echo $email ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">URL</label>
						<div class="col-sm-10">
							<input type="text" id="testim_url" name="testim_url" class="form-control" value="<?php echo $urlweb ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Keterangan</label>
						<div class="col-sm-10">
							<textarea id="testim_komen" name="testim_komen" row="3" class="form-control"><?php echo $komentar ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-4">
							<select id="testim_status" name="testim_status" class="form-control">
								<option value="1" <?php if($status=='1') echo "selected" ?>> Ya</option>
								<option value="0" <?php if($status=='0') echo "selected" ?>> Tidak </option>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Approve</label>
						<div class="col-sm-4">
							<select id="testim_approve" name="testim_approve" class="form-control">
								<option value="1" <?php echo $approve == '1' ? 'selected' : '' ?>> Ya</option>
								<option value="0" <?php echo $approve == '0' ? 'selected' : '' ?>> Tidak </option>
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