<div class="col-lg-12 main-content">
    <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
    <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
			<div class="widget-content nopadding">
				<form class="form-horizontal" method="POST" name="frmdata" id="frmdata" onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
					<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
					<input type="hidden" id="iddata" name="iddata" value="<?php echo $iddata ?>">
					<div id="hasil" style="display: none;"></div>
			  
					<div class="form-group">
						<label class="col-sm-2 control-label">Nama</label>
						<div class="col-sm-4">
							<input type="text" id="nama" name="nama" class="form-control frm" value="<?php echo isset($datauser['login_nama']) ? $datauser['login_nama']:''?>" size="40">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Username</label>
						<div class="col-sm-4">
							<input type="text" id="user" name="user" class="form-control frm" value="<?php echo isset($datauser['login_username']) ? $datauser['login_username']:''?>" size="40" <?php echo $lock ?>>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-4">
							<input type="password" id="pass" name="pass" class="form-control frm" value="<?php echo isset($datauser['login_pwd']) ? $dtFungsi->fDekrip($datauser['login_pwd']):''?>" size="35">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Grup User</label>
						<div class="col-sm-4">
							<select id="grup" name="grup" class="form-control">
								 <option value="0">- Grup User -</option>
								<?php foreach($datagrup as $grup) {?>
								 <option value="<?php echo $grup['lg_id'] ?>" <?php if(isset($datauser['lg_id']) && $datauser['lg_id']== $grup['lg_id']) echo "selected" ?>><?php echo $grup['lg_nm'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-4">
						   <select id="status" name="status" class="form-control frm">
						   <?php $datauser['login_status'] = isset($datauser['login_status']) ? $datauser['login_status']:''?>
							  <option value="1" <?php echo $datauser['login_status']=='1' ? 'selected':'' ?>>Enabled</option>
							  <option value="0" <?php echo $datauser['login_status']=='0' ? 'selected':'' ?>>Disabled</option>
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
	var status = 'tutup';
	$("#checkall").click(function(){
		if(status=='tutup') {
			$('.chk').each(function () {
				$(this).prop('checked', true);
			});
			status='buka';
		} else {
			$('.chk').each(function () {
            	$(this).prop('checked', false);
			});
			status='tutup';
		}
		return false;
	});
	
	$("#frmdata").submit(function(event){
		event.preventDefault();
		simpandata();
	});
	
	
});
function simpandata(){
	var action = $('#frmdata').prop("action");
	var datasimpan = $('#frmdata').serialize();
	var rv = true;
	$('#btnsimpan').button("loading");
	$('#hasil').removeClass();
	
	//alert(datasimpan);
	
	$.ajax({
		url: action,
		method: "POST",
		data: datasimpan,
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