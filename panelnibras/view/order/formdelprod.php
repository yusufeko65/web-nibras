<form method="POST" name="frmeditstatus" id="frmeditstatus" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" id="aksistatus" name="aksistatus" value="hapusprodukorder">
	<input type="hidden" id="dnopesan" name="dnopesan" value="<?php echo $nopesan ?>">
	<input type="hidden" name="urlfolder" id="urlfolder" value="<?php echo URL_PROGRAM_ADMIN . folder . '?op=info&pid=' . $nopesan ?>">
	<input type="hidden" id="didproduk" name="didproduk" value="<?php echo $produkid ?>">
	<input type="hidden" id="diddetail" name="diddetail" value="<?php echo $iddetail ?>">
	<input type="hidden" id="didmember" name="didmember" value="<?php echo $idmember ?>">
	<input type="hidden" id="didgrup" name="didgrup" value="<?php echo $idgrup ?>">
	<input type="hidden" id="qty" name="qty" value="<?php echo $qty ?>">
	<input type="hidden" id="dukuran" name="dukuran" value="<?php echo $ukuran ?>">
	<input type="hidden" id="dwarna" name="dwarna" value="<?php echo $warna ?>">
	<input type="hidden" id="dtotlama" name="dtotlama" value="<?php echo $subtotal ?>">
	<input type="hidden" id="redirectview" value="<?php echo URL_PROGRAM_ADMIN . 'order/?u_token=' . $u_token ?>">
	<input type="hidden" id="redirectedit" value="<?php echo URL_PROGRAM_ADMIN . 'order/?op=info&pid=' . $nopesan . '&u_token=' . $u_token ?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 class="modal-title">Hapus Produk</h4>
			</div>
			<div class="modal-body">
				<div id="hasileditstatus" style="display:none"></div>
				<div class="well well-sm">
					Apakah Anda ingin menghapus produk "<b><?php echo $produknm ?><?php echo $nmwarna != '' ? ', Warna : ' . $nmwarna : '' ?><?php echo $nmukuran != '' ? ', Ukuran : ' . $nmukuran : '' ?></b>" ?
				</div>
				<?php if ($jmlproduk < 2) { ?>
				<div class="text-danger text-center"><b>Penghapusan produk ini akan mengakibatkan penghapusan Order ini</b></div>
				<?php } ?>

			</div>
			<div class="modal-footer">
				<div class="form-group">
					<button type="button" id="btndelproduk" onclick="hapusprodukorder()" class="btn btn-success">Ya</a>
						<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnclose">Tidak</button>
				</div>
			</div>
		</div>
	</div>
</form>