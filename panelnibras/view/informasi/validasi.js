$(function(){
	var mnheadline = $('#headline');
	$('#judul').focus();
	mnheadline.change(function(){
	   if(mnheadline.val() == '0'){
	       $("#trmenuatas").show();
	   } else {
	      $("#trmenuatas").hide();
	   }
	});

    $('#keterangan').summernote({
     height: "300px"
    });
});


function kosongform(){
$('#judul').focus();
$('#judul').val("");
$('#aliasurl').val("");
//CKEDITOR.instances.keterangan.setData('');
}
function disableEnterKey(e){ //Disable Tekan Enter
    var key;
     if(window.event)
          key = window.event.keyCode;     //IE
     else
          key = e.which;     //firefox

     if(key == 13){ // Jika ditekan tombol enter
		  simpandata(); // Panggil fungsi simpandata()
          return false;
     } else {
          return true;
	 }
}
function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
function simpandata(){ //Proses Simpan
	var judul = escape($.trim($('#judul').val()));
	var aliasurl = escape($.trim($('#aliasurl').val()));
	
	var keterangan = $('#keterangan').code();
	var status = $('#status').val();
	var headline = $('#headline').val();
	var menuatas = $('#menuatas').val();
	var pesan = "";
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var action = $('#frmdata').prop('action');
	
	if(judul.length==0){
		$('#judul').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Judul Informasi </div>");
		$('html, body').animate({ scrollTop: 0 }, 'slow');
		$('#hasil').show(500);
		return false;
	}
	
	keterangan = keterangan.replace('<iframe', '[iframe');
    keterangan = keterangan.replace('></iframe>', '][/iframe>');
    keterangan = keterangan.replace('][/iframe>', '][/iframe]');
	keterangan = escape(keterangan);
	
	var datasimpan = "aksi="+aksi+"&judul="+judul+"&aliasurl="+aliasurl+"&status="+status+"&keterangan="+keterangan+"&iddata="+iddata;
	$('#loadingweb').show(500);
    
	$.ajax({
 		type: "POST",
   		url: action,
		crossDomain: true,
    	data: datasimpan,
 		cache: false,
    	success: function(msg){
		    
			$('#loadingweb').hide(0);
			hasilnya = msg.split("|");
			$('#hasil').removeClass();
			$('#hasil').show(0).fadeOut(5000);
			
			if(hasilnya[0]=="sukses") {
  			   if(hasilnya[1]=="input") kosongform();
			   $('#hasil').addClass('alert alert-success');
			} else {
			   $('#hasil').addClass('alert alert-danger');
			}
			$('#hasil').html(hasilnya[2]);
			$('html, body').animate({ scrollTop: 0 }, 'slow');
			return false;
		},  
			error: function(e){  
      		//alert('Error: ' + e);  
			console.log(e)
      	}  
  	});  
}