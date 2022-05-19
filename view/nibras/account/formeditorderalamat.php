<form method="POST" name="frmeditlamat" id="frmeditlamat" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" id="aksi" name="aksi" value="simpanalamat">
	<input type="hidden" id="pesanan_no" name="pesanan_no" value="<?php echo $formalamat['order']['pesanan_no'] ?>">
	<input type="hidden" id="urlorder" value="<?php echo URL_PROGRAM.'orderdetail/?order='.$formalamat['order']['pesanan_no']?>">
	<input type="hidden" name="jenis_alamat" id="jenis_alamat" value="<?php echo $formalamat['jenisalamat'] ?>">
	<input type="hidden" name="totberat" id="totberat" value="<?php echo $formalamat['totberat'] ?>">
	
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title text-center">Edit <?php echo $formalamat['caption_form'] ?></h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				<div id="hasileditstatus" style="display:none"></div>
				<div role="tabpanel">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" id="tabaccount">
						<li class="nav-item">
							<a class="nav-link active" href="#formalamat" role="tab" data-toggle="tab" id="formalamat-tab" aria-controls="formalamat" aria-selected="true">Form Alamat</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="alamat-tab" data-toggle="tab" href="#dataalamat" role="tab" aria-controls="dataalamat" aria-selected="false">Daftar Alamat</a>
						</li>
					</ul>
					
				
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="formalamat">
							
							<div id="hasiladdress"></div>
							<div class="form-row align-items-center">
								<div class="form-group col-md-6">
									<label for="add_nama" class="col-form-label col-form-label-sm">Nama</label>
									<input type="text" id="add_nama" name="add_nama" class="form-control "  placeholder="Nama">
									<div class="invalid-feedback">
										Masukkan Nama
									</div>
								</div>
								<div class="form-group col-md-6">
									<label for="add_telp" class="col-form-label col-form-label-sm">Nomor Hp</label>
									<input type="text" id="add_telp" name="add_telp" class="form-control " placeholder="Nomor Hp">
									<div class="invalid-feedback">
										Masukkan Nomor Hp
									</div>
								</div>
								<div class="form-group col-md-12">
									<label for="add_alamat" class="col-form-label col-form-label-sm">Alamat</label>
									<textarea id="add_alamat" name="add_alamat" class="form-control "></textarea>
									<div class="invalid-feedback">
										Masukkan Alamat
									</div>
								</div>
								
								<div class="form-group col-md-4">
									<label for="add_propinsi" class="col-form-label col-form-label-sm">Propinsi</label>
									<select id="add_propinsi" name="add_propinsi" class="form-control custom-select" onchange="getKabupaten(this.value)">
										<option value="0">- Propinsi -</option>
									<?php if($formalamat['dataprop']) { ?>
										<?php foreach($formalamat['dataprop'] as $prop) { ?>
										<option value="<?php echo $prop['idp'] ?>"><?php echo $prop['nmp'] ?></option>
										<?php } ?>
									<?php } ?>
									</select>
									<div class="invalid-feedback">
										Masukkan Propinsi
									</div>
								</div>
								<div class="form-group col-md-4">
									<label for="add_kabupaten" class="col-form-label col-form-label-sm">Kota/Kabupaten</label>
									<select id="add_kabupaten" name="add_kabupaten" class="form-control custom-select" onchange="getKecamatan(this.value)">
										<option value="0">- Kota/Kabupaten -</option>
										
									<select>
									<div class="invalid-feedback">
										Masukkan Kabupaten
									</div>
								</div>
								<div class="form-group col-md-4">
									<label for="add_kecamatan" class="col-form-label col-form-label-sm">Kecamatan</label>
									<select id="add_kecamatan" name="add_kecamatan" class="form-control custom-select">
										<option value="0">- Kecamatan -</option>
										
									<select>
									<div class="invalid-feedback">
										Masukkan Kecamatan
									</div>
								</div>
								<div class="form-group col-md-6">
									<label for="add_kelurahan" class="col-form-label col-form-label-sm">Kelurahan</label>
									<input type="text" id="add_kelurahan" name="add_kelurahan" class="form-control " placeholder="Kelurahan">
								</div>
								<div class="form-group col-md-6">
									<label for="add_kodepos" class="col-form-label col-form-label-sm">Kode Pos</label>
									<input type="text" id="add_kodepos" name="add_kodepos" class="form-control " placeholder="Kode Pos">
								</div>
								
								<div class="form-group col-md-6">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" name="chkdefault" id="chkdefault" value="1">
										<label class="custom-control-label" for="chkdefault">Jadikan Alamat Utama ?</label>
									</div>
								</div>
								
							</div>
							
							<div class="clearfix"></div>
						</div>
						<div role="tabpanel" class="tab-pane" id="dataalamat">
							<h4>Data Alamat</h4>
							<div class="data-alamat">
							   
							<?php if($formalamat['alamatcustomer']) {?>
								<?php $i = 0 ?>
								<?php foreach($formalamat['alamatcustomer'] as $alamat) {?>
								<div class="well wel-sm">
									<b><?php echo $alamat['ca_nama'] ?></b><br>
									<?php echo $alamat['ca_alamat'] ?>,
									<?php echo $alamat['ca_kelurahan'] != '' ? $alamat['ca_kelurahan'] .', ' : '' ?>
									<?php echo $alamat['kecamatan_nama'] ?>,<?php echo $alamat['kabupaten_nama'] ?>,
									<?php echo $alamat['provinsi_nama'] ?>
									<?php echo $alamat['ca_kodepos'] != '' ? ', '.$alamat['ca_kodepos'] : '' ?>
									<?php echo $alamat['ca_hp'] != '' ? ', Hp. '.$alamat['ca_hp'] : '' ?><br>
									<button onclick="useAddress('<?php echo $alamat['ca_id'] ?>')" type="button" class="btn btn-sm btn-default">Gunakan Alamat Ini</button>
								</div>
								<?php $i++ ?>
								<?php } ?>
							<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="form-group col-md-12 text-right">
					<button type="button" onclick="simpanaddress()" id="btnsimpanaddress" class="btn btn-sm btn-primary">Simpan</button>
					<button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
				</div>
			</div>
		</div>
	</div>
</form>
