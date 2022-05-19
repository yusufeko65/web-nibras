var action = $('#frmdata').attr('action');
$(function(){
	$('#atribut').focus();
	jscolor.init();
	//$('#grup').change(function() {
    //  	var value = this.value;
	//	var sts = value.split("::");
	//	if(sts[1] == '1') $('.sembunyi').show(0);
	//	else $('.sembunyi').hide(0);
	//	$('#atribut').focus();
	//	return false;
	//});
});

function kosongform(){
$('#atribut').focus();
$('#atribut').val("");
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
	var atribut = $('#atribut').val();
	var atributlama = $('#atributlama').val();
	var warna = $('#warna').val();
	var grup = $('#grup').val();
	var pesan = "";
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var action = $('#frmdata').attr('action');
	var grp = grup.split("::");
	if(grp[1] != 1) warna = '-';
	if(atribut.length==0){
		$('#atribut').focus();
		$('#hasil').html("<div class=\"warning\"> Masukkan Nama Atribut</div>");
		$('#hasil').show(500);
		return false;
	}
	
	if(grup == 0){
		$('#hasil').html("<div class=\"warning\"> Masukkan Nama Grup Atribut</div>");
		$('#hasil').show(500);
		return false;
	}
	
	var datasimpan = "aksi="+aksi+"&grup="+grp[0]+"&warna="+warna+"&atributlama="+atributlama+"&atribut="+atribut+"&iddata="+iddata;
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
function hapus(){ //Proses Simpan

	var iddata = $('#iddata').val();
	var datahapus = "aksi=hapus&id="+iddata;
	$('#waiting').show(500);
	$.ajax({
 		type: "POST",
   		url: action,
    	data: datahapus,
 		cache: false,
    	success: function(msg){
		    alert(msg);
			$('#waiting').hide(0);
			hasilnya = msg.split("|");
			$('#hasil').html(hasilnya[2]);
			$('#hasil').show(0).fadeOut(5000);
			if(hasilnya[0]=="sukses") tampilkan(URL_PROGRAM_ADMIN + 'atribut/');;
			return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}