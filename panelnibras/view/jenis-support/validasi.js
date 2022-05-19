jQuery(document).ready(function(){
	$('#propinsi').focus();
});

function kosongform(){
$('#propinsi').focus();
$('#propinsi').val("");
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
	var propinsi = $('#propinsi').val();
	var negara = $('#negara').val();
	var pesan = "";
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var action = $('#frmdata').attr('action');
	var datasimpan;
	if(propinsi.length==0){
		$('#propinsi').focus();
		$('#hasil').html("<div class=\"warning\"> Masukkan Nama Propinsi</div>");
		$('#hasil').show(500);
		return false;
	}
	if(negara==0){
		$('#negara').focus();
		$('#hasil').html("<div class=\"warning\"> Pilih Negara </div>");
		$('#hasil').show(500);
		return false;
	}
	datasimpan = "aksi="+aksi+"&propinsi="+propinsi+"&idnegara="+negara+"&iddata="+iddata;
	$('#waiting').show(500);
	$.ajax({
 		type: "POST",
   		url: action,
    	data: datasimpan,
 		cache: false,
    	success: function(msg){
			$('#waiting').hide(0);
			hasilnya = msg.split("|");
			$('#hasil').html(hasilnya[2]);
			$('#hasil').show(0).fadeOut(5000);
			if(hasilnya[0]=="sukses" && hasilnya[1]=="input") kosongform();
			return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}