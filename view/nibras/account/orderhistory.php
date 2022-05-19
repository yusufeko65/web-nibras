<div class="container">
	<section class="page-section">
		<h1 class="section-title"><span>Data Belanja </span></h2>
	</section>
	<div class="col-sm-12">
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr class="table-secondary">
						<th class="text-center"><b>Order ID</b></th>
						<th class="text-center"><b>Pengirim</b></th>
						<th class="text-center"><b>Penerima</b></th>
						<th class="text-center"><b>Jumlah</b></th>
						<th class="text-center"><b>Total</b></th>
						<th class="text-center"><b>Tgl Beli</b></th>
						<th class="text-center"><b>Status</b></th>
						<th class="text-center"><b>Detail</b></th>
					</tr>
				</thead>
				<tbody>
					<?php if ($ambildata) { ?>
						<?php foreach ($ambildata as $order) { ?>
							<?php $order['pesanan_kurir'] = $order['pesanan_kurir'] > 0 ? $order['pesanan_kurir'] : 0; ?>
							<tr>
								<td class="text-center"><?php echo $order['pesanan_no'] ?></td>
								<td><?php echo $order['nama_pengirim'] ?></td>
								<td><?php echo $order['nama_penerima'] ?></td>
								<td class="text-right"><?php echo $order['pesanan_jml'] ?></td>
								<td class="text-right"><?php echo $dtFungsi->fFormatuang((int)$order['pesanan_subtotal'] + (int)$order['pesanan_kurir'] - (int)$order['dari_poin']) ?></td>
								<td class="text-center"><?php echo $dtFungsi->ftanggalFull2($order['pesanan_tgl']) ?></td>
								<td class="text-center"><?php echo $order['status_nama'] ?></td>
								<td class="text-center"><a href="<?php echo URL_PROGRAM . 'orderdetail/?order=' . $order['pesanan_no'] ?>" class="btn btn-sm btn-outline-info">Detail</a></td>
							</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td colspan="8" class="text-center">Tidak ada belanja</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>

		<div class="col-lg-12">
			<div class="row">
				<div class="col-md-6">
					Hal <?php echo $page ?> dari <?php echo $jmlpage ?>, Jumlah Belanja <?php echo $totals ?>
				</div>
				<div class="col-md-6">
					<?php if ($jmlpage > 0) { ?>
						<div class="float-right">
							<div class="row">
								<nav aria-label="Page navigation">
									<ul class="pagination pagination-sm">
										<?php echo $dtPaging->GetPaging2($totals, $baris, $page, $jmlpage, $linkpage, $linkcari, $amenu) ?>
									</ul>
								</nav>
							</div>
						</div>
					<?php } ?>
				</div>

			</div>
		</div>
	</div>
</div>