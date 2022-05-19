$(function(){
	$('#warna').focus();
});

function kosongform(){
$('#warna').focus();
$('#warna').val("");
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
	var warna = $('#warna').val();
	var warnalama = $('#warnalama').val();
	var alias = $('#alias').val();
	var pesan = "";
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var action = $('#frmdata').attr('action');
	var datasimpan;
	if(warna.length==0){
		$('#warna').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Nama Warna</div>");
		$('#hasil').show(500);
		return false;
	}
	datasimpan = "aksi="+aksi+"&warna="+warna+"&warnalama="+warnalama+"&iddata="+iddata+'&alias='+alias;
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
			if(hasilnya[0]=="sukses"){
  			   if(hasilnya[1]=="input") kosongform();
			   hasilnya[2] = '<div class="alert alert-success">'+hasilnya[2]+'</div>';
			   $('#warnalama').val(warna);
			} else {
			   hasilnya[2] = '<div class="alert alert-danger">'+hasilnya[2]+'</div>';
			}
			$('#hasil').html(hasilnya[2]);
			return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}