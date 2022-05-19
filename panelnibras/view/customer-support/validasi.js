$(function(){
	$('#nama').focus();
});

function kosongform(){
$('#nama').focus();
$('#nama').val("");
$('#jenis').val("0");
$('#akun').val("");
$('#status').val("0");
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
	var nama = $('#nama').val();
	var jenis = $('#jenis').val();
	var akun = $('#akun').val();
	var status = $('#status').val();
	var pesan = "";
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var action = $('#frmdata').attr('action');
	var datasimpan;
	if(nama.length==0){
		$('#nama').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Nama CS </div>");
		$('#hasil').show(500);
		return false;
	}
	if(jenis=='0'){
		$('#jenis').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Pilih Jenis Support </div>");
		$('#hasil').show(500);
		return false;
	}
	if(akun==''){
		$('#akun').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Akun </div>");
		$('#hasil').show(500);
		return false;
	}
	
	datasimpan = "aksi="+aksi+"&nama="+nama+"&jenis="+jenis+"&akun="+akun+"&status="+status+"&iddata="+iddata;
	$('#loadingweb').show(500);
	$.ajax({
 		type: "POST",
   		url: action,
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
			return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}
