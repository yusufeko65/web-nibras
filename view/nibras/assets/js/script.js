var url_wil = $('#url_wil').val();
$(function () {
	$(".zoom-produk").colorbox({
		rel: 'zoom-produk'
	});
	$('#plat-cart').popover({
		html: true,
		placement: "bottom",
		trigger: "click",
		content: function () {
			return $(this).siblings('.pop-content').html()
		}
	});

	$("#formfilters").submit(function (event) {
		event.preventDefault();
		filterproduk();
	});

	$('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
		if (!$(this).next().hasClass('show')) {
			$(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
		}
		var $subMenu = $(this).next(".dropdown-menu");
		$subMenu.toggleClass('show');


		$(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
			$('.dropdown-submenu .show').removeClass("show");
		});


		return false;
	});


});

function filterproduk() {
	var kategori = $('#fcat').val();
	var warna = $('#fwarna').val();
	var ukuran = $('#fukuran').val();
	var data = [];
	var variable = '';
	var url = $("#formfilters").attr("action");

	if (kategori != '' && kategori != '0') {
		url += kategori;
	}


	if ((warna != '' && warna != '0')) {
		data.push('fwarna=' + warna);
	}
	if (ukuran != '' && ukuran != '0') {
		data.push('fukuran=' + ukuran);
	}
	if (data.length > 0) {
		variable = '?' + data.join('&');
	}
	location.href = url + variable;
}

function pilihwarna(ukuran, produk_id, headproduk, persen, sale) {
	var urlimage = $('#url_image').val();
	var hargasatuan = $('#hargasatuan').val();
	/* var hargacust = $('#hargacust').val();*/
	var urlproduk = $('#url_produk').val() + '/?load=warna&ukuran=' + ukuran + '&produk_id=' + produk_id;

	$('#hasil').removeClass();
	$('#hasil').html('');
	$('#hasil').hide();
	$('#button-beli').prop('disabled', true);
	$('.warna-lain-produk').after("<div class='loading'>Tunggu..sedang memuat warna</div>");
	$('.warna-lain-produk').html('');

	$.getJSON(urlproduk, function (result) {

		$('.loading').remove();
		$('#button-beli').prop('disabled', false);

		textwarna = '<div class="input-produk"><input type="radio" name="warna" id="warna" value="0" checked></div>';

		for (i = 0; i < result['warna'].length; i++) {
			textwarna += '<div class="input-produk"><input title="' + result['warna'][i].warna + '" class="warna" type="radio" id="warna' + result['warna'][i].idwarna + '" name="warna" value="' + result['warna'][i].idwarna + '" rel="' + result['warna'][i].gbr + '" onchange="viewImageStok(\'' + result['warna'][i].gbr + '\',\'' + result['warna'][i].idwarna + '\',\'' + produk_id + '\');$(\'#nama_warna\').html($(this).prop(\'title\'))" />';

			textwarna += '<label title="' + result['warna'][i].warna + '" class="produk_warna" for="warna' + result['warna'][i].idwarna + '" style="background-image:url(\'' + urlimage + '_small/small_gproduk' + result['warna'][i].gbr + '\');"></label>';
			textwarna += '</div>';

		}



		$('.warna-lain-produk').html(textwarna);

		$('#jmlwarna').val(result['jmlwarna'] + ' warna');

		$('#ket_stok').val(result['stok'] + ' pcs');

		$('#nama_warna').html('-');
		caristok();
		/*
		tambahan_harga = result['warna'][0]['tambahan_harga'];
		harganormal = parseInt(hargasatuan) + parseInt(tambahan_harga);
		persen = parseInt(persen);
		if (persen > 0) {

			harganormal_diskon = harganormal - ((harganormal * persen) / 100);

			$('#caption_price_old').html(convertToRupiah(harganormal));
			$('#caption_price').html(convertToRupiah(harganormal_diskon));
		} else {

			$('#caption_price').html(convertToRupiah(harganormal));
		}
		*/

	});
	return false;
}

