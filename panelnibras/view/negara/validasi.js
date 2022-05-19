$(function(){
	$('#nama').focus();
});

function kosongform(){
$('#nama').focus();
$('#nama').val("");
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
	var pesan = "";
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var action = $('#frmdata').attr('action');
	var datasimpan;
	if(nama.length==0){
		$('#nama').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Nama</div>");
		$('#hasil').show(500);
		return false;
	}
	datasimpan = "aksi="+aksi+"&negara="+nama+"&iddata="+iddata;
	$('#waiting').show(500);
	$.ajax({
 		type: "POST",
   		url: action,
    	data: datasimpan,
 		cache: false,
    	success: function(msg){
			
			$('#waiting').hide(0);
			hasilnya = msg.split("|");
			
			$('#hasil').show(0).fadeOut(5000);
			if(hasilnya[0]=="sukses" && hasilnya[1]=="input") kosongform();
			if(hasilnya[0]=='sukses') {
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