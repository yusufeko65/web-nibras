var wil = [];
var tarifwil = [];
var nilaitarifwil = [];
var totaltarifwil = [];
var urlwilayah = $('#urlwilayah').val();
$(function () {

	$("#frmorder").submit(function (event) {

		event.preventDefault();
		simpandata();
		return false;
	});
	$('#btneditorderkurir').click(function () {
		formEditOrderKurir();
		return false;
	});
	$('#update_keterangan').click(function () {
		updateketerangan();
		return false;
	});
	$('#search_produk').autocomplete({
		delay: 0,
		source: function (request, response) {
			$.ajax({
				url: $('#frmorder').prop("action") + '/?order=' + $('#pesanan_no').val(),
				dataType: "json",
				data: {
					load: 'cariproduk',
					cariproduk: request.term
				},
				success: function (data) {

					response($.map(data, function (item) {
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
							persen_diskon: item.persen_diskon,
							stok: item.stok,
							minbeli: item.min_beli,
							minbelisyarat: item.min_beli_syarat,
							diskon_member: item.diskon_member,
							grup_nama: item.grup_nama
						}
					}));
				},
				error: function (e) {
					alert('Error: ' + e);
				}
			});
		},
		minLength: 3,
		select: function (event, ui) {

			var url = $('#frmorder').prop("action") + '/?order=' + $('#pesanan_no').val() + '&load=frmAddProduk';
			var ukuran = '';
			for (j = 0; j < ui.item.ukuran.length; j++) {
				ukuran += '<option value="' + ui.item.ukuran[j]['id'] + '">' + ui.item.ukuran[j]['nm'] + '</option>';
			}

			var zdata = ui.item.id + ":" + ui.item.kode + ":" +
				ui.item.nama + ":" + ukuran + ":" +
				ui.item.berat + ":" + ui.item.hrgsatuan + ":" +
				ui.item.diskon_satuan + ":" +
				ui.item.stok + ":" +
				ui.item.minbeli + ':' +
				ui.item.diskon_member + ':' +
				$('#pesanan_no').val() + ':editorder:' +
				ui.item.grup_nama + ':' +
				ui.item.minbelisyarat + ':' +
				ui.item.persen_diskon;

			$("#loadingweb").fadeIn();
			$.post(url, {
				data: zdata
			}, function (data) {

				$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function () {
					$(this).remove();
					$('#btneditorderkurir').prop('disabled', false);
				});
			});
			return false;
		},
		focus: function (event, ui) {
			return false;
		}
	});


});

function getWarna(idukuran) {
	var action = $('#frmorder').attr("action");
	var url = action + '?order=' + $('#pesanan_no').val() + '&load=warna';
	var idproduk = $('#product_id').val();
	$.ajax({
		type: "GET",
		url: url,
		data: 'ukuran=' + idukuran + '&produk_id=' + idproduk,
		dataType: 'json',
		success: function (result) {

			var html = '<option value="0">- Pilih Warna -</option>';
			var stok = 0;
			for (i = 0; i < result['warna'].length; i++) {
				html += '<option value="' + result['warna'][i]['idwarna'] + '"';
				html += '>' + result['warna'][i]['warna'] + '</option>';

			}

			$('#idwarna').html(html);
			$('#stok').val(result['stok']);

		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});

}

function getStok(idwarna, idukuran, stokall) {
	var action = $('#frmorder').attr("action");
	var url = action + '?order=' + $('#pesanan_no').val() + '&load=stok';
	var idproduk = $('#product_id').val();

}

function formEditOrderKurir() {
	$('#btneditorderkurir').prop('disabled', true);
	var url = $('#frmorder').attr('action');
	url = url + '?load=frmEditOrderKurir';
	var frm = $('#frmorder').serialize();

	$.post(url, frm, function (data) {

		$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function () {
			$(this).remove();
			$('#btneditorderkurir').prop('disabled', false);
		});
	});
}

function cektarifkurir() {
	var frm = $('#frmeditkurir').serialize();
	var url = $('#frmorder').prop("action") + '?load=tarifkurir';
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
			success: function (json) {

				if (json['status'] == 'error') {
					alert(json['result']);
				} else {

					tarifwil[datawil] = json['tarif'];
					nilaitarifwil[datawil] = json['nilaitarif'];

					tarif = json['tarif'];

					$('#tarifkurir').val(tarif);

				}
			},
			error: function (e) {
				alert('Error: ' + e);
			}
		});

	} else {

		tarif = tarifwil[datawil];
		nilaitarif = nilaitarifwil[datawil];

		$('#tarifkurir').val(tarif);


	}
}

