jQuery(document).ready(function () {
	$('#judulweb').focus();
	$('#umum').addClass("active");
	$('#headernotaemail').summernote({
		height: "250px"
	});
	$('#notaregisweb').summernote({
		height: "250px"
	});
	$('#notabelanja').summernote({
		height: "250px"
	});

	$('#infoshipping').summernote({
		height: "250px"
	});
	$('#infosudahbayar').summernote({
		height: "250px"
	});
	$('#note_maintenance').summernote({
		height: "250px"
	});

});

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

function checkdata() {
	var judul = escape($.trim($('#judulsite').val()));
	var url = escape($.trim($('#alamatsite').val()));
	var email = escape($.trim($('#email').val()));
	var notaregis = escape($.trim($('#notaregis').val()));

	var pesan = "";
	$('#hasil').removeClass();
	$('#hasil').hide(0);

	if (judul == '') {
		$('#judulsite').focus();
		$('#hasil').addClass("alert alert-danger");

		$('#hasil').html('Masukkan Judul Site');
		$('#hasil').show(500);
		return false;
	}

	if (url.length == 0) {
		$('#alamatsite').focus();
		$('#hasil').addClass("alert alert-danger");

		$('#hasil').html('Masukkan Alamat Site');
		$('#hasil').show(500);
		return false;
	}
}

function simpandata() { //Proses Simpan
	var judul = escape($.trim($('#judulsite').val()));
	var url = escape($.trim($('#alamatsite').val()));
	var email = escape($.trim($('#email').val()));
	var emailnotif = escape($.trim($('#emailnotif').val()));
	var headernotaemail = escape($.trim($('#headernotaemail').code()));
	var notaregisweb = escape($.trim($('#notaregisweb').code()));
	var notabelanja = escape($.trim($('#notabelanja').code()));
	var notabelanjaweb = escape($.trim($('#notabelanjaweb').code()));
	var infoshipping = escape($.trim($('#infoshipping').code()));
	var infosudahbayar = escape($.trim($('#infosudahbayar').code()));
	var note_maintenance = escape($.trim($('#note_maintenance').code()));
	var pesan = "";
	var action = $('#frmdata').prop('action');

	$('#btnsimpan').button("loading");
	$('#hasil').removeClass();
	$('#hasil').hide(0);

	if (judul == '') {
		$('#judulsite').focus();
		$('#hasil').addClass("alert alert-danger");

		$('#hasil').html('Masukkan Judul Site');
		$('#hasil').show(500);
		$('#btnsimpan').button("reset");
		return false;
	}

	if (url.length == 0) {
		$('#alamatsite').focus();
		$('#hasil').addClass("alert alert-danger");

		$('#hasil').html('Masukkan Alamat Site');
		$('#hasil').show(500);
		$('#btnsimpan').button("reset");
		return false;
	}

	$.ajax({
		type: "POST",
		url: action,
		data: $('#frmdata').serialize() + '&config_headernotaemail=' + headernotaemail + '&config_notabelanja=' + notabelanja + '&config_notaregisweb=' + notaregisweb + '&config_infoshipping=' + infoshipping + '&config_infosudahbayar=' + infosudahbayar + '&config_note_maintenance=' + note_maintenance,
		cache: false,
		success: function (msg) {
			$('#btnsimpan').button("reset");
			hasilnya = msg.split("|");
			if (hasilnya[0] == "sukses")
				$('#hasil').addClass("alert alert-success");

			else
				$('#hasil').addClass("alert alert-danger");


			$('#hasil').html(hasilnya[2]);
			$('#hasil').show(0);

			$('html, body').animate({
				scrollTop: 0
			}, 'slow');
			return false;
		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});
}