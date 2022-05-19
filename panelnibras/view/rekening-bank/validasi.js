$(function(){
	$('#norek').focus();
});

function kosongform(){
$('#norek').focus();
$('#norek').val("");
$('#cabang').val("");
$('#atasnorek').val("");
$('#bank').val("0");
$('#atasnama').val("");
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
	var norek = $('#norek').val();
	var bank = $('#bank').val();
	var atasnama = $('#atasnama').val();
	var cabang = $('#cabang').val();
	var status = $('#status').val();
	var pesan = "";
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var status = $('#status').val();
	var action = $('#frmdata').attr('action');
	
	var datasimpan;
	if(norek.length==0){
		$('#norek').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan No. Rekening </div>");
		$('#hasil').show(500);
		return false;
	}
	if(bank=='0'){
		$('#bank').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Pilih Bank </div>");
		$('#hasil').show(500);
		return false;
	}
	if(cabang==''){
		$('#cabang').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Cabang </div>");
		$('#hasil').show(500);
		return false;
	}
	if(atasnama==''){
		$('#atasnama').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Atas Nama Rekening </div>");
		$('#hasil').show(500);
		return false;
	}
	datasimpan = "aksi="+aksi+"&norek="+norek+"&bank="+bank+"&cabang="+cabang+"&atasnama="+atasnama+"&status="+status+"&iddata="+iddata+'&status='+status;
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
