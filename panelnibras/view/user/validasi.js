jQuery(document).ready(function(){
	$('#nama').focus();
});

function kosongform(){
$('#nama').focus();
$('.frm').each(function () {
 	$(this).val("");
});
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
	var nama = $('#nama').val();
	var user = $('#user').val();
	var pass = $('#pass').val();
	var grup = $('#grup').val();
	var sts	 = $('#status').val();
	var pesan = "";
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var action = $('#frmdata').attr('action');
	if(nama.length==0){
		$('#nama').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Nama</div>");
		$('#hasil').show(500);
		return false;
	}
	if(user.length==0){
		$('#user').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Username</div>");
		$('#hasil').show(500);
		return false;
	}
	if(pass.length==0){
		$('#pass').focus();
		$('#hasil').html("<div class=\"alert alert-danger\">Masukkan Password</div>");
		$('#hasil').show(500);
		return false;
	}
	if(grup=='0'){
		$('#grup').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Pilih Grup User</div>");
		$('#hasil').show(500);
		return false;
	}
	var datasimpan = "aksi="+aksi+"&user="+user+"&pass="+pass+"&nama="+escape(nama)+"&grup="+grup+"&status="+sts+"&iddata="+iddata;
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
			   hasilnya[2] = '<div class="alert alert-success">'+hasilnya[2]+'</div>';
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