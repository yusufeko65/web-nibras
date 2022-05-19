<form id="frmalamat" name="frmalamat" class="needs-validation" novalidate>
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title text-center">Daftar Alamat</h6>
				<input type='text' id='inputCariNama' placeHolder='Cari Nama'  />
				<button type='button' id='btnCariNama' onclick="cariNama('<?php echo $tipe ?>')" >Cari</button>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="list-alamat">
					<input type="hidden" id="idcust" name="idcust" value="<?php echo $idmember ?>">
					<input type="hidden" id="tipe" name="tipe" value="<?php echo $tipe ?>">
					<input type="hidden" id="url_wil" value="<?php echo URL_THEMES.'wilayah/index.php' ?>">
					<?php if($listalamat) { ?>
					<div class="container">
					<?php $no = 0 ?>
					<?php foreach($listalamat as $alamat) { 
						/*
						$dataalamat  = $alamat['ca_nama'].':'.$alamat['ca_alamat'].':'.$alamat['provinsi_nama'].':'.$alamat['ca_propinsi'].':';
						$dataalamat .= $alamat['kabupaten_nama'].':'.$alamat['ca_kabupaten'].':';
						$dataalamat .= $alamat['kecamatan_nama'].':'.$alamat['ca_kecamatan'].':'.$alamat['ca_kelurahan'].':'.$alamat['ca_kodepos'].':';
						$dataalamat .= $alamat['ca_hp'];
						*/
						$dataalamat  = $alamat['ca_nama'].':'.preg_replace( "/\r|\n/", " ", $alamat['ca_alamat'] ).':'.$alamat['provinsi_nama'].':'.$alamat['ca_propinsi'].':';
						$dataalamat .= $alamat['kabupaten_nama'].':'.$alamat['ca_kabupaten'].':';
						$dataalamat .= $alamat['kecamatan_nama'].':'.$alamat['ca_kecamatan'].':'.$alamat['ca_kelurahan'].':'.$alamat['ca_kodepos'].':';
						$dataalamat .= $alamat['ca_hp'];
						

						?>
						<div class="col-md-12 plat-alamat-detail">
							<b><?php echo $alamat['ca_nama'] ?></b><br>
							<small>
							<?php echo $alamat['ca_alamat'] ?>,<?php echo $alamat['provinsi_nama'] ?>,<?php echo $alamat['kabupaten_nama'] ?>,
							<?php echo $alamat['kecamatan_nama'] ?><?php echo $alamat['ca_kelurahan'] != '' ? ', '.$alamat['ca_kelurahan']:'' ?><?php echo $alamat['ca_kodepos'] != '' ? ', '.$alamat['ca_kodepos']:'' ?><br>
							Hp. <?php echo $alamat['ca_hp'] ?>
							</small>
							<br>
							<div class="text-right"><button type="button" class="btn btn-sm btn-danger" id="btnpilih<?php echo $no ?>" onclick="gantiAlamat('<?php echo $tipe ?>','<?php echo $dataalamat ?>')">Pilih Alamat Ini</button></div>
						</div>
						<?php $no++ ?>
					<?php } ?>
					</div>
					<?php } ?>
					
					
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="modal-footer">
			   <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
			</div>
		</div>
	</div>
</form>