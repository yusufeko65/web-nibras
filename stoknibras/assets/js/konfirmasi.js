jQuery(document).ready(function(){
    
	$( "#tglbayar" ).datepicker({ dateFormat: 'yy/mm/dd' });
	 
	$("#frmkonfirmasi").submit(function(event){
		$('#btnsimpan').before('<span class="loading">Tunggu Sebentar..</span>');
		event.preventDefault();
		konfirmasi();
	   
	});
	
	$('#noorder').autocomplete({
		delay: 0,
		source: function( request, response ) {
			$.ajax({
				url: $('#frmkonfirmasi').prop("action"),
				dataType: "json",
				data: {
				   load: 'searchnoorder',
				   search: request.term
				},
				success: function( data ) {
					
					response( $.map( data, function( item ) {
						
						return {
							label: item.noorder,
							value: item.noorder,
							total: item.totalbelanja
						}
					}));
				},
				error: function(e){  
					alert('Error: ' + e);  
				}  
			});
		},
		minLength: 1,
		select: function( event, ui ) {
			
			$('#noorder').val(ui.item.value);
			$('#jmlbayar').val(ui.item.total);
			$('#totalbelanja').val(ui.item.total);
			$('#bankfrom').focus();
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
});

function konfirmasi(){
	var noorder      	 = $('#noorder').val();
	var jmlbayar      	= $('#jmlbayar').val();
	var bankfrom      	= $('#bankfrom').val();
	var norekfrom     	= $('#norekfrom').val();
	var atasnamafrom  	= $('#atasnamafrom').val();
	var tglbayar      	= $('#tglbayar').val();
	var action 			= $('#frmkonfirmasi').prop('action');
	var file_value		= $('#buktitransfer').val();
	
	var match= ["image/jpeg","image/png","image/jpg","image/gif"];
  
	$('#hasil').removeClass();
	if(noorder == '') {
		$('#hasil').show();
		$('#hasil').addClass("alert alert-danger");
		$('#hasil').html('Masukkan No. Order Anda');
		$('#noorder').focus();
		$('.loading').remove();
		return false;
	}
  
	if(jmlbayar == '' || isNaN(jmlbayar)) {
		$('#hasil').show();
		$('#hasil').addClass("alert alert-danger");
		$('#hasil').html("Masukkan Jumlah Bayar");
		$('#jmlbayar').focus();
		$('.loading').remove();
		return false;
	}
  
	if(bankfrom == '') {
		$('#hasil').show();
		$('#hasil').addClass("alert alert-danger");
		$('#hasil').html("Pilih Bank Transfer Anda");
		$('#bankfrom').focus();
		$('.loading').remove();
		return false;
	}
	if(norekfrom == '') {
		$('#hasil').show();
		$('#hasil').addClass("alert alert-danger");
		$('#hasil').html("Masukkan No. Rekening Bank Anda");
		$('#norekfrom').focus();
		$('.loading').remove();
		return false;
	}
	if(atasnamafrom == '') {
		$('#hasil').show();
		$('#hasil').addClass("alert alert-danger");
		$('#hasil').html("Masukkan Atas Nama Bank");
		$('#atasnamafrom').focus();
		$('.loading').remove();
		return false;
	}
	if(tglbayar == '') {
		$('#hasil').show();
		$('#hasil').addClass("alert alert-danger");
		$('#hasil').html("Masukkan Tanggal Bayar");
		$('#tglbayar').focus();
		$('.loading').remove();
		return false;
	}
	if(file_value != '') {
		var file          	= $('#buktitransfer').prop('files')[0];
		var imagefile = file.type;
		if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]) || (imagefile==match[3])))
		{
			$('#hasil').show();
			$('#hasil').addClass("alert alert-danger");
			$('#hasil').html("Masukkan file bukti transfer berupa : jpg, gif, png");
			
			$('.loading').remove();
			return false;
		}
		
	} else {
		$('#hasil').show();
		$('#hasil').addClass("alert alert-danger");
		$('#hasil').html("Masukkan Bukti Transfer Anda");
		$('.loading').remove();
		$('html, body').animate({ scrollTop: 0 }, 'slow');
		return false;
	}
	
	
	$.ajax({
		type: "POST",
		url: action,
		data: new FormData(frmkonfirmasi),
		cache: false,
		processData: false,
		contentType: false,
		dataType: 'json',
		success: function(msg){
			$('#hasil').show(0);
			
			
			if( $.trim(msg.status) == "success" ) {
				$('#hasil').addClass("alert alert-success");
				$(".elmi").each(function(){
				  $(this).val('');
				});
			}
			else 
			{
				$('#hasil').addClass("alert alert-danger");
			}	
			$('#hasil').html(msg.result);
			$('.loading').remove();
			$('html, body').animate({ scrollTop: 0 }, 'slow');
			
			return false;
		} 
	}); 
}