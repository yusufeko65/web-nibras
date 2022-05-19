var action = $('#frmdata').attr('action');
jQuery(document).ready(function(){
	$('#nmdisk').focus();
	$('#negara').change(function() {
		$('#propinsi').load(action +'?load=propinsi&idp=' + this.value);
		
		return false;
	});
	$('#propinsi').change(function() {
		/*$('#kabupaten').load(action +'?load=kabupaten2&idp=' + this.value).trigger('chosen:updated');*/
		
		$.ajax({
			type: "POST",
			url: action +'?load=kabupaten2&idp=' + this.value,
			cache: false,
			success: function(msg){
			    $('#kabupaten').html(msg).trigger('chosen:updated');
			},  
				error: function(e){  
				alert('Error: ' + e);  
			}  
		});  
		
		return false;
	});

	$('#selectall').click(function(){
		$('#kabupaten option').attr('selected', true);
		 $('#kabupaten').trigger('chosen:updated');
		return false;
	});
	$('#deselectall').click(function(){
       $('#kabupaten option').attr('selected', false);
	   $('#kabupaten').trigger('chosen:updated');
   	   return false;
	});
});

function kosongform(){
  $('#negara').focus();
  $('.elmi').each(function () {
	$(this).val("");
  });
   $('#kabupaten').prop("selected", false).trigger('chosen:updated');
   $('#kabupaten').val("").trigger('chosen:updated');
   $('#jservis').prop("selected", false).trigger('chosen:updated');
   $('#jservis').val("").trigger('chosen:updated');
   $('#negara').val("");
   $('#propinsi').val("");
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
    var nmdisk      = $('#nmdisk').val();
	var jmldisk     = $('#jmldisk').val();
	var propinsi    = $('#propinsi').val();
	var negara      = $('#negara').val();
	var pesan       = "";
	var aksi        = $('#aksi').val();
	var iddata      = $('#iddata').val();
	var dlokasi = [];
	var dservis = [];

	
	$("#kabupaten option:selected ").each(function(){
        dlokasi.push($(this).val());
	});
	$("#jservis option:selected ").each(function(){
        dservis.push($(this).val());
	});
	
	var kabupaten = dlokasi.join(':');
	var servis = dservis.join(':');

    if(nmdisk==''){
		$('#nmdisk').focus();
		$('#hasil').html("<div class=\"warning\"> Masukkan Nama Diskon JNE </div>");
		$('#hasil').show(500);
		return false;
	}
	if(jmldisk=='' || isNaN(jmldisk)){
		$('#jmldisk').focus();
		$('#hasil').html("<div class=\"warning\"> Masukkan Jumlah Diskon JNE </div>");
		$('#hasil').show(500);
		return false;
	}
	if(servis==''){
	    $('#hasil').html("<div class=\"warning\"> Masukkan Servis JNE </div>");
		$('#hasil').show(500);
	}
	if(negara==''){
		$('#negara').focus();
		$('#hasil').html("<div class=\"warning\"> Pilih Negara </div>");
		$('#hasil').show(500);
		return false;
	}
	if(propinsi==''){
		$('#propinsi').focus();
		$('#hasil').html("<div class=\"warning\"> Pilih Propinsi </div>");
		$('#hasil').show(500);
		return false;
	}
	if(kabupaten==''){
		$('#kabupaten').focus();
		$('#hasil').html("<div class=\"warning\"> Pilih Kotamadya/Kabupaten</div>");
		$('#hasil').show(500);
		return false;
	}
	
    
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