function simpaneditkurir() {
	//$('#btnsimpankurir').button("loading");
	var tarif = $('#tarifkurir').val();
	var redirect = $('#redirectedit').val();
	var serviskurir = $('#serviskurir').val();
	var frm = $('#frmeditkurir').serialize();
	var url = $('#frmorder').prop("action") + '?load=simpaneditkurir';

	$('#hasileditkurir').removeClass();
	$('#hasileditkurir').hide();


	if (serviskurir == '') {
		$('#hasileditkurir').addClass("alert alert-danger");
		$('#hasileditkurir').html('Masukkan Tarif Kurir');
		$('#hasileditkurir').show();
		//$('#btnsimpankurir').button("reset");
		return false;
	}

	$.ajax({
		type: "POST",
		url: url,
		data: frm,
		dataType: 'json',
		success: function (json) {

			if (json['status'] == "error") {
				$('#hasileditkurir').addClass("alert alert-danger");
				//$('#btnsimpankurir').button("reset");
			} else {
				$('#hasileditkurir').addClass("alert alert-success");
				location.href = redirect;
			}
			$('#hasileditkurir').html(json['result']);
			$('#hasileditkurir').show(0);
			return false;
		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});

}

function editProduk(pid) {
	var url = $('#frmorder').attr('action');
	url = url + '?load=frmEditOrderProduk';
	var frm = $('#frmorder').serialize() + '&pid=' + pid;


	$.post(url, frm, function (data) {

		$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function () {
			$(this).remove();
			$('#btneditorderkurir').prop('disabled', false);
		});
	});

	return false;
}

function simpaneditproduk() {

	var frm = $('#frmeditproduk').serialize();
	var url = $('#frmorder').prop("action") + '?load=simpaneditorderproduk';
	var jml = parseInt($('#qty').val());
	var redirect = $('#redirect').val();

	//$('#btnsimpanprod').button('loading');
	$('#hasiladdprod').removeClass();
	$('#hasiladdprod').hide();
	if (jml == '' || jml < 1 || !Number.isInteger(jml)) {
		$('#hasiladdprod').addClass("alert alert-danger");
		$('#hasiladdprod').html('Masukkan Jumlah');
		$('#hasiladdprod').show();
		//$('#btnsimpanprod').button('reset');
		return false;
	}

	$.ajax({
		type: "POST",
		url: url,
		data: frm,
		dataType: 'json',
		success: function (msg) {


			$('#loadingweb').hide(500);

			if ($.trim(msg['status']) == "error") {
				$('#hasiladdprod').addClass("alert alert-danger");
				//$('#btnsimpanprod').button('reset');

			} else {
				$('#hasiladdprod').addClass("alert alert-success");

				location = redirect;
			}
			$('#hasiladdprod').html(msg['result']);
			$('#hasiladdprod').show(0);
			return false;
		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});

}

function hapusProduk(pid) {
	var url = $('#frmorder').attr('action');
	url = url + '?load=frmHapusOrderProduk';
	var frm = $('#frmorder').serialize() + '&pid=' + pid;

	$.post(url, frm, function (data) {

		$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function () {
			$(this).remove();
			$('#btneditorderkurir').prop('disabled', false);
		});
	});

	return false;
}

function hapusprodukorder() {
	var frm = $('#frmdelproduk').serialize();
	var url = $('#frmorder').prop("action") + '?load=simpandelorderproduk';
	var redirect = $('#redirect').val();
	$('#btndelproduk').prop('disabled', true);
	$('#btnclose').prop('disabled', true);
	$('#btndelproduk').before('<span class="loading">Tunggu..jangan ditutup / direfresh</span>');
	$.ajax({
		type: "POST",
		url: url,
		data: frm,
		dataType: 'json',
		success: function (msg) {


			$('#loadingweb').hide(500);

			if ($.trim(msg['status']) == "error") {
				$('#hasildelproduk').addClass("alert alert-danger");
				//$('#btndelproduk').button('reset');

			} else {
				$('#hasildelproduk').addClass("alert alert-success");

				location = redirect;
			}
			$('#hasildelproduk').html(msg['result']);
			$('#hasildelproduk').show(0);
			$('.loading').remove();
			$('#btndelproduk').prop('disabled', false);
			$('#btnclose').prop('disabled', false);
			return false;
		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});
}

function formAlamat(jenis) {
	if (jenis == 'alamatpengirim') {
		caption_alamat = 'Pengirim';
	} else {
		caption_alamat = 'Penerima';
	}
	var url = $('#frmorder').attr('action');
	url = url + '?order=' + $('#pesanan_no').val() + '&load=frmEditOrderAlamat';
	var frm = $('#frmorder').serialize() + '&jenisalamat=' + jenis;


	$.post(url, frm, function (data) {


		$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function () {
			$(this).remove();
			$('#btneditorderkurir').prop('disabled', false);
		});

		return false;
	});


}

