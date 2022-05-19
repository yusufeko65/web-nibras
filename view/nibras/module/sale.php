<?php
$produksale = $dtProduk->getModuleProdukSale($config_produksalehome);

if ($produksale) { ?>
	<div class="container">
		<section class="product-section">
			<h2 class="section-title"><span>Sales</span></h2>
		</section>
		<div class="col-sm-12">
			<div class="pull-right">
				<a href="<?php echo URL_PROGRAM . 'produk-sale' ?>" class="btn btn-sm btn-default">Selengkapnya</a>
			</div>
			<div class="clearfix"></div>
			<div class="row list-produk">
				<?php
					foreach ($produksale as $ps) {
						$gbrprod    = $ps['gbr_produk'];
						$produkwarna = $dtProduk->getProdukSemuaWarna($ps['idproduk']);
						?>
					<div class="col-sm-3">
						<div class="thumb_produk wow bounceIn" data-wow-delay="0.2s" data-wow-iteration="1">
							<?php if ($produkwarna) { ?>
								<div class="thumb_produk_item">
									<?php $i = 0; ?>
									<?php foreach ($produkwarna as $pw) { ?>
										<?php if ($i < 4) { ?>
											<?php if (file_exists(DIR_IMAGE . '_small/small_gproduk' . $pw['gbr'])) { ?>
												<img src="<?php echo URL_IMAGE . '_small/small_gproduk' . $pw['gbr']; ?>" class="rounded img-thumbnail">
											<?php } ?>
										<?php } ?>
									<?php } ?>
								</div>
							<?php } ?>

							<a class="imgproduk" href="<?php echo URL_PROGRAM . $ps['alias_url'] ?>" title="<?php echo $ps['nama_produk']; ?>" width="<?php echo $config_produkthumbnail_p ?>px" height="<?php echo $config_produkthumbnail_l ?>px">
								<img src="<?php echo URL_IMAGE . '_thumb/thumbs_gproduk' . $gbrprod; ?>" alt="<?php echo $ps['nama_produk']; ?>" title="<?php echo $ps['nama_produk']; ?>" />
							</a>
							<?php
									$harga_normal	    = $ps['hrg_jual'];
									$persen = (int) $grup_diskon + (int) $ps['persen_diskon'];
									/*
						if($ps['sale'] == '1') {
							$hargadiskon = $ps['hrg_diskon'];
							$labelsale = '<span class="onsales"></span>';
							$labelharga = '<div class="oldprice">Rp. '.$dtFungsi->fuang($harga).'</div>';
							$labelharga	.= '<div class="diskonpersen">'.$datanya['persen_diskon'].'% </div>';
							$labelharga .= '<div class="newprice">Rp. '.$dtFungsi->fuang($hargadiskon).'</div>';
						} else {
							$labelsale = '';
							$labelharga = '<div class="price">Rp. '.$dtFungsi->fuang($harga).'</div>';
						}							
						*/
									if ($ps['sale'] == '1') {

										if ($idmember != '') {


											//$harga_diskon = $ps['hrg_diskon'] - (($ps['hrg_diskon']*$grup_diskon/100));
											$harga_diskon = $ps['hrg_jual'] - (($ps['hrg_jual'] * $persen) / 100);
										} else {
											$harga_diskon = $ps['hrg_diskon'];
										}
										$labelsale 	 = '<span class="onsales"></span>';
										$labelharga  = '<div class="oldprice">Rp. ' . $dtFungsi->fuang($harga_normal) . '</div>';
										$labelharga	.= '<div class="diskonpersen">' . $persen . '% </div>';
										$labelharga .= '<div class="newprice">Rp. ' . $dtFungsi->fuang($harga_diskon) . '</div>';
									} else {
										$labelsale 	 = '';
										if ($idmember != '') {

											$harga_diskon = $ps['hrg_jual'] - (($ps['hrg_jual'] * $grup_diskon) / 100);
										}
										if ($harga_diskon > 0) {
											//$persen = (int)$grup_diskon + (int)$ps['persen_diskon'];
											$labelharga  = '<div class="oldprice">Rp. ' . $dtFungsi->fuang($harga_normal) . '</div>';
											$labelharga	.= '<div class="diskonpersen">' . $persen . '% </div>';
											$labelharga .= '<div class="newprice">Rp. ' . $dtFungsi->fuang($harga_diskon) . '</div>';
										} else {

											$labelharga  = '<div class="price">Rp. ' . $dtFungsi->fuang($harga_normal) . '</div>';
										}
									}
									?>
							<div class="nama_produk"><?php echo $ps['nama_produk'] ?></div>
							<?php echo $labelharga ?>
							<?php echo $labelsale ?>

						</div>
					</div>

				<?php } // end foreach 
					?>

			</div>
		</div>
	</div>
<?php } ?>