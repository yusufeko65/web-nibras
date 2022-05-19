$(function(){
	$('#banner').focus();
	$("#frmdata").submit(function(){
		$('#tombollihat').after('<span class="loading"><img src="../images/22.gif" style="padding-left: 5px;" /> Tunggu Sebentar..</span>');
		var cek = checkdata();
		if(!cek) return false;
		else return true;
	 });
});

function kosongform(){
$('#banner').focus();
$('#banner').val("");
$('#filegbr').val("");
}
function checkdata(){
  var banner = $('#banner').val();
  var filegbr = $('#filegbr').val();
  var filelama = $('#filelama').val();
  var panjang = $('#panjang').val();
  var lebar = $('#lebar').val();
  //alert(isNaN(panjang));
   if(banner.length==0){
	$('#banner').focus();
	$('#hasil').html("<div class=\"warning\"> Masukkan Nama Banner</div>");
	$('#hasil').show(500);
	$('.loading').remove();	
	return false;
  }
  
  if(filelama==''){
    if(filegbr=='') {
		$('#hasil').html("<div class=\"warning\"> Masukkan File Gambar</div>");
		$('#hasil').show(500);
		$('.loading').remove();	
		return false;
	}
  }
  
  if(isNaN(panjang) || panjang =='') {
     $('#hasil').html("<div class=\"warning\"> Masukkan Panjang Ukuran Banner</div>");
     $('#hasil').show(500);
	 $('.loading').remove();	
	 return false;
  }
  if(isNaN(lebar) || lebar == '') {
     $('#hasil').html("<div class=\"warning\"> Masukkan Lebar Ukuran Banner</div>");
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
   //alert(pesan);
	hasilnya = pesan.split("|");
	$('#hasil').html(hasilnya[2]);
	$('#hasil').show(0);
	if(hasilnya[0]=="sukses" && hasilnya[1]=="input") kosongform();
	$('.loading').remove();
	return false;
}