 
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4 class="modal-title"><?php echo $titleform ?> Alamat Baru</h4>
		</div>
		<div class="modal-body">
			<form id="frmalamat" name="frmalamat">
				<input type="hidden" name="urlfolder" id="urlfolder" value="<?php echo URL_PROGRAM_ADMIN.folder.'?op=edit&pid='.$idcust ?>">
				<input type="hidden" id="id" name="id" value="<?php echo $id ?>">
				<input type="hidden" id="idcust" name="idcust" value="<?php echo $idcust ?>">
				<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>alamat">
				<div id="hasilcust" style="display:none"></div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Nama</label>
						<input type="text" id="add_nama" name="add_nama" class="form-control" value="<?php echo $nama ?>">
						
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>No. Handphone</label>
						<input type="text" id="add_telp" name="add_telp" class="form-control" value="<?php echo $hp ?>">
						
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Alamat</label>
						<textarea id="add_alamat" name="add_alamat" class="form-control"><?php echo $alamat ?></textarea>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Propinsi</label>
						<select id="add_propinsi" name="add_propinsi">
							<option value="0">- Propinsi -</option>
							<?php foreach($dataprop as $prop) { ?>
							<option value="<?php echo $prop['idp'] ?>" <?php echo $propinsi == $prop['idp'] ? 'selected': '' ?>><?php echo $prop['nmp'] ?></option>
							<?php } ?>
						<select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Kota/Kabupaten</label>
						<select id="add_kabupaten" name="add_kabupaten">
							<option value="0">- Kota/Kabupaten -</option>
							<?php echo $optkabupaten; ?>
						<select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Kecamatan</label>
						<select id="add_kecamatan" name="add_kecamatan">
							<option value="0">- Kecamatan -</option>
							<?php echo $optkecamatan ?>
						<select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Kelurahan</label>
						<input type="text" id="add_kelurahan" name="add_kelurahan" class="form-control" value="<?php echo $kelurahan ?>">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Kode Pos</label>
						<input type="text" id="add_kodepos" name="add_kodepos" class="form-control" value="<?php echo $kodepos ?>">
					</div>
				</div>
				<div class="col-md-6">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="chkdefault" id="chkdefault" value="1" <?php echo $default == '1' ? 'checked' : '' ?>> Jadikan Alamat Utama ?
						</label>
					</div>
				</div>
				<div class="clearfix"></div>
			</form>
		</div>
		<div class="modal-footer">
		   <button id="btnsimpanalamat" onclick="simpanalamat()" data-loading-text="Sedang menyimpan..." class="btn btn-sm btn-primary">Simpan</button>
		   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
		</div>
	</div>
</div>

<script>
$(function(){
	$("#add_propinsi").chosen({
		no_results_text: "Tidak Ada Propinsi!",
		width: "100%"
	}); 
	$("#add_kabupaten").chosen({
		no_results_text: "Tidak Ada Kota/Kabupaten!",
		width: "100%"
	}); 
	$("#add_kecamatan").chosen({
		no_results_text: "Tidak Ada Kecamatan!",
		width: "100%"
	});
	$('#add_propinsi').chosen().change(function() {
		$.ajax({
			type: "GET",
			url: '<?php echo URL_PROGRAM_ADMIN.folder?>/?load=kabupaten',
			data: 'propinsi=' + this.value,
			success: function(dt) {
				$('#add_kabupaten').html(dt).trigger("chosen:updated");
				$('#add_kecamatan').html('<option value="0">- Kecamatan -</option>').trigger("chosen:updated");;
			},  
			error: function(e){  
				alert('Error: ' + e);  
			}  
		 });
		
		return false;
	});
	$('#add_kabupaten').chosen().change(function() {
		$.ajax({
			type: "GET",
			url: '<?php echo URL_PROGRAM_ADMIN.folder?>/?load=kecamatan',
			data: 'kabupaten=' + this.value,
			success: function(dt) {
				$('#add_kecamatan').html(dt).trigger("chosen:updated");
			},  
			error: function(e){  
				alert('Error: ' + e);  
			}  
		 });
		
		return false;
	});
	
});

function simpanalamat(){
	
	$('#btnsimpanalamat').button('loading');
	var dataalamat = $('#frmalamat').serialize();
	$('#hasilcust').removeClass();
	$('#hasilcust').hide();
	
	$.ajax({
		type: "POST",
		url: '<?php echo URL_PROGRAM_ADMIN.folder?>',
		data: dataalamat,
		dataType: 'json',
		success: function(json){
		
			
			if(json['status'] == 'error') {
					
				$('#hasilcust').addClass('alert alert-danger');
				$('#btnsimpan').button("reset");
				
			} else {
				
				$('#hasilcust').addClass('alert alert-success');
				location.href="<?php echo URL_PROGRAM_ADMIN.folder.'/?op=edit&pid=' ?>"+json['idcust'];
			}
			$('#hasilcust').show();
			$('#hasilcust').html(json['result']);
			$('html, body').animate({ scrollTop: 0 }, 'slow');
			$('#btnsimpanalamat').button('reset');
		},  
		error: function(e){  
			alert('Error: ' + e);  
		}  
	});
	
	
}
</script>