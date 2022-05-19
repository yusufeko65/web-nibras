<div class="container">
	<section class="product-section">
		<h2 class="section-title"><span><?php echo $namakategori ?></span></h2>
	</section>
	<div class="col-sm-12">
		<?php if ($ambildata) { ?>
			<?php if ($deskripsikategori) { ?>
				<?php echo $deskripsikategori ?>
			<?php } ?>
			<div class="row">
				<div class="col-md-1 text-right">
					<a href="#" class="btn btn-sm btn-block btn-outline-info" data-toggle="modal" data-target="#formfilter"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
				</div>
				<?php include "formfilter.php" ?>
				<div class="col-md-8"></div>
				<div class="col-sm-3 sort">
					<select name="sort" class="form-control form-control-sm" onchange="location = '<?php echo URL_PROGRAM . $linkpage . $linkcari ?>sort=' + this.value">
						<option value="upd" <?php if ($sort == 'upd' || $sort == '') echo "selected" ?>>Update Terbaru</option>
						<option value="new" <?php if ($sort == 'new') echo "selected" ?>>Produk Terbaru</option>
						<option value="old" <?php if ($sort == 'old') echo "selected" ?>>Produk Terlama</option>
						<option value="hrgasc" <?php if ($sort == 'hrgasc') echo "selected" ?>>Harga Terendah</option>
						<option value="hrgdesc" <?php if ($sort == 'hrgdesc') echo "selected" ?>>Harga Tertinggi</option>
						<option value="namaasc" <?php if ($sort == 'namaasc') echo "selected" ?>>A - Z</option>
						<option value="namadesc" <?php if ($sort == 'namadesc') echo "selected" ?>>Z - A</option>
					</select>
				</div>

			</div>
			<?php if (($fwarna != '' && $fwarna != '0') || ($fukuran != '' && $fukuran != '0')) { ?>
				<div class="row filterproduk">
					Difilter berdasarkan : <?php echo implode(", ", $atribut) ?>
				</div>
				<div class="clearfix"></div>
			<?php } ?>
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
							$persen = (int)$grup_diskon + (int)$datanya['persen_diskon'];
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