$(function(){
	
	$("#frmakun").submit(function(event){

		event.preventDefault();
		simpandata();
		return false;
	});
	
	$('#btnaddalamat').click(function(){
		formAlamat();
		return false;
	});
	
	$('#btngantipassword').click(function(){
		formGantiPassword();
		return false;
	});
	var idhash = $('#tab').val();
	
	$('#tabaccount #'+idhash).tab('show');

});
function simpandata() {
	var email = $('#remail').val();
	var nama = $('#rnama').val();
	var telp = $('#rtelp').val();
	
	var url = $('#frmakun').attr('action');
	var datafrm = $('#frmakun').serialize();
	var ref = $('#url_ref').val();
	var redirect = '';
	
	$('#btnsimpan').prop('disabled', true);
	$('#hasil').removeClass();
	
	if(nama == '') {
		
		alert('Error','error','Masukkan Nama Anda','btnsimpan');
		$('html, body').animate({ scrollTop: 0 }, 'slow');
		return false;
	}
	if(email == ''){
		
		alert('Error','error','Masukkan Email Anda','btnsimpan');
		$('html, body').animate({ scrollTop: 0 }, 'slow');
		return false;
	}
	if(telp == ''){
		
		alert('Error','error','Masukkan Nomor Hp Anda','btnsimpan');
		$('html, body').animate({ scrollTop: 0 }, 'slow');
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
					redirect = url;
				} else {
					redirect = ref;
				}
				alert('Success','success',msg['result'],'btnsimpan',redirect);
				
			} else {
				alert('Error','error',msg['result'],'btnsimpan');
				
			}
			$('#hasil').html(msg['result']);
			$('#btnsimpan').prop('disabled', false);
			$('.loading').remove();
			return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}

function formAlamat(tipe=null){
	$('#btnaddalamat').prop('disabled', true);
	var url = $('#frmakun').attr('action');
	    url = url+'?load=frmAddAlamat';
	var modul = 'input';
	
	$.post(url,  { modul:modul,tipe:tipe } ,function(data) {
		
		$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function () {
			$(this).remove();
			$('#btnaddalamat').prop('disabled', false);
		});
	});
	
}
function ubahAlamat(id,row) {
	var url = $('#frmakun').attr('action');
	
	$('#btnubah'+row).prop('disabled', true);
	url = url+'?load=frmEditAlamat';
	var modul = 'update';
	
	$.post(url,  { modul:modul,id:id } ,function(data) {
		
		$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function () {
			$(this).remove();
			$('#btnubah'+row).prop('disabled', false);
		});
	});
}

function formhapusAlamat(id,row){
	var url = $('#frmakun').attr('action');
	
	$('#btnhapus'+row).prop('disabled', true);
	url = url+'?load=frmHapusAlamat';
	var modul = 'hapus';
	
	$.post(url,  { modul:modul,id:id } ,function(data) {
		
		$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function () {
			$(this).remove();
			$('#btnubah'+row).prop('disabled', false);
		});
	});
}

function formGantiPassword(){
	$('#btngantipassword').prop('disabled', true);
	var url = $('#frmakun').attr('action');
	    url = url+'?load=frmEditPassword';
	var modul = 'ubah';
	
	$.post(url,  { modul:modul } ,function(data) {
		
		$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function () {
			$(this).remove();
			$('#btngantipassword').prop('disabled', false);
		});
	});
}

function simpanalamat(){
	
	var dataalamat	= $('#frmalamat').serialize();
	var nama 		= $('#add_nama').val();
	var telp 		= $('#add_telp').val();
	var alamat 		= $('#add_alamat').val();
	var propinsi 	= $('#add_propinsi').val();
	var kabupaten 	= $('#add_kabupaten').val();
	var kecamatan 	= $('#add_kecamatan').val();
	var kodepos		= $('#add_kodepos').val();
	var tipe 		= $('#tipe').val();
	var urlweb		= $('#url_web').val();
	var url = $('#frmakun').attr('action');
	$('#btnsimpanalamat').prop('disabled', true);
	if(nama == '' || nama.length < 3) {
		alert('Error','error','Masukkan Nama Anda','btnsimpanalamat');
		return false;
	}
	if(telp == '' || telp.length < 3) {
		alert('Error','error','Masukkan Nomor Hp Anda','btnsimpanalamat');
		$('html, body').animate({ scrollTop: 0 }, 'slow');
		return false;
	}
	if(alamat == '' || alamat.length < 3) {
		alert('Error','error','Masukkan Alamat','btnsimpanalamat');
	
		return false;
	}
	if(propinsi == '' || propinsi == '0') {
		alert('Error','error','Masukkan Propinsi','btnsimpanalamat');
		return false;
	}
	if(kabupaten == '' || kabupaten == '0') {
		alert('Error','error','Masukkan Kabupaten','btnsimpanalamat');
		return false;
	}
	if(kecamatan == '' || kecamatan == '0') {
		alert('Error','error','Masukkan Kecamatan','btnsimpanalamat');
		return false;
	}
	if(kodepos != '' && kodepos.length > 5) {
		alert('Error','error','Masukkan Kode Pos, maksimal 5 karakter','btnsimpanalamat');
		return false;
	}
	
	$.ajax({
		type: "POST",
		url: url,
		data: dataalamat,
		dataType: 'json',
		success: function(json){
			if(tipe == '') {
				url = url+'?tb=alamat';
			} else {
				url = urlweb + 'cart/kasir';
			}
			if(json['status'] == 'error') {
				alert('Error','error',json['result'],'btnsimpanalamat');
			} else {
				alert('Success','success',json['result'],'btnsimpanalamat',url);
			}
		},  
		error: function(e){  
			alert('Error: ' + e);  
		}  
	});
}

