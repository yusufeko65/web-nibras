<form method="POST" name="frmeditkurir" id="frmeditkurir" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" id="nopesanan" name="nopesanan" value="<?php echo $formkurir['order']['pesanan_no'] ?>">
	<input type="hidden" id="propinsi_penerima" name="propinsi_penerima" value="<?php echo $formkurir['order']['propinsi_penerima'] ?>">
	<input type="hidden" id="kabupaten_penerima" name="kabupaten_penerima" value="<?php echo $formkurir['order']['kota_penerima'] ?>">
	<input type="hidden" id="kecamatan_penerima" name="kecamatan_penerima" value="<?php echo $formkurir['order']['kecamatan_penerima'] ?>">
	<input type="hidden" id="kodepos_penerima" name="kodepos_penerima" value="<?php echo $formkurir['order']['kodepos_penerima'] ?>">
	<input type="hidden" id="totberat" name="totberat" value="<?php echo $formkurir['totberat'] ?>">
	<input type="hidden" id="subtotal" name="subtotal" value="<?php echo $formkurir['order']['pesanan_subtotal'] ?>">
	<input type="hidden" id="redirectview" value="<?php echo URL_PROGRAM_ADMIN . 'order' ?>">
	<input type="hidden" id="redirectedit" value="<?php echo URL_PROGRAM_ADMIN . 'order/?op=info&pid=' . $formkurir['order']['pesanan_no'] . '&u_token=' . $u_token ?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 class="modal-title">Edit Kurir</h4>
			</div>
			<div class="modal-body">
				<div id="hasileditkurir" style="display:none"></div>

				<div class="form-group">
					<?php //echo "<pre>" ?>
					<?php //print_r($formkurir['servis']) ?>
					<label for="serviskurir" class="control-label">Servis : <cite>Harga yang ditampikan adalah harga per kilo</cite></label>
					<select class="form-control form-control-sm" name="serviskurir" id="serviskurir" onchange="cektarifkurir()">
						<option value="0">- Pilih Kurir -</option>
						<?php if ($formkurir['servis']) { ?>
						<?php foreach ($formkurir['servis'] as $ship) { ?>
						<?php if ($ship['shipping_rajaongkir'] == '1') { ?>
						<option value="<?php echo $ship['servis_id'] ?>::<?php echo $ship['tarif'] ?>::<?php echo $ship['shipping_code_rajaongkir'] ?>::<?php echo $ship['servis_code'] ?>"><?php echo $ship['shipping_code_rajaongkir'] . ' - ' . $ship['servis_code'] . ' (' . $ship['etd'] . ') - ' . $ship['tarif'] ?></option>
						<?php } else { ?>
						<?php
									if ($ship['shipping_cod'] == '1') {
										$tarifs = 0;
									} else {
										if($ship['shipping_konfirmadmin'] == '1') {
											$tarifs = 'Konfirmasi Admin';
										} else { 
											$tarifs = 0;
										}
										
									}
									?>

						<option value="<?php echo $ship['servis_id'] ?>::<?php echo $tarifs ?>::<?php echo $ship['shipping_kode'] ?>::<?php echo $ship['servis_code'] ?>"><?php echo $ship['shipping_kode'] . ' - ' . $ship['servis_code'] . ' - ' . $tarifs ?></option>
						<?php } ?>
						<?php } ?>
						<?php } ?>
						<select>
				</div>
				<div class="form-group">
					<label>Berat</label>
					<input type="text" disabled class="form-control form-control-sm" value="<?php echo $formkurir['totberat'] . 'Gr (' . $formkurir['totberat'] / 1000 . ' Kg)' ?>">
				</div>
				<div class="form-group" id="plattarif" <?php //echo $konfirmadmin == '0' ? ' style="display:none"':'' 
														?>>
					<label for="tarifkurir" class="control-label">Tarif : <cite>Harga tarif yang sudah berdasarkan hitungan total berat</cite></label>
					<input type="text" name="tarifkurir" id="tarifkurir" value="" placeholder="Masukkan Tarif" class="form-control form-control-sm" aria-describedby="helpBlock" <?php echo $formkurir['order']['shipping_konfirmadmin'] == '0' ? ' readonly' : '' ?>>
					<span id="helpBlock" class="help-block"><cite>Khusus Servis yang masih "Konfirmasi Admin", silakan masukkan tarif sesuai servis masing-masing dari kurir tersebut.</cite></span>

				</div>

			</div>
			<div class="modal-footer">
				<a onclick="simpaneditkurir()" data-loading-text="Tunggu..." id="btnsimpankurir" class="btn btn-sm btn-primary">Simpan</a>
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
			</div>
		</div>
	</div>

</form>