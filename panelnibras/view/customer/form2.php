<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	 
    <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
			<div class="widget-content nopadding">
				<form class="form-horizontal" method="POST" name="frmdata" id="frmdata" onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
					<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
					<input type="hidden" id="iddata" name="iddata" value="<?php echo $iddata ?>">
					<input type="hidden" name="remaillama" id="remaillama" value="<?php echo trim($reseller['cust_email']) ?> ">
					<input type="hidden" name="rtipecustlama" id="rtipecustlama" value="<?php echo $reseller['cust_grup_id'] ?>" >
					<input type="hidden" name="rapprovelama" id="rapprovelama" value="<?php echo $reseller['cust_approve'] ?>">
					<input type="hidden" name="rchkdepositlama" id="rchkrepositlama" value="<?php echo isset($reseller['cg_deposito']) ? $reseller['cg_deposito'] : 0 ?>">
					<div id="hasil" style="display: none;"></div>
			  
					<div role="tabpanel">
					
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#dataprofil" aria-controls="dataprofil" role="tab" data-toggle="tab">Profil</a></li>
							<?php if($modul == 'ubah') { ?>
							<li role="presentation"><a href="#dataalamat" aria-controls="dataalamat" role="tab" data-toggle="tab">Daftar Alamat</a></li>
							<?php } ?>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="dataprofil">
								<div class="well">
									
									<div class="form-group">
										<label class="col-sm-2 control-label">Email</label>
										<div class="col-sm-4">
											<input type="email" name="remail" id="remail" value="<?php echo $reseller['cust_email'] ?>" class="form-control" placeholder="Email">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Password</label>
										<div class="col-sm-4">
											<input type="password" id="rpass" name="rpass" class="form-control" placeholder="Password">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Ulangi Password</label>
										<div class="col-sm-4">
											<input type="password" id="rrepass" name="rrepass" class="form-control" placeholder="Re Password">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Nama</label>
										<div class="col-sm-4">
											<input type="text" id="rnama" name="rnama" class="form-control" value="<?php echo $reseller['cust_nama'] ?>" size="40">
										</div>
									</div>
									<div class="form-group" >
										<label class="col-sm-2 control-label">Grup</label>
										<div class="col-sm-4">
											<select id="rtipecust" name="rtipecust" class="form-control">
												<option value="0">- Grup Pelanggan -</option>
												<?php foreach($datagrup as $grup) { ?>
												<option rel="<?php echo $grup['dp'] ?>" value="<?php echo $grup['id'] ?>" <?php if($grup['id'] == $reseller['cust_grup_id']) echo "selected" ?>><?php echo $grup['nm'] ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label">Approval</label>
										<div class="col-sm-4">
											<select id="rapprove" name="rapprove" class="form-control">
												<option value="1" <?php echo $reseller['cust_approve']=='1' ? 'selected':''?>>Ya</option>
												<option value="0" <?php echo $reseller['cust_approve']=='0' ? 'selected':''?>>Tidak</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Status</label>
										<div class="col-sm-4">
											<select id="rstatus" name="rstatus" class="form-control">
												<option value="1" <?php echo $reseller['cust_status']=='1' ? 'selected':''?>>Aktif</option>
												<option value="0" <?php echo $reseller['cust_status']=='0' ? 'selected':''?>>Tidak</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<a onclick="simpandata()" id="btnsimpan" class="btn btn-sm btn-primary">Simpan</a>
											<a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="dataalamat">
								<div class="well">
									<button type="button" class="btn btn-sm btn-success">Tambah Alamat Baru</button>
									<table class="table">
										<thead>
											<tr>
												<th>Nama</th>
												<th>Alamat</th>
												<th>Wilayah</th>
												<th>Status</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										<?php if($dataalamat) { ?>
											<?php foreach($dataalamat as $alamat) { ?>
												<tr>
													<td><?php echo $alamat['ca_nama'] ?><br><?php echo $alamat['ca_hp'] ?></td>
													<td>
														<?php echo $alamat['ca_nama_alamat'] ?><br>
														<?php echo $alamat['ca_alamat'] ?>
													</td>
													<td>
														<?php echo $alamat['provinsi_nama'] ?>, <?php echo $alamat['kabupaten_nama'] ?>,
														<?php echo $alamat['kecamatan_nama'] ?>, <?php echo $alamat['ca_kelurahan'] ?> <?php echo $alamat['ca_kodepos'] ?>
													</td>
													<td><?php echo $alamat['ca_default'] == '0' ? '' : '<i class="fa fa-check" aria-hidden="true"></i>' ?></td>
													<td>
														<button type="button" class="btn btn-sm btn-white" onclick="ubahAlamat('<?php echo $alamat['ca_id']?>')">Ubah</button>
														<button type="button" class="btn btn-sm btn-white" onclick="hapusAlamat('<?php echo $alamat['ca_id']?>')">Hapus</button>
													</td>
												</tr>
											<?php } ?>
										<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
                   
						</div>

					</div> <!-- tabpanel -->
			  
					
				</form>
			</div>
	    </div> 
	</div>
</div>

<script>
$(function(){
	$('#rnama').focus();
	
});

var action = $('#frmdata').attr('action');

function kosongform(){
	
	$('#rnama').focus();
	$('#frmdata')[0].reset();
	
}
function disableEnterKey(e){ //Disable Tekan Enter
    var key;
	if(window.event)
		key = window.event.keyCode;     //IE
	else
		key = e.which;     //firefox

	if(key == 13){ // Jika ditekan tombol enter
		simpandata(); // Panggil fungsi simpandata()
		return false;
	} else {
		return true;
	}
}

function simpandata()
{
	var rv = true;
	$('#btnsimpan').button("loading");
	$('#hasil').removeClass();
	
	if(rv){
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
					$('#btnsimpan').button("reset");
					
				} else {
					
					$('#hasil').addClass('alert alert-success');
					
					if($('#aksi').val() == 'tambah') {
						//$('#frmdata')[0].reset();
						location.href='<?php echo URL_PROGRAM_ADMIN ?>customer/?op=edit&pid='+json['idcust'];
					} 
				}
				$('#hasil').show();
				$('#hasil').html(json['result']);
				$('html, body').animate({ scrollTop: 0 }, 'slow');
				$('#btnsimpan').button("reset");
			}
		});
	} else {
		$('html, body').animate({ scrollTop: 0 }, 'slow');
	}
	return rv;
}
</script>

