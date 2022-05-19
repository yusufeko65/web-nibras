$(function(){
	$('#bank').focus();
	$("#frmdata").submit(function(){
	    
		$('#btnsimpan').before('<span class="loading"><img src="../assets/img/loading.gif" style="padding-left: 5px;" /> Tunggu Sebentar..</span>');
		var cek = checkdata();
		if(!cek) return false;
		else return true;
	 });
});

function kosongform(){
$('#bank').focus();
$('#bank').val("");
$('#filelogo').val("");
}
function checkdata(){
  var bank = $('#bank').val();
  var filelogo = $('#filelogo').val();
  var filelama = $('#filelama').val();
   if(bank.length==0){
	$('#bank').focus();
	$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Nama Bank</div>");
	$('#hasil').show(500);
	$('.loading').remove();	
	return false;
  }

  return true;
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

function suksesdata(pesan){
	hasilnya = pesan.split("|");
	
	$('#hasil').show(0);
	if(hasilnya[0]=="sukses") {
   	   if(hasilnya[1]=="input") kosongform();
	   $('#hasil').html('<div class="alert alert-success">'+hasilnya[2]+'</div>');   
	} else {
	   $('#hasil').html('<div class="alert alert-danger">'+hasilnya[2]+'</div>');   
	}
	$('.loading').remove();
	return false;
}