<div class="container">
	<section class="page-section">
		<h1 class="section-title"><span><?php echo $headproduk['dataproduk']['nama_produk'] ?></span></h2>
	</section>
	<div class="col-sm-12">

		<form id="frmpesan">
			<input type="hidden" id="url_image" value="<?php echo URL_IMAGE ?>">
			<div class="container">
				<div class="row justify-content-md-center">
					<div class="col-sm-5">

						<a href="<?php echo URL_IMAGE . '_zoom/zoom_gproduk' . $headproduk['dataproduk']['gbr_produk'] ?>" title="<?php echo $headproduk['dataproduk']['nama_produk'] ?>" class="zoom-produk" id="urlgbr"><img src="<?php echo URL_IMAGE . '_detail/detail_gproduk' . $headproduk['dataproduk']['gbr_produk'] ?>" class="img-fluid cover-produk" id="idimage"></a>
						<?php $produk_old = [] ?>


						<div class="col-md-12">
							<?php foreach ($dataproduk as $produk) { ?>
								<?php if (!in_array($produk['idproduk'], $produk_old)) { ?>
									<?php array_push($produk_old, $produk['idproduk']); ?>
									<div class="detail-img-produk">
										<a href="<?php echo URL_PROGRAM . $produk['alias_url'] ?>" title="<?php echo $produk['nama_produk'] ?>">
											<img class="img-fluid img-thumbnail" src="<?php echo URL_IMAGE . '_small/small_gproduk' . $produk['gbr_produk'] ?>" alt="<?php echo $produk['nama_produk'] ?>">
										</a>
									</div>
								<?php } ?>
							<?php } ?>

						</div>
						<div class="clearfix"></div>
						<div class="form-group row">

							<div class="col-md-12">
								<input type="text" readonly class="form-control-plaintext" value="<?php echo strtoupper($headproduk['dataproduk']['kode_produk']) ?>">
							</div>
						</div>

						<?php if ($headproduk['dataproduk']['deskripsi_head']) { ?>
							<div class="form-group row">
								<div class="col-md-12">
									<?php echo $headproduk['dataproduk']['deskripsi_head']; ?>
								</div>
							</div>
						<?php } ?>
					</div>
					<?php if (count($dataproduk) > 0) { ?>
						<div class="col-sm-7">
							<input type="hidden" id="url_produk" value="<?php echo $currentUrl ?>">
							<div class="well description">

								<?php if ($warna) { ?>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Warna yang tersedia</label>
										<div class="col-sm-8">
											<input type="text" id="jmlwarna" readonly class="form-control-plaintext" value="<?php echo count($warna) ?> warna">
											<input type="hidden" id="idwarna" name="idwarna">
										</div>
									</div>
									<div class="form-group row">
										<div class="col-sm-8">
											<div class="warna-lain-produk">
												<div class="input-produk"><input type="radio" name="warna" value="0" checked></div>
												<?php foreach ($warna as $w) { ?>
													<div class="input-produk">
														<input data-toggle="tooltip" data-placement="bottom" title="<?php echo $w['warna'] ?>" class="warna" type="radio" id="warna<?php echo $w['idwarna'] ?>" name="warna" value="<?php echo $w['idwarna'] ?>" rel="<?php echo $w['image_head'] ?>" onchange="viewProduk('<?php echo $w['image_head'] ?>','<?php echo $w['idwarna'] ?>','<?php echo $headproduk['dataproduk']['head_idproduk'] ?>');$('#keteranganwarna').html($(this).prop('title'))" />
														<label title="<?php echo $w['warna'] ?>" class="produk_warna" for="warna<?php echo $w['idwarna'] ?>" style="background-image:url('<?php echo URL_IMAGE . '_thumb/thumbs_gproduk' . $w['image_head'] ?>');"></label>
													</div>
												<?php } ?>
											</div>

										</div>
									</div>
								<?php } ?>

								<div class="col-md-12">
									<div class="row">
										<?php
											$kategori_old = [];
											$ukuranperkat = [];
											$datastoks = [];
											$produk_olds = [];
											$kategori_list = [];
											$kategori_list_old = [];
											$list_produk_head = [];
											$list_produk_head_old = [];

											?>
										<?php foreach ($dataproduk as $product) {
												if (!in_array($product['idkategori'], $kategori_list_old)) {
													array_push($kategori_list_old, $product['idkategori']);
													$kategori_list[] = array('idkategori' => $product['idkategori'], 'nama_kategori' => $product['nama_kategori']);
												}
												if (!in_array($product['idproduk'] . $product["warna"], $list_produk_head_old)) {
													array_push($list_produk_head_old, $product['idproduk'] . $product["warna"]);
													$list_produk_head[] = $product;
												}
											}
											//echo '<pre>';
											//print_r($list_produk_head);
											?>
										<?php foreach ($kategori_list as $kate_list) { ?>
											<div class="table-responsive" style="font-size:11px">

												<table class="table_multi_kolom">
													<?php $datastoks 		= $dtProduk->getStokProdukPerKategoriPerWarnaUkuran($kate_list['idkategori']); ?>
													<?php if (!in_array($kate_list['idkategori'], $kategori_old)) { ?>
														<?php array_push($kategori_old, $kate_list['idkategori']); ?>
														<?php $ukuranperkat 	= $dtProduk->getUkuranKategori($kate_list['idkategori']); ?>

														<thead>
															<tr>
																<th colspan="<?php echo count($ukuranperkat) + 2 ?>" class="text-center kolom-row-multi-group"><?php echo $kate_list['nama_kategori'] ?></th>
															</tr>
															<tr>
																<th rowspan="2" class="text-center" valign="middle" width="20%">Nama Produk</th>
																<th rowspan="2" class="text-center" valign="middle" width="20%">Warna</th>
																<th colspan="<?php echo count($ukuranperkat) ?>" class="text-center" valign="middle">Size</th>
															</tr>

															<tr>
																<?php
																			$jmluk = count($ukuranperkat);
																			$persen = 60 / $jmluk . '%';
																			?>
																<?php foreach ($ukuranperkat as $uk) { ?>
																	<th class="text-center" width="<?php echo $persen ?>"><?php echo $uk['ukuran'] ?></th>
																<?php } ?>
															</tr>
														</thead>
													<?php }	?>
													<tbody>
														<?php $produk_olds2 = [] ?>

														<?php foreach ($list_produk_head as $prod) { ?>
															<?php if ($prod['idkategori'] == $kate_list['idkategori']) { ?>
																<?php if (!in_array($prod['idproduk'] . $prod["warna"], $produk_olds2)) { ?>

																	<?php array_push($produk_olds2, $prod['idproduk'] . $prod["warna"]); ?>
																	<tr>
																		<td class="rows"><a href="<?php echo URL_PROGRAM . $prod['alias_url'] ?>"><?php echo ucwords($prod["nama_produk"]) ?></a></td>
																		<td class="rows"><?php echo ucwords($prod["warna"]) ?></td>

																		<?php foreach ($ukuranperkat as $uk) { ?>

																			<?php $ids = $prod['idproduk'] . ':' . $prod['idwarna'] . ':' . $uk['idukuran'] ?>
																			<td class="rows text-center">
																				<?php
																										$stok_produk = isset($datastoks["{$ids}"]) ? $datastoks["{$ids}"] : 0;
																										if ($stok_produk > 0) {
																											echo '<font class="text-success"><b>' . $stok_produk . '</b></font>';
																										} else {
																											echo $stok_produk;
																										}

																										?>
																			</td>
																		<?php } ?>

																	</tr>
																<?php } ?>
															<?php } ?>
														<?php } ?>
														<?php //echo '<pre>';
																//print_r($produk_olds);
																?>
													</tbody>
												</table>

											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>

					<?php } ?>
				</div>
			</div>
		</form>
	</div>
</div>