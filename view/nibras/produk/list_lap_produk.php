<div class="container">
	<section class="page-section">
		<h1 class="section-title"><span>Cek Stok Produk</span></h1>
	</section>
	<div class="col-sm-12">
		<form role="form-inline" id="frmcari" name="frmcari" style="margin-bottom:20px" action='<?php echo URL_PROGRAM . $amenu ?>' method="GET">
			<div class="row">
				<div class="col-md-3" style="display:none;">
					<select id="kategori" name="kat" class="form-control form-control-sm">
						<option value="0">Semua Kategori</option>
						<?php

						foreach ($kategori as $kat) {
							if ($kat['kategori_spesial'] == '0') {

								if ($kat['children']) {

									foreach ($kat['children'] as $child) {
										$childs = $dtKategori->getKategori($child['id']);
										if ($kats == $child['id']) {
											$selected = ' selected ';
										} else {
											$selected = '';
										}
										if ($childs) {
											foreach ($childs as $ch) {
												if ($kats == $ch['kategori_id']) {
													$selected = ' selected ';
												} else {
													$selected = '';
												}
												$options .= '<option value="' . $ch['kategori_id'] . '"' . $selected . '>' . strip_tags($ch['kategori_nama']) . '</option>';
												$data['kategories'][] = array('id' => $ch['kategori_id'], 'nama' => strip_tags($ch['kategori_nama']));
											}
										} else {
											$options .= '<option value="' . $child['id'] . '"' . $selected . '>' . $child['nama'] . '</option>';
											$data['kategories'][] = array('id' => $child['id'], 'nama' => strip_tags($child['nama']));
										}
									}
								} else {
									if(empty($kat['id'])){
										continue;
									}
									
									if ($kats == $kat['id']) {
										$selected = ' selected ';
									} else {
										$selected = '';
									}
									$options .= '<option value="' . $kat['id'] . '"' . $selected . '>' . strip_tags($kat['nama']) . '></option>';
									$data['kategories'][] = array('id' => $kat['id'], 'nama' => strip_tags($kat['nama']));
								}
							}
						}

						echo $options;
						?>
					</select>
				</div>
				<div class="col-md-4">

					<input type="text" id="s" name="s" class="form-control form-control-sm" placeholder="Pencarian Nama Produk" value="<?php echo $search  ?>">
					<small class="form-text text-muted">
						Ketikkan nama produk
					</small>
				</div>
				<div class="col-md-1">
					<button type="submit" id="btnsearch" class="btn btn-primary btn-sm btn-block">Cari</button>

				</div>

			</div>
		</form>
		<?php $kategori_old = array(); ?>

		<?php if ($totalproduk > 0) { ?>

			<div class=" table-responsive">
				<table class="table_multi_kolom">

					<thead>
						<tr>
							<th colspan="<?php echo count($ukuranperkat) + 2 ?>" class="text-center kolom-row-multi-group"><?php echo isset($dataproduk[0]['nama_kategori']) ? strip_tags($dataproduk[0]['nama_kategori']) : '' ?></th>
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
					<tbody>
						
						<?php foreach ($dataproduk as $prod) { ?>
							<tr>
								<td class="rows"><a href="<?php echo URL_PROGRAM . $prod['alias_url'] ?>"><?php echo ucwords($prod["nama_produk"]) ?></a></td>
								<td class="rows"><?php echo ucwords($prod["warna"]) ?></td>

								<?php foreach ($ukuranperkat as $uk) { ?>

									<?php $ids = $prod['idproduk'] . ':' . $prod['idwarna'] . ':' . $uk['idukuran'] ?>
									<td class="rows text-center">
										<?php echo isset($datastoks["{$ids}"]) ? $datastoks["{$ids}"] : 0 ?>
									</td>
								<?php } ?>

							</tr>
						<?php } ?>
					</tbody>

				</table>
				<?php if ($totalproduk > 0) { ?>
					<div class="col-md-12">
						<div class="float-right">
							<nav aria-label="Page navigation">
								<ul class="pagination pagination-sm">
									<?php echo $dtPaging->GetPaging2($totalproduk, $baris, $page, $jmlpage, $linkpage, $linkcari, $amenu) ?>
								</ul>
							</nav>
						</div>
						<div class="clearfix"></div>
					</div>

				<?php
					} ?>
			</div>
		<?php } else { ?>
			<div class="col-sm-12">


				<?php if ($kats == '0' || $kats == '') { ?>
					<div class="alert alert-info text-center" role="alert">
						<h4><b>List Produk.</b></h4> <Br> Data produk akan tampil setelah input pencarian.
					</div>
				<?php } else { ?>
					<div class="alert alert-danger text-center">
						Tidak ada produk
					</div>
				<?php } ?>


			</div>
		<?php } ?>


	</div>
	<div class="clearfix"></div>
</div>

<script type="text/javascript">
	$(function(){
	
		/* autocomplete */
		$('#s').autocomplete({
			delay: 0,
			source: function( request, response ) {
				$.ajax({
					url: "https://grobogan.nibras.id/view/nibras/produk/list_product.php",
					dataType: "json",
					data: {
						loads: 'kode_produk',
						category: $('#kategori').val(),
						cariproduk: request.term
					},
					success: function( data ) {
						response( $.map( data, function( item ) {
							return {
								label: item.kode+' :: '+item.nama_produk,
								value: item.kode
					 
							}
						}));
					},
					error: function(e){  
						alert('Error: ' + e);  
					}  
				});
			},
			minLength: 1,
			select: function( event, ui ) {
				$('input[name=\'s\']').val(ui.item.value);
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
   
	/* @end autocomplete */
	});
</script>
