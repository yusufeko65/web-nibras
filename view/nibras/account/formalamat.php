<form id="frmalamat" name="frmalamat" class="needs-validation" novalidate>
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title text-center"><?php echo $titleform ?> Alamat Baru</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				
					<input type="hidden" id="id" name="id" value="<?php echo $id ?>">
					<input type="hidden" id="idcust" name="idcust" value="<?php echo $idmember ?>">
					<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>alamat">
					<input type="hidden" id="tipe" name="tipe" value="<?php echo $tipe ?>">
					<input type="hidden" id="url_wil" value="<?php echo URL_THEMES.'wilayah/index.php' ?>">
					
					<div class="form-row align-items-center">
						<div class="form-group col-md-6">
							<label for="add_nama" class="col-form-label col-form-label-sm">Nama</label>
							<input type="text" id="add_nama" name="add_nama" class="form-control " value="<?php echo $nama ?>" placeholder="Nama">
							<div class="invalid-feedback">
								Masukkan Nama
							</div>
						</div>
						<div class="form-group col-md-6">
							<label for="add_telp" class="col-form-label col-form-label-sm">Nomor Hp</label>
							<input type="text" id="add_telp" name="add_telp" class="form-control " value="<?php echo $hp ?>" placeholder="Nomor Hp">
							<div class="invalid-feedback">
								Masukkan Nomor Hp
							</div>
						</div>
						<div class="form-group col-md-12">
							<label for="add_alamat" class="col-form-label col-form-label-sm">Alamat</label>
							<textarea id="add_alamat" name="add_alamat" class="form-control "><?php echo $alamat ?></textarea>
							<div class="invalid-feedback">
								Masukkan Alamat
							</div>
						</div>
						
						<div class="form-group col-md-4">
							<label for="add_propinsi" class="col-form-label col-form-label-sm">Propinsi</label>
							<select id="add_propinsi" name="add_propinsi" class="form-control custom-select" onchange="findKabupaten(this.value,'add_kabupaten')">
							<?php if($dataprop) { ?>
								<?php //foreach($dataprop as $prop) { ?>
								<!--<option value="<?php //echo $prop['idp'] ?>" <?php //echo $propinsi == $prop['idp'] ? 'selected': '' ?>><?php //echo $prop['nmp'] ?></option>-->
								<?php //} ?>
								<?php echo $dataprop ?>
							<?php } ?>
							</select>
							<div class="invalid-feedback">
								Masukkan Propinsi
							</div>
						</div>
						<div class="form-group col-md-4">
							<label for="add_kabupaten" class="col-form-label col-form-label-sm">Kota/Kabupaten</label>
							<select id="add_kabupaten" name="add_kabupaten" class="form-control custom-select" onchange="findKecamatan(this.value,'add_kecamatan')">
								<option value="0">- Kota/Kabupaten -</option>
								<?php echo $optkabupaten; ?>
							<select>
							<div class="invalid-feedback">
								Masukkan Kabupaten
							</div>
						</div>
						<div class="form-group col-md-4">
							<label for="add_kecamatan" class="col-form-label col-form-label-sm">Kecamatan</label>
							<select id="add_kecamatan" name="add_kecamatan" class="form-control custom-select">
								<option value="0">- Kecamatan -</option>
								<?php echo $optkecamatan ?>
							<select>
							<div class="invalid-feedback">
								Masukkan Kecamatan
							</div>
						</div>
						<div class="form-group col-md-6">
							<label for="add_kelurahan" class="col-form-label col-form-label-sm">Kelurahan</label>
							<input type="text" id="add_kelurahan" name="add_kelurahan" class="form-control " value="<?php echo $kelurahan ?>" placeholder="Kelurahan">
						</div>
						<div class="form-group col-md-6">
							<label for="add_kodepos" class="col-form-label col-form-label-sm">Kode Pos</label>
							<input type="text" id="add_kodepos" name="add_kodepos" class="form-control " value="<?php echo $kodepos ?>" placeholder="Kode Pos">
						</div>
						<?php if($tipe == null ) {?>
						<div class="form-group col-md-6">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" name="chkdefault" id="chkdefault" value="1" <?php echo $default == '1' ? 'checked' : '' ?>>
								<label class="custom-control-label" for="chkdefault">Jadikan Alamat Utama ?</label>
							</div>
						</div>
						<?php } ?>
					</div>
					
					<div class="clearfix"></div>
			
			</div>
			<div class="modal-footer">
			   <button id="btnsimpanalamat" onclick="simpanalamat()" class="btn btn-sm btn-success" type="button">Simpan</button>
			   <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
			</div>
		</div>
	</div>
</form>