<div class="container">
	<section class="page-section">
		<h1 class="section-title"><span>Data Poin</span></h2>
	</section>
	<div class="col-md-12">
		<div class="text-right">
			<h4>Poin Anda Sekarang : <?php echo isset($totalpoin['totalpoin']) ? $totalpoin['totalpoin'] : 0; ?></h4>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead class="thead-light">
					<tr>
						<th class="text-left" width="20%">Tanggal</th>
						<th class="text-right">Poin</th>
						<th class="text-right">TIPE</th>
						<th class="text-right">No. Order</th>
					</tr>
				</thead>
				<tbody>
					<?php if($datapoin) { ?>
						<?php foreach($datapoin as $dt) {?>
						<tr>
							<td class="text-left"><b><?php echo $dt['cph_tgl'] ?></b></td>
							<td class="text-right"><?php echo $dt['cph_poin'] ?></td>
							<td class="text-right"><?php echo $dt['cph_tipe']  ?></td>
							<td class="text-right"><?php echo sprintf('%08s', (int)$dt['cph_order']) ?></td>
						</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td class="text-center" colspan="4">Anda Belum Ada Poin</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>