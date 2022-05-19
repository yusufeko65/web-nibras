<form method="POST" name="frmeditproduk" id="frmeditproduk" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" id="aksistatus" name="aksistatus" value="editprodukorder">
	<input type="hidden" name="urlfolder" id="urlfolder" value="<?php echo URL_PROGRAM_ADMIN . folder . '?op=info&pid=' . $nopesan . '&u_token=' . $u_token ?>">
	<input type="hidden" id="product_id" name="product_id" value="<?php echo $produkid ?>">
	<input type="hidden" id="iddetail" name="iddetail" value="<?php echo $iddetail ?>">
	<input type="hidden" id="idmember" name="idmember" value="<?php echo $idmember ?>">
	<input type="hidden" id="idgrup" name="idgrup" value="<?php echo $idgrup ?>">
	<input type="hidden" id="qtylama" name="qtylama" value="<?php echo $qty ?>">
	<input type="hidden" id="idukuran" name="idukuran" value="<?php echo $idukuran ?>">
	<input type="hidden" id="idwarna" name="idwarna" value="<?php echo $idwarna ?>">
	<input type="hidden" id="nopesanan" name="nopesanan" value="<?php echo $nopesan ?>">
	<input type="hidden" id="redirectview" value="<?php echo URL_PROGRAM_ADMIN . 'order' ?>">
	<input type="hidden" id="redirectedit" value="<?php echo URL_PROGRAM_ADMIN . 'order/?op=info&pid=' . $nopesan . '&u_token=' . $u_token ?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 class="modal-title">Edit Produk</h4>
			</div>
			<div class="modal-body">
				<div id="hasiladdprod" style="display:none"></div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Nama Produk</label>
						<input type="text" id="enmproduk" name="enmproduk" class="form-control" value="<?php echo $produknm ?>" readonly>
					</div>

				</div>
				<?php if ($ukuran != '') { ?>
				<div class="col-md-6">
					<div class="form-group">
						<label>Ukuran</label>
						<input type="text" class="form-control" value="<?php echo $ukuran ?>" readonly>
					</div>
				</div>
				<?php } ?>

				<?php if ($warna != '') { ?>
				<div class="col-md-6">
					<div class="form-group">
						<label>Warna</label>
						<input type="text" class="form-control" value="<?php echo $warna ?>" readonly>
					</div>
				</div>
				<?php } ?>
				<div class="col-md-3">
					<div class="form-group">
						<label>Sisa Stok</label>
						<input type="text" class="form-control" value="<?php echo $stok ?> pcs" readonly>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Jumlah</label>
						<input type="number" class="form-control" id="qty" name="qty" value="<?php echo $qty ?>"><span id="keterangan"></span>
					</div>
				</div>
				<div class="clearfix"></div>

			</div>
			<div class="modal-footer">
				<a onclick="simpaneditproduk()" data-loading-text="Tunggu..." id="btnsimpanprod" class="btn btn-sm btn-primary">Simpan</a>
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
			</div>
		</div>
	</div>
</form>