function getKabupaten(idpropinsi) {

	$('#add_kecamatan').find('option:not(:first)').remove();
	$('#add_kecamatan').prop('selectedIndex', 0);
	$.ajax({
		type: "GET",
		url: urlwilayah + '?load=kabupaten',
		data: 'propinsi=' + idpropinsi,
		dataType: 'json',
		success: function (result) {

			var html = '<option value="0">- Kota/Kabupaten -</option>';
			for (i = 0; i < result.length; i++) {
				html += '<option value="' + result[i]['kabupaten_id'] + '"';
				html += '>' + result[i]['kabupaten_nama'] + '</option>';
			}

			$('#add_kabupaten').html(html);

		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});

}

function getKecamatan(idkabupaten) {

	$.ajax({
		type: "GET",
		url: urlwilayah + '?load=kecamatan',
		data: 'kabupaten=' + idkabupaten,
		dataType: 'json',
		success: function (result) {

			var html = '<option value="0">- Kecamatan -</option>';
			for (i = 0; i < result.length; i++) {
				html += '<option value="' + result[i]['kecamatan_id'] + '"';
				html += '>' + result[i]['kecamatan_nama'] + '</option>';
			}

			$('#add_kecamatan').html(html);

		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});

	return false;
}

function saveAddress() {
	var frm = $('#frmeditlamat').serialize();
	var url = $('#frmorder').prop("action") + '?load=useorderalamat';
}

function useAddress(idalamat) {
	var frm = $('#frmeditlamat').serialize() + '&idalamat=' + idalamat;
	var url = $('#frmorder').prop("action") + '?load=useorderalamat';
	var redirect = $('#urlorder').val();
	//alert(url);

	$.ajax({
		type: "POST",
		url: url,
		data: frm,
		dataType: 'json',
		success: function (msg) {


			$('#loadingweb').hide(500);

			if ($.trim(msg['status']) == "error") {
				$('#hasiladdress').addClass("alert alert-danger");
				//$('#btnsimpanaddress').button('reset');

			} else {
				$('#hasiladdress').addClass("alert alert-success");

				location = redirect;

			}
			$('#hasiladdress').html(msg['result']);
			$('#hasiladdress').show(0);
			return false;
		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});

}

function simpanaddress() {
	var frm = $('#frmeditlamat').serialize();

	var redirect = $('#urlorder').val();
	var nama = $('#add_nama').val();
	var alamat = $('#add_alamat').val();
	var no_hp = $('#add_telp').val();
	var propinsi = $('#add_propinsi').val();
	var propinsi_caption = $('#add_propinsi option:selected').text();
	var kabupaten = $('#add_kabupaten').val();
	var kabupaten_caption = $('#add_kabupaten option:selected').text();
	var kecamatan = $('#add_kecamatan').val();
	var kecamatan_caption = $('#add_kecamatan option:selected').text();
	var kelurahan = $('#add_kelurahan').val();
	var kodepos = $('#add_kodepos').val();
	var jenis_alamat = $('#jenis_alamat').val();
	var modulform = $('#modulform').val();
	var modul;
	if (modulform == 'editorder') {
		modul = 'editorderalamat';
	} else {
		modul = 'addorderalamat';
	}
	//var url = $('#frmeditlamat').prop("action")+'?modul='+modul;
	var url = $('#frmorder').prop("action") + '?load=saveorderalamat';
	//$('#btnsimpanaddress').button("loading");
	$('#hasiladdress').removeClass();
	$('#hasiladdress').hide();

	if (nama == '' || (nama.length < 3 && nama.length > 30)) {
		$('#hasiladdress').addClass("alert alert-danger");
		$('#hasiladdress').show();
		$('#hasiladdress').html('Masukkan Nama, Maksimal 30 karakter');
		//$('#btnsimpanaddress').button("reset");
		return false
	}

	if (no_hp == '' || (no_hp.length < 5 && no_hp.length > 20)) {
		$('#hasiladdress').addClass("alert alert-danger");
		$('#hasiladdress').show();
		$('#hasiladdress').html('Masukkan No. Handphone, Maksimal 20 Karakter');
		//$('#btnsimpanaddress').button("reset");
		return false
	}

	if (propinsi == '' || propinsi == '0') {
		$('#hasiladdress').addClass("alert alert-danger");
		$('#hasiladdress').show();
		$('#hasiladdress').html('Pilih Propinsi');
		//$('#btnsimpanaddress').button("reset");
		return false
	}

	if (kabupaten == '' || kabupaten == '0') {
		$('#hasiladdress').addClass("alert alert-danger");
		$('#hasiladdress').show();
		$('#hasiladdress').html('Pilih Kabupaten/Kota');
		//$('#btnsimpanaddress').button("reset");
		return false
	}

	if (kecamatan == '' || kecamatan == '0') {
		$('#hasiladdress').addClass("alert alert-danger");
		$('#hasiladdress').show();
		$('#hasiladdress').html('Pilih Kecamatan');
		//$('#btnsimpanaddress').button("reset");
		return false
	}

	$.ajax({
		type: "POST",
		url: url,
		data: frm,
		dataType: 'json',
		success: function (msg) {

			$('#loadingweb').hide(500);

			if ($.trim(msg['status']) == "error") {
				$('#hasiladdress').addClass("alert alert-danger");


			} else {
				$('#hasiladdress').addClass("alert alert-success");
				/*
				if($('#modulform').val() == 'editorder') {
					location = redirect;
				} else {
					gantiAlamat(jenis_alamat,nama,no_hp,alamat,propinsi,propinsi_caption,kabupaten,kabupaten_caption,kecamatan,kecamatan_caption,kelurahan,kodepos);
					$('#btnclose').trigger( "click" );
				}
				*/
				location.href = redirect;
				//$('#btnclose').trigger( "click" );
			}
			$('#hasiladdress').html(msg['result']);
			$('#hasiladdress').show(0);
			return false;
		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});
}


function simpanaddproduk() {
	var frm = $('#frmaddproduk').serialize();
	var url = $('#frmorder').prop("action") + '?order=' + $('#pesanan_no').val() + '&load=simpanaddproduk';
	var qty = $('#qty').val();
	var aksi = $('#aksi').val();
	var redirect = $('#redirect').val();

	$('#hasiladdproduk').removeClass();
	$('#hasiladdproduk').hide();
	if (qty == '' && !Number.isInteger(qty)) {
		$('#hasiladdproduk').addClas("alert alert-danger");
		$('#hasiladdproduk').html('Masukkan Jumlah QTY');
		$('#hasiladdproduk').show();
		//$('#btnsimpanaddproduk').button("reset");

		return false;
	}

	$.ajax({
		type: "POST",
		url: url,
		data: frm,
		dataType: 'json',
		success: function (msg) {


			$('#loadingweb').hide(500);

			if ($.trim(msg['status']) == "error") {
				$('#hasiladdproduk').addClass("alert alert-danger");
				//$('#btnsimpanaddproduk').button('reset');
				$('#hasiladdproduk').show();
			} else {
				$('#hasiladdproduk').addClass("alert alert-success");


				location = redirect;

			}
			$('#hasiladdproduk').html(msg['result']);
			$('#hasiladdproduk').show(0);
			return false;
		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});
}

function updateketerangan() {
	var data = 'keterangan=' + encodeURIComponent($.trim($('#keterangan').val())) + '&pesanan_no=' + $('#pesanan_no').val();
	var redirect = $('#redirect').val();

	$.ajax({
		type: "POST",
		url: $('#frmorder').prop("action") + '?load=updateketerangan',
		data: data,
		dataType: 'json',
		success: function (msg) {


			$('#loadingweb').hide(500);

			if ($.trim(msg['status']) == "error") {
				$('#hasilketerangan').addClass("alert alert-danger");
				//$('#btnsimpanaddproduk').button('reset');
				$('#hasilketerangan').show();
			} else {
				$('#hasilketerangan').addClass("alert alert-success");


				location = redirect;

			}
			$('#hasilketerangan').html(msg['result']);
			$('#hasilketerangan').show(0);

			return false;
		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});

}