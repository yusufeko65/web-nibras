jQuery(document).ready(function(){
   
    $("#btnnext").click(function(){
	   var redirect = $("#url_redirect").val();
	   var datanya = $('#frmmetode').serialize();
	   var urlnya = $('#frmmetode').prop("action");
	   
	    $('#hasil').removeClass();
		$('#hasil').hide();
		$("#btnnext").button("loading");
	     $.ajax({
			type: "POST",
			url: urlnya,
			data: datanya,
			cache: false,
			success: function(msg){
			  alert(msg);
			   hasilnya = msg.split("|");
			  
			   if($.trim(hasilnya[0]) == 'gagal') {
			      $("#btnnext").button("reset");
			      $('#hasil').addClass("alert alert-danger");
			      $('#hasil').html(hasilnya[1]);
			      $('#hasil').show(0);
				  $('html, body').animate({ scrollTop: 0 }, 'slow');
				  return false;
			   } else {
			      location = redirect;
			   }
			   
		    }  
	      });  
		 
		 
     });

});