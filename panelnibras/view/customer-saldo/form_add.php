<div class="col-lg-12 main-content">
    <h2 class="judulmodul"><?php echo $judul ?></h2>
    <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
			<div class="widget-content nopadding">
				<form autocomplete="off" class="form-horizontal" method="POST" name="frmdata" id="frmdata" onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
					<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
		      
					<div id="hasil"></div>
					
			 
					<div class="well">
						<div class="form-group" id="divformnama">
							<label class="col-sm-2 control-label">Nama Pelanggan</label>
							<div class="col-sm-4">
								<div class="row">
									<input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control" placeholder="Nama Pelanggan" >
									<input type="hidden" id="iddata" name="iddata">
								</div>
							</div>
						</div>
					
						<div class="form-group">
							<label class="col-sm-2 control-label">Jumlah Saldo</label>
							<div class="col-sm-4">
								<div class="row">
									<input type="text" id="indeposito" name="indeposito" class="form-control" placeholder="Jumlah Deposito">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">Upload *</label>
							<div class="col-sm-4">
								<div class="row">
									<input type="file" id="buktitransfer" name="buktitransfer" class="form-control" placeholder="Bukti Transfer" required>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Keterangan</label>
							<div class="col-sm-4">
								<div class="row">
									<input type="text" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan">
								</div>
							</div>
						</div>
					</div>
			  
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<a onclick="simpandata()" id="btnsimpan" class="btn btn-sm btn-primary">Simpan</a>
							<a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
						</div>
					</div>
					<div class="clearfix"></div>
			 
				</form>
			</div>
	    </div> 
	</div>
</div>

<script>
var action = $('#frmdata').attr('action');
$(function(){
	
	$('#nama_pelanggan').change(function(){
		if($('#nama_pelanggan').val() == '') {
			$('#iddata').val('');
			$('#divformnama').removeClass(" has-success has-feedback ");
			$('#divformnama').addClass(" has-error has-feedback ");
			$('.glyphicon').remove();
			$('#nama_pelanggan').after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
		}
		return false;
	});
	
	/* autocomplete */
	$('#nama_pelanggan').autocomplete({
		delay: 0,
		source: function( request, response ) {
			$.ajax({
				url: action,
				dataType: "json",
				data: {
					loads: 'customer',
					stsdeposito: '1',
					caripelanggan: request.term
				},
				success: function( data ) {
					response( $.map( data, function( item ) {
						return {
							label: item.cust_nama + ' - ' + item.cg_nm,
							value: item.cust_id
				 
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
			$('#divformnama').removeClass(" has-error has-feedback ");
			$('#divformnama').addClass(" has-success has-feedback ");
			$('input[name=\'nama_pelanggan\']').val(ui.item.label);
			$('.glyphicon').remove();
			$('#nama_pelanggan').after('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
			$('#iddata').val(ui.item.value);
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
   
	/* @end autocomplete */
});



function kosongform(){
	$('#frmdata')[0].reset();
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
	
	$('#btnsimpan').button('loading');
	var jmlsaldo = $('#indeposito').val();
	var iddata = $('#iddata').val();
	var bukti = $('#buktitransfer').val();
	if(iddata =='' || iddata =='0'){
		$('#hasil').addClass('alert alert-danger');
		$('#hasil').html('Masukkan Nama Pelanggan');
		$('#btnsimpan').button('reset');
		$('#hasil').show();
		return false;
	}
	if(bukti =='' || bukti =='0'){
		$('#hasil').addClass('alert alert-danger');
		$('#hasil').html('Upload Bukti Pembayaran');
		$('#btnsimpan').button('reset'); 
		$('#hasil').show();
		return false;
	}
	if(jmlsaldo ==''){
		$('#hasil').addClass('alert alert-danger');
		$('#hasil').html('Masukkan Jumlah Saldo');
		$('#btnsimpan').button('reset');
		$('#hasil').show();
		return false;
	} 

	//Get File input
	var formData = new FormData(); 
	var file = $('#buktitransfer')[0].files;
	var data = $('#frmdata').serializeArray();

	for(var i = 0;i < data.length; i++){
		formData.append(data[i]['name'],data[i]['value']);
	}


	if(file.length>0){
		formData.append('file[]',file[0]);
	}

	$.ajax({
 		type: "POST",
   		url: action,
    	data: formData,
		processData:false,
		contentType:false,
		cache:false,
    	success: function(msg){
		    msg = JSON.parse(msg);

		    $('#btnsimpan').button('reset');
		    $('#loadingweb').fadeOut(500);

			$('#hasil').show(0);
			if(msg['status']=="success") {
				$('#hasil').removeClass('alert-danger');
			   $('#hasil').addClass('alert alert-success');
			   kosongform();
			} else {
				$('#hasil').removeClass('alert-success');
				$('#hasil').addClass('alert alert-danger');
				
			}

			$('#btnsimpan').button("reset");
			$('#hasil').html(msg['result']);
			
			return false;
      	}  
  	});  
	$('html, body').animate({ scrollTop: 0 }, 'slow');
}
</script>