function hapusalamat(){
	var dataalamat	= $('#frmalamat').serialize();
	var url = $('#frmakun').attr('action');
	$('#btnhapusalamat').prop('disabled', true);
	$.ajax({
		type: "POST",
		url: url,
		data: dataalamat,
		dataType: 'json',
		success: function(json){

			if(json['status'] == 'error') {
					
				alert('Error','error',json['result'],'btnhapusalamat');

			} else {
				
				alert('Success','success',json['result'],'btnhapusalamat',url+'?tb=alamat');
				
			}
			
			
		},  
		error: function(e){  
			alert('Error: ' + e);  
		}  
	});
}
function editalamat(){
	var datapassword	= $('#frmeditpassword').serialize();
	var url = $('#frmakun').attr('action');
	var oldpassword = $('#oldpassword').val();
	var newpassword = $('#newpassword').val();
	var renewpassword = $('#renewpassword').val();
	$('#btneditalamat').prop('disabled', true);
	if(oldpassword == '' && newpassword == '' && renewpassword == '') {
		alert('Error','error','Masukkan semua form ganti password','btneditalamat');
	}
	
	if(oldpassword == '') {
		alert('Error','error','Masukkan password lama','btneditalamat');
	}
	
	if(newpassword == '') {
		alert('Error','error','Masukkan password baru','btneditalamat');
	}
	
	if(renewpassword == '') {
		alert('Error','error','Masukkan repassword baru','btneditalamat');
	}
	
	if(newpassword != renewpassword) {
		alert('Error','error','Password Baru tidak sama dengan Re Password Baru','btneditalamat');
	}
	
	$.ajax({
		type: "POST",
		url: url,
		data: datapassword,
		dataType: 'json',
		success: function(json){
			if(json['status'] == 'error') {
				alert('Error','error',json['result'],'btnsimpanalamat');
			} else {
				alert('Success','success',json['result'],'btneditalamat',url);
			}
		},  
		error: function(e){  
			alert('Error: ' + e);  
		}  
	});
}
function editpassword(){
	var datapassword	= $('#frmeditpassword').serialize();
	var url = $('#frmakun').attr('action');
	var oldpassword = $('#oldpassword').val();
	var newpassword = $('#newpassword').val();
	var renewpassword = $('#renewpassword').val();
	
	$('#btneditpassword').prop('disabled', true);
	
	if(oldpassword == '' && newpassword == '' && renewpassword == '') {
		alert('Error','error','Masukkan semua form ganti password','btneditpassword');
		return false;
	}
	
	if(oldpassword == '') {
		alert('Error','error','Masukkan password lama','btneditpassword');
		return false;
	}
	
	if(newpassword == '') {
		alert('Error','error','Masukkan password baru','btneditpassword');
		return false;
	}
	
	if(renewpassword == '') {
		alert('Error','error','Masukkan repassword baru','btneditpassword');
		return false;
	}
	
	if(newpassword != renewpassword) {
		alert('Error','error','Password Baru tidak sama dengan Re Password Baru','btneditpassword');
		return false;
	}
	
	$.ajax({
		type: "POST",
		url: url,
		data: datapassword,
		dataType: 'json',
		success: function(json){
			if(json['status'] == 'error') {
				alert('Error','error',json['result'],'btnsimpanalamat');
			} else {
				alert('Success','success',json['result'],'btneditalamat',url);
			}
		},  
		error: function(e){  
			alert('Error: ' + e);  
		}  
	});
	
}