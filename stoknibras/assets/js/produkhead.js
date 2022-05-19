$(function(){
	$('.warna-lain-produk').enscroll({
		showOnHover: true,
		verticalTrackClass: 'track3',
		verticalHandleClass: 'handle3'
	});
});

function viewProduk(image,idwarna,idhead){
	
	var urlgbr = $('#url_image').val();
	var urlgbrzoom = urlgbr+'_zoom/zoom_gproduk'+image;
	var urlgbrdetail = urlgbr+'_detail/detail_gproduk'+image;
	$('#urlgbr').attr({
		"href" : urlgbrzoom
	});
	$('#urlgbr').addClass("zoom-produk");
	$('.cover-produk').attr("src",urlgbrdetail);
	$(".zoom-produk").colorbox({rel:'zoom-produk'});
	
}