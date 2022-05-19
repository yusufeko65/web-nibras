<div class="col-lg-12 main-content">
     <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
     <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
		  <div class="widget-content nopadding">
	        <form class="form-horizontal" autocomplete="off" method="POST" name="frmdata" id="frmdata" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
				<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
				<input type="hidden" id="shipping_id" name="shipping_id" value="<?php echo isset($shipping_id) ? $shipping_id : 0 ?>">
				<div id="hasil" style="display: none;"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Kode Shipping</label>
					<div class="col-sm-2">
						<input type="text" id="shipping_kode" name="shipping_kode" class="form-control" value="<?php echo isset($shipping_kode) ? $shipping_kode : '' ?>">
						<input type="hidden" id="shipping_kode_lama" name="shipping_kode_lama" value="<?php echo isset($shipping_kode) ? $shipping_kode : '' ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Kode Shipping Rajaongkir<br><cite>Jika ingin terintegrasi rajaongkir.com, kode shipping harus disamakan dengan kode di <a href="https://rajaongkir.com/dokumentasi/pro#cost-request" target="_blank">rajaongkir.com</a></label>
					<div class="col-sm-2">
						<input type="text" id="shipping_kdrajaongkir" name="shipping_kdrajaongkir" class="form-control" value="<?php echo isset($shipping_kdrajaongkir) ? $shipping_kdrajaongkir : '' ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Nama Shipping</label>
					<div class="col-sm-4">
						<input type="text" id="shipping_nama" name="shipping_nama" class="form-control" value="<?php echo isset($shipping_nama) ? $shipping_nama : '' ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Logo Shipping</label>
					<div class="col-sm-4">
						<input type="file" id="shipping_logo" name="shipping_logo">
						<input type="hidden" id="shipping_logo_old" name="shipping_logo_old" value="<?php echo isset($shipping_logo) ? $shipping_logo : '' ?>">
						<?php if(isset($shipping_logo) && $shipping_logo != '') { ?>
						<img src="<?php echo URL_IMAGE.'_other/other_'.$shipping_logo ?>" class="rounded img-thumbnail">
						<?php } ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Batas Koma
					<br><cite>Misal JNE, Batas terakhir 1 Kg adalah 1,3 Kg. Karena berat 1,4 Kg dianggap 2 Kg. Maka 1,3 - 1 = 0.3</cite>
					</label>
					<div class="col-sm-1">
						<input type="text" id="shipping_bataskoma" name="shipping_bataskoma" class="form-control" value="<?php echo isset($shipping_bataskoma) ? $shipping_bataskoma: '' ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Untuk Plublik ? <br><cite>Jika "Ya", maka akan tampil di halaman website untuk dipilih Pelanggan sebagai kurir pengiriman</cite></label>
					<div class="col-sm-2">
						<select id="shipping_publik" name="shipping_publik" class="form-control">
							<option value="0" <?php echo isset($shipping_publik) && $shipping_publik == '0' ? 'selected' : '' ?>>Tidak</option>
							<option value="1" <?php echo isset($shipping_publik) && $shipping_publik == '1' ? 'selected' : '' ?>>Ya</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Konfirmasi Admin ? <br><cite>Jika "Ya", maka saat pelanggan memilih kurir tersebut, harga tarif tidak tercantum dan bertuliskan "Konfirmasi Admin"</cite></label>
					<div class="col-sm-2">
						<select id="shipping_konfirmadmin" name="shipping_konfirmadmin" class="form-control">
							<option value="0" <?php echo isset($shipping_konfirmadmin) && $shipping_konfirmadmin == '0' ? 'selected' : '' ?>>Tidak</option>
							<option value="1" <?php echo isset($shipping_konfirmadmin) && $shipping_konfirmadmin == '1' ? 'selected' : '' ?>>Ya</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">COD ? <br><cite>Cash On Delivery / Bayar di Tempat, Shipping yang pembayarannya oleh si Pelanggan saat paket diterima. Misal GOJEK </cite></label>
					<div class="col-sm-2">
						<select id="shipping_cod" name="shipping_cod" class="form-control">
							<option value="0" <?php echo isset($shipping_cod) && $shipping_cod == '0' ? 'selected': '' ?>>Tidak</option>
							<option value="1" <?php echo isset($shipping_cod) && $shipping_cod == '1' ? 'selected': '' ?>>Ya</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Integrasi Rajaongkir.com ? <br><cite>Harga tarif otomatis dari rajaongkir.com ?sebelum memilih ini, pastikan sudah mendapatkan API Key dari rajaongkir.com</cite></label>
					<div class="col-sm-2">
						<select id="shipping_rajaongkir" name="shipping_rajaongkir" class="form-control">
							<option value="0" <?php echo isset($shipping_rajaongkir) && $shipping_rajaongkir == '0' ? 'selected': '' ?>>Tidak</option>
							<option value="1" <?php echo isset($shipping_rajaongkir) && $shipping_rajaongkir == '1' ? 'selected': '' ?>>Ya</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status</label>
					<div class="col-sm-2">
						<select id="tampil" name="tampil" class="form-control">
							<option value="0" <?php echo isset($tampil) && $tampil == '0' ? 'selected': '' ?>>Disabled</option>
							<option value="1" <?php echo isset($tampil) && $tampil == '1' ? 'selected': '' ?>>Enabled</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12 text-right">
						
						<button type="submit" class="btn btn-sm btn-success">Simpan</button>
						<a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
						<?php if($modul == 'ubah') { ?>
						<a href="<?php echo URL_PROGRAM_ADMIN.folder.'?op=add' ?>" class="btn btn-sm btn-primary">Tambah Data</a>
						<?php } ?>
					</div>
				</div>
				<div class="clearfix"></div>
	        </form>
		</div>
    </div> 
</div>
 

<script src="<?php echo URL_PROGRAM_ADMIN_VIEW.folder."/validasi.js" ?>"></script>