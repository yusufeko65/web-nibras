<form method="POST" name="frmdelproduk" id="frmdelproduk" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" id="aksistatus" name="aksistatus" value="hapusprodukorder">
	<input type="hidden" id="pesanan_no" name="pesanan_no" value="<?php echo $formproduk['produk']['pesanan_no'] ?>">
	<input type="hidden" id="product_id" name="product_id" value="<?php echo $formproduk['produk']['produk_id'] ?>">

	<input type="hidden" id="qty" name="qty" value="<?php echo $formproduk['produk']['qty'] ?>">
	<input type="hidden" id="idukuran" name="idukuran" value="<?php echo $formproduk['produk']['idukuran'] ?>">
	<input type="hidden" id="idwarna" name="idwarna" value="<?php echo $formproduk['produk']['idwarna'] ?>">
	<input type="hidden" id="jmlproduk" name="jmlproduk" value="<?php echo $formproduk['produk']['jmlproduk'] ?>">
	
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title text-center">Hapus Produk</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				
				<?php if($formproduk['produk']['jmlproduk'] < 2) { ?>
				<div class="text-danger text-center"><b><?php echo $formproduk['msg'] ?></b></div>
				<?php } else { ?>
				<div id="hasildelproduk" style="display:none"></div>
				<div class="well well-sm">
					Apakah Anda ingin menghapus produk "<b><?php echo $formproduk['produk']['nama_produk'] ?><?php echo $formproduk['produk']['warna'] != '' ? ', Warna : '.$formproduk['produk']['warna'] : '' ?><?php echo $formproduk['produk']['ukuran'] != '' ? ', Ukuran : '.$formproduk['produk']['ukuran'] : '' ?></b>" ?
				</div>
				
				<?php } ?>

			</div>
			<div class="modal-footer">
				<div class="form-group">
					<?php if($formproduk['status'] != 'error') { ?>
					<button type="button" id="btndelproduk" onclick="hapusprodukorder()" class="btn btn-sm btn-success">Ya</button>
					
					<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" id="btnclose">Tidak</button>
					<?php } else {?>
					
						<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" id="btnclose">Tutup</button>
					<?php } ?>
					
				</div>
			</div>
		</div>
   </div>
</form>