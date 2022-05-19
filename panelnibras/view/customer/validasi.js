var action = $('#frmdata').attr('action');
function kosongform(){
  $('#kdgrup').focus();
  $('#kdgrup').val("");
  $('#grup').val("");
  $('#dropship').val("0");
  $('#target_beli').val("0");
  $('#target_aktif').val("0");
  $('#biaya_register').val("0");
  $('#biaya_perpanjang').val("0");
  $('#aktif_anggota').val("0");
  $('#tenggang_anggota').val("0");
  $('#status').val("1");
  $('#jml_seleksi').val("");
  $('#seleksi').val("-");
  $('.chk').each(function () {
    $(this).attr('checked', false);
  });
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

function approvedata(){
	$('#waiting').show(500);
	$.ajax({
 		type: "POST",
   		url: action,
    	data: $('#frmdata').serialize(),
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

function simpandata(){ //Proses Simpan
	
	$('#waiting').show(500);
	$.ajax({
 		type: "POST",
   		url: action,
    	data: $('#frmdata').serialize(),
 		cache: false,
    	success: function(msg){
		   alert(msg);
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

