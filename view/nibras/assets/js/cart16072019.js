var jml = 0;
var totCart = 0;
var jmlwarna = 0;
$(function(){
   
	$('#button-beli').click(function(){
		pesanproduk();
		return false;
	});
   
	$('#button-update').click(function(){
		updateproduk();
		return false;
	});
	$("#frmpesan").submit(function(event){
	   event.preventDefault();
	   pesanproduk();	   
	});
	
	$(".zoom-produk").colorbox({rel:'zoom-produk'});
	
});

function pesanproduk() {
	jml = $('#jumlah').val();
	var frm = $('#frmpesan').serialize();
	var url = $('#url_cart').val();
	var urlweb = $('#url_web').val();
	$('#button-beli').prop('disabled', true);
	if(jml < 1 || isNaN(jml)) {
		alert('Error','error','Masukkan Jumlah Pesanan Anda','button-beli');
		return false;
	}
	
	$.post(url, frm , function(result){
		
		if($.trim(result.status) == 'success') {
	      
			$('.loading').remove();
			if(result.qty != '') {
				totCart = parseInt(result.qty);
			} else {
				totCart = 0;
			}
			
			$('#button-beli').prop('disabled', false);
			titlealert = 'Success';
			tipealert = 'success';
			
			$('#cart-count').html(totCart + ' item - Rp. '+result.total);
			$('.list-cart').load(urlweb + 'cart/info');
			
		} else {
	        titlealert = 'Error';
			tipealert  = 'error';
			$('.loading').remove();
		
		}
		alert(titlealert,tipealert,result.msg,'button-beli');
       
	}, 'json');
  
}

function updateproduk(){
	var url = $('#url_cart').val();
	var urlweb = $('#url_web_cart').val();
	var frm = $('#frmcart').serialize();
	
	$('#button-update').prop('disabled', true);
	
	$.post(url, frm , function(result){
		
		if($.trim(result.status) == 'success') {
			
			
			titlealert = 'Success';
			tipealert = 'success';
			urllocation = urlweb;
			
		} else {
			
			
			titlealert = 'Error';
			tipealert = 'error';
			urllocation = '';
			
		}
		
		alert(titlealert,tipealert,result.msg,'button-update',urllocation);
		$('html, body').animate({ scrollTop: 0 }, 'slow');
		
	}, 'json');
}

function hapusCart(zdata){
	var del = 'aksi=del&data=' + zdata;
	var urldel = $('#url_del').val();
	var urlweb = $('#url_web_cart').val();
   
	
	$('#btnhapuscart').prop('disabled', true);
	$.post(urldel, del , function(result){
		if($.trim(result.status) == 'success') {
			titlealert = 'Success';
			tipealert = 'success';
			urllocation = urlweb;
		} else {
		   
			titlealert = 'Error';
			tipealert = 'error';
			urllocation = '';
		  
		}
		alert(titlealert,tipealert,result.msg,'btnhapuscart',urllocation);
		$('html, body').animate({ scrollTop: 0 }, 'slow');
	}, 'json');
   
}
function viewImageStok(gbr,warna,headproduk) {
	
	var urlgbr = $('#url_image').val();
	var urlgbrzoom = urlgbr+'_zoom/zoom_gproduk'+gbr;
	var urlgbrdetail = urlgbr+'_detail/detail_gproduk'+gbr;
	var ukuran = $('#ukuran').val();

	$('#idwarna').val(warna);
	$('#loadinggbr').show();
	$('#urlgbr').attr({
		"href" : urlgbrzoom
	});
	$('#urlgbr').addClass("zoom-produk");
	$('.cover-produk').attr("src",urlgbrdetail);
	$('#image_product').val(gbr);
	$(".zoom-produk").colorbox({rel:'zoom-produk'});
	$('#hasil').removeClass();
	$('#hasil').html('');
	$('#hasil').hide();
	if(headproduk > 0) {
		caristok();  
	}
	
}
function caristok(){
	var label = '';
	var dataload = $('#frmpesan').serialize();	
	var urlweb = $('#url_produk').val()+'?load=stok';
	
	$('#button-beli').prop('disabled', true);
	$('#loading').after('<span class="loading">Tunggu...</span>');
	$('#ket_stok').html('Tunggu...sedang memuat stok');
	$.post(urlweb, dataload , function(result){
		$('.loading').remove();
		$('#button-beli').prop('disabled', false);
		$('#ket_stok').val(result['stok']);
	},'json');
	
}