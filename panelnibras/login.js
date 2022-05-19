  var action = $('#frmdata').attr('action');
$(function(){

  $('#username').focus();
  $("#tbllogin").click(function(){
    $('#loadingweb').show();
    var cek = cekdata();
	if(!cek) return false;
	  else login();
    });
  
});

function disableEnterKey(e){ //Disable Tekan Enter
    var key;
     if(window.event)
          key = window.event.keyCode;     //IE
     else
          key = e.which;     //firefox

     if(key == 13){ // Jika ditekan tombol enter
		  var cek = cekdata();
		  if(!cek) return false;
		  else login();
		
     } else {
          return true;
	 }
}

function cekdata(){
 var username = $('#username').val();
 var password = $('#password').val();
 if(username.length<4) {
    $('#username').focus();
    $('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Username, minimal 4 karakter</div>");
    $('#hasil').show(500);
    return false;
  }
  if(password==''){
    $('#password').focus();
	$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Password</div>");
	$('#hasil').show(500);
	return false;
  }
  return true;
}
function hasildata(pesan){
   alert(pesan);
   return false;
}
function login(){
	var username = $('#username').val();
	var password = $('#password').val();
	var pesan = "";
	var aksi = $('#aksi').val();
	var datalogin = "aksi="+aksi+"&username="+escape(username)+"&password="+escape(password);
	$.ajax({
 		type: "POST",
   		url: action,
    	data: datalogin,
 		cache: false,
    	success: function(msg){
		   //alert(action);
			$('#loadingweb').hide(0);
			hasilnya = msg.split("|");
			if(hasilnya[0]=='gagal') {
			   $('#hasil').html("<div class=\"alert alert-danger\">" + hasilnya[1] + "</div>");
			   $('#hasil').show(0);   
			} else {
			   location='/nibrascoid/controletnik/home';
			   $('#hasil').html(hasilnya[1]);
			}
			
			return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}