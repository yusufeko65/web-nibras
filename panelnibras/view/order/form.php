<div class="col-lg-12 main-content">

	<h2 class="judulmodul"><?php echo $judul ?></h2>

	<div class="widget-box">

		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span>

			<h5>FORM <?php echo strtoupper($judul) ?></h5>

		</div>

		<div class="widget-content nopadding">

			<form autocomplete="off" method="POST" name="frmdata" id="frmdata" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="form-horizontal">

				<input type="hidden" name="aksi" id="aksi" value="<?php echo $modul ?>">

				<input type="hidden" name="iddata" id="iddata" value="<?php echo $iddata ?>">

				<input type="hidden" name="stsnow" id="stsnow" value="<?php echo $order['status_id'] ?>">

				<input type="hidden" name="stsshipping" id="stsshipping" value="<?php echo $dataset['config_shippingstatus'] ?>">

				<input type="hidden" name="stsdone" id="stsdone" value="<?php echo $dataset['config_donestatus'] ?>"> <!-- aar 22-06-2020 -->

				<input type="hidden" name="stskonfirm" id="stskonfirm" value="<?php echo $dataset['config_konfirmstatus'] ?>">

				<input type="hidden" name="stssudahbayar" id="stssudahbayar" value="<?php echo $dataset['config_sudahbayarstatus'] ?>">

				<input type="hidden" name="stsgetpoin" id="stsgetpoin" value="<?php echo $dataset['config_getpoincust'] ?>">

				<input type="hidden" name="stscancel" id="stscancel" value="<?php echo $dataset['config_ordercancel'] ?>">

				<input type="hidden" name="grppelanggan" id="grppelanggan" value="<?php echo $order['grup_member'] ?>">

				<input type="hidden" name="idkurir" id="idkurir" value="<?php echo $order['kurir'] ?>">

				<input type="hidden" name="idserviskurir" id="idserviskurir" value="<?php echo $order['servis_kurir'] ?>">

				<input type="hidden" name="tglkirimshipping" id="tglkirimshipping" value="<?php echo $order['tgl_kirim'] ?>">

				<input type="hidden" name="awbshipping" id="awbshipping" value="<?php echo $order['no_awb'] ?>">

				<input type="hidden" name="pelangganid" id="pelangganid" value="<?php echo $order['pelanggan_id'] ?>">

				<input type="hidden" name="tglorder" id="tglorder" value="<?php echo $order['pesanan_tgl'] ?>">

				<input type="hidden" name="nmpelanggan" id="nmpelanggan" value="<?php echo $order['cust_nama'] ?>">

				<input type="hidden" name="nmgrppelanggan" id="nmgrppelanggan" value="<?php echo $order['grup_cust'] ?>">

				<input type="hidden" name="nmstatus" id="nmstatus" value="<?php echo $order['status_nama'] ?>">

				<input type="hidden" name="grup_dropship" id="grup_dropship" value="<?php echo $grup_droship ?>">

				<input type="hidden" name="jmlprodukorder" id="jmlprodukorder" value="<?php echo count($datadetail) ?>">

				<?php

				$md = 'col-md-12';

				$style = ' style="display:none"';

				$labelorder = '';

				if ($order['dropship'] == '1') {

					$md = 'col-md-6';

					$style = '';

					$labelorder = '<span class="label label-warning">Dropship</span>';

				}

				?>



				<div id="hasil" style="display: none;"></div>

				<div class="col-md-12 text-right">

					<a href="<?php echo URL_PROGRAM_ADMIN . folder . '?modul=cetak&pid=' . $iddata . '&u_token=' . $u_token ?>" target="_blank" id="btncetak" class="btn btn-sm btn-default"><i class="icon-print" aria-hidden="true"></i> Cetak Nota</a>

					<?php

					if ($order['keterangan'] != '' || $order['keterangan'] != null) {

						$class_button = "btn-warning";

					} else {

						$class_button = 'btn-default';

					}

					?>

					<a href="<?php echo URL_PROGRAM_ADMIN . folder . '?modul=cetaklabel&pid=' . $iddata . '&u_token=' . $u_token ?>" target="_blank" id="btncetaklabel" class="btn btn-sm <?php echo $class_button ?>"><i class="icon-print" aria-hidden="true"></i> Cetak Label</a>

				</div>

				<div class="clearfix"></div>

				<div class="well plat-order">

					<div class="col-md-2">

						<b>No Order</b>

					</div>

					<div class="col-md-4">

						: <?php echo (int) $order['pesanan_no'] ?> <?php echo $labelorder ?>

					</div>

					<div class="col-md-2">

						<b>Pelanggan</b>

					</div>

					<div class="col-md-4">

						: <?php echo $order['cust_nama'] . ' (' . $order['grup_cust'] . ') ' ?>

					</div>

					<div class="col-md-2">

						<b>Tgl Order</b>

					</div>

					<div class="col-md-4">

						: <?php echo $dtFungsi->ftanggalFull1($order['pesanan_tgl']) ?>

					</div>

					<div class="col-md-2">

						<b>Status</b>

					</div>

					<div class="col-md-4">

						: <?php echo $order['status_nama']; ?>







						<?php

						$tabeljoin = "`_order_status` LEFT JOIN _login ON _order_status.admin_id = _login.login_id";

						$wherejoin = "nopesanan='" . $order['pesanan_no'] . "' AND status_id = '" . $order['status_id'] . "'";

						$actionby = $dtFungsi->fcaridata2($tabeljoin, 'login_nama', $wherejoin);

						if ($actionby['login_nama'] != null) {

							echo ' (' . $actionby['login_nama'] . ')';

						} else {

							echo ' (Sistem)';

						}

						if ($order['status_id'] == $dataset['config_ordercancel']  || $order['status_id'] == $dataset['config_shippingstatus'] || $order['status_id'] == 18) {

							$caption_button = 'Detail';

						} else {

							$caption_button = 'Edit';

						}

						?>

						<a href="javascript:void(0)" id="btnEditStatus" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-pencil"></span> <?php echo $caption_button ?></a>

					</div>

					<div class="col-md-2 col-md-offset-6">

						<b>No Resi</b>

					</div>

					<div class="col-md-4">

						: <?php echo $order['no_awb']; ?>

					</div>



					<div class="clearfix"></div>

				</div> <!-- @well -->

				<?php if ($dataset['config_orderstatus'] == $order['status_id']) { ?>

				<div class="pull-right">

					<div class="btn-group">

						<a class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">

							<span class="glyphicon glyphicon-pencil"></span> Edit Alamat <span class="caret"></span>

						</a>

						<ul class="dropdown-menu dropdown-menu-right">

							<li><a href="#" id="btnalamatpengirim">Alamat Pengirim</a></li>

							<li><a href="#" id="btnalamatpenerima">Alamat Tujuan</a></li>

						</ul>

					</div>

				</div>

				<div class="clearfix"></div>

				<?php } ?>

				<div class="<?php echo $md ?> deskripsi" <?php echo $style ?>>



					<h4 class="judulmodul">Alamat Pengirim</h4>

					<b><?php echo stripslashes(ucwords(strtolower($order['nama_pengirim']))) ?></b><br>

					<?php echo stripslashes($order['alamat_pengirim']) ?>

					<?php if ($order['kelurahan_pengirim']) { ?>

					, Kel. <?php echo $order['kelurahan_pengirim'] ?> <br>

					<?php } else { ?>

					<br>

					<?php } ?>

					Kec. <?php echo $order['kecamatannm_pengirim'] ?>, <?php echo stripslashes($order['kotanm_pengirim']) ?> <br>

					<?php echo stripslashes($order['propinsinm_pengirim']) ?>,

					<?php echo $order['negaranm_pengirim'] ?> <?php echo $order['kodepos_pengirim'] ?><br>

					<?php if ($order['hp_pengirim'] != '') echo 'Hp. ' . $order['hp_pengirim'] ?>

				</div>



				<div class="<?php echo $md ?> deskripsi">

					<div class="pull-right">



						<h4 class="judulmodul text-right">



							Alamat Tujuan

						</h4>

						<div class="text-right">

							<b><?php echo stripslashes(ucwords(strtolower($order['nama_penerima']))) ?></b><br>

							<?php echo $order['alamat_penerima'] ?><br>

							<?php if ($order['kelurahan_penerima']) { ?>

							, Kel. <?php echo $order['kelurahan_penerima'] ?> <br>

							<?php } ?>

							Kec. <?php echo $order['kecamatannm_penerima'] ?>,<?php echo $order['kotanm_penerima'] ?><br>

							<?php echo $order['propinsinm_penerima'] ?>,

							<?php echo $order['negaranm_penerima'] ?> <?php echo $order['kodepos_penerima'] ?><br>

							<?php if ($order['hp_penerima'] != '') echo 'Hp. ' . $order['hp_penerima'] ?>

						</div>

					</div>

				</div>

				<div class="clearfix"></div>

				<?php if ($dataset['config_orderstatus'] == $order['status_id']) { ?>

				<div class="col-md-12">

					<div class="row">



						<div id="platcari" class="alert alert-info">

							<input type="text" id="cariproduk" name="cariproduk" placeholder="Cari Nama produk yang ingin di tambah" class="form-control input-lg" autocomplete="off" onKeyPress="return disableEnterKey(event)">

						</div>

					</div>

				</div>

				<?php } ?>

				<table class="table table-bordered table-striped tabel-grid">

					<thead>

						<tr>

							<td class="text-left">Produk</td>

							<td class="text-right">Jumlah</td>

							<td class="text-right">Berat</td>

							<td class="text-right">Harga Normal</td>

							<td class="text-center">Diskon</td>

							<td class="text-right">Harga</td>

							<td class="text-right" width="200px">Total</td>

						</tr>

					</thead>

					<tbody>

						<?php

						$totberat = 0;

						$totpoin = 0;



						foreach ($datadetail as $dt) {

							$totberat = $totberat + $dt['berat'];

							$dt['poin'] = isset($dt['poin']) && $dt['poin'] != null && $dt['poin'] != '' ? $dt['poin'] : '0';

							$totpoin = $totpoin + ((int) $dt['poin'] * $dt['jml']);

							$harga_tambahan = $dt['harga_tambahan'];

							$diskon = ($dt['satuan'] + $harga_tambahan) - $dt['harga'];



							$harga_normal = $dtFungsi->fFormatuang($dt['satuan']);

							if ($harga_tambahan) {

								$harga_normal .= '<br><small> + ' . $dtFungsi->fFormatuang($harga_tambahan) . ' <br>(tambahan harga) </small>';

							}

							$persendiskon = ($diskon / ($dt['satuan'] + $harga_tambahan)) * 100;

							$datapoin = $dtFungsi->fcaridata('_produk', 'poin', 'idproduk', $dt['produk_id']);

							$datapoin = $datapoin == '' && $datapoin == null ? '0' : $datapoin;

							?>

						<tr>



							<td class="text-left">

								<b><?php echo $dt['nama_produk'] ?></b><br>

								<?php if ($dt['ukuran'] != '') { ?>

								<div class="label label-default"><?php echo 'Ukuran : ' . $dt['ukuran']; ?></div>

								<?php } ?>

								<?php if ($dt['warna'] != '') { ?>

								<div class="label label-default"><?php echo 'Warna  : ' . $dt['warna']; ?></div><br>

								<?php } ?>

								<?php if ($dt['get_poin'] > 0) { ?>

								<small>Poin : <?php echo $dt['get_poin'] ?> x <?php echo $dt['jml'] ?> pcs = <?php echo $dt['jml'] * $dt['get_poin'] ?> Poin</small>

								<?php } ?>



								<?php if (in_array($order['status_id'], $dataset['config_editorder'])) { ?>

								<br><br>

								<div>

									<a class="btn btn-sm btn-danger" onclick="delProduk('<?php echo $iddata ?>','<?php echo $dt['iddetail'] ?>','<?php echo $dt['produk_id'] ?>','<?php echo addslashes($dt['nama_produk']) ?>','<?php echo $dt['warnaid'] ?>','<?php echo $dt['ukuranid'] ?>','<?php echo $dt['jml'] ?>','<?php echo $order['grup_member'] ?>','<?php echo $order['pelanggan_id'] ?>','<?php echo $dt['warna'] ?>','<?php echo $dt['ukuran'] ?>','<?php echo $order['pesanan_subtotal'] ?>','<?php echo $datapoin ?>')"><span class="glyphicon glyphicon-trash"></span></a>

									<a class="btn btn-sm btn-primary" onclick="editProduk('<?php echo $iddata ?>','<?php echo $dt['iddetail'] ?>','<?php echo $dt['produk_id'] ?>','<?php echo addslashes($dt['nama_produk']) ?>','<?php echo $dt['warnaid'] ?>','<?php echo $dt['warna'] ?>','<?php echo $dt['ukuranid'] ?>','<?php echo $dt['ukuran'] ?>','<?php echo $dt['jml'] ?>','<?php echo $order['grup_member'] ?>','<?php echo $order['pelanggan_id'] ?>','<?php echo $datapoin ?>')"><span class="glyphicon glyphicon-pencil"></span></a>

								</div>

								<?php } ?>

							</td>

							<td class="text-right"><?php echo $dt['jml'] ?></td>

							<td class="text-right"><?php echo $dt['berat'] ?> Gram</td>

							<td class="text-right"><?php echo $harga_normal ?></td>

							<td class="text-center"><?php echo $persendiskon ?> %</td>

							<td class="text-right"><?php echo $dtFungsi->fFormatuang($dt['harga']) ?><input type="hidden" name="iddetail[]" value="<?php echo $dt['iddetail'] ?>"></td>

							<td class="text-right"><?php echo $dtFungsi->fFormatuang(((int) $dt['jml']) * (int) $dt['harga']) ?></td>

						</tr>

						<?php } ?>

						<tr>

							<td colspan="7" class="text-center"><b>TOTAL BERAT </b><?php echo $totberat . ' Gram / ' . ($totberat / 1000) . ' Kg' ?> </td>

						</tr>

						<tr>

							<td colspan="7">

								<?php

								if ($order['status_id'] == $config_ordercancel && $order['status_id'] == $config_shippingstatus) {

									$readonly = 'disabled';

								} else {

									$readonly = '';

								}

								?>

								<?php

								if ($dataset['config_shippingstatus'] == $order['status_id']) {

									$disabled = 'disabled';

								} else {

									$disabled = '';

								}

								?>

								<label>Keterangan Tambahan </label>

								<div class="col-md-12">

									<div class="row">

										<input type="checkbox" <?php echo $disabled ?> name="tampilketeranganlabel" id="tampilketeranganlabel" value="1" <?php echo $order['tampil_label_keterangan'] == '1' ? 'checked' : '' ?>> Tampil di Label Alamat ?

									</div>

								</div>



								<div class="input-group">

									<input type="text" id="keterangan" <?php echo $disabled ?> class="form-control" value="<?php echo $order['keterangan'] ?>">



									<span class="input-group-btn">

										<button class="btn btn-primary" id="updateketerangan" <?php echo $disabled ?> type="button">Update Keterangan</button>

									</span>



								</div><!-- /input-group -->



								<cite class="small">Misal : No Resi dari Marketplace</cite>

								<?php

								?>

							</td>

						</tr>

						<tr>

							<td colspan="6" class="text-right"><b>Subtotal</b></td>

							<td class="text-right"><?php echo $dtFungsi->fFormatuang($order['pesanan_subtotal']) ?>

								<input type="hidden" id="subtotal" value="<?php echo $order['pesanan_subtotal'] ?>">

							</td>

						</tr>

						<tr>

							<td colspan="6" class="text-right">

								<?php if (in_array($order['status_id'], $dataset['config_editorder'])) { ?>

								<a onclick="editKurir('<?php echo $order['pesanan_no'] ?>','<?php echo $totberat ?>');" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-pencil"></span> Edit</a>

								<?php } ?>

								<b><?php echo $order['kurir'] . ' - ' . $order['servis_code'] ?></b>



							</td>

							<td class="text-right"> <?php echo $order['kurir_konfirm'] == '1' && $order['pesanan_kurir'] < 0 ? 'Konfirmasi Admin' : $dtFungsi->fFormatuang($order['pesanan_kurir']) ?>

								<input type="hidden" name="zhrgkurir" id="zhrgkurir" value="<?php echo $order['pesanan_kurir'] ?>">

								<input type="hidden" name="totpoin" id="totpoin" value="<?php echo $totpoin ?>">

								<input type="hidden" name="totberat" id="totberat" value="<?php echo $totberat ?>">

							</td>

						</tr>

						<tr>

							<td colspan="6" class="text-right"><b>POTONGAN POIN</b></td>

							<td class="text-right">(<?php echo $dtFungsi->fFormatuang($order['dari_poin']) ?> )

								<input type="hidden" name="potpoinlama" id="potpoinlama" value="<?php echo $order['dari_poin'] ?>">

							</td>

						</tr>



						<tr>

							<td colspan="6" class="text-right">

								<?php if (in_array($order['status_id'], $dataset['config_editorder'])) { ?>

								<a onclick="editDeposito();" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-pencil"></span> Edit</a>

								<?php } ?>

								<b>POTONGAN DARI SALDO</b></td>

							<td class="text-right">(<?php echo $dtFungsi->fFormatuang($order['dari_deposito']) ?> )

								<input type="hidden" name="potdepositolama" id="potdepositolama" value="<?php echo $order['dari_deposito'] ?>">

							</td>

						</tr>



						<tr>



							<td colspan="6" class="text-right"><b>TOTAL</b></td>

							<td class="text-right">

								<?php if ($order['pesanan_kurir'] < 0) $order['pesanan_kurir'] = 0 ?>

								<?php $grandtotal = ((int) $order['pesanan_kurir'] + (int) $order['pesanan_subtotal']) - (int) $order['dari_poin'] - (int) $order['dari_deposito']; ?>

								<input type="hidden" name="grandtotal" id="grandtotal" value="<?php echo $grandtotal ?>">

								<b><?php echo $dtFungsi->fFormatuang($grandtotal) ?></b></td>

						</tr>

					</tbody>

				</table>



				<div class="col-md-12">

					<a href="<?php echo URL_PROGRAM_ADMIN . 'order/?u_token=' . $u_token ?>" class="btn btn-primary btn-block">Kembali</a>

				</div>

				<div class="clearfix"></div>

			</form>

		</div>

	</div>

