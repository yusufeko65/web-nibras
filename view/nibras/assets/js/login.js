$(function(){
  $("#formlogin").submit(function(event){
	   event.preventDefault();
	   proseslogin();
	   
  });
});
function proseslogin(){
	var url = $('#formlogin').prop("action");
	var frm = $('#formlogin').serialize();
	var redirect = $('#url_redirect').val();
	$('#tbllogin').button('loading');
	$('#hasil').hide();
	$('#hasil').removeClass();
	if($('#lemailuser').val() == '') {
		alert('Error','error','Masukkan Email Anda','tbllogin');
		return false;
	}
	if($('#lpassuser').val() == '') {
		alert('Error','error','Masukkan Password Anda','tbllogin');
		return false;
	}
	$.ajax({
 		type: "POST",
   		url: url,
		data: frm,
 		dataType: 'json',
		success: function(msg){
			
			
			$('#hasil').show(0);
			if(msg['status'] =='success') {
				$('.loading').remove();
				location = redirect;
				$('#tbllogin').button('reset');
				
			} else {
				alert('Error','error',msg['result'],'tbllogin');
				
			}
			$('#hasil').html(msg['result']);
			$('#btnok').button("reset");
			$('.loading').remove();
			return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
	
	
}