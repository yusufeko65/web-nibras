$(function(){
	$('#slide').focus();
	$("#frmdata").submit(function(){
		$('#btnsimpan').before('<span class="loading"><img src="../assets/img/loading.gif" style="padding-left: 5px;" /> Tunggu Sebentar..</span>');
		var cek = checkdata();
		if(!cek) return false;
		else return true;
	 });
});

function kosongform(){
$('#slide').focus();
$('#slide').val("");
$('#filegbr').val("");
}
function checkdata(){
  var slide = $('#slide').val();
  var filegbr = $('#filegbr').val();
  var filelama = $('#filelama').val();
  var panjang = $('#panjang').val();
  var lebar = $('#lebar').val();
  //alert(isNaN(panjang));
   if(slide.length==0){
	$('#slide').focus();
	$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Nama slide</div>");
	$('#hasil').show(500);
	$('.loading').remove();	
	return false;
  }
  
  if(filelama==''){
    if(filegbr=='') {
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan File Gambar</div>");
		$('#hasil').show(500);
		$('.loading').remove();	
		return false;
	}
  }
  
  if(isNaN(panjang) || panjang =='') {
     $('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Panjang Ukuran slide</div>");
     $('#hasil').show(500);
	 $('.loading').remove();	
	 return false;
  }
  if(isNaN(lebar) || lebar == '') {
     $('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Lebar Ukuran slide</div>");
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
	
	$('#hasil').show(0);
	if(hasilnya[0]=="sukses") {
   	   if(hasilnya[1]=="input") kosongform();
	   $('#hasil').addClass('alert alert-success');
	} else {
	   $('#hasil').addClass('alert alert-danger');
	}
	$('#hasil').html(hasilnya[2]);
	$('.loading').remove();
	return false;
}