</div>



<script>

	var action = $('#frmdata').prop("action");

	var wil = [];

	var tarifwil = [];

	var nilaitarifwil = [];

	var nopesan = $('#iddata').val();



	var stsnow = $('#stsnow').val();

	var namaservis = '<?php echo $order['servis_code'] . ':' . $order['kurir'] ?>';

	var stsshipping = $('#stsshipping').val();

	var stsdone = $('#stsdone').val(); // aar 22-06-2020

	var stssudahbayar = $('#stssudahbayar').val();

	var stskonfirm = $('#stskonfirm').val();

	var tglshipping = $('#tglkirimshipping').val();

	var awbshipping = $('#awbshipping').val();

	var stsgetpoin = $('#stsgetpoin').val();

	var stscancel = $('#stscancel').val();

	var pelangganid = $('#pelangganid').val();

	var grppelanggan = $('#grppelanggan').val();

	var totpoin = $('#totpoin').val();

	var grandtotal = $('#grandtotal').val();

	$(function() {



		$('#btnEditStatus').click(function() {

			var url = action + '?modul=frmEditStatus&u_token=<?php echo $u_token ?>';

			var zdata = nopesan + ',' + stsnow + ',' + stsshipping + ',' + stskonfirm + ',' + namaservis + ',' + tglshipping + ',' + awbshipping + ',' + stsgetpoin + ',' + pelangganid + ',' + totpoin + ',' + stssudahbayar + ',' + grandtotal + ',' + stscancel + ',' + stsdone;



			$("#loadingweb").fadeIn();

			tampilform(url, zdata);

			return false;

		});



		$('#btnalamatpenerima').click(function() {



			formAlamat('alamatpenerima');

			return false;

		});



		$('#updateketerangan').click(function() {

			updateketerangan();

			return false;

		});

		$('#btnalamatpengirim').click(function() {

			formAlamat('alamatpengirim');

			return false;

		});

		$('#cariproduk').autocomplete({

			delay: 0,

			source: function(request, response) {

				$.ajax({

					url: action + '?u_token=<?php echo $u_token ?>',

					dataType: "json",

					data: {

						modul: 'cariproduk',

						grupreseller: $('#grppelanggan').val(),

						cariproduk: request.term

					},

					success: function(data) {

						//alert(data);

						response($.map(data, function(item) {

							return {

								label: item.kode + ' :: ' + item.nama_produk,

								value: item.name,

								kode: item.kode,

								nama: item.nama_produk,

								id: item.product_id,

								ukuran: item.ukuran,

								berat: item.berat,

								hrgsatuan: item.satuan,

								diskon_satuan: item.diskon_satuan,

								stok: item.stok,

								minbeli: item.min_beli,

								minbelisyarat: item.min_beli_syarat,

								diskon_member: item.diskon_member,

								grup_nama: item.grup_nama

							}

						}));

					},

					error: function(e) {

						alert('Error: ' + e);

					}

				});

			},

			minLength: 1,

			select: function(event, ui) {

				var url = action + '?modul=frmAddProduk&u_token=<?php echo $u_token ?>';

				var ukuran = '';

				for (j = 0; j < ui.item.ukuran.length; j++) {

					ukuran += '<option value="' + ui.item.ukuran[j]['id'] + '">' + ui.item.ukuran[j]['alias'] + '</option>';

				}

				var zdata = ui.item.id + ":" + ui.item.kode + ":" +

					ui.item.nama + ":" + ukuran + ":" +

					ui.item.berat + ":" + ui.item.hrgsatuan + ":" +

					ui.item.diskon_satuan + ":" +

					ui.item.stok + ":" +

					ui.item.minbeli + ':' +

					ui.item.diskon_member + ':' +

					nopesan + ':' + grppelanggan + ':' +

					pelangganid + ':editorder:' +

					ui.item.grup_nama + ':' +

					ui.item.minbelisyarat;



				$("#loadingweb").fadeIn();

				tampilform(url, zdata);

				return false;

			},

			focus: function(event, ui) {

				return false;

			}

		});

		/* @end pencarian produk */

	});



	function formAlamat(jenis) {

		var url = action + '?modul=frmAlamat&u_token=<?php echo $u_token ?>';

		var totberat = $('#totberat').val();

		var grup_dropship = $('#grup_dropship').val();

		var caption_alamat;



		if (jenis == 'alamatpengirim') {

			caption_alamat = 'Pengirim';

		} else {

			caption_alamat = 'Penerima/Tujuan';

		}



		var zdata = nopesan + '::' + pelangganid + '::' + grppelanggan + '::' + jenis + '::' + caption_alamat + '::' + totberat + '::editorder';



		$("#loadingweb").fadeIn();

		if (grup_dropship == '0') {

			alert('Pelanggan tidak memiliki fasilitas dropship');

			$("#loadingweb").fadeOut();

			return false;

		}

		tampilform(url, zdata);

	}



	function editProduk(nopesan, iddetail, produkid, produknm, idwarna, warna, idukuran, ukuran, qty, idgrup, idmember, poin) {

		var url = action + '?modul=frmEditProduk&u_token=<?php echo $u_token ?>';



		var zdata = nopesan + '::' + iddetail + '::' + produkid + '::' + produknm + '::' + idwarna + '::' + warna + '::' + idukuran + '::' + ukuran + '::' + qty + '::' + idgrup + '::' + idmember + "::" + poin;



		$("#loadingweb").fadeIn();

		tampilform(url, zdata);

		return false;

	}



	function delProduk(nopesan, iddetail, produkid, produknm, warna, ukuran, qty, idgrup, idmember, nmwarna, nmukuran, subtotal, poin) {

		var url = action + '?modul=frmDelProduk&u_token=<?php echo $u_token ?>';

		var jmlproduk = $('#jmlprodukorder').val();

		var zdata = nopesan + '::' + iddetail + '::' + produkid + '::' + produknm + '::' + warna + '::' + ukuran + '::' + qty + '::' + idgrup + '::' + idmember + '::' + nmwarna + '::' + nmukuran + '::' + subtotal + '::' + poin + '::' + jmlproduk;

		$("#loadingweb").fadeIn();

		tampilform(url, zdata);

		return false;

	}



	function editKurir(nopesan, berat) {

		var url = action + '?modul=frmEditKurir&u_token=<?php echo $u_token ?>';

		var data = nopesan + '::' + berat;

		$("#loadingweb").fadeIn();

		tampilform(url, data);

		return false;

	}



	function editDeposito() {

		var url = action + '?modul=frmEditDeposito&u_token=<?php echo $u_token ?>';

		var pelanggan_id = $('#pelangganid').val();

		var pesanan_no = $('#iddata').val();

		var grup_pelanggan = $('#grppelanggan').val();

		var data = pesanan_no + '::' + pelanggan_id + '::' + grup_pelanggan;

		$("#loadingweb").fadeIn();

		tampilform(url, data);

		return false;

	}



	function tampilform(url, zdata) {

		$.post(url, {

			stsload: "load",

			data: zdata

		}, function(data) {

			//alert(data);

			$("#loadingweb").fadeOut();

			$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function() {

				$(this).remove();

			});

		});

	}



	function simpanstatus() {

		var orderstatus = $('#orderstatus').val();

		var keterangan = $('#keterangan').val();



		var aksi = $('#aksi').val();

		var nopesanan = $('#nopesanan').val();

		var urlfolder = $('#urlfolder').val();

		var stsnow = $('#stsnow').val();

		var frm = $('#frmeditstatus').serialize();

		

		$('#btnsimpanstatus').button("loading");

		frm = frm + '&tglorder=' + $("#tglorder").val() + "&nmpelanggan=" + $("#nmpelanggan").val() + "&nmgrppelanggan=" + $("#nmgrppelanggan").val() + "&nmstatus=" + $("#nmstatus").val() + "&alamatreport=" + $("#alamatreport").val();





		$.post(action + '?u_token=<?php echo $u_token ?>', frm, function(xdata) {



			if (xdata['status'] == 'success') {

				location = urlfolder;

			} else {

				$('#hasileditstatus').removeClass();

				$('#hasileditstatus').addClass('alert alert-danger');

				if (xdata['result'].length > 0) {

					$('#hasileditstatus').show();

					$('#hasileditstatus').html(xdata['result']);

				}

			}



		}, 'json');



	}



	function hapusprodukorder() {

		var datanya = "product_id=" + $('#didproduk').val() +

			'&idmember=' + $('#didmember').val();

		datanya += '&idukuran=' + $('#dukuran').val() + '&idwarna=' + $('#dwarna').val() + '&idgrup=' + $('#didgrup').val();

		datanya += "&nopesanan=" + $('#dnopesan').val() + '&qty=' + $('#qty').val();

		datanya += "&iddetail=" + $('#diddetail').val() + "&tarifkurir=" + $("#zhrgkurir").val() + "&dari_poin=" + $("#potpoinlama").val() + "&dari_deposito=" + $("#potdepositolama").val();

		datanya += "&total=" + $('#dtotlama').val();

		datanya += "&jmlproduk=" + $('#jmlprodukorder').val();

		var redirectview = $("#redirectview").val();

		var redirectedit = $("#redirectedit").val();

		var redirect = '';



		$('#loadingweb').show(500);

		$('#btndelproduk').button('loading');

		$.ajax({

			type: "POST",

			url: action + "/?modul=hapusorderproduk&u_token=<?php echo $u_token ?>",

			data: datanya,

			cache: false,

			dataType: 'json',

			success: function(msg) {



				$('#loadingweb').hide(500);



				if ($.trim(msg['status']) == "error") {

					$('#hasildelprod').addClass("alert alert-danger");

					$('#btndelproduk').button('reset');



				} else {

					$('#hasildelprod').addClass("alert alert-success");



					if ($.trim(msg['delall']) == '0') {

						redirect = redirectedit;

					} else {

						redirect = redirectview;

					}

					location = redirect;

				}

				$('#hasildelprod').html(msg['result']);

				$('#hasildelprod').show(0);

				return false;



			},

			error: function(e) {

				alert('Error: ' + e);

			}

		});



	}



	function pilihwarna(ukuran, warna, idproduk) {

		$('#' + warna).hide();

		$('#loadingweb').show(500);

		var dataload = '&ukuran=' + ukuran + '&idproduk=' + $('#' + idproduk).val() + '&idtxtwarna=' + warna;



		$('#' + warna).load(action + '/?modul=cariwarna&u_token=<?php echo $u_token ?>' + dataload, function(responseTxt, statusTxt, xhr) {



			if (statusTxt == "success") {



				$('#' + warna).show();

				$('.loading').remove();

				$('#loadingweb').hide(500);



			}

		});



	}



	function cetakInvoice() {

		var frm = $('#frmdata').serialize();

		$('#btncetak').button("loading");





		$.post(action + '?modul=cetak', frm, function(xdata) {

			data = xdata.split("|");

			if ($.trim(data[0]) == 'sukses') {

				location = urlfolder;

			} else {

				$('#hasileditstatus').removeClass();

				$('#hasileditstatus').addClass('alert alert-danger');

				if (data[1].length > 0) {

					$('#hasileditstatus').show();

					$('#hasileditstatus').html(data[1]);

				}



			}



		});

	}



	function simpaneditkurir() {

		$('#btnsimpankurir').button("loading");

		var tarif = $('#tarifkurir').val();

		var redirect = $('#redirectedit').val();

		var potdepositolama = $('#potdepositolama').val();

		var pelanggan_id = $('#pelangganid').val();

		var frm = $('#frmeditkurir').serialize() + '&jmldeposit=' + potdepositolama + '&pelanggan_id=' + pelanggan_id;

		var url = $('#frmdata').prop("action") + '?modul=simpaneditkurir&u_token=<?php echo $u_token ?>';

		alert(frm);

		$('#hasileditkurir').removeClass();

		$('#hasileditkurir').hide();

		//alert(frm);



		if (tarif == '') {

			$('#hasileditkurir').addClass("alert alert-danger");

			$('#hasileditkurir').html('Masukkan Tarif Kurir');

			$('#hasileditkurir').show();

			$('#btnsimpankurir').button("reset");

			return false;

		}



		$.ajax({

			type: "POST",

			url: url,

			data: frm,

			dataType: 'json',

			success: function(json) {



				if (json['status'] == "error") {

					$('#hasileditkurir').addClass("alert alert-danger");

					$('#btnsimpankurir').button("reset");

				} else {

					$('#hasileditkurir').addClass("alert alert-success");

					location.href = redirect;

				}

				$('#hasileditkurir').html(json['result']);

				$('#hasileditkurir').show(0);

				return false;

			},

			error: function(e) {

				alert('Error: ' + e);

			}

		});



	}



	function simpaneditdeposito() {

		var pesanan_no = $('#dnopesanan').val();

		var jmldeposit = parseInt($('#potongandeposit').val());

		var pelanggan = $('#didmember').val();

		var potdepositolama = $('#potdepositolama').val();

		var tarifkurir = $('#zhrgkurir').val();

		var subtotal = $('#subtotal').val();

		$('#btnsimpandeposito').button("loading");

		if (jmldeposit < 1) {

			$('#hasileditdeposito').addClass("alert alert-danger");

			$('#hasileditdeposito').html('Masukkan jumlah potongan dari deposit');

			$('#hasileditdeposito').show(0);

			$('#potongandeposit').focus();

			$('.loading').remove();

			$('#btnsimpandeposito').button("reset");

			return false;

		}

		var datanya = "pesanan_no=" + pesanan_no + "&jmldeposit=" + jmldeposit + "&pelanggan_id=" + pelanggan + "&potongandepositold=" + potdepositolama;

		datanya += "&tarifkurir=" + tarifkurir + "&subtotal=" + subtotal;

		var url = $('#frmdata').prop("action") + '?modul=simpaneditpotongandeposit&u_token=<?php echo $u_token ?>';

		//alert(url+datanya);

		//return false;

		$.ajax({

			type: "POST",

			url: url,

			data: datanya,

			dataType: 'json',

			success: function(json) {



				if (json['status'] == "error") {

					$('#hasileditdeposito').addClass("alert alert-danger");

					$('#btnsimpandeposito').button("reset");

				} else {

					$('#hasileditdeposito').addClass("alert alert-success");

					//location.href = redirect;

					location.reload();

				}

				$('#hasileditdeposito').html(json['result']);

				$('#hasileditdeposito').show(0);

				return false;

			},

			error: function(e) {

				alert('Error: ' + e);

			}

		});



	}



	function cektarifkurir() {

		var frm = $('#frmeditkurir').serialize();

		var url = $('#frmdata').prop("action") + '?modul=tarifkurir&u_token=<?php echo $u_token ?>';

		var servis = $('#serviskurir').val();



		var tarif;

		var nilaitarif;

		var datawil = $('#kecamatan_penerima').val() + ',' + servis;

		var cekwil = wil.indexOf(datawil);

		var totaltarif;



		if (cekwil < 0) {



			wil.push(datawil);

			$.ajax({

				type: "POST",

				url: url,

				data: frm,

				dataType: 'json',

				success: function(json) {



					if (json['status'] == 'error') {

						alert(json['result']);

					} else {



						tarifwil[datawil] = json['tarif'];

						nilaitarifwil[datawil] = json['nilaitarif'];



						tarif = json['tarif'];



						$('#tarifkurir').val(json['nilaitarif']);

						if (json['nilaitarif'] == '0') {

							$('#tarifkurir').attr("readonly", false);

							$('#tarifkurir').focus();

						} else {

							$('#tarifkurir').attr("readonly", true);

						}



					}

				},

				error: function(e) {

					alert('Error: ' + e);

				}

			});



		} else {



			tarif = tarifwil[datawil];

			nilaitarif = nilaitarifwil[datawil];



			/* $('#tarif').html(tarif); */

			$('#tarifkurir').val(nilaitarif);

			if (tarif == 'Konfirmasi Admin') {

				$('#tarifkurir').attr("readonly", false);

				$('#tarifkurir').focus();

			} else {

				$('#tarifkurir').attr("readonly", true);

			}



		}



	}



	function updateketerangan() {



		if ($('#tampilketeranganlabel').prop("checked") == true) {

			tampilketerangan = '1';

		} else {

			tampilketerangan = '0';

		}



		var data = 'keterangan=' + encodeURIComponent($.trim($('#keterangan').val())) + '&tampilketerangan=' + tampilketerangan + '&pesanan_no=' + $('#iddata').val();

		var url = $('#frmdata').prop("action") + '?modul=updateketeranganorder&u_token=<?php echo $u_token ?>';





		$.ajax({

			type: "POST",

			url: url,

			data: data,

			dataType: 'json',

			success: function(msg) {





				$('#loadingweb').hide(500);



				if ($.trim(msg['status']) == "error") {

					$('#hasilketerangan').addClass("alert alert-danger");

					//$('#btnsimpanaddproduk').button('reset');

					$('#hasilketerangan').show();

				} else {

					$('#hasilketerangan').addClass("alert alert-success");





					location.reload();



				}

				$('#hasilketerangan').html(msg['result']);

				$('#hasilketerangan').show(0);



				return false;

			},

			error: function(e) {

				alert('Error: ' + e);

			}

		});

	}



	function simpaneditproduk() {

		var frm = $('#frmeditproduk').serialize();

		var url = $('#frmdata').prop("action") + '?modul=editorderproduk&u_token=<?php echo $u_token ?>';

		var jml = parseInt($('#qty').val());

		var redirect = $("#redirectedit").val();



		$('#btnsimpanprod').button('loading');

		$('#hasiladdprod').removeClass();

		$('#hasiladdprod').hide();

		if (jml == '' || jml < 1 || !Number.isInteger(jml)) {

			$('#hasiladdprod').addClass("alert alert-danger");

			$('#hasiladdprod').html('Masukkan Jumlah');

			$('#hasiladdprod').show();

			$('#btnsimpanprod').button('reset');

			return false;

		}



		$.ajax({

			type: "POST",

			url: url,

			data: frm,

			dataType: 'json',

			success: function(msg) {



				$('#loadingweb').hide(500);



				if ($.trim(msg['status']) == "error") {

					$('#hasiladdprod').addClass("alert alert-danger");

					$('#btnsimpanprod').button('reset');



				} else {

					$('#hasiladdprod').addClass("alert alert-success");



					location = redirect;

				}

				$('#hasiladdprod').html(msg['result']);

				$('#hasiladdprod').show(0);

				return false;

			},

			error: function(e) {

				alert('Error: ' + e);

			}

		});

	}

</script>