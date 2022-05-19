$(function(){
	$('#kategori').focus();
	$('#keterangan').summernote({
     height: "300px"
    });
	$("#frmdata").submit(function(){
		$('#btnsimpan').before('<span class="loading"><img src="../assets/img/loading.gif" style="padding-left: 5px;" /> Tunggu Sebentar..</span>');
		var cek = checkdata();
		if(!cek) return false;
		else return true;
	});
});

function kosongform(){
$('#kategori').focus();
$('#kategori').val("");
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
function checkdata(){
    var kategori = $('#kategori').val();
	var aliasurl = $('#aliasurl').val();
	var urutan   = $('#urutan').val();
	var induk    = $('#induk').val();
	var pesan    = "";
	var aksi     = $('#aksi').val();
	var iddata   = $('#iddata').val();
	var action   = $('#frmdata').attr('action');
	var datasimpan;
	if(kategori.length==0){
		$('#kategori').focus();
		$('#hasil').html("<div class=\"alert alert-danger\">Masukkan Nama Kategori</div>");
		$('#hasil').show(500);
		return false;
	}

  return true;
}
function simpandata(){ //Proses Simpan
	var kategori = $('#kategori').val();
	var aliasurl = $('#aliasurl').val();
	var induk = $('#induk').val();
	var urutan = $('#urutan').val();
	var pesan = "";
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var action = $('#frmdata').attr('action');
	var datasimpan;
	if(kategori.length==0){
		$('#kategori').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Nama Kategori");
		$('#hasil').show(500);
		return false;
	}
	datasimpan = "aksi="+aksi+"&aliasurl="+escape(aliasurl)+"&kategori="+escape(kategori)+"&induk="+induk+"&iddata="+iddata+"&urutan="+urutan;
	$('#loadingweb').show(500);
	$.ajax({
 		type: "POST",
   		url: action,
    	data: datasimpan,
 		cache: false,
    	success: function(msg){
		    alert(msg);
			$('#loadingweb').hide(0);
			hasilnya = msg.split("|");
			
			$('#hasil').show(0).fadeOut(5000);
			if(hasilnya[0]=="sukses") {
  			   if(hasilnya[1]=="input") kosongform();
			   hasilnya[2] = '<div class="alert alert-success">' + hasilnya[2] + '</div>';
			} else {
			   hasilnya[2] = '<div class="alert alert-danger">' + hasilnya[2] + '</div>';
			}
			$('#hasil').html(hasilnya[2]);
			return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
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