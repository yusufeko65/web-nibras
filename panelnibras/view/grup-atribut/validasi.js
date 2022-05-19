$(function(){
	$('#grup').focus();
});

function kosongform(){
$('#grup').focus();
$('#grup').val("");
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
	var grup = $('#grup').val();
	var gruplama = $('#gruplama').val();
	var warna = $('#warna').val();
	var pesan = "";
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var action = $('#frmdata').attr('action');
	var datasimpan;
	if(grup.length==0){
		$('#grup').focus();
		$('#hasil').html("<div class=\"warning\"> Masukkan Nama Grup Atribut</div>");
		$('#hasil').show(500);
		return false;
	}
	datasimpan = "aksi="+aksi+"&grup="+grup+"&warna="+warna+"&gruplama="+gruplama+"&iddata="+iddata;
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