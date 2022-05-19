<form method="POST" name="frmaddproduk" id="frmaddproduk" action="<?php echo $_SERVER['PHP_SELF'] ?>">

	<input type="hidden" id="product_id" name="product_id" value="<?php echo $formproduk['produk']['produkid'] ?>">
	<input type="hidden" id="nopesanan" name="nopesanan" value="<?php echo $formproduk['produk']['pesanan_no'] ?>">
	
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			
			<div class="modal-header">
				<h6 class="modal-title text-center">Tambah Produk</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				
			</div>
			
			<div class="modal-body">
				<div id="hasiladdproduk"></div>
				<div class="row">
					
					<div class="col-md-6">
						<div class="form-group">
						   <label>Kode Produk</label>
						   <input type="text" readonly class="form-control form-control-sm" value="<?php echo $formproduk['produk']['produkkode'] ?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
						   <label>Nama Produk</label>
						   <input type="text" readonly class="form-control form-control-sm" value="<?php echo $formproduk['produk']['produknm'] ?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Ukuran</label>
							<select id="idukuran" name="idukuran" onclick="getWarna(this.value)" class="form-control form-control-sm">
								<option value="0">- Pilih Ukuran -</option>
								<?php echo $formproduk['produk']['ukuran'] ?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Warna</label>
							<select id="idwarna" name="idwarna" class="form-control form-control-sm">
								<option value="0">- Pilih Warna -</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
						   <label>Harga Normal</label>
						   <input type="text" readonly class="form-control form-control-sm" value="<?php echo $dtFungsi->fuang($formproduk['produk']['hrgsatuan']) ?> ">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
						   <label>Harga <?php echo $grup_nama ?></label>
						   <input type="text" readonly class="form-control form-control-sm" value="<?php echo $dtFungsi->fuang($formproduk['produk']['harga_member']).' ('.$formproduk['produk']['minbeli'].' pcs '.$formproduk['produk']['syarat'].')' ?>">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						   <label>Berat</label>
						   <input type="text" id="berat" readonly class="form-control form-control-sm" value="<?php echo $formproduk['produk']['berat'] ?> Gr">
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group">
						   <label>Stok</label>
						   <input type="text" id="stok" readonly class="form-control form-control-sm" value="<?php echo $formproduk['produk']['stok'] ?>">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						   <label>QTY</label>
						   <input type="text" id="qty" name="qty" class="form-control form-control-sm" value="1">
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<div class="form-group">
				   <button type="button" id="btnsimpanaddproduk" onclick="simpanaddproduk()" class="btn btn-sm btn-success">Tambah Produk</a>
				   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
				</div>
			</div>
		</div>
	</div>
</form>
<script>

</script>