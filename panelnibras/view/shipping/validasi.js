var action = $('#frmdata').attr('action');
jQuery(document).ready(function(){
	$('#shipping_kode').focus();
	$("#frmdata").submit(function(event){
		event.preventDefault();
		simpandata();
	});
});

function kosongform(){
	$('#shipping_kode').focus();
	$('.tarif').each(function () {
	   $(this).val('');
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
function simpandata(){ //Proses Simpan
	$('#hasil').removeClass();
	$('#loadingweb').show(500);
	var data = new FormData(frmdata);
	
	$.ajax({
 		method: "POST",
   		url: action,
    	data: data,
		processData:false,
		contentType:false,
		dataType: 'json',
    	success: function(msg){
			$('#loadingweb').hide(0);
			
			$('#hasil').show(0).fadeOut(5000);
			if(msg['status']=="success") {
				if($('#aksi').val()=="tambah") {
				   kosongform();
				} else {
					location.reload();
				}	
			   $('#hasil').addClass("alert alert-success");
			} else {
			   $('#hasil').addClass("alert alert-danger");
			}
			$('#hasil').html(msg['result']);
			$('html, body').animate({ scrollTop: 0 }, 'slow');
			return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}