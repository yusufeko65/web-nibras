var wil = [];
var tarifwil = [];
var nilaitarifwil = [];
var totaltarifwil = [];
if ($('#tarif').html() == '-') {
	$('#serviskurir').val(0);
}
$(function () {
	var urldata = $('#urldata').val();
	var url_wil = $('#urlwil').val();

	$("#frmkasir").submit(function (event) {
		event.preventDefault();
		simpanorder();
	});

	$('#serviskurir').change(function () {
		tarifkurir();
		return false;
	});

	$('#kecamatan_penerima').change(function () {
		serviskurirtarif();
		return false;
	});

	$('#btnalamatpengirim').click(function () {
		listalamat('pengirim', 'btnalamatpengirim');
		return false;
	});

	$('#btnalamatpenerima').click(function () {
		listalamat('penerima', 'btnalamatpenerima');
		return false;
	});

});

function serviskurirtarif(id_propinsi = 0, id_kabupaten = 0, id_kecamatan = 0) {
	var kecamatan = id_kecamatan;
	if (id_kecamatan == '0') {
		kecamatan = $('#kecamatan_penerima').val();
	}
	var kabupaten = id_kabupaten;
	if (id_kabupaten == '0') {

		kabupaten = $('#kabupaten_penerima').val();
	}
	var propinsi = id_propinsi;
	if (id_propinsi == '0') {
		propinsi = $('#propinsi_penerima').val();
	}
	var totberat = $('#totberat').val();
	var url = $('#frmkasir').prop("action");
	var data = "totberat=" + totberat + "&kecamatan=" + kecamatan + "&kabupaten=" + kabupaten + "&propinsi=" + propinsi + "&aksi=serviskurir";


	$('#serviskurir')
		.find('option')
		.remove()
		.end()
		.append('<option value="0">Tunggu.. Sedang memuat tarif</option>')
		.val('0');


	$.ajax({
		type: "POST",
		url: url,
		data: data,
		dataType: 'json',
		success: function (json) {

			if (json['status'] == 'error') {
				alert('Error', 'error', json['result'], 'serviskurir');
			} else {
				html = '<option value="0">- Pilih Kurir -</option>';
				for (i = 0; i < json['result'].length; i++) {
					if (json['result'][i]['shipping_rajaongkir'] == '1') {
						html += '<option value="' + json['result'][i]['servis_id'] + '::' + json['result'][i]['tarif'] + '::' + json['result'][i]['shipping_code_rajaongkir'] + '::' + json['result'][i]['servis_code'] + '">' + json['result'][i]['shipping_code_rajaongkir'] + ' - ' + json['result'][i]['servis_code'] + ' (' + json['result'][i]['etd'] + ' Hari) - ' + json['result'][i]['tarif'] + '</option>';
					} else {
						if(json['result'][i]['shipping_konfirmadmin'] == '1') {
							tarif = 'Konfirmasi Admin';
						} else {
							tarif = 0;
						}
						html += '<option value="' + json['result'][i]['servis_id'] + '::'+tarif+'::' + json['result'][i]['shipping_kode'] + '::' + json['result'][i]['servis_code'] + '">' + json['result'][i]['shipping_kode'] + ' - ' + json['result'][i]['servis_code'] + ' - ' + tarif + '</option>';
					}
				}
				$('#serviskurir').html(html);
			}
		}
	});
}

function tarifkurir() {
	var frm = $('#frmkasir').serialize() + '&aksi=tarifkurir';
	var url = $('#frmkasir').prop("action");
	var tarif;
	var nilaitarif;
	var datawil = $('#kecamatan_penerima').val() + ',' + $('#serviskurir').val();
	var cekwil = wil.indexOf(datawil);
	var totaltarif;

	if (cekwil < 0 && $('#serviskurir').val() != '0') {

		wil.push(datawil);
		$.ajax({
			type: "POST",
			url: url,
			data: frm,
			dataType: 'json',
			success: function (json) {

				if (json['status'] == 'error') {
					alert('Error', 'error', json['result'], 'serviskurir');
				} else {

					tarifwil[datawil] = json['tarif'];
					nilaitarifwil[datawil] = json['tarifnilai'];
					totaltarifwil[datawil] = json['total'];
					tarif = json['tarif'];
					$('#tarif').html(tarif);
					$('#tarifkurir').val(json['tarifnilai']);
					$('#totaltagihan').html(json['total']);
				}
			},
			error: function (e) {
				alert('Error: ' + e);
			}
		});

	} else {

		tarif = tarifwil[datawil];
		nilaitarif = nilaitarifwil[datawil];
		totaltarif = totaltarifwil[datawil];
		$('#tarif').html(tarif);
		$('#tarifkurir').val(nilaitarif);
		$('#totaltagihan').html(totaltarif);
	}



}

