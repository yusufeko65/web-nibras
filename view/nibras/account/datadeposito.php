<div class="container">
	<section class="page-section">
		<h1 class="section-title"><span>Data Saldo</span></h2>
	</section>
	<div class="col-md-12">
		<div class="text-right">
			<h4>Saldo Anda Sekarang : <?php echo isset($totaldeposito['totaldeposito']) ? $dtFungsi->fuang($totaldeposito['totaldeposito']) : 0; ?></h4>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="text-left" width="20%">Tanggal</th>
						<th class="text-right">Saldo</th>
						<th class="text-center">TIPE</th>
						<th class="text-center">Keterangan</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($datadeposito as $dt) {?>
					<tr>
					   <td class="text-left"><b><?php echo $dt['cdh_tgl'] ?></b></td>
					   <td class="text-right"><?php echo $dtFungsi->fuang($dt['cdh_deposito']) ?></td>
					   <td class="text-center"><?php echo $dt['cdh_tipe']  ?></td>
					   <td class="text-left"><?php echo $dt['cdh_keterangan']  ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>