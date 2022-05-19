<div class="col-lg-12 main-content">

	<h2 class="judulmodul"><?php echo $judul ?></h2>

	<div class="widget-box">

		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span>

			<h5>FORM <?php echo strtoupper($judul) ?></h5>

		</div>

		<div class="widget-content nopadding">

			<form method="POST" autocomplete="off" name="frmdata" id="frmdata" action="<?php echo $_SERVER['PHP_SELF'] . '?u_token=' . $u_token ?>">

				<input type="hidden" name="aksi" id="aksi" value="<?php echo $modul ?>">

				<input type="hidden" name="status_order" id="status_order" value="<?php echo $dataset['config_orderstatus'] ?>">

				<input type="hidden" name="user_kasir" id="user_kasir" value="<?php echo $_SESSION["idlogin"] ?>">

				<input type="hidden" name="gruppelanggan" id="gruppelanggan">

				<input type="hidden" name="grup_dropship" id="grup_dropship">

				<input type='hidden' name='biaya_packing' id='biaya_packing' value='<?= $dataset["config_biayapacking"] ?>' >

				<input type='hidden' name="cg_biaya_packing" id="cg_biaya_packing">

				<input type='hidden' name="cust_tanpa_biaya_packing" id="cust_tanpa_biaya_packing">

				<input type="hidden" name="idmember" id="idmember">

				<input type="hidden" name="status_valid" id="status_valid">



				<div class="well plat-order">

					<div class="col-md-2">

						<b>Kasir</b>

					</div>

					<div class="col-md-3">

						<?php echo $_SESSION["userlogin"] ?>

					</div>

					<div class="col-md-1"></div>

					<div class="col-md-2">

						<b>Cari Pelanggan</b>

					</div>

					<div class="col-md-3">

						<div class="row">

							<input type="text" autocomplete="off" placeholder="Cari Pelanggan.." class="form-control" id="pelanggan" name="pelanggan" onKeyPress="return disableEnterKey(event)">



						</div>

					</div>



					<div class="col-md-2">

						<b>Tanggal Order</b>

					</div>

					<div class="col-md-3">

						<?php echo date('d-m-Y') ?>

					</div>

					<div class="col-md-1"></div>

					<div class="col-md-2">

						<b>Nama Pelanggan</b>

					</div>

					<div class="col-md-3">

						<div class="row">

							<input type="text" class="form-control" readonly id="nama_pelanggan" value="">



						</div>

					</div>

					<div class="col-md-1">

						<!--<button id="addcust" class="btn btn-default"><i class="icon-plus"></i></button>-->

					</div>

					<div class="clearfix"></div>

				</div>

				<div id="hasil" style="display: none;"></div>

				<div role="tabpanel">

					<!-- Nav tabs -->

					<ul class="nav nav-tabs" role="tablist" id="taborder">

						<li role="presentation" class="active" style="display:none"><a href="#tabproduk" aria-controls="tabketerangan" role="tab" data-toggle="tab">Produk</a></li>

						<li role="presentation" style="display:none"></li>

					</ul>

					<!-- @end Nav tabs -->

					<!-- Tab panes -->

					<div class="tab-content">

						<div role="tabpanel" class="tab-pane active" id="tabproduk">

							<div class="well">

								<div class="col-md-12">

									<div class="row">

										<div id="platcari" class="alert alert-info">

											<input onKeyPress="return disableEnterKey(event)" type="text" id="cariproduk" disabled name="cariproduk" placeholder="Cari Nama produk yang ingin di tambah" class="form-control input-lg" autocomplete="off">

										</div>

									</div>

								</div>

								<div class="clearfix"></div>

								<div id="hasillistcart"></div>

								<table class="table table-bordered table-striped tabel-grid">

									<thead>

										<tr>

											<td class="text-center" width="3%"></td>

											<td class="text-left">Produk</td>

											<td class="text-right" width="5%">Jumlah</td>

											<td class="text-right" width="6%">Berat</td>

											<td class="text-right" width="15%">Harga Normal</td>

											<td class="text-center" width="5%">Diskon</td>

											<td class="text-right" width="15%">Harga</td>

											<td class="text-right" width="200px">Subtotal</td>

										</tr>

									</thead>

									<tbody id="listcart">

									</tbody>

								</table>



								<div class="text-right">

									<a href="<?php echo URL_PROGRAM_ADMIN . folder . '?u_token=' . $u_token ?>" class="btn btn-lg btn-warning">Batal</a>

									<a href="#tabalamat" aria-controls="tabalamat" role="tab" data-toggle="tab" id="btnnextalamat" class="btn btn-lg btn-info">Lanjut <i class="icon-arrow-right"></i></a>

								</div>

							</div>

						</div>

						<div role="tabpanel" class="tab-pane" id="tabalamat">

							<div class="well">



								<div class="col-md-9">

									<div class="plat_alamat" id="platalamat_pengirim" style="display:none">

										<h4>Alamat Pengirim</h4>

										<div id="deskripsi_alamat_pengirim"></div>

										<hr>

									</div>



									<div class="plat_alamat" id="platalamat_penerima">

										<h4>Alamat Tujuan / Penerima</h4>

										<div id="deskripsi_alamat_penerima"></div>

									</div>

									<input type="hidden" name="nama_penerima" id="nama_penerima" class="form-control">

									<input type="hidden" name="telp_penerima" id="telp_penerima" class="form-control">

									<input type="hidden" id="alamat_penerima" name="alamat_penerima">

									<input type="hidden" id="propinsi_penerima" name="propinsi_penerima">

									<input type="hidden" id="kabupaten_penerima" name="kabupaten_penerima">

									<input type="hidden" id="kecamatan_penerima" name="kecamatan_penerima">

									<input type="hidden" name="kelurahan_penerima" id="kelurahan_penerima" class="form-control">

									<input type="hidden" name="kodepos_penerima" id="kodepos_penerima" class="form-control">



									<input type="hidden" name="nama_pengirim" id="nama_pengirim" class="form-control">

									<input type="hidden" name="telp_pengirim" id="telp_pengirim" class="form-control">

									<input type="hidden" id="alamat_pengirim" name="alamat_pengirim">

									<input type="hidden" id="propinsi_pengirim" name="propinsi_pengirim">

									<input type="hidden" id="kabupaten_pengirim" name="kabupaten_pengirim">

									<input type="hidden" id="kecamatan_pengirim" name="kecamatan_pengirim">

									<input type="hidden" name="kelurahan_pengirim" id="kelurahan_pengirim" class="form-control">

									<input type="hidden" name="kodepos_pengirim" id="kodepos_pengirim" class="form-control">

									<div class="col-md-6">

										<div class="row">

											<div class="form-group">

												<label>Servis</label>

												<select id="serviskurir" name="serviskurir" class="form-control">

													<option value='0'>- Pilih Kurir -

													<option>

												</select>

											</div>

										</div>

									</div>

									<div class="clearfix"></div>

									<div class="col-md-12">

										<div class="row">

											<ul class="list-group">

												<li class="list-group-item">

													<div class="col-md-6">Subtotal</div>

													<div class="col-md-6 text-right" id="caption_subtotal">0</div>

													<div class="clearfix"></div>

												</li>

												<li class="list-group-item">

													<div class="col-md-6">Tarif Kurir</div>

													<div class="col-md-6 text-right" id="caption_kurir">-</div>

													<div class="clearfix"></div>

												</li>

												<li class="list-group-item" id="caption_biayapacking" style="display:none">

													<div class="col-md-6">Biaya Packing</div>

													<div class="col-md-6 text-right"><?php echo $dataset["config_biayapacking"] ?></div>

													<div class="clearfix"></div>

												</li>

												<li class="list-group-item">

													<div class="col-md-6">Total</div>

													<div class="col-md-6 text-right" id="caption_total">-</div>

													<div class="clearfix"></div>

												</li>



											</ul>

										</div>

									</div>

								</div>

								<div class="col-md-3">

									<button id="btnalamatpengirim" type="button" class="btn btn-sm btn-default" onclick="formAlamat('alamatpengirim')"><i class="icon-pencil"></i> Alamat Pengirim</button>

									<button id="btnalamatpenerima" type="button" class="btn btn-sm btn-default" onclick="formAlamat('alamatpenerima')"><i class="icon-pencil"></i> Alamat Penerima</button>

								</div>

								<div class="clearfix"></div>

								<div class="col-md-6">

									<a href="#tabproduk" aria-controls="tabproduk" role="tab" data-toggle="tab" id="btnproduk" class="btn btn-lg btn-info">Kembali <i class="icon-arrow-left"></i></a>

								</div>

								<div class="col-md-6 text-right">

									<a href="#tabmsgorder" aria-controls="tabmsgorder" role="tab" data-toggle="tab" id="btnsimpan" class="btn btn-lg btn-info">Lanjut <i class="icon-arrow-right"></i></a>

								</div>

								<div class="clearfix"></div>

							</div>

						</div>

						<div role="tabpanel" class="tab-pane" id="tabmsgorder">

							<div class="well">

								<div id="msgorder"></div>

								<a href="<?php echo URL_PROGRAM_ADMIN . folder ?>?op=add&u_token=<?php echo $u_token ?>" class="btn btn-lg btn-warning">Order Baru</a>

							</div>

						</div>

					</div>

					<!-- @end Tab panes -->

				</div>

			</form>

		</div>

	</div>

