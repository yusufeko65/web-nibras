<div class="col-lg-12 main-content">
    <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
		<div class="widget-box">
			<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
			<div class="widget-content nopadding">
				<form class="form-horizontal" method="POST" name="frmdata" id="frmdata" action="<?php echo $_SERVER['PHP_SELF'] ?>">
					<input type="hidden" name="aksi" id="aksi" value="<?php echo $modul ?>">
					<input type="hidden" name="iddata" id="iddata" value="<?php echo $iddata ?>">
					<input type="hidden" name="gruplama" id="gruplama" value="<?php echo isset($grup_nama) ? $grup_nama:'' ?>">
					<div id="hasil" style="display: none;"></div>
				  
					<div class="form-group">
						<label class="col-sm-2 control-label">Nama Grup</label>
						<div class="col-sm-4">
							<input type="text" id="grup" name="grup" class="form-control" value="<?php echo isset($grup_nama) ? $grup_nama:'' ?>" size="30">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Keterangan</label>
						<div class="col-sm-4">
							<input type="text" id="keterangan" name="keterangan" class="form-control" value="<?php echo isset($grup_ket) ? $grup_ket:'' ?>" size="60">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Hak Akses</label>
						<div class="col-sm-8">
							<table class="table">
								<thead>
									<tr>
									   <th>Menu</th>
									   <th>Add</th>
									   <th>Edit</th>
									   <th>Del</th>
									   <th>View</th>
									</tr>
								</thead>
								<tbody style="height:200px">
								<?php
								if($menunya){
									foreach($menunya as $menud) {
										
										$selectadd=""; $selectedit=""; $selectdel=""; $selectview="";
										
										foreach($hakakses as $h){
								
											if($menud['idm']==$h['idm']){
												if ($h['add']=='1') 
													$selectadd="checked";
												else
													$selectadd="";
															
												if ($h['edit']=='1') 
													$selectedit="checked";
												else
													$selectedit="";
															
												if ($h['del']=='1') 
													$selectdel="checked";
												else
													$selectdel="";
															
												if ($h['view']=='1') 
													$selectview="checked";
												else
													$selectview="";
											} 
											
										}
								?>
									<tr>
										<td><input type="hidden" id="idmenu" name="idmenu[]" class="idmenu" value="<?php echo $menud['idm'] ?>"><?php echo $menud['nmm'] ?></td>
										<td><input type="checkbox" id="chkadd<?php echo $menud['idm'] ?>" name="chkadd<?php echo $menud['idm'] ?>" class="chk chkadd form-control" value="1" <?php echo $selectadd ?>></td>
										<td><input type="checkbox" id="chkedit<?php echo $menud['idm'] ?>" name="chkedit<?php echo $menud['idm'] ?>" class="chk chkedit form-control" value="1" <?php echo $selectedit ?>></td>
										<td align="center"><input type="checkbox" id="chkdel<?php echo $menud['idm'] ?>" name="chkdel<?php echo $menud['idm'] ?>" class="chk chkdel form-control" value="1" <?php echo $selectdel ?>></td>
										<td align="center"><input type="checkbox" id="chkview<?php echo $menud['idm'] ?>" name="chkview<?php echo $menud['idm'] ?>" class="chk chkview form-control" value="1" <?php echo $selectview ?>></td>
									</tr>
							  <?php } // end foreach menu ?>
						  <?php } //end if ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-8 col-sm-2">
							<a href="javascript:void(0)" id="checkall">Check / Uncheck All</a>
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
	var status = 'tutup';
	$("#checkall").click(function(){
		if(status=='tutup') {
			$('.chk').each(function () {
				$(this).prop('checked', true);
			});
			status='buka';
		} else {
			$('.chk').each(function () {
            	$(this).prop('checked', false);
			});
			status='tutup';
		}
		return false;
	});
	
	$("#frmdata").submit(function(event){
		event.preventDefault();
		simpandata();
	});
	
	
});
function simpandata(){
	var action = $('#frmdata').prop("action");
	var datasimpan = $('#frmdata').serialize();
	var rv = true;
	$('#btnsimpan').button("loading");
	$('#hasil').removeClass();
	
	//alert(datasimpan);
	
	$.ajax({
		url: action,
		method: "POST",
		data: datasimpan,
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
	return rv;
	
}
</script>