<div class="container">
	<section class="page-section">
		<h1 class="section-title"><span>Detail Belanja</span></h1>
	</section>
	<form id="frmorder" autocomplete="off" action="<?php echo URL_PROGRAM . 'orderdetail' ?>">
		<input type="hidden" id="pesanan_no" name="pesanan_no" value="<?php echo $dataorder['pesanan_no'] ?>">
		<input type="hidden" id="redirect" value="<?php echo URL_PROGRAM ?>orderdetail/?order=<?php echo $dataorder['pesanan_no'] ?>">
		<input type="hidden" name="jmlproduk" value="<?php echo count($datadetail) ?>">
		<input type="hidden" id="urlwilayah" value="<?php echo URL_THEMES . 'wilayah/index.php' ?>">
		<div class="col-sm-12">
			<div class="plat-order">
				<div class="row">

					<div class="col-md-6">
						<b>No Order </b> : #<?php echo (int)$dataorder['pesanan_no'] ?><br>
						<b>Tgl Order</b> : <?php echo $dtFungsi->ftanggalFull1($dataorder['pesanan_tgl']) ?>
					</div>
					<div class="col-md-6">

						<b>Status</b> : <?php echo $dataorder['status_nama']; ?> <?php echo $config_orderstatus == $dataorder['status_id'] ? "<a href=\"" . URL_PROGRAM . "konfirmasi\?order=" . $dataorder['pesanan_no'] . "\">[Form Konfirmasi Pembayaran]</a>" : '' ?> <?php echo $config_orderstatus != $dataorder['status_id'] && $config_ordercancel != $dataorder['status_id'] && isset($dataorder['jml_bayar'])  ? "<button type='button' data-toggle=\"modal\" data-target=\"#detailPembayaran\" class=\"btn btn-sm btn-info\">[Detail Pembayaran]</button>" : '' ?><br>

						<b>No. Resi</b> : <?php echo $dataorder['no_awb']; ?>
						<?php if($config_orderstatus == $dataorder['status_id']): ;?>
							<br><br>Saldo : <b style="color:<?php echo  $checkdeposit['totaldeposito']>=$tagihan?'green':'red';?>"><?php echo  $dtFungsi->fFormatuang($checkdeposit['totaldeposito']) ;?></b><br>
							<?php if($checkdeposit['totaldeposito']>=$tagihan){ ;?>
									<b>Bayar Potong saldo?</b> : <a href="<?php echo URL_PROGRAM."orderdetail\?order=".$dataorder['pesanan_no']."&load=potongSaldo";?>">[Ya]</a>
							<?php 
								}else{
									echo "<span style='color:red'>Saldo tidak mencukupi untuk membayar tagihan ini</span><br>";
									echo "<span style='color:blue'>(Silahkan tambah saldo)</span><br>";
								} 
							endif;
						?>

					</div>

				</div>
			</div>
			<hr>

			<div class="col-md-12">
				<div class="row">
					<?php if ($dataorder['cg_dropship'] == '1') { ?>
						<div class="col-md-6 kol-alamat">

							<?php if ($dataorder['status_id'] == $config_orderstatus) { ?>
								<button id="btnalamatpengirim" class="btn btn-sm btn-info" type="button" onclick="formAlamat('alamatpengirim')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Ganti Alamat Pengirim</button><br>
							<?php } ?>
							<h5>Alamat Pengirim</h5>
							<?php
							$alamat  = '<b>' . stripslashes($dataorder['nama_pengirim']) . '</b><br> ';
							$alamat .= 'Hp. ' . $dataorder['hp_pengirim'];
							echo $alamat;
							?>
						</div>
					<?php } ?>
					<div class="col-md-6 kol-alamat">
						<?php if ($dataorder['status_id'] == $config_orderstatus) { ?>
							<button id="btnalamatpenerima" onclick="formAlamat('alamatpenerima')" class="btn btn-sm btn-info" type="button"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Ganti Alamat Penerima</button><br>
						<?php } ?>
						<h5>Alamat Penerima</h5>
						<?php
						$alamat  = '<b>' . stripslashes($dataorder['nama_penerima']) . '</b><br> ';
						$alamat .= stripslashes($dataorder['alamat_penerima']) . ' <br>';

						if ($dataorder['kelurahan_penerima'] != '') {

							$alamat .= $dataorder['kelurahan_penerima'] . ', ';
						}
						$alamat .= $dataorder['kecamatannm_penerima'] . ', ' . $dataorder['kotanm_penerima'] . ', ' . $dataorder['propinsinm_penerima'];
						$alamat .= ', ' . $dataorder['negaranm_penerima'];
						if ($dataorder['kodepos_penerima'] != '') {

							$alamat .= ' ' . $dataorder['kodepos_penerima'];
						}
						$alamat .= '<br> Hp. ' . $dataorder['hp_penerima'];
						echo $alamat;
						?>
					</div>
				</div>
			</div>

			<?php if ($dataorder['status_id'] == $config_orderstatus) { ?>
				<div class="row">
					<div class="col-md-12">
						<div class="well-blue">
							<div class="form-group">
								<input type="text" class="form-control form-lg" name="search_produk" id="search_produk" placeholder="Cari nama produk yang ingin ditambahkan">
								<small clas="text-danger">Untuk menambah Produk, silahkan lakukan pencarian produk di box atas</small>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th width="10%"></th>
							<th class="text-left">Produk</th>
							<th class="text-right">Jumlah</th>
							<th class="text-center">Berat</th>
							<th class="text-right">Harga Normal</th>
							<th class="text-center">Diskon</th>
							<th class="text-right">Harga</th>
							<th class="text-right">Total</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$totberat = 0;
						$i = 0;
						$subtotal = 0;
						$total = 0;
						foreach ($datadetail as $dt) {
							$harga_satuan = $dt['harga_satuan'];
							$harga_tambahan = $dt['harga_tambahan'];
							$persen_diskon_satuan = $dt['diskon_satuan'];
							$harga_normal_total = $harga_satuan + $harga_tambahan;
							$diskon_rupiah = $harga_normal_total - $dt['harga'];
							$persen_all_diskon = $diskon_rupiah / $harga_normal_total * 100;
							$diskon_cust_persen = $persen_all_diskon - $persen_diskon_satuan;

							$harga_normal = $dtFungsi->fFormatuang($dt['harga_satuan'] + $dt['harga_tambahan']);
							$subtotal = ((int)$dt['jml']) * (int)$dt['harga'];
							/*
								if($dt['harga_tambahan'] > 0) {
									$harga_normal .= '<br><small> + '.$dtFungsi->fuang($dt['harga_tambahan']) . '<br>(Harga tambahan)</small>';
								}
								*/
							$options = array($dt['ukuranid'], $dt['warnaid'], $dt['ukuran'], $dt['warna']);
							$pid =  $dt['produkid'] . '::' . base64_encode(serialize($options)) . '::' . $dt['jml'];

							?>
							<tr>

								<td class="text-center">
									<?php if ($dataorder['status_id'] == $config_orderstatus) { ?>
										<a id="btnedit<?php echo $i ?>" href="javascript:void(0)" class="btn btn-sm btn-outline-primary" onclick="editProduk('<?php echo $pid ?>')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a id="btnhapus<?php echo $i ?>" href="javascript:void(0)" class="btn btn-sm btn-outline-danger" onclick="hapusProduk('<?php echo $pid ?>')"><i class="fa fa-trash" aria-hidden="true"></i></a>
									<?php } ?>
								</td>
								<td class="text-left"><b><?php echo $dt['nama_produk'] ?></b>
									<?php if ($dt['warnaid'] || $dt['ukuranid']) { ?>
										<br>
										<?php echo 'Warna  :' . $dt['warna'] ?><br>
										<?php echo 'Ukuran :' . $dt['ukuran']; ?>
									<?php } ?>
								</td>
								<td class="text-right"><?php echo $dt['jml'] ?></td>
								<td class="text-center"><?php echo $dt['berat'] ?>Gr</td>
								<td class="text-right"><?php echo $harga_normal ?></td>
								<td class="text-right"><?php echo $persen_all_diskon ?> %</td>
								<td class="text-right"><?php echo $dtFungsi->fFormatuang($dt['harga']) ?></td>
								<td class="text-right"><?php echo $dtFungsi->fFormatuang($subtotal) ?></td>
							</tr>
							<?php $totberat = $totberat + $dt['berat'] ?>
							<?php $total = $total + $subtotal ?>
							<?php $i++ ?>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php
			if ($dataorder['status_id'] == $config_ordercancel || $dataorder['status_id'] == $config_shippingstatus) {
				$readonly = 'disabled';
			} else {
				$readonly = '';
			}
			?>

			<div class="row">
				<div class="col-md-12">
					<div id="hasilketerangan"></div>
					<div class="form-group">
						<label><b>Keterangan Tambahan</b></label>
						<div class="input-group">
							<input type="text" class="form-control" <?php echo $readonly ?> name="keterangan" id="keterangan" <?php echo $readonly ?> value="<?php echo $dataorder['keterangan'] ?>" placeholder="Keterangan Tambahan" aria-describedby="update_keterangan">
							<div class="input-group-append">
								<button type="button" class="btn btn-primary" <?php echo $readonly ?> type="button" id="update_keterangan">Update Keterangan</button>
							</div>

						</div>
						<cite>Misal : No. Resi dari marketplace</cite>
					</div>
				</div>
			</div>

			<table class="table table-bordered">

				<tr>
					<td class="text-right" colspan="7"><b>Total Berat</b></td>
					<td class="text-right"><?php echo $totberat ?> Gr
						<input type="hidden" name="totberat" value="<?php echo $totberat ?>">
					</td>

				</tr>
				<tr>
					<td colspan="7" class="text-right"><b>Subtotal</b></td>
					<td class="text-right"><?php echo $dtFungsi->fFormatuang($total) ?></td>
				</tr>
				<tr>
					<td colspan="7" class="text-right">
						<?php if ($dataorder['status_id'] == $config_orderstatus) { ?>
							<a href="javascript:void(0)" class="btn btn-sm btn-info" id="btneditorderkurir"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Ubah</a>
						<?php } ?>
						<b>Tarif Kurir (<?php echo $dataorder['kurir'] ?> - <?php echo $dataorder['servis_code'] ?>)</b></td>
					<td class="text-right"><?php echo $dataorder['kurir_konfirm'] == '1' ? 'Konfirmasi Admin' : $dtFungsi->fFormatuang($dataorder['pesanan_kurir']) ?></td>
				</tr>
				<?php
				?>
				<!--
						<tr>
							<td colspan="7" class="text-right"><b>POTONGAN DARI POIN</b></td>
							<td class="text-right"><?php
													?></td>
						</tr>
						-->
				<?php
				?>
				<?php if ($dataorder['dari_deposito'] > 0 || $dataorder['deposito_agen'] > 0) { ?>

					<tr>
						<td colspan="7" class="text-right"><b>POTONGAN DARI SALDO</b></td>
						<td class="text-right">(<?php echo $dtFungsi->fFormatuang($dataorder['dari_deposito']) ?>)
							<input type="hidden" name="jmldeposit" value="<?php echo $dataorder['dari_deposito'] ?>">
						</td>
					</tr>

				<?php } ?>
				<tr>
					<td colspan="7" class="text-right"><b>TOTAL</b></td>
					<td class="text-right"><b><?php echo $dataorder['kurir_konfirm'] == '1' ? 'Konfirmasi Admin' : $dtFungsi->fFormatuang(((int)$total + (int)$dataorder['pesanan_kurir']) - (int)$dataorder['dari_poin'] - (int)$dataorder['dari_deposito']) ?></b></td>
				</tr>

			</table>

		</div>
	</form>
</div>
<?php if ($dataorder['jml_bayar']) { ?>
	<!-- Modal -->
	<div class="modal fade" id="detailPembayaran" tabindex="-1" role="dialog" aria-labelledby="detailPembayaranLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="detailPembayaranLabel">Detail Pembayaran</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<h6>Rekening Tujuan : </h6>

					<div class="col-md-12 row">
						<?php echo $dataorder['bank_nama'] ?>, No. Rek <?php echo $dataorder['rekening_no'] ?> A/n <?php echo $dataorder['rekening_atasnama'] ?>
					</div>


					<hr>
					<h6>Ditransfer dari Rekening : </h6>
					<div class="col-md-12 row">
						<?php echo $dataorder['bank_dari'] ?>, No Rek. <?php echo $dataorder['bank_rek_dari'] ?> a/n <?php echo $dataorder['bank_atasnama_dari'] ?>
					</div>
					<hr>
					<div class="form-group-modal row">
						<label class="col-md-4 col-form-label">Tanggal Transfer : </label>
						<div class="col-md-8">
							<input type="text" readonly class="form-control-plaintext form-control-sm" value="<?php echo $dataorder['tgl_transfer'] ?>">
						</div>
					</div>
					<div class="form-group-modal row">
						<label class="col-md-4 col-form-label">Jumlah Transfer : </label>
						<div class="col-md-8">
							<input type="text" readonly class="form-control-plaintext form-control-sm" value="<?php echo $dataorder['jml_bayar'] ?>">
						</div>
					</div>
					<div class="form-group-modal row">
						<label class="col-md-4 col-form-label">Bukti Transfer : </label>
						<div class="col-md-8">
							<img src="<?php echo URL_IMAGE ?>_other/other_<?php echo $dataorder['buktitransfer'] ?>" style="width:30%">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

				</div>
			</div>
		</div>
	</div>
<?php } ?>