</div>

<script>

	var action = $('#frmdata').prop("action");

	var biaya_packing = '0';

	var wil = [];

	var tarifwil = [];

	var nilaitarifwil = [];

	var totalwil = [];

	$(function() {

		biaya_packing = $('#biaya_packing').val();

		$('#pelanggan').val("");

		$('#pelanggan').focus();



		$('#idmember').change(function() {

			if ($('#idmember').val() != '' && $('#idmember').val() != '0') {

				$('#cariproduk').attr("disabled", false);

			}

			return false;

		});

		$('#serviskurir').change(function() {

			tarifkurir();

			return false;

		});

		$('#btnnextalamat').click(function(e) {

			e.preventDefault();

			checkCart();

			return false;





		});

		$('#btnsimpan').click(function(e) {

			e.preventDefault();

			simpanorder();

			return false;





		});

		$('#btnproduk').click(function(e) {

			e.preventDefault();

			$(this).tab("show");

			return false;



		});

		$('#cariproduk').autocomplete({

			delay: 0,

			source: function(request, response) {

				$.ajax({

					url: action,

					dataType: "json",

					data: {

						modul: 'cariproduk',

						grupreseller: $('#gruppelanggan').val(),

						cariproduk: request.term

					},

					success: function(data) {



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

								diskon_member: item.diskon_member,

								minbelisyarat: item.min_beli_syarat,

								grup_nama: item.grup_nama,

								grup_id: item.grup_id

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

				var url = action + '&modul=frmAddProduk';

				var ukuran = '';

				var idmember = $('#idmember').val();

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

					'' + ':' + ui.item.grup_id +

					':' + idmember + ':addorder:' +

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



		/* pencarian pelanggan */

		$('#pelanggan').autocomplete({

			delay: 0,

			source: function(request, response) {

				$.ajax({

					url: action,

					dataType: "json",

					data: {

						modul: 'caripelanggan',

						caripelanggan: request.term

					},

					success: function(data) {

						//alert(data);

						response($.map(data, function(item) {

							return {

								label: item.cust_nama + ' - ' + item.cust_telp,

								value: item.cust_id,

								cg_biaya_packing: item.biaya_packing,

								cust_tanpa_biaya_packing: item.tanpa_biaya_packing,

								alamat: item.cust_alamat,

								nama: item.cust_nama,

								grup: item.cust_grup_id,

								grup_nama: item.cg_nm,

								telp: item.cust_telp,

								alamat: item.cust_alamat,

								kelurahan: item.cust_kelurahan,

								kota: item.cust_kota,

								kota_nama: item.kabupaten_nama,

								propinsi: item.cust_propinsi,

								propinsi_nama: item.provinsi_nama,

								kecamatan: item.cust_kecamatan,

								kecamatan_nama: item.kecamatan_nama,

								kodepos: item.cust_kdpos,

								email: item.cust_email,

								status: item.cust_status,

								min_beli: item.cg_min_beli,

								min_beli_syarat: item.cg_min_beli_syarat,

								min_beli_wajib: item.cg_min_beli_wajib,

								deposito: item.cg_deposito,

								dropship: item.cg_dropship,

								diskon: item.cg_diskon

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

				$('#nama_pelanggan').val(ui.item.nama + ' (' + ui.item.grup_nama + ')');

				$('#idmember').val(ui.item.value);

				$('#cg_biaya_packing').val(ui.item.cg_biaya_packing);

				$('#cust_tanpa_biaya_packing').val(ui.item.cust_tanpa_biaya_packing);

				$('#gruppelanggan').val(ui.item.grup);

				$('#grup_dropship').val(ui.item.dropship);

				$('#pelanggan').val("");

				if ($('#idmember').val() != '' && $('#idmember').val() != '0') {

					$('#cariproduk').attr("disabled", false);

					$('#cariproduk').focus();

				}

				/* alamat penerima */

				var alamatpenerima = '<b>' + ui.item.nama + '</b><br>';

				alamatpenerima += ui.item.alamat + '<br>';

				alamatpenerima += ui.item.propinsi_nama + ',' + ui.item.kota_nama + ', ';

				alamatpenerima += ui.item.kecamatan_nama;

				if (ui.item.kelurahan != '') {

					alamatpenerima += ', ' + ui.item.kelurahan;

				}

				if (ui.item.kodepos != '') {

					alamatpenerima += ', ' + ui.item.kodepos;

				}

				alamatpenerima += '<br>No. Hp ' + ui.item.telp;



				$('#deskripsi_alamat_penerima').html(alamatpenerima);

				$('#nama_penerima').val(ui.item.nama);

				$('#nama_penerima_caption').html(ui.item.nama);

				$('#alamat_penerima').val(ui.item.alamat);



				$('#telp_penerima').val(ui.item.telp);

				$('#propinsi_penerima').val(ui.item.propinsi);

				$('#kabupaten_penerima').val(ui.item.kota);

				$('#kecamatan_penerima').val(ui.item.kecamatan);



				/*

				$('#propinsi_penerima').val(ui.item.propinsi).trigger("chosen:updated");

				kabupaten(ui.item.propinsi,ui.item.kota);

				kecamatan(ui.item.kota,ui.item.kecamatan);

				*/

				$('#kelurahan_penerima').val(ui.item.kelurahan);

				$('#kodepos_penerima').val(ui.item.kodepos);



				/* end alamat penerima */



				/* alamat pengirim */

				$('#nama_pengirim').val(ui.item.nama);

				$('#alamat_pengirim').val(ui.item.alamat);



				$('#telp_pengirim').val(ui.item.telp);

				$('#propinsi_pengirim').val(ui.item.propinsi);

				$('#kabupaten_pengirim').val(ui.item.kota);

				$('#kecamatan_pengirim').val(ui.item.kecamatan);

				$('#kelurahan_pengirim').val(ui.item.kelurahan);

				$('#kodepos_pengirim').val(ui.item.kodepos);



				var alamatpengirim = '<b>' + ui.item.nama + '</b><br>';

				alamatpengirim += ui.item.alamat + '<br>';

				alamatpengirim += ui.item.propinsi_nama + ',' + ui.item.kota_nama + ', ';

				alamatpengirim += ui.item.kecamatan_nama;

				if (ui.item.kelurahan != '') {

					alamatpengirim += ', ' + ui.item.kelurahan;

				}

				if (ui.item.kodepos != '') {

					alamatpengirim += ', ' + ui.item.kodepos;

				}

				alamatpengirim += '<br>No. Hp ' + ui.item.telp;

				$('#deskripsi_alamat_pengirim').html(alamatpengirim);

				/* end alamat pengirim */



				return false;

			},

			focus: function(event, ui) {

				return false;

			}

		});

		/* @end pencarian pelanggan */

	});



	function listcart(idelement, hitung = '') {

		var datalist = {

			modul: 'listcart',

			tipemember: $('#gruppelanggan').val(),

			idmember: $('#idmember').val(),

			hitung: hitung

		};



		$.getJSON(action, datalist, function(data) {



			$('#' + idelement).html(data['html']);

			$('#hasilcart').removeClass();

			$('.loading').remove();

			if (parseInt(data['jmlerror']) > 0) {

				$('#hasilcart').addClass("alert alert-danger");

				$('#hasilcart').html(data['msgerror']);

				$(".tab_content").hide();

				$('#areacust').fadeIn();



			} else {

				$('#hasilcart').html("");

				/* servis shipping */

				var propinsi = $('#propinsi_penerima').val();

				var kabupaten = $('#kabupaten_penerima').val();

				var kecamatan = $('#kecamatan_penerima').val();

				var totberat = $('#totberat').val();



				servisKurir(propinsi, kabupaten, kecamatan, totberat);

				/* end servis shipping */

				$('#caption_subtotal').html(data['totallabel']);



			}





		});

	}



	function tarifkurir() {

		var propinsi = $('#propinsi_penerima').val();

		var kabupaten = $('#kabupaten_penerima').val();

		var kecamatan = $('#kecamatan_penerima').val();

		var totberat = $('#totberat').val();

		var subtotal = $('#subtotal').val();

		var cg_biaya_packing =  $('#cg_biaya_packing').val();

		var cust_tanpa_biaya_packing = $('#cust_tanpa_biaya_packing').val();

		var biaya_packing = isAddressEqual() ? '0' : $('#biaya_packing').val();

		var serviskurir = $('#serviskurir').val();

		var tarif;

		var nilaitarif;

		var datawil = $('#kecamatan_penerima').val() + ',' + serviskurir + ',' + totberat;

		var cekwil = wil.indexOf(datawil);



		var total;

		var data = 'propinsi_penerima=' + propinsi + '&kabupaten_penerima=' + kabupaten;

		data += '&kecamatan_penerima=' + kecamatan + '&serviskurir=' + serviskurir + '&subtotal=' + subtotal;

		data += '&totberat=' + totberat;

		data += '&cg_biaya_packing=' + cg_biaya_packing + '&cust_tanpa_biaya_packing=' + cust_tanpa_biaya_packing + '&biaya_packing=' + biaya_packing;

		if ($('#nama_penerima').val() != $('#nama_pengirim').val() &&

			$('#alamat_pengirim').val() != $('#alamat_penerima').val() &&

			$('#telp_pengirim').val() != $('#telp_penerima').val()) {

			$('#platalamat_pengirim').show();

			if($('#cg_biaya_packing').val() == '1' && $('#cust_tanpa_biaya_packing').val() != '1'){simpanorder

				$('#biaya_packing').val(biaya_packing);

			}

			else {

				$('#biaya_packing').val('0');

			}

		}

		else {
			$('#biaya_packing').val('0');
		}



		$('#caption_kurir').html('Tunggu...');

		$('#caption_total').html('Tunggu...');

		// if (cekwil < 0) {



			// wil.push(datawil);

			$.ajax({

				type: "POST",

				url: action + '&modul=tarifkurir',

				data: data,

				dataType: 'json',

				success: function(json) {

					let biayaPacking = parseInt(json['biayaPacking']);

					if (json['status'] == 'error') {

						alert(json['result']);

					} else {



						// tarifwil[datawil] = json['tarif'];

						// nilaitarifwil[datawil] = convertToRupiah(json['nilaitarif']);



						tarif = json['tarif'];

						if (tarif != 'Konfirmasi Admin') {

							// total = parseInt(json['nilaitarif']) + parseInt(subtotal) + parse;

							total = json['total'];

						} else {

							total = 'Konfirmasi Admin';

						}

						// totalwil[datawil] = total;

						if(biayaPacking == 0){
							$('#caption_biayapacking').hide()
						}
						else {
							$('#caption_biayapacking').show()
						}

						$('#caption_kurir').html(tarif);

						$('#caption_total').html(total);



					}

				},

				error: function(e) {

					alert('Error: ' + e);

				}

			});



		// } 
		
		// else {



		// 	tarif = tarifwil[datawil];

		// 	nilaitarif = nilaitarifwil[datawil];

		// 	total = totalwil[datawil];



		// 	$('#caption_kurir').html(nilaitarif);

		// 	$('#caption_total').html(total);



		// }

	}



	function servisKurir(propinsi, kota, kecamatan, totberat) {

		var data = 'propinsi_penerima=' + propinsi + '&kabupaten_penerima=' + kota;

		data += '&kecamatan_penerima=' + kecamatan + '&totberat=' + totberat;



		$('#serviskurir')

			.find('option')

			.remove()

			.end()

			.append('<option value="0">Tunggu..</option>')

			.val('0');

		$.ajax({

			type: "POST",

			url: action + '&modul=serviskurir',

			data: data,

			dataType: 'json',

			success: function(json) {

				

				if (json['status'] == 'error') {

					alert(json['result']);

				} else {

					html = '<option value="0">- Pilih Kurir -</option>';

					for (i = 0; i < json['result'].length; i++) {

						if (json['result'][i]['shipping_rajaongkir'] == '1') {

							

							html += '<option value="' + json['result'][i]['servis_id'] + '::' + json['result'][i]['tarif'] + '::' + json['result'][i]['shipping_code_rajaongkir'] + '::' + json['result'][i]['servis_code'] + '">' + json['result'][i]['shipping_code_rajaongkir'] + ' - ' + json['result'][i]['servis_code'] + ' (' + json['result'][i]['etd'] + ' Hari) - ' + json['result'][i]['tarif'] + '</option>';

						} else {

							if (json['result'][i]['shipping_cod'] == '1') {

								tarif = 0;

							} else {

								//tarif = 'Konfirmasi Admin';

								if(json['result'][i]['shipping_konfirmadmin'] == '1') {

									tarif = 'Konfirmasi Admin';

								} else {

									tarif = 0;

								}

							}

							

								

								

							

							html += '<option value="' + json['result'][i]['servis_id'] + '::' + tarif + '::' + json['result'][i]['shipping_kode'] + '::' + json['result'][i]['servis_code'] + '">' + json['result'][i]['shipping_kode'] + ' - ' + json['result'][i]['servis_code'] + ' - ' + tarif + '</option>';

						}

					}



					$('#serviskurir').html(html);

					//$('#btnsimpanaddproduk').button("reset");

					return true;

				}



			},

			error: function(e) {

				return false;

			}

		});

	}



	function checkCart() {

		var jmlitem = 0;

		$('.jumlahqty').each(function() {

			jmlitem += 1;

		});

		if (jmlitem < 1) {

			alert('Masukkan produk-produk yang ingin dipesan');

			return false;

		}

		if ($('#status_valid').val() == 'error') {

			alert('Mohon periksa produk pesanan Anda');

			return false;

		}

		$('#btnnextalamat').tab("show");

	}



	function kabupaten(idpropinsi, idkabupaten) {

		var selected = '';

		$.ajax({

			type: "GET",

			url: '<?php echo URL_THEMES . 'wilayah/index.php' ?>?load=kabupaten',

			data: 'propinsi=' + idpropinsi,

			dataType: 'json',

			success: function(result) {



				var html = '<option value="0">- Kota/Kabupaten -</option>';

				for (i = 0; i < result.length; i++) {

					if (result[i]['kabupaten_id'] == idkabupaten) {

						selected = 'selected';

					} else {

						selected = '';

					}



					html += '<option value="' + result[i]['kabupaten_id'] + '"' + selected;

					html += '>' + result[i]['kabupaten_nama'] + '</option>';

				}



				$('#kabupaten_penerima').html(html).trigger("chosen:updated");



			},

			error: function(e) {

				alert('Error: ' + e);

			}

		});



		return false;

	}



	function kecamatan(idkabupaten, idkecamatan) {

		$.ajax({

			type: "GET",

			url: '<?php echo URL_THEMES . 'wilayah/index.php' ?>?load=kecamatan',

			data: 'kabupaten=' + idkabupaten,

			dataType: 'json',

			success: function(result) {



				var html = '<option value="0">- Kecamatan -</option>';

				for (i = 0; i < result.length; i++) {

					if (result[i]['kecamatan_id'] == idkecamatan) {

						selected = 'selected';

					} else {

						selected = '';

					}

					html += '<option value="' + result[i]['kecamatan_id'] + '"' + selected;

					html += '>' + result[i]['kecamatan_nama'] + '</option>';

				}



				$('#kecamatan_penerima').html(html).trigger("chosen:updated");



			},

			error: function(e) {

				alert('Error: ' + e);

			}

		});



		return false;

	}



	function formAlamat(jenis) {

		var url = action + '&modul=frmAlamat';

		var totberat = $('#totberat').val();

		var grup_dropship = $('#grup_dropship').val();

		var caption_alamat;

		var pelangganid = $('#idmember').val();

		var grppelanggan = $('#gruppelanggan').val();

		var nopesan = '';

		var error;

		if (jenis == 'alamatpengirim') {

			caption_alamat = 'Pengirim';

			if ($('#grup_dropship').val() == '0') {

				error = '1';

			} else {

				error = '0';

			}

		} else {

			caption_alamat = 'Penerima/Tujuan';

			error = '0';

		}

		if (error == '1') {

			alert('Pelanggan tidak memiliki fasilitas dropship');

			return false;

		}

		var zdata = nopesan + '::' + pelangganid + '::' + grppelanggan + '::' + jenis + '::' + caption_alamat + '::' + totberat + '::addorder';



		$("#loadingweb").fadeIn();



		tampilform(url, zdata);

	}



	function updatecart() {

		var dataupdate = $('#frmdata').serialize();

		$('#hasillistcart').removeClass();

		$('#hasillistcart').hide();

		$('#tblupdate').button("loading");





		$.ajax({

			type: "POST",

			url: action + '&modul=updatecart',

			data: dataupdate,

			dataType: 'json',

			success: function(result) {



				if (result['status'] == "error") {

					$('#hasillistcart').addClass("alert alert-danger");

					$('html, body').animate({

						scrollTop: 0

					}, 'slow');



				} else {

					$('#hasillistcart').addClass("alert alert-success");

					listcart('listcart', 'hitung');

				}

				$('#status_valid').val(result['status']);

				$('#hasillistcart').show();

				$('#hasillistcart').html(result['msg']);

				$('#tblupdate').button("reset");

				return false;

			}

		});



	}



	function gantiAlamat(jenis_alamat, nama, no_hp, alamat, propinsi, propinsi_caption, kabupaten, kabupaten_caption, kecamatan, kecamatan_caption, kelurahan, kodepos) {



		if (jenis_alamat == 'alamatpenerima') {

			var totberat = $('#totberat').val();



			servisKurir(propinsi, kabupaten, kecamatan, totberat);

			var nama_penerima = $('#nama_penerima').val(nama);

			var alamat_penerima = $('#alamat_penerima').val(alamat);

			var telp_penerima = $('#telp_penerima').val(no_hp);

			var propinsi_penerima = $('#propinsi_penerima').val(propinsi);

			var kabupaten_penerima = $('#kabupaten_penerima').val(kabupaten);

			var kecamatan_penerima = $('#kecamatan_penerima').val(kecamatan);

			var kelurahan_penerima = $('#kelurahan_penerima').val(kelurahan);

			var kodepos_penerima = $('#kodepos_penerima').val(kodepos);

			var alamatpenerima = '<b>' + nama + '</b><br>';

			alamatpenerima += alamat + '<br>';

			alamatpenerima += propinsi_caption + ', ' + kabupaten_caption + '<br>';

			alamatpenerima += kecamatan_caption;

			if (kelurahan != '') {

				alamatpenerima += ', ' + kelurahan;

			}

			if (kodepos != '') {

				alamatpenerima += ', ' + kodepos;

			}

			alamatpenerima += '<br> Hp. ' + no_hp;



			$('#deskripsi_alamat_penerima').html(alamatpenerima);

		} else {



			var nama_pengirim = $('#nama_pengirim').val(nama);

			var alamat_pengirim = $('#alamat_pengirim').val(alamat);

			var telp_pengirim = $('#telp_pengirim').val(no_hp);

			var propinsi_pengirim = $('#propinsi_pengirim').val(propinsi);

			var kabupaten_pengirim = $('#kabupaten_pengirim').val(kabupaten);

			var kecamatan_pengirim = $('#kecamatan_pengirim').val(kecamatan);

			var kelurahan_pengirim = $('#kelurahan_pengirim').val(kelurahan);

			var kodepos_pengirim = $('#kodepos_pengirim').val(kodepos);

			var alamatpengirim = '<b>' + nama + '</b><br>';

			alamatpengirim += alamat + '<br>';

			alamatpengirim += propinsi_caption + ', ' + kabupaten_caption + '<br>';

			alamatpengirim += kecamatan_caption;

			if (kelurahan != '') {

				alamatpengirim += ', ' + kelurahan;

			}

			if (kodepos != '') {

				alamatpengirim += ', ' + kodepos;

			}

			alamatpengirim += '<br> Hp. ' + no_hp;

			$('#deskripsi_alamat_pengirim').html(alamatpengirim);

		}

		if ($('#nama_penerima').val() != $('#nama_pengirim').val() &&

			$('#alamat_pengirim').val() != $('#alamat_penerima').val() &&

			$('#telp_pengirim').val() != $('#telp_penerima').val()) {

			$('#platalamat_pengirim').show();

			if($('#cg_biaya_packing').val() == '1' && $('#cust_tanpa_biaya_packing').val() != '1'){simpanorder

				$('#biaya_packing').val(biaya_packing);
				
				$('#caption_biayapacking').show()

			}

			else {

				$('#biaya_packing').val('0');

				$('#caption_biayapacking').hide();

			}

		}

		else {
			$('#biaya_packing').val('0');

			$('#caption_biayapacking').hide();
		}

		$('#caption_total').html('-');

		$('#serviskurir').val('0');

	}

	function isAddressEqual(){
		if ($('#nama_penerima').val() != $('#nama_pengirim').val() &&

			$('#alamat_pengirim').val() != $('#alamat_penerima').val() &&

			$('#telp_pengirim').val() != $('#telp_penerima').val()) {
			return false
			}
		else {
			return true
		}
	}

	function simpanorder() {





		var frm = $('#frmdata').serialize();

		var serviskurir = $('#serviskurir').val();





		$('#hasil').removeClass();

		$('#hasil').hide();

		$('#btnsimpan').button("loading");



		if (serviskurir == 0) {

			$('#hasil').show();

			$('#hasil').html('Pilih Servis Pengiriman');

			$('#hasil').addClass("alert alert-danger");

			$('#btnsimpan').button("reset");

			return false;

		}

		$.ajax({

			type: "POST",

			url: action,

			data: frm,

			dataType: 'json',

			success: function(json) {



				if (json['status'] == 'error') {



					$('#hasil').addClass("alert alert-success");

					$('#hasil').html(json['result']);

					$('#hasil').show();

					$('#btnsimpan').button("reset");



				} else {



					$('#btnsimpan').tab("show");

					$('#msgorder').html(json['msgorder']);



				}

				return false;

			},

			error: function(e) {

				alert('Error: ' + e);

				$('#btnsimpan').button("reset");

			}

		});

	}



	function hapusCart(zdata) {



		var del = $('#frmdata').serialize() + '&data=' + zdata;

		$('#hasillistcart').removeClass();

		$('#hasillistcart').hide();





		$.ajax({

			type: "POST",

			url: action + '&modul=delcart',

			data: del,

			dataType: 'json',

			success: function(msg) {



				if (msg['status'] == "error") {

					$('#hasillistcart').addClass("alert alert-danger");

					$('html, body').animate({

						scrollTop: 0

					}, 'slow');

				} else {

					$('#hasillistcart').addClass("alert alert-success");

					listcart('listcart', 'hitung');

				}

				$('#hasillistcart').show();

				$('#hasillistcart').html(msg['msg']);

				return false;

			}

		});



	}

</script>