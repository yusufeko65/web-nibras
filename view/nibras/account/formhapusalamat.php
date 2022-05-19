<form id="frmalamat" name="frmalamat" class="needs-validation" novalidate>
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title text-center"><?php echo $titleform ?> Alamat Baru</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				
					<input type="hidden" id="id" name="id" value="<?php echo $id ?>">
					<input type="hidden" id="idcust" name="idcust" value="<?php echo $idmember ?>">
					<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>alamat">
					
					
					<div class="col-md-12 alert alert-warning">
					
						Apakah Anda ingin Menghapus data alamat dibawah ini ?<br><br>
						<b><?php echo $nama ?></b><br>
						Hp. <?php echo $hp ?><br><br>
						<?php echo $alamat ?><br>
						<?php echo $propinsi ?>,<?php echo $kabupaten ?>, <?php echo $kecamatan ?>
						<?php echo $kelurahan != '' ? ', '.$kelurahan : '' ?>
						<?php echo $kodepos != '' ? ', '.$kodepos : '' ?>
					</div>
					
					<div class="clearfix"></div>
			
			</div>
			<div class="modal-footer">
			   <button id="btnhapusalamat" onclick="hapusalamat()" class="btn btn-sm btn-danger" type="button">Hapus</button>
			   <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
			</div>
		</div>
	</div>
</form>