function pilihgambar(warna, gambar, jmlstok, ukuran) {
	var dataload = 'load=gambar&warna=' + warna + '&gambar=' + gambar + '&ukuran=' + ukuran + '&jmlstok=' + jmlstok;
	var urlproduk = $('#url_produk').val();
	var urlimage = $('#url_image').val();
	$('#button-beli').before('<span class="loading">Loading..</span>');

	$.ajax({
		type: "GET",
		url: urlproduk,
		data: dataload,
		cache: false,
		success: function (msg) {

			$('.loading').remove();
			hasilnya = msg.split("|");
			$('#idimage').prop('src', urlimage + hasilnya[0]);
			$('#urlgbr').prop('href', hasilnya[1]);
			$('.ketstok').html(hasilnya[2]);
			return false;
		},
		error: function (e) {
			alert('Error: ' + e);
		}
	});
}

function alert(title, tipe, content, idbutton, redirect = '') {
	var icon = '';
	if (tipe == 'error') {
		icon = '<i class="fa fa-times-circle fa-3x text-danger" aria-hidden="true"></i><br><br>';
	} else {
		icon = '<i class="fa fa-check-circle fa-3x text-success" aria-hidden="true"></i><br><br>';
	}
	var html = '<div class="modal-dialog modal-dialog-centered" role="document">';
	html += '<div class="modal-content">';
	html += '<div class="modal-header">';
	html += '<h6 class="modal-title" id="alertmodal">' + title + '</h6>';
	html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
	html += '<span aria-hidden="true">&times;</span>';
	html += '</button>';
	html += '</div>';
	html += '<div class="modal-body">';
	html += '<p class="text-center">' + icon + content + '</p>';
	html += '</div>';
	html += '<div class="modal-footer">';
	html += '<button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Close</button>';
	html += '</div>';
	html += '</div>';
	html += '</div>';

	$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + html + '</div>').modal().on("hidden.bs.modal", function () {
		$(this).remove();
		$('#' + idbutton).prop('disabled', false);
		if (redirect != '') {
			location.href = redirect;
		}
	});
}

function findKabupaten(id, idselect, selectedvalue = '0') {
	var load = 'load=kabupaten';
	var data = 'propinsi=' + id;

	$.ajax({
		type: "GET",
		url: url_wil + '/?' + load,
		data: data,
		dataType: 'json',
		success: function (result) {
			var html = '<option value="0">- Kota/Kabupaten -</option>';

			for (i = 0; i < result.length; i++) {
				html += '<option value="' + result[i]['kabupaten_id'] + '"';
				if (selectedvalue == result[i]['kabupaten_id']) {
					html += ' selected ';
				}
				html += '>' + result[i]['kabupaten_nama'] + '</option>';
			}
			$('.loading').remove();

			$('#' + idselect).html(html);
		}
	});

	return false;
}

function findKecamatan(id, idselect, selectedvalue) {
	var load = 'load=kecamatan';
	var data = 'kabupaten=' + id;

	$.ajax({
		type: "GET",
		url: url_wil + '/?' + load,
		data: data,
		dataType: 'json',
		success: function (result) {
			var html = '<option value="0">- Kecamatan -</option>';

			for (i = 0; i < result.length; i++) {
				html += '<option value="' + result[i]['kecamatan_id'] + '"';
				if (selectedvalue == result[i]['kecamatan_id']) {
					html += ' selected ';
				}
				html += '>' + result[i]['kecamatan_nama'] + '</option>';
			}
			$('.loading').remove();
			$('#' + idselect).html(html);
		}
	});

	return false;
}
/*
 * JavaScript Code Snippet
 * Convert Number to Rupiah & vice versa
 * https://gist.github.com/845309
 *
 * Copyright 2011-2012, Faisalman
 * Licensed under The MIT License
 * http://www.opensource.org/licenses/mit-license  
 *
 */

function convertToRupiah(angka) {
	var rupiah = '';
	var angkarev = angka.toString().split('').reverse().join('');
	for (var i = 0; i < angkarev.length; i++)
		if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
	return 'Rp. ' + rupiah.split('', rupiah.length - 1).reverse().join('');
}