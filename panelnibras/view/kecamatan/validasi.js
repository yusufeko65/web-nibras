var action = $('#frmdata').attr('action');
jQuery(document).ready(function () {
	$('#kecamatan').focus();

	$('#propinsi').change(function () {
		var token = $('#token').val();
		$('#kabupaten').load(action + '?load=kabupaten&propinsi=' + this.value + '&u_token=' + token);
		return false;
	});
});

function kosongform() {
	$('#kecamatan').focus();
	$('#kecamatan').val("");
}

function disableEnterKey(e) { //Disable Tekan Enter
	var key;
	if (window.event)
		key = window.event.keyCode; //IE
	else
		key = e.which; //firefox

	if (key == 13) { // Jika ditekan tombol enter
		simpandata(); // Panggil fungsi simpandata()
		return false;
	} else {
		return true;
	}
}

function simpandata() { //Proses Simpan
	var kabupaten = $('#kabupaten').val();
	var propinsi = $('#propinsi').val();
	var kecamatan = $('#kecamatan').val();
	var token = $('#token').val();
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var datasimpan;
	if (propinsi == 0) {
		$('#propinsi').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Pilih Propinsi </div>");
		$('#hasil').show(500);
		return false;
	}
	if (kabupaten == 0) {
		$('#kabupaten').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Pilih Kotamadya/Kabupaten</div>");
		$('#hasil').show(500);
		return false;
	}
	if (kecamatan.length == 0) {
		$('#kecamatan').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Nama Kecamatan</div>");
		$('#hasil').show(500);
		return false;
	}
	datasimpan = "aksi=" + aksi + "&kecamatan=" + escape(kecamatan) + "&idkabupaten=" + kabupaten + "&iddata=" + iddata;
	$('#waiting').show(500);
	$.ajax({
		type: "POST",
		url: action + '?u_token=' + token,
		data: datasimpan,
		cache: false,
		success: function (msg) {
			$('#waiting').hide(0);
			hasilnya = msg.split("|");

			$('#hasil').show(0).fadeOut(5000);
			if (hasilnya[0] == "sukses") {
				if (hasilnya[1] == "input") kosongform();
				$('#hasil').html('<div class="alert alert-success">' + hasilnya[2] + '</div>');
			} else {
				$('#hasil').html('<div class="alert alert-danger">' + hasilnya[2] + '</div>');
			}
			return false;
		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});
}

function hapus() { //Proses Simpan

	var iddata = $('#iddata').val();
	var datahapus = "aksi=hapus&id=" + iddata;
	$('#waiting').show(500);
	$.ajax({
		type: "POST",
		url: action + '?u_token=' + token,
		data: datahapus,
		cache: false,
		success: function (msg) {
			alert(msg);
			$('#waiting').hide(0);
			hasilnya = msg.split("|");
			$('#hasil').html(hasilnya[2]);
			$('#hasil').show(0).fadeOut(5000);
			if (hasilnya[0] == "sukses") tampilkan(URL_PROGRAM_ADMIN + 'kecamatan/');;
			return false;
		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});
}