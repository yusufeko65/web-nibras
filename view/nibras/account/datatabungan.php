<div class="col-xs-9 hskonten">
	<div class="judul-konten"><h2>Data Tabungan</h2></div>
   <div class="isi-konten">
	  <div class="panel panel-default">
	    <div class="panel-body">
	  	  <table class="table table-bordered">
			 <thead>
				<tr>
					<th class="margintengah"><b>Tanggal</b></th>
					<th class="margintengah"><b>No Order</b></td>
					<th class="margintengah"><b>Status</b></td>
					<th class="margintengah"><b>Jumlah</b></td>
				</tr>
			 </thead>
		     <tbody>
		   <?php 
	  	     if ($datatabungan) {
				$totaltabungan = 0;
				foreach ($datatabungan as $tabungan) { ?>
	            <tr>
					<td class="margintengah"><?php echo $dtFungsi->ftanggalFull1($tabungan['tanggal'])?></td>
					<td class="margintengah"><?php echo $tabungan['noorder']?></td>
					<td class="margintengah"><?php echo $tabungan['status']?></td>
					<td class="marginkanan">
					<?php 
						$awal = '';
						$akhiran = '';
						if($tabungan['status'] == 'SPEND') {
							$awalan = '(';
							$akhiran = ')';
							$totaltabungan = $totaltabungan - (int)$tabungan['jumlah'];
						} else {
							$totaltabungan = $totaltabungan + (int)$tabungan['jumlah'];
						}
						echo $awalan.$dtFungsi->fFormatuang((int)$tabungan['jumlah']).$akhiran;
					?>
					</td>
				</tr>
		   <?php } ?>
				<tr>
					<td colspan="4" class="marginkanan"><h2>Total Deposit <?php echo $dtFungsi->fFormatuang($totaltabungan) ?></h2></td>
				</tr>
			 </tbody>
		  </table>
	 
	  <?php } else { ?>
	          <tr><td colspan="4" class="margintengah">Tidak ada deposit</td></tr>
	    <?php } ?>
	  </table>
	    </div>
	  </div>
   </div>
</div>
