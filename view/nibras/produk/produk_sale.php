<div class="container">
	<section class="product-section">
		<h2 class="section-title"><span>Produk Sale</span></h2>
	</section>
	<div class="col-sm-12">
		<?php if ($ambildata) { ?>


			<div class="row list-produk">
				<?php
					$asid = 0;
					foreach ($ambildata as $datanya) {
						$gbrprod    = $datanya['gbr_produk'];
						$produkwarna = $dtProduk->getProdukSemuaWarna($datanya['idproduk']);
						?>
					<div class="col-sm-3">
						<div class="thumb_produk wow bounceIn" data-wow-delay="0.2s" data-wow-iteration="1">

							<a class="imgproduk" href="<?php echo URL_PROGRAM . $datanya['alias_url'] ?>" title="<?php echo $datanya['nama_produk']; ?>" width="<?php echo $config_produkthumbnail_p ?>px" height="<?php echo $config_produkthumbnail_l ?>px">
								<img src="<?php echo URL_IMAGE . '_thumb/thumbs_gproduk' . $gbrprod; ?>" alt="<?php echo $datanya['nama_produk']; ?>" title="<?php echo $datanya['nama_produk']; ?>" />
							</a>
							<?php
									$harga_normal = $datanya['hrg_jual'];
									$harga_diskon = 0;
									$persen = (int) $grup_diskon + (int) $datanya['persen_diskon'];
									if ($datanya['sale'] == '1') {

										if ($idmember != '') {

											$harga_diskon = $datanya['hrg_jual'] - (($datanya['hrg_jual'] * $persen) / 100);
										} else {
											$harga_diskon = $datanya['hrg_diskon'];
										}
										$labelsale 	 = '<span class="onsales"></span>';
										$labelharga  = '<div class="oldprice">Rp. ' . $dtFungsi->fuang($harga_normal) . '</div>';
										$labelharga	.= '<div class="diskonpersen">' . $persen . '% </div>';
										$labelharga .= '<div class="newprice">Rp. ' . $dtFungsi->fuang($harga_diskon) . '</div>';
									} else {
										$labelsale 	 = '';
										if ($idmember != '') {

											$harga_diskon = $datanya['hrg_jual'] - (($datanya['hrg_jual'] * $grup_diskon) / 100);
										}
										if ($harga_diskon > 0) {

											$labelharga  = '<div class="oldprice">Rp. ' . $dtFungsi->fuang($harga_normal) . '</div>';
											$labelharga	.= '<div class="diskonpersen">' . $persen . '% </div>';
											$labelharga .= '<div class="newprice">Rp. ' . $dtFungsi->fuang($harga_diskon) . '</div>';
										} else {

											$labelharga  = '<div class="price">Rp. ' . $dtFungsi->fuang($harga_normal) . '</div>';
										}
									}

									?>
							<div class="nama_produk"><?php echo $datanya['nama_produk'] ?></div>
							<?php echo $labelharga ?>
							<?php echo $labelsale ?>
						</div>
					</div>

				<?php } ?>
			</div>
			<?php print_r($totaldata) ?>
			<?php if ($totaldata > 0) { ?>
				<div class="float-right">
					<nav aria-label="Page navigation">
						<ul class="pagination pagination-sm">
						    
							<?php echo $dtPaging->GetPaging2($totaldata, $baris, $page, $jmlpage, $linkpage, $linkcari, $amenu) ?>
						</ul>
					</nav>
				</div>
				<div class="clearfix"></div>
			<?php } ?>
		<?php } else { ?>
			<p class="text-center">Tidak ada produk</p>
		<?php } ?>
	</div>
</div>