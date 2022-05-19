<form method="POST" name="frmeditproduk" id="frmeditproduk" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" id="aksistatus" name="aksistatus" value="editprodukorder">
	<input type="hidden" id="product_id" name="product_id" value="<?php echo $formproduk['produk']['produk_id'] ?>">
	<input type="hidden" id="qtylama" name="qtylama" value="<?php echo $formproduk['produk']['qty'] ?>">
	<input type="hidden" id="idukuran" name="idukuran" value="<?php echo $formproduk['produk']['idukuran'] ?>">
	<input type="hidden" id="idwarna" name="idwarna" value="<?php echo $formproduk['produk']['idwarna'] ?>">
	<input type="hidden" id="nopesanan" name="nopesanan" value="<?php echo $formproduk['produk']['pesanan_no'] ?>">
	
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title text-center">Edit Produk</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				
			</div>
			<div class="modal-body">
				
				<div id="hasiladdprod" style="display:none"></div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
						   <label>Nama Produk</label>
						   <input type="text" id="enmproduk" name="enmproduk" class="form-control" value="<?php echo $formproduk['produk']['nama_produk'] ?>" readonly>
						</div>
						
					</div>
				
					<?php if($formproduk['produk']['ukuran'] != '') {?>
					<div class="col-md-6">
						<div class="form-group">
						   <label>Ukuran</label>
						   <input type="text" class="form-control" value="<?php echo $formproduk['produk']['ukuran'] ?>" readonly>
						</div>
					</div>
					<?php } ?>
			
					<?php if($formproduk['produk']['warna'] != '') {?>
					<div class="col-md-6">
						<div class="form-group">
						   <label>Warna</label>
						   <input type="text" class="form-control" value="<?php echo $formproduk['produk']['warna'] ?>" readonly>
						</div>
					</div>
					<?php } ?>
				
					<div class="col-md-3">
						<div class="form-group">
						   <label>Sisa Stok</label>
						   <input type="text" class="form-control" value="<?php echo $formproduk['produk']['stok'] ?> pcs" readonly>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						   <label>Jumlah</label>
						   <input type="number" class="form-control" id="qty" name="qty" value="<?php echo $formproduk['produk']['qty'] ?>"><span id="keterangan"></span>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				
			</div>
			<div class="modal-footer">
			   <button type="button" onclick="simpaneditproduk()" data-loading-text="Tunggu..." id="btnsimpanprod" class="btn btn-sm btn-primary">Simpan</button>
			   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
			</div>
		</div>
	</div>
</form>
