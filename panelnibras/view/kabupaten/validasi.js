jQuery(document).ready(function () {
	$('#kabupaten').focus();
});

function kosongform() {
	$('#kabupaten').focus();
	$('#kabupaten').val("");
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
	var token = $('#token').val();
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var action = $('#frmdata').attr('action') + '?u_token=' + token;
	var datasimpan;

	if (kabupaten.length == 0) {
		$('#kabupaten').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Nama Kotamadya/Kabupaten</div>");
		$('#hasil').show(500);
		return false;
	}
	if (propinsi == 0) {
		$('#propinsi').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Pilih Propinsi </div>");
		$('#hasil').show(500);
		return false;
	}
	datasimpan = "aksi=" + aksi + "&kabupaten=" + kabupaten + "&idpropinsi=" + propinsi + "&iddata=" + iddata;
	$('#waiting').show(500);
	$.ajax({
		type: "POST",
		url: action,
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