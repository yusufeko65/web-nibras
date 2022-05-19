<div class="col-lg-12 main-content">
     <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
     <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
			<div class="widget-content nopadding">
				<form class="form-horizontal" method="POST" name="frmdata" id="frmdata" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>">
					<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
					<input type="hidden" id="id_info" name="id_info" value="<?php echo $iddata ?>">
					<div id="hasil" style="display: none;"></div>
				  
					<div class="form-group">
						<label class="col-sm-2 control-label">Judul</label>
						<div class="col-sm-10">
							<input type="text" name="info_judul" id="info_judul" class="form-control" value="<?php echo stripslashes($judulinfo) ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Alias URL</label>
						<div class="col-sm-10">
							<input type="text" id="aliasurl" name="aliasurl" class="form-control" value="<?php echo $aliasurl ?>"> <cite>Jika dikosongkan, otomatis alias url berdasarkan judul</cite>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Keterangan</label>
						<div class="col-sm-10">
							<textarea cols="80" id="info_detail" name="info_detail" class="form-control"><?php echo trim(stripslashes(html_entity_decode($keterangan))) ?></textarea>
						</div>
					</div>
				  
					<div class="form-group">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-4">
							<select id="sts_info" name="sts_info" class="form-control">
								<option value="1" <?php if($status=='1') echo "selected" ?>> Aktif </option>
								<option value="0" <?php if($status=='0') echo "selected" ?>> Tidak Aktif </option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button id="btnsimpan" type="submit" class="btn btn-sm btn-primary">Simpan</button>
							<a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
						</div>
					</div>
					<div class="clearfix"></div>
				</form>
			</div>
	    </div> 
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$("#frmdata").submit(function(event){
			event.preventDefault();
			simpandata();
		});
		
		$('#info_detail').summernote({
			height: "300px"
		});
	});
	
	function simpandata(){
		var action = $('#frmdata').prop("action");
		var rv = true;
		$('#btnsimpan').button("loading");
		$('#hasil').removeClass();
		var formdata = new FormData(frmdata);
		formdata.append("info_detail", $('#info_detail').code() );
		
		if(rv){
			$.ajax({
				url: action,
				method: "POST",
				data: formdata,
				processData:false,
				contentType:false,
				dataType: 'json',
				success: function(json){
					
					if(json['status'] == 'error') {
						
						$('#hasil').addClass('alert alert-danger');
						$('#btnsimpan').button("loading");
						
					} else {
						
						$('#hasil').addClass('alert alert-success');
						
						if($('#aksi').val() == 'tambah') {
							$('#frmdata')[0].reset();
						}
					}
					$('#hasil').show();
					$('#hasil').html(json['result']);
					$('html, body').animate({ scrollTop: 0 }, 'slow');
					$('#btnsimpan').button("reset");
				}
			});
		}
		return rv;
	}
	
</script>