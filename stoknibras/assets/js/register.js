var url     = $('#frmdaftar').prop('action');
var url_wil = $('#url_wil').val();

jQuery(document).ready(function(){
   
	$("#frmdaftar").submit(function(event){
	   event.preventDefault();
	   daftarmember();
	});
	$('#rnegara').change(function(){
		var dataform = "load=propinsi&negara="+ $(this).val();
		$('#rpropinsi').html('<option value="0">Loading</option>');
		$.ajax({
			url: url_wil,
			method: "GET",
			data: dataform,
			dataType: 'json',
			success: function(result){
				var html = '<option value="0">- Propinsi -</option>';
				for (i = 0; i < result['wilayah'].length; i++) {
					html += '<option value="' + result['wilayah'][i]['provinsi_id'] + '"';
					html += '>' + result['wilayah'][i]['provinsi_nama'] + '</option>';
				}
				$('.loading').remove();
				$('#rpropinsi').html(html);
			}
		});
		
		return false;
	});

	$('#rpropinsi').change(function(){
		var dataform = "load=kabupaten&propinsi="+ $(this).val();
		$('#rkabupaten').html('<option value="0">Loading</option>');
		$.ajax({
			url: url_wil,
			method: "GET",
			data: dataform,
			dataType: 'json',
			success: function(result){
				var html = '<option value="0">- Kota/Kabupaten -</option>';
		  
				for (i = 0; i < result.length; i++) {
					html += '<option value="' + result[i]['kabupaten_id'] + '"';
					html += '>' + result[i]['kabupaten_nama'] + '</option>';
				}
				$('.loading').remove();
				$('#rkabupaten').html(html);
			}
		});
		
		return false;
	});
	$('#rkabupaten').change(function(){
		var dataform = "load=kecamatan&kabupaten="+ $(this).val();
		$('#rkecamatan').html('<option value="0">Loading</option>');
		
		$.ajax({
			url: url_wil,
			method: "GET",
			data: dataform,
			dataType: 'json',
			success: function(result){
				var html = '<option value="0">- Kecamatan -</option>';
		  
				for (i = 0; i < result.length; i++) {
					html += '<option value="' + result[i]['kecamatan_id'] + '"';
					html += '>' + result[i]['kecamatan_nama'] + '</option>';
				}
				$('.loading').remove();
				$('#rkecamatan').html(html);
			}
		});
		
		return false;
	});
});
function daftarmember() {
	var email = $('#remail').val();
	var pass = $('#rpass').val();
	var repass = $('#rrepass').val();
	var nama = $('#rnama').val();
	var alamat = $('#ralamat').val();
	var negara = $('#rnegara').val();
	var propinsi = $('#rpropinsi').val();
	var kabupaten = $('#rkabupaten').val();
	var kecamatan = $('#rkecamatan').val();
	var centang = $('#rprivasi').is(':checked');
	var datafrm = $('#frmdaftar').serialize();
	var ref = $('#url_ref').val();
 
	$('#btnok').button('loading');
	$('#hasil').removeClass();
	if(email == '' && pass == '' && repass == '' && nama == '' && alamat == '' && (propinsi == '' || propinsi == '0') && (kabupaten == '' || kabupaten == '0') && (kecamatan == '' || kecamatan == '0') && !centang){
		alert('Error','error','Harap form pendaftaran diisi terlebih dahulu','btnok');
		return false;
	}
	if(email == ''){
		
		alert('Error','error','Masukkan Email','btnok');
		return false;
	}
	if(pass == ''){
		
		alert('Error','error','Masukkan Password Anda','btnok');
		return false;
	}
	if(pass != repass){
		
		alert('Error','error','Password dan Ulangi Password tidak sama','btnok');
		return false;
	}
  
	if(nama == '') {
		
		alert('Error','error','Masukkan Nama Anda','btnok');
		return false;
	}
	if(alamat == '') {
		
		alert('Error','error','Masukkan Alamat Anda','btnok');
		return false;
	}
	if(negara == '' && negara == '0') {
		
		alert('Error','Masukkan Negara','btnok');
		return false;
	}
	if(propinsi == '' || propinsi == '0') {
		
		alert('Error','error','Masukkan Propinsi','btnok');
		return false;
	}
	if(kabupaten == '' || kabupaten == '0') {
		
		alert('Error','error','Masukkan Kota/Kabupaten','btnok');
		return false;
	}
	if(kecamatan == '' || kecamatan == '0') {
		
		alert('Error','error','Masukkan Kecamatan','btnok');
		return false;
	}
	if(!centang) {
		
		alert('Error','error','Harap Centang Kebijakan Privasi','btnok');
		return false;
	}
	
	$.ajax({
 		type: "POST",
   		url: url,
		data: datafrm,
 		dataType: 'json',
		success: function(msg){
			
			
			$('#hasil').show(0);
			if(msg['status'] =='success') {
				$('#hasil').addClass("alert alert-success");
				
				if(ref == '') {
					location.href = url+'?sukses';
				} else {
					location.href = ref;
				}
				
			} else {
				alert('Error','error',msg['result'],'btnok');
				
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