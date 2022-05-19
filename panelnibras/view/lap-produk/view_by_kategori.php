<div id="hasil"></div>
<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>

	<div class="col-md-12 bagian-frm-cari ">
		<div class="row">
			<form role="form-inline" id="frmcari" name="frmcari">
				<input type="hidden" name="op" value="bykategori">
				<div class="col-md-2">
					<div class="row">

						<select id="kategori" name="kat" class="form-control">
							<option value="0">Pilih Kategori</option>
							<?php

							foreach ($kategories as $kat) {
								if ($kat['kategori_spesial'] == '0') {

									if ($kat['children']) {

										foreach ($kat['children'] as $child) {
											$childs = $dtKategori->getListKategori($child['id']);
											if ($kategori == $child['id']) {
												$selected = ' selected ';
											} else {
												$selected = '';
											}
											if ($childs) {
												foreach ($childs as $ch) {
													if ($kategori == $ch['kategori_id']) {
														$selected = ' selected ';
													} else {
														$selected = '';
													}
													$options .= '<option value="' . $ch['kategori_id'] .  '"' . $selected . '>' . strip_tags($ch['kategori_nama']) . 's</option>';
													$data['kategories'][] = array('id' => $ch['kategori_id'], 'nama' => strip_tags($ch['kategori_nama']));
												}
											} else {
												$options .= '<option value="' . $child['id'] .  '"' . $selected     . '>' . strip_tags($child['nama']) . '</option>';
												$data['kategories'][] = array('id' => $child['id'], 'nama' => strip_tags($child['nama']));
											}
										}
									} else {
										if ($kategori == $kat['id']) {
											$selected = ' selected ';
										} else {
											$selected = '';
										}
										$options .= '<option value="' . $kat['id'] . '"' . $selected  . '>' . strip_tags($kat['nama']) . '></option>';
										$data['kategories'][] = array('id' => $kat['id'], 'nama' => strip_tags($kat['nama']));
									}
								}
							}

							echo $options;
							?>
						</select>

					</div>

				</div>
				<div class="col-md-2">
					<div class="row">
						<input type="text" id="search_kode" name="search_kode" class="form-control" placeholder="Pencarian Kode Produk" value="<?php echo $search  ?>">
					</div>
				</div>
				<div class="col-md-4">
					<button type="button" id="btnsearch" class="btn btn-default">Filter</button>
					<?php if ($total > 0) { ?>
						<button type="button" id="btnexcel" class="btn btn-default" onclick="location='<?php echo URL_PROGRAM_ADMIN . "view/" . folder ?>'+'/exportexcel_perkategori.php?kat='+$('#kategori').val()">Export to Excel</button>
					<?php } ?>
				</div>
			</form>
		</div>
	</div>

	<?php if ($total > 0) { ?>

		<table class="table_multi_kolom">

			<thead>
				<tr>
					<th colspan="<?php echo count($ukuranperkat) + 2 ?>" class="text-center kolom-row-multi-group"><?php echo isset($dataview['rows'][0]['nama_kategori']) ? strip_tags($dataview['rows'][0]['nama_kategori']) : '' ?></th>
				</tr>
				<tr>
					<th rowspan="2" class="text-center" valign="middle" width="40%">Nama Produk</th>
					<th rowspan="2" class="text-center" valign="middle" width="30%">Warna</th>
					<th colspan="<?php echo count($ukuranperkat) ?>" class="text-center" valign="middle">Size</th>
				</tr>

				<tr>
					<?php foreach ($ukuranperkat as $uk) { ?>
						<th class="text-center"><?php echo $uk['ukuran'] ?></th>
					<?php } ?>
				</tr>

			</thead>
			<tbody>

				<?php foreach ($dataview['rows'] as $prod) { ?>
					<tr>
						<td class="row"><?php echo ucwords($prod["nama_produk"]) ?></td>
						<td class="row"><?php echo ucwords($prod["warna"]) ?></td>

						<?php foreach ($ukuranperkat as $uk) { ?>
							<?php $ids = $prod['idproduk'] . ':' . $prod['idwarna'] . ':' . $uk['idukuran'] ?>
							<td class="row text-center">
								<?php echo isset($datastoks["{$ids}"]) ? $datastoks["{$ids}"] : 0 ?>
							</td>
						<?php } ?>

					</tr>
				<?php } ?>
			</tbody>

		</table>



		<!-- Pagging -->
		<div class="col-md-6">
			<div class="row">Showing <?php echo $page ?> of <?php echo $jmlpage ?> Page, <?php echo $total ?> data</div>
		</div>
		<div class="col-md-6 text-right">
			<ul class="pagination pagination-sm"><?php echo $dtPaging->GetPaging($total, $baris, $page, $jmlpage, $linkpage) ?></ul>
		</div>

		<!-- End Pagging -->
	<?php } else { ?>

		<div class="col-md-6 col-md-offset-3" style="margin-top:20px">
			<div class="row">

				<?php if ($kategori == '0' || $kategori == '') { ?>
					<div class="alert alert-info text-center" style="padding:20px">
						<h4><b>Laporan Produk per Kategori.</b></h4> <Br> Data produk akan tampil setelah memilih kategori di menu pilihan di atas.
					</div>
				<?php } else { ?>
					<div class="alert alert-danger text-center">
						Tidak ada produk
					</div>
				<?php } ?>

			</div>
		</div>
	<?php } ?>

</div>
<script>
	$(function() {
		$("#btnsearch").click(function() {
			caridata();
		});
	});

	function caridata() {
		var zdata = escape($('#kategori').val());
		var search_kode = escape($('#search_kode').val());
		location = '<?php echo URL_PROGRAM_ADMIN . folder . '/?op=bykategori&kat=' ?>' + zdata + '&search_kode=' + search_kode;

	}
</script>