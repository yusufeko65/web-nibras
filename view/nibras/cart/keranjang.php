<div class="container">
	<section class="page-section">
		<h2 class="section-title"><span>Keranjang Belanja</span></h2>
	</section>
	<div class="col-md-12">
		<div id="hasil" <?php echo $style . '' . $kelaserror ?>><?php echo $pesan ?></div>
		<form name="frmcart" id="frmcart" autocomplete="off">
			<input type="hidden" id="url_cart" name="url_cart" value="<?php echo URL_PROGRAM . "cart/update" ?>">
			<input type="hidden" id="url_web_cart" name="url_web_cart" value="<?php echo URL_PROGRAM . "cart" ?>">
			<input type="hidden" id="url_del" name="url_del" value="<?php echo URL_PROGRAM . "cart/del" ?>">
			<div class="alert alert-info">
				List Produk yang dibawah ini hanya sementara, untuk membooking produk-produk dibawah ini Anda harus selesaikan Order sampai selesai atau sampai Anda mendapatkan Nomor Order
			</div>
			<div class="row shopingcart">
				<?php if ($jmlcart == 0) { ?>
					<div class="container">
						<div class="row justify-content-md-center">
							<div class="col-md-4 text-center text-danger">
								Keranjang Belanjang Kosong <br><br>
								<a href="<?php echo URL_PROGRAM ?>" class="btn btn-block btn-outline-success">Belanja Sekarang</a>
								<br>
							</div>
						</div>
					</div>
				<?php } else { ?>
					<?php
					$i = 0;
					$cart = $dtFungsi->urutkan($hcart, 'product_id');

					foreach ($cart as $c) {
						$pid 		 	= $c['product_id'];
						$nama_produk 	= $c['product'];
						$jml 		 	= $c['qty'];
						$satuanberat 	= $c['satuanberat'];
						$berat 		 	= $c['berat'];
						$hargasatuan	= $c['hargasatuan'];
						$harga 		 	= $c['harga'];
						$hargatambahan	= $c['hargatambahan'];
						$total 		 	= $c['total'];
						$diskon_cust	= $c['diskon_cust'];
						$diskon_produk	= $c['persen_diskon_prod'];
						$subtotal	   += $total;
						$idwarna     	= $c['warna'];
						$idukuran    	= $c['ukuran'];
						$warna			= $c['warna_nama'];
						$ukuran			= $c['ukuran_nama'];
						$options		= array($idukuran, $idwarna);
						$alias_url		= $c['aliasurl'];
						$image_produk 	= $c['image_produk'];

						$persendiskon = $diskon_cust + $diskon_produk;
						?>
						<div class="col-12 text-right">
							<a id="btnhapuscart" href="#" class="btn btn-sm btn-outline-danger" onclick="hapusCart('<?php echo $pid . '::' . base64_encode(serialize($options)) . '::' . $jml . '::' . $image_produk ?>')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</div>
						<div class="cart-deskripsi-item col-md-12">
							<div class="row">
								<div class="col-3 text-center">
									<img class="img-fluid" src="<?php echo URL_IMAGE . '_thumb/thumbs_gproduk' . $image_produk ?>" width="70%">
								</div>
								<div class="col-9">

									<h4 class="cart_nama_produk">
										<a href="<?php echo URL_PROGRAM . $alias_url ?>"><?php echo $nama_produk ?></a>
									</h4>

									<small><?php echo $warna != '' || $warna != '0' ? 'Warna : ' . $warna . ', ' : '' ?> <?php echo $ukuran != '' ? 'Ukuran : ' . $ukuran : '' ?></small><br>
									<b>Harga Normal : Rp. <?php echo $dtFungsi->fuang($hargasatuan) ?> <?php echo $hargatambahan > 0 ? ' <small> + ' . $dtFungsi->fuang($hargatambahan) . ' (harga tambahan) </small>' : '' ?></b><br>
									<b>Diskon : <?php echo $persendiskon ?> %</b><br>
									<b>Harga <?php echo $grup_nama ?>: Rp. <?php echo $dtFungsi->fuang($harga) ?>

									</b>
									<div class="col-md-1 col-5">
										<div class="row">

											<?php //$qty_c =  $pid.'::'.base64_encode(serialize($options)).'::'.$jml.'::'.base64_encode($image_produk)
											//echo base64_encode($image_produk);
											$qty_c =  $pid . '::' . base64_encode(serialize($options)) . '::' . $jml;
											?>
											<input type="number" min='1' class="form-control form-control-sm" size="1" value="<?php echo $jml ?>" class="form-control" name="qty[<?php echo $qty_c ?>]">
										</div>
									</div>
								</div>

								<div class="col-md-12 text-right">
									<h5 class="subtotal">Subtotal : Rp. <?php echo $dtFungsi->fuang($total) ?></h5>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="col-12 text-center">
						<h3><?php echo $dtFungsi->fFormatuang($subtotal) ?></h3>
						<div class="alert alert-info text-center">
							<?php echo $dtFungsi->terbilang($subtotal) ?> Rupiah
						</div>
					</div>
					<div class="col-md-12 text-center">
						<button class="btn btn-sm btn-warning" id="button-update" type="button">Update Quantity</button><Br><br>
					</div>
					<div class="col-12">
						<div class="row">

							<div class="col-md-6"><a href="<?php echo URL_PROGRAM ?>" class="btn btn-danger btn-block">Belanja Lagi</a></div>
							<div class="col-md-6 text-right"><a href="<?php echo URL_PROGRAM . 'cart/checkout' ?>" class="btn btn-danger btn-block">Lanjut Bayar</a></div>
						</div>
					</div>
				<?php } ?>
			</div>

		</form>
		<div class="clearfix"></div>
	</div>
</div>