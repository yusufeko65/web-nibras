var action = $('#frmdata').attr('action');
jQuery(document).ready(function(){
	$('#kecamatan').focus();
	$('#negara').change(function() {
		$('#propinsi').load(action +'?load=propinsi&idp=' + this.value);
		return false;
	});
	$('#propinsi').change(function() {
		$('#kabupaten').load(action +'?load=kabupaten&idp=' + this.value);
		return false;
	});
	$('#kabupaten').change(function() {
		$('#kecamatan').load(action +'?load=kecamatan&idp=' + this.value);
		return false;
	});
});

function kosongform(){
$('#negara').focus();
$('.tarif').each(function () {
   $(this).val('');
});
$('.tarifberikut').each(function () {
   $(this).val('');
});
$('.keterangan').each(function () {
   $(this).val('');
});
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
function simpandata(){ //Proses Simpan
	var kabupaten   = $('#kabupaten').val();
	var propinsi    = $('#propinsi').val();
	var kecamatan   = $('#kecamatan').val();
	var negara      = $('#negara').val();
	var pesan       = "";
	var aksi        = $('#aksi').val();
	var iddata      = $('#iddata').val();
	var t  = [];
	var s  = [];
	var k  = [];
	var tb = [];
	
	$('.tarif').each(function () {
    	//if($(this).val()!=''){
			t.push($(this).val());
		//}
	
	});
	$('.tarifberikut').each(function () {
    	//if($(this).val()!=''){
			tb.push($(this).val());
		//}
	
	});
	$('.servis').each(function () {
		s.push($(this).val());
	});
	
	$('.keterangan').each(function () {
		k.push($(this).val());
	});
	
	var tarif        = t.join(':');
	var tarifberikut = tb.join(':');
	var servis       = s.join(':');
	var keterangan   = k.join(':');
	
	if(propinsi==0){
		$('#propinsi').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Pilih Propinsi </div>");
		$('#hasil').show(500);
		return false;
	}
	if(kabupaten==0){
		$('#kabupaten').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Pilih Kotamadya/Kabupaten</div>");
		$('#hasil').show(500);
		return false;
	}
	if(kecamatan.length==0){
		$('#kecamatan').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Nama Kecamatan</div>");
		$('#hasil').show(500);
		return false;
	}
	if(tarif==''){
	    $('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Tarif Wahana </div>");
		$('#hasil').show(500);
	}
	var datasimpan = "aksi="+aksi+"&tarif="+tarif+"&tarifberikut="+tarifberikut+"&servis="+servis+"&keterangan="+keterangan+
	                 "&negara="+negara+"&propinsi="+propinsi+"&kecamatan="+kecamatan+
					 "&kabupaten="+kabupaten+"&iddata="+iddata;
	$('#loadingweb').show(500);
	$.ajax({
 		type: "POST",
   		url: action,
    	data: datasimpan,
 		cache: false,
    	success: function(msg){
			$('#loadingweb').hide(0);
			hasilnya = msg.split("|");
			$('#hasil').show(0).fadeOut(5000);
			if(hasilnya[0]=="sukses") {
   			   if(hasilnya[1]=="input") kosongform();
			   $('#hasil').html('<div class="alert alert-success">'+hasilnya[2]+'</div>');
			} else {
			   $('#hasil').html('<div class="alert alert-danger">'+hasilnya[2]+'</div>');   
			}
			return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}