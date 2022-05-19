<form id="frmeditpassword" name="frmeditpassword" class="needs-validation" novalidate>
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title text-center"><?php echo $titleform ?></h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				
					<input type="hidden" id="id" name="id" value="<?php echo $id ?>">
					<input type="hidden" id="idcust" name="idcust" value="<?php echo $idmember ?>">
					<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>password">
					
					<div class="form-row align-items-center">
						<div class="form-group col-md-12">
							<label for="oldpassword" class="col-form-label col-form-label-sm">Password Lama</label>
							<input type="password" id="oldpassword" name="oldpassword" class="form-control form-control-sm " placeholder="Password Lama">
							
						</div>
						<div class="form-group col-md-12">
							<label for="newpassword" class="col-form-label col-form-label-sm">Password Baru</label>
							<input type="password" id="newpassword" name="newpassword" class="form-control form-control-sm " placeholder="Password Baru">		
						</div>
						<div class="form-group col-md-12">
							<label for="renewpassword" class="col-form-label col-form-label-sm">Ulangi Password Baru</label>
							<input type="password" id="renewpassword" name="renewpassword" class="form-control form-control-sm " placeholder="Ulangi Password Baru">		
						</div>
						
					</div>
					
					<div class="clearfix"></div>
			
			</div>
			<div class="modal-footer">
			   <button id="btneditpassword" onclick="editpassword()" class="btn btn-sm btn-success" type="button">Simpan</button>
			   <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
			</div>
		</div>
	</div>
</form>