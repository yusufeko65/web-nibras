<div class="container">

	<section class="page-section">

		<h2 class="section-title"><span>Check Out</span></h2>

	</section>

	<div class="col-md-12">

		<form method="POST" name="frmkasir" id="frmkasir" action="<?php echo URL_PROGRAM . $folder . '/checkout/' ?>">

			<input type="hidden" id="url_wil" value="<?php echo URL_THEMES . 'wilayah/index.php' ?>">

			<input type="hidden" id="biaya_packing" name="biaya_packing" value='<?= $biaya_packing ?>' >

			<input type="hidden" id="cg_biaya_packing" name="cg_biaya_packing" value="<?= $customer_group["cg_biaya_packing"] ?>" >

			<input type="hidden" id="cust_tanpa_biaya_packing" name="cust_tanpa_biaya_packing" value="<?= $customer["cust_tanpa_biaya_packing"] ?>" >

			<div class="row">

				<div class="col-md-4 order-md-2">



					<h5 class="justify-content-between align-items-center">

						<span class="text-muted">Belanja</span>

						<span class="badge badge-secondary badge-pill"><?php echo $jmlitem ?></span>

					</h5>



					<ul class="list-group">

						<?php $totberat = 0 ?>

						<?php foreach ($hcart as $cart) { ?>

							<?php $totberat = $totberat + $cart['berat'] ?>

							<li class="list-group-item">

								<h6 class="list-title"><?php echo $cart['product'] ?></h6>

								<small class="text-muted">

									<?php echo $cart['warna_nama'] != '' ? 'Warna : ' . $cart['warna_nama'] : '' ?>, <?php echo $cart['ukuran_nama'] != '' ? 'Ukuran : ' . $cart['ukuran_nama'] : '' ?><br>

									Berat : <?php echo $cart['berat'] ?> gr<br>

									QTY : <?php echo $cart['qty'] ?>, Subtotal : Rp. <?php echo $dtFungsi->fuang($cart['total']) ?>

								</small>

							</li>

						<?php } ?>

						<li class="list-group-item">

							<h4 class="text-center listcart-subtotal">Subtotal : <?php echo 'Rp. ' . $dtFungsi->fuang($zsubtotal) ?></h4>

						</li>

					</ul>

					<br>

					<input type="hidden" name="totberat" id="totberat" value="<?php echo $totberat ?>">

				</div>

				<div class="col-md-8 order-md-1">

					<div class="row">

						<div class="col-md-12 plat-alamat">

							<h4 <?php echo $grup_dropship == '0' ? "style=display:none" : '' ?>><i class="fa fa-map-marker" aria-hidden="true"></i> Alamat Pengirim</h4>

							<div class="plat-alamat-detail" <?php echo $grup_dropship == '0' ? "style=display:none" : '' ?>>

								<div class="text-right">

									<button type="button" id="btnalamatpengirim" class="btn btn-sm btn-outline-secondary">Ganti Alamat</button>

								</div>



								<div id="alamatpengirim">

									<div class="align-items-center">

										<div class="form-group">

											<label class="col-form-label col-form-label-sm">Nama Pengirim</label>



											<input type="text" class="form-control form-control-sm" name="nama_pengirim" id="nama_pengirim" value="<?php echo $nama_pengirim ?>">



										</div>

										<div class="form-group">

											<label class="col-form-label col-form-label-sm">No. Hp Pengirim</label>



											<input type="text" class="form-control form-control-sm" name="telp_pengirim" id="telp_pengirim" value="<?php echo $telp_pengirim ?>">



										</div>

										<input type="hidden" name="alamat_pengirim" id="alamat_pengirim" value="<?php echo $alamat_pengirim ?>" >

										<input type="hidden" name="propinsi_pengirim" id="propinsi_pengirim" value="<?php echo $propinsi_pengirim ?>">

										<input type="hidden" name="kabupaten_pengirim" id="kabupaten_pengirim" value="<?php echo $kabupaten_pengirim ?>">

										<input type="hidden" name="kecamatan_pengirim" id="kecamatan_pengirim" value="<?php echo $kecamatan_pengirim ?>">

										<input type="hidden" name="kelurahan_pengirim" id="kelurahan_pengirim" value="<?php echo $kelurahan_pengirim ?>">

										<input type="hidden" name="kodepos_pengirim" id="kodepos_pengirim" value="<?php echo $kodepos_pengirim ?>">

									</div>

								</div>

							</div>

						</div>

						<div class="col-md-12 plat-alamat">

							<h4><i class="fa fa-map-marker" aria-hidden="true"></i> Alamat Penerima</h4>

							<div class="plat-alamat-detail">

								<div class="text-right">

									<button type="button" id="btnalamatpenerima" class="btn btn-sm btn-outline-secondary">Ganti Alamat</button>

								</div>

								<div id="alamatpenerima">

									<!-- form alamat penerima -->

									<div class="form-row align-items-center">

										<div class="form-group col-sm-6">

											<label for="nama_penerima" class="col-form-label col-form-label-sm">Nama</label>

											<input type="text" id="nama_penerima" name="nama_penerima" class="form-control " value="<?php echo $nama_penerima ?>" placeholder="Nama">

											<div class="invalid-feedback">

												Masukkan Nama

											</div>

										</div>

										<div class="form-group col-sm-6">

											<label for="telp_penerima" class="col-form-label col-form-label-sm">Nomor Hp</label>

											<input type="text" id="telp_penerima" name="telp_penerima" class="form-control " value="<?php echo $telp_penerima ?>" placeholder="Nomor Hp">

											<div class="invalid-feedback">

												Masukkan Nomor Hp

											</div>

										</div>

										<div class="form-group col-sm-12">

											<label for="alamat_penerima" class="col-form-label col-form-label-sm">Alamat</label>

											<textarea id="alamat_penerima" name="alamat_penerima" class="form-control "><?php echo $alamat_penerima ?></textarea>

											<div class="invalid-feedback">

												Masukkan Alamat

											</div>

										</div>

										<div class="form-group col-sm-4">



											<label for="propinsi_penerima" class="col-form-label col-form-label-sm">Propinsi</label>

											<select id="propinsi_penerima" name="propinsi_penerima" class="form-control custom-select" onchange="findKabupaten(this.value,'kabupaten_penerima');$('#kecamatan_penerima').html('<option value=\'0\'>- Kecamatan -</option>');">

												<?php if ($dataprop) { ?>

													<?php echo $dataprop ?>

												<?php } ?>

												<select>

													<div class="invalid-feedback">

														Masukkan Propinsi

													</div>

										</div>

										<div class="form-group col-sm-4">

											<label for="kabupaten_penerima" class="col-form-label col-form-label-sm">Kota/Kabupaten</label>

											<select id="kabupaten_penerima" name="kabupaten_penerima" class="form-control custom-select" onchange="findKecamatan(this.value,'kecamatan_penerima')">

												<?php echo $optkabupaten; ?>

												<select>

													<div class="invalid-feedback">

														Masukkan Kabupaten

													</div>

										</div>

										<div class="form-group col-sm-4">

											<label for="kecamatan_penerima" class="col-form-label col-form-label-sm">Kecamatan</label>

											<select id="kecamatan_penerima" name="kecamatan_penerima" class="form-control custom-select">

												<?php echo $optkecamatan ?>

												<select>

													<div class="invalid-feedback">

														Masukkan Kecamatan

													</div>

										</div>

										<div class="form-group col-sm-6">

											<label for="kelurahan_penerima" class="col-form-label col-form-label-sm">Kelurahan</label>

											<input type="text" id="kelurahan_penerima" name="kelurahan_penerima" class="form-control " value="<?php echo $kelurahan_penerima ?>" placeholder="Kelurahan">

										</div>

										<div class="form-group col-sm-6">

											<label for="kodepos_penerima" class="col-form-label col-form-label-sm">Kode Pos</label>

											<input type="text" id="kodepos_penerima" name="kodepos_penerima" class="form-control " value="<?php echo $kodepos_penerima ?>" placeholder="Kode Pos">

										</div>

									</div>

									<!-- end form alamat penerima -->

								</div>

							</div>

						</div>

						<div class="col-md-12 plat-alamat">

							<h4><i class="fa fa-truck" aria-hidden="true"></i> Kurir</h4>

							<small class="form-text text-muted">

								<span class="text-danger">(Wajib Dipilih)</span>

							</small>

							<div class="plat-alamat-detail">

								<div class="form-group">

									<select class="custom-select form-control" name="serviskurir" id="serviskurir">

										<option value="0">- Pilih Kurir -</option>

										<?php $servis_rajaongkir = $dtShipping->getAllServicesAndTarifByWilayahRajaOngkir($config_lokasiorigin, $kecamatan_penerima, $totberat, $config_apiurlongkir, $config_apikeyongkir); ?>

										<?php $servis_ondb = $dtShipping->getAllServisKonfirmAdmin(); ?>

										<?php if ($servis_rajaongkir) { ?>

											<?php foreach ($servis_rajaongkir as $ship) { ?>



												<option value="<?php echo $ship['servis_id'] ?>::<?php echo $ship['tarif'] ?>::<?php echo $ship['shipping_code_rajaongkir'] ?>::<?php echo $ship['servis_code'] ?>"><?php echo $ship['shipping_code_rajaongkir'] . ' - ' . $ship['servis_code'] . ' (' . $ship['etd'] . ') - ' . $ship['tarif'] ?></option>



											<?php } ?>

										<?php } ?>

										<?php if ($servis_ondb) { ?>

										

											<?php foreach ($servis_ondb as $servdb) { ?>

												<?php 

												if($servdb['shipping_konfirmadmin'] == '1') {

													$konfirm = 'Konfirmasi Admin';

													$hrgperkilo = $servdb['hrg_perkilo'];

												} else {

													$konfirm = '0';

													$hrgperkilo = '0';

												}

												?>

												<option value="<?php echo $servdb['servis_id'] ?>::<?php echo $konfirm ?>::<?php echo $servdb['shipping_kode'] ?>::<?php echo $servdb['servis_code'] ?>"><?php echo $servdb['shipping_kode'] . ' - ' . $servdb['servis_code'] . ' - ' . $hrgperkilo ?></option>

											<?php } ?>

										<?php } ?>

										<select>



								</div>

								<div class="form-group">

									<b>Total Berat </b><?php echo $totberat ?> Gr / <?php echo $totberat / 1000 ?> Kg

								</div>

							</div>

						</div>

						<div class="col-md-12 plat-alamat">

							<h4>Keterangan Tambahan</h4>

							<div class="plat-alamat-detail">

								<div class="form-group">

									<input type="text" name="keterangan" id="keterangan" class="form-control">

									<cite>Misal : No. Resi dari marketplace</cite>

								</div>

							</div>

						</div>

						<div class="col-md-12">

							<ul class="list-group">

								<li class="list-group-item">

									<div class="row">

										<div class="col-6">

											<h6>Subtotal : </h6>

										</div>

										<div class="col-6">

											<h6 class="text-right"><?php echo 'Rp. ' . $dtFungsi->fuang($zsubtotal) ?></h6>

											<input type="hidden" id="subtotal" name="subtotal" value="<?php echo $zsubtotal ?>">

										</div>

									</div>



								</li>

								<li class="list-group-item">

									<div class="row">

										<div class="col-6">

											<h6>Kurir : </h6>

										</div>

										<div class="col-6">

											<h6 class="text-right" id="tarif">-</h6>

											<input type="hidden" name="tarifkurir" id="tarifkurir">

										</div>

									</div>



								</li>
								
								<li class="list-group-item">

									<div class="row">

										<div class="col-6">

											<h6>Kode Unik : </h6>

										</div>

										<div class="col-6">

											<h6 class="text-right" id="view_kode_unik">-</h6>

											<input type="hidden" name="kode_unik" id="kode_unik">

										</div>

									</div>

								</li>

								<li class="list-group-item" id='field_biaya_packing' style="display:none">

									<div class="row">

										<div class="col-6">

											<h6>Biaya Packing: </h6>

										</div>

										<div class="col-6">

											<h6 class="text-right" id="caption_biaya_packing" >-</h6>

										</div>

									</div>

								</li>

								<li class="list-group-item">

									<div class="row">

										<div class="col-6">

											<h5>Total Tagihan : </h5>

										</div>

										<div class="col-6">

											<h5 class="text-right" id="totaltagihan">-</h5>

											<input type="hidden" name="nilaiTotalTagihan" id="nilaiTotalTagihan">

										</div>

									</div>

								</li>

								<?php if ($grup_deposito == '1' && $grup_deposito > 0) { ?>

									<?php

									//if($depositmember['totaldeposito'] > $zsubtotal) {  

									//	$potdeposito = 'Rp. '.$dtFungsi->fuang($zsubtotal);

									//} else { 	

									$potdeposito = 'Rp. ' . $dtFungsi->fuang($depositmember['totaldeposito']);

									//} 

									if ($depositmember['totaldeposito'] > 0) {

									?>

										<li class="list-group-item">

											<div class="row">

												<div class="col-6">

													<input type="hidden" name="totalDeposito" id="totalDeposito" value="<?php echo $depositmember['totaldeposito'] ?>"

													<h5>Gunakan Saldo Anda ?</h5>

													<div class="text-success">Anda memiliki Saldo sebesar <?php echo $potdeposito ?></div>

													<div class="text-danger" style="display:none" id="kekuranganInfo" >Kekurangan : <span id='kekuranganSaldo'></span> </div>

												</div>

												<div class="col-6 text-right">

													<div class="checkbox">

														<label>

															<input type="checkbox" name="potdeposito" id="potdeposito" value="1"> Ya/Tidak (Centang jika Ya)

														</label>

													</div>

												</div>

											</div>

										</li>

									<?php } ?>

								<?php } ?>

								<?php if ($poinmember['totalpoin']) { ?>



								<?php } ?>

							</ul>



						</div>

						</div>

						<div class="container" style="padding-top:10px">

							<div class="row justify-content-md-center">

								<div class="col-md-4 text-center text-danger">

									<button id="simpancart" type="submit" class="btn btn-block btn-info">Bayar</button>

								</div>

							</div>

						</div>



					</div>

				</div>



			</div>

		</form>

	</div>

</div>