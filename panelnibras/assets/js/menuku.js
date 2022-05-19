
$(window).bind("load", function() {
   $("#loadingweb").fadeOut();
});

function cekall(){

	$('.chk').each(function () {
		if($(this).is(":checked")){
			$(this).prop('checked', false);	
		} else {
			$(this).prop('checked', true);	
		}
	});
	return false;
}

function tampilkan(alamatUrl){
  $('#loadingweb').show();
  $('#content').load(alamatUrl, {"stsload":"load"},function(){
     $('#loadingweb').fadeOut(2000);
     history.pushState(alamatUrl, "page", alamatUrl);
   });
}

function tampilkanedit(pid,jenis){
    if(jenis=='edit') url = '?op=edit&pid='+pid;
    else url='?op=detail&pid=' + pid;
	tampilkan(url);
}
function resetForm(form,elemenFocus){
	$('#'+form).trigger("reset");
	$('.text-danger').remove();
	focus(elemenFocus);
}
function tampilform(url,zdata){
	$.post(url,  { stsload: "load",data:zdata } ,function(data) {
   
		$("#loadingweb").fadeOut();
		$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function () {
		   $(this).remove();
		});
	});
}
function focus(keyobject)
{
	
	var endFocus = $("input[name="+keyobject+"]").val().length * 2;
	
	$("input[name="+keyobject+"]").focus();
	$("input[name="+keyobject+"]")[0].setSelectionRange(endFocus, endFocus);
	
}

function disableEnterKey(e){ //Disable Tekan Enter
	var key;
	if(window.event)
	  key = window.event.keyCode;     //IE
	else
	  key = e.which;     //firefox

	if(key == 13){ // Jika ditekan tombol enter
		return false;
	} else {
		return true;
	}
}
/*
 * JavaScript Code Snippet
 * Convert Number to Rupiah & vice versa
 * https://gist.github.com/845309
 *
 * Copyright 2011-2012, Faisalman
 * Licensed under The MIT License
 * http://www.opensource.org/licenses/mit-license  
 *
 */
 
function convertToRupiah(angka)
{
	var rupiah = '';		
	var angkarev = angka.toString().split('').reverse().join('');
	for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
	return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
}
