var action = $('#frmdata').attr('action');
function kosongform(){
  $('#remail').focus();
  $('.inputbox').each(function () {
    $(this).va("");
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
  var id 			= $('#iddata').val();
  var dataapprove 	= 'aksi=approve&id=' + id;
  alert(action);
  var a = confirm('Apakah ingin mengapprove data ini?');
  if (a == true) {
	  $.ajax({
		type: "POST",
		url: action,
		data: dataapprove,
		success: function(msg){
		   alert(msg)
			hasilnya = msg.split("|");
			$('#hasil').html(hasilnya[2]);
			$('#hasil').show(0).fadeOut(5000);
			//if(hasilnya[0]=="gagal") alert('Error' + hasilnya[1]); 
			//tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/' ?>');
		
			return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
			}  
	   });  
   }
}

function simpandata(){ //Proses Simpan
	
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

