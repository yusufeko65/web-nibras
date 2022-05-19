jQuery(document).ready(function(){
  $("#frmpassword").keypress(function(e) {
     if (e.which == 13) {
        resetpass();
		return false;
     }
  });

  $("#btnok").click(function(){
		resetpass();
  });
});

function resetpass(){
   var email = $('#lemail').val();
   var capca = $('#capcaku').val();
   var action 		= $('#frmpassword').prop('action');
   $('#btnok').after('<span class="loading"> Tunggu Sebentar..</span>');
   $('#btnok').button("loading");
   if(email == '') {
     $('#hasil').show();
	 $('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Email Anda</div>");
	 $('#lemail').focus();
	 $('.loading').remove();
	  return false;
   }
   
   if(capca == '') {
     $('#hasil').show();
	 $('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Kode Sekuriti</div>");
	 $('#capcaku').focus();
	 $('.loading').remove();
   }

   $.ajax({
		type: "POST",
		url: action,
		data: $('#frmpassword').serialize(),
		dataType: 'json',
		success: function(msg){
			$('#loadsimpan').hide();
			$('#hasil').show(0);
			$('#btnok').button("reset");
			
			
			if( $.trim(msg['status']) == "success" ) {
			    $('#frmpassword')[0].reset();
				$('#hasil').addClass("alert alert-success");
				$('#hasil').html(msg['result']);
				location = $('#url_web').val()+'lupa-password';
			}
			else 
			{
				$('#hasil').addClass("alert alert-danger");
				$('#hasil').html(msg['result']);
			}	
			
			
			$('.loading').remove();
			return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
		}  
	});  
   
}
