$(function () {
	$('#nama').focus();
});

function kosongform() {
	$('#nama').focus();
	$('#nama').val("");
	$('#keterangan').val("");
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
	var nama = $('#nama').val();
	var keterangan = $('#keterangan').val();
	var pesan = "";
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var token = $('#token').val();
	var action = $('#frmdata').attr('action') + '?u_token=' + token;
	var datasimpan;
	if (nama.length == 0) {
		$('#nama').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Nama Status</div>");
		$('#hasil').show(500);
		return false;
	}
	datasimpan = "aksi=" + aksi + "&status=" + nama + "&iddata=" + iddata + "&keterangan=" + keterangan;
	$('#loadingweb').show(500);
	$.ajax({
		type: "POST",
		url: action,
		data: datasimpan,
		cache: false,
		success: function (msg) {
			$('#loadingweb').hide(0);
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