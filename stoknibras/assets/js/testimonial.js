$(function(){
    $('#knama').focus();
	$("#btnok").click(function(){
	    simpandata();
		return true;
	});
	
});
function kosongform(){
   $('.forms').each(function () {
	  $(this).val("");
   });
   $('#knama').focus();
}
function simpandata(){
  var action = $('#frmkomentar').prop('action') + 'form';
  $('#btnok').button("loading");
  $.ajax({
	type: "POST",
	url: action,
	data: $('#frmkomentar').serialize(),
	cache: false,
	success: function(msg){
	    $('#btnok').button("reset");
		hasilnya = msg.split("|");
		$('.loading').remove();
		if($.trim(hasilnya[0])=="sukses") {
		   $('#hasil').addClass("alert alert-success");
		   kosongform(); 
		} else {
		   $('#hasil').addClass("alert alert-danger");
		}
		$('#hasil').html(hasilnya[1]);
		$('#hasil').show(0);
		$('html, body').animate({ scrollTop: 0 }, 'slow');
		return false;
    }  
  });  
}
