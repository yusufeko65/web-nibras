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
					<input type="hidden" id="tab" value="<?php echo isset($_GET['tb']) ? $_GET['tb'].'-tab' : 'tblogin-tab' ?>">
					<div id="hasil" style="display: none;"></div>
					<div class="form-group">
						<div class="col-sm-12 text-right">
							<a onclick="simpandata()" id="btnsimpan" class="btn btn-sm btn-primary">Simpan</a>
							<a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
						</div>
					</div>
					
					<div role="tabpanel">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" id="tabmenucust" role="tablist">
							<li role="presentation" class="active"><a id="tblogin-tab" href="#datalogin" aria-controls="datalogin" role="tab" data-toggle="tab">Data Login</a></li>
							<li role="presentation"><a id="tbprofil-tab" href="#dataprofil" aria-controls="dataprofil" role="tab" data-toggle="tab">Biodata</a></li>
							<?php if($modul == 'ubah') { ?>
							<li role="presentation"><a id="tbalamat-tab" href="#dataalamat" aria-controls="dataalamat" role="tab" data-toggle="tab">Daftar Alamat</a></li>
							<?php } ?>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="datalogin">
								<div class="well">
									<div class="form-group">
										<label class="col-sm-2 control-label">Email</label>
										<div class="col-sm-4">
											<input type="email" name="remail" id="remail" value="<?php echo $reseller['cust_email'] ?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Password</label>
										<div class="col-sm-4">
											<input type="password" id="rpass" name="rpass" class="form-control" value="<?php echo $dtFungsi->fDekrip($reseller['cust_pass']) ?>" size="40">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Ulangi Password</label>
										<div class="col-sm-4">
											<input type="password" id="rrepass" name="rrepass" class="form-control" value="<?php echo $dtFungsi->fDekrip($reseller['cust_pass']) ?>" size="40">
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
										<label class="col-sm-2 control-label">Biaya Packing</label>
										<div class="col-sm-4">
											<select id="biaya_packing" name="biaya_packing" class="form-control">
												<option value="1" <?php echo $reseller["cust_tanpa_biaya_packing"]=='1' ? 'selected':''?>>Tidak</option>
												<option value="0" <?php echo $reseller["cust_tanpa_biaya_packing"]=='0' ? 'selected':''?>>Iya</option>
											</select>
										</div>
									</div>

								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="dataprofil">
								<div class="well">
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
									
					  
									<div class="form-group" id="depositoku">
										<label class="col-sm-2 control-label">Uang Deposito</label>
										<div class="col-sm-4">
											<input type="number" id="rdeposit" name="rdeposit" class="form-control">
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label">No. Telp</label>
										<div class="col-sm-4">
											<input type="text" id="rtelp" name="rtelp" class="form-control" value="<?php echo $reseller['cust_telp'] ?>" size="40">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Alamat</label>
										<div class="col-sm-4">
											<textarea id="ralamat" name="ralamat" class="form-control"><?php echo $reseller['cust_alamat'] ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Propinsi</label>
										<div class="col-sm-4">
											<select id="rpropinsi" name="rpropinsi" class="form-control">
												<option value="0">- Propinsi -</option>
												<?php foreach($dataprop as $prop) { ?>
												<option value="<?php echo $prop['idp'] ?>" <?php if($reseller['cust_propinsi'] == $prop['idp']) echo "selected" ?>><?php echo $prop['nmp'] ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Kotamadya/Kabupaten</label>
										<div class="col-sm-4">
											<select id="rkabupaten" name="rkabupaten" class="form-control">
												<option value="0">- Kotamadya/Kabupaten -</option>
												<?php if($reseller['cust_propinsi'] != '') { ?>
												<?php $datakabupaten = $dtKabupaten->getKabupatenByPropinsi($reseller['cust_propinsi']) ?>
												<?php foreach($datakabupaten as $kab) { ?>
												<option value="<?php echo $kab['idk'] ?>" <?php if($kab['idk'] == $reseller['cust_kota']) echo "selected" ?>><?php echo $kab['nmk'] ?></option>
												<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Kecamatan</label>
										<div class="col-sm-4">
											<select id="rkecamatan" name="rkecamatan" class="form-control">
												<option value="0">- Kecamatan -</option>
												<?php if($reseller['cust_kota'] != '') { ?>
												<?php $datakecamatan = $dtKecamatan->dataKecamatanByKabupaten($reseller['cust_kota']) ?>
												<?php foreach($datakecamatan as $kec) { ?>
												<option value="<?php echo $kec['idn'] ?>" <?php if($kec['idn'] == $reseller['cust_kecamatan']) echo "selected" ?>><?php echo $kec['nmn'] ?></option>
												<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Kelurahan</label>
										<div class="col-sm-4">
											<input type="text" id="rkelurahan" name="rkelurahan" class="form-control" value="<?php echo $reseller['cust_kelurahan'] ?>" size="40">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Kode Pos</label>
										<div class="col-sm-4">
											<input type="text" id="rkodepos" name="rkodepos" class="form-control" value="<?php echo $reseller['cust_kdpos'] ?>" size="40">
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="dataalamat">
								<div class="well">
									<div class="form-group">
										<div class="col-md-12">
											<button type="button" data-loading-text="Tunggu sebentar ......" id="btnaddalamat" class="btn btn-sm btn-success" onclick="formAddAlamat('<?php echo $iddata ?>')">Tambah Alamat Baru</button>
										</div>
									</div>
									
									<div class="table-responsive">
									
										<table class="table table-bordered table-condensed">
											<thead>
												<tr>
													<th width="30%">Nama</th>
													<th width="28%">Alamat</th>
													<th width="20%">Wilayah</th>
													<th width="7%">Status</th>
													<th width="15%"></th>
												</tr>
											</thead>
											<tbody>
											<?php $row = 0 ?>
											<?php if($dataalamat) { ?>
												<?php foreach($dataalamat as $alamat) { ?>
													<tr>
														<td><b><?php echo $alamat['ca_nama'] ?></b><br><?php echo $alamat['ca_hp'] ?></td>
														<td>
															<?php echo $alamat['ca_alamat'] ?>
														</td>
														<td>
															<?php echo $alamat['provinsi_nama'] ?>, <?php echo $alamat['kabupaten_nama'] ?>,
															<?php echo $alamat['kecamatan_nama'] ?>, <?php echo $alamat['ca_kelurahan'] ?> <?php echo $alamat['ca_kodepos'] ?>
														</td>
														<td class="text-center"><?php echo $alamat['ca_default'] == '0' ? '' : '<i class="icon-home"></i>' ?></td>
														<td class="text-center">
															<button type="button" class="btn btn-sm btn-default" onclick="ubahAlamat('<?php echo $alamat['ca_id']?>','<?php echo $iddata?>','<?php echo $row ?>')" id="btnubahalamat<?php echo $row ?>" >Ubah</button>
															<button type="button" class="btn btn-sm btn-default" onclick="hapusAlamat('<?php echo $alamat['ca_id']?>','<?php echo $iddata?>','<?php echo $row ?>')" id="btnhapusalamat<?php echo $row ?>">Hapus</button>
														</td>
													</tr>
													<?php $row++ ?>
												<?php } ?>
											<?php } ?>
											</tbody>
										</table>
									</div>
									<div class="clearfix"></div>
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
var action = $('#frmdata').attr('action');
var hash = window.location.hash;
$(function(){
   $('#remail').focus();
	
	$('#rnegara').change(function() {
      
		$('#rpropinsi').load('<?php echo URL_PROGRAM_ADMIN.folder;?>/?load=propinsi&negara=' + this.value);
		return false;
	});
	$('#rpropinsi').change(function() {
		$('#rkabupaten').load('<?php echo URL_PROGRAM_ADMIN.folder;?>/?load=kabupaten&propinsi=' + this.value);
		$('#rkecamatan').html('<option value="0">- Kecamatan -</option>');
		return false;
	});
	$('#rkabupaten').change(function() {
    	$('#rkecamatan').load('<?php echo URL_PROGRAM_ADMIN.folder;?>/?load=kecamatan&kabupaten=' + this.value);
		return false;
	});
	$("#depositoku").hide();
	if($("#aksi").val()!="ubah") {
		$('#rtipecust').change(function() {
			var deposit =$(this).find('option:selected').attr('rel');
			if(deposit == '1') {
				$("#depositoku").show();
			} else {
				$("#depositoku").hide();
			}
		});
	}
	var idhash = $('#tab').val();
	
	$('#tabmenucust #'+idhash).tab('show');
	
});


function kosongform(){
	$('#remail').focus();
	$('.form-control').each(function () {
		$(this).val("");
	});
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
						$('#frmdata')[0].reset();
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
function formAddAlamat(idcust){
	$('#btnaddalamat').button("loading");
	var url = action+'?load=frmAddAlamat';
	var modul = 'input';
	tampilform(url,'0',idcust,modul,'btnaddalamat');
	
}
function ubahAlamat(id,idcust,row) {
	$('#btnubahalamat'+row).button("loading");
	var url = action+'?load=frmEditAlamat';
	var modul = 'update';
	tampilform(url,id,idcust,modul,'btnubahalamat'+row);
}
function hapusAlamat(id,idcust,row) {
	$('#btnhapusalamat'+row).button("loading");
	var dataalamat = "id="+id+"&idcust="+idcust+"&aksi=hapusalamat";
	$.ajax({
		type: "POST",
		url: '<?php echo URL_PROGRAM_ADMIN.folder?>',
		data: dataalamat,
		dataType: 'json',
		success: function(json){
		
			
			if(json['status'] == 'error') {
					
				$('#hasil').addClass('alert alert-danger');
				$('#btnsimpan').button("reset");
				
			} else {
				
				$('#hasil').addClass('alert alert-success');
				location.href="<?php echo URL_PROGRAM_ADMIN.folder.'/?op=edit&pid=' ?>"+json['idcust']+'&tb=tbalamat';
			}
			$('#hasil').show();
			$('#hasil').html(json['result']);
			$('html, body').animate({ scrollTop: 0 }, 'slow');
			$('#btnsimpanalamat').button('reset');
		},  
		error: function(e){  
			alert('Error: ' + e);  
		}  
	});
}
function tampilform(url,id,idcust,modul,idbutton){
	$.post(url,  { stsload: "load",id:id,idcust:idcust, modul:modul } ,function(data) {
		
		$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function () {
			$(this).remove();
			$('#'+idbutton).button("reset");
		});
	});
}
</script>