function ambiltarif() {
	var frm = $('#frmkasir').serialize() + '&aksi=tarifkurir';
	var url = $('#frmkasir').prop("action");
	var subtotal = $('#subtotal').val();
	var result = [];

	$.ajax({
		type: "POST",
		url: url,
		data: frm,
		dataType: 'json',
		success: function (json) {

			if (json['status'] == 'error') {
				alert('Error', 'error', json['result'], 'serviskurir');
			} else {

				result['tarif'] = json['tarif'];
				result['tarifkurir'] = json['nilaitarif'];
				result['totaltagihan'] = json['total'];
				return result;
			}
		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});
}

function simpanorder() {
	var frm = $('#frmkasir').serialize() + '&aksi=simpanorder';
	var url = $('#frmkasir').prop("action");
	var serviskurir = $('#serviskurir').val();

	var propinsi = $('#propinsi_penerima').val();
	var kabupaten = $('#kabupaten_penerima').val();
	var kecamatan = $('#kecamatan_penerima').val();

	var urlweb = $('#url_web').val();

	//alert(frm);
	//return false;
	if ((propinsi == '' || propinsi == '0') || (kabupaten == '' && kabupaten == '0') || (kecamatan == '' || kecamatan == '0')) {
		alert('Error', 'error', 'Masukkan Wilayah (Propinsi, Kota/kabupaten, Kecamatan)', 'simpancart');
		return false;
	}

	if (serviskurir == '' || serviskurir == '0') {
		alert('Error', 'error', 'Pilih Kurir', 'simpancart');
		return false;
	}

	$.ajax({
		type: "POST",
		url: url,
		data: frm,
		dataType: 'json',
		success: function (json) {

			if (json['status'] == 'error') {
				alert('Error', 'error', json['result'], 'simpancart');
			} else {
				location = urlweb + 'cart/sukses';
			}
		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});

}

function listalamat(tipe, button) {
	$('#' + button).prop('disabled', true);
	var urlweb = $('#url_web').val();
	var url = urlweb + 'account?load=listAlamat';

	$.post(url, {
		tipe: tipe
	}, function (data) {

		$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function () {
			$(this).remove();
			$('#' + button).prop('disabled', false);
		});
	});
}

function gantiAlamat(tipe, alamat) {

	$('#modalfrm').modal("hide");
	var alamat_pengirim = '';
	var nama = '';
	var alamats = '';
	var nm_propinsi = '';
	var id_propinsi = '';
	var nm_kabupaten = '';
	var id_kabupaten = '';
	var nm_kecamatan = '';
	var id_kecamatan = '';
	var kelurahan = '';
	var kodepos = '';
	var telp = '';

	$('#modalfrm').on('hidden.bs.modal', function (e) {
		dataalamat = alamat.split(":");
		nama = dataalamat[0];
		alamats = dataalamat[1];
		nm_propinsi = dataalamat[2];
		id_propinsi = dataalamat[3];
		nm_kabupaten = dataalamat[4];
		id_kabupaten = dataalamat[5];
		nm_kecamatan = dataalamat[6];
		id_kecamatan = dataalamat[7];
		kelurahan = dataalamat[8];
		kodepos = dataalamat[9];
		telp = dataalamat[10];

		if (tipe == 'pengirim') {
			$('#nama_pengirim').val(nama);
			$('#telp_pengirim').val(telp);

		} else {
			//$('#alamatpenerima').html(alamats);
			$('#propinsi_penerima').val(id_propinsi);
			$('#nama_penerima').val(nama);
			$('#telp_penerima').val(telp);
			$('#alamat_penerima').val(alamats);
			findKabupaten(id_propinsi, 'kabupaten_penerima', id_kabupaten);
			findKecamatan(id_kabupaten, 'kecamatan_penerima', id_kecamatan);
			$('#kelurahan_penerima').val(kelurahan);
			$('#kodepos_penerima').val(kodepos);
			serviskurirtarif(id_propinsi, id_kabupaten, id_kecamatan);

		}
	})

}