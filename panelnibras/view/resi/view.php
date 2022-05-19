<div id="hasil"></div>
<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	<?php if (!is_null($result)): ?>
	<?php if ($result["status"] == 'success'): ?>
	<div id="hasil" class="alert alert-success"><?php echo $result["message"] ?></div>
	<?php else: ?>
	<div id="hasil" class="alert alert-danger"><?php echo $result["message"] ?></div>
	<?php endif;?>
	<?php endif;?>
	<div class="row">
		<div class="col-md-8 bagian-frm-cari ">
			<div class="row">
				<div class="col-md-4">
					<select class="form-control input-sm" id="kurir" name="kurir">
						<option value="0">- Kurir -</option>
						<?php foreach ($list_kurir as $kurir) {?>
						<?php if ($kurir['kurir'] == 'undefined' || $kurir['kurir'] == '') {
    continue;
}
    ?>
						<option value="<?php echo $kurir['kurir'] . "#" . $kurir['service_id']
    ?>" <?php if ($kurir_cari == $kurir['kurir'] . "#" . $kurir['service_id']) {
        echo "selected";
    }
    ?>><?php echo $kurir['kurir'] . " - " . $kurir['service'] ?></option>
						<?php }?>
					</select>
				</div>
				<div class="col-md-4">
					<form role="form-inline" action="<?php echo URL_PROGRAM_ADMIN . folder ?>
                                                  " id="frmcari" name="frmcari">
						<div class="input-group">
							<input type="text" class="form-control input-sm" id="datacari" name="datacari"
								value="<?php echo isset($_GET['datacari']) ? $_GET['datacari'] : '' ?>" placeholder="Pencarian Order">
							<span class="input-group-btn">
								<button class="btn btn-hijau btn-sm" type="button" id="tblcari"><span
										class="glyphicon glyphicon-search"></span></button>
							</span>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<table class="table table-bordered table-striped table-hover tabel-grid">
		<thead>
			<tr>
				<td>ID Order</td>
				<td>Customer</td>
				<td>Pengirim</td>
				<td>Penerima</td>
				<td>Kurir</td>
				<td>Biaya Ongkir</td>
				<td>No Resi</td>
				<td>Admin</td>
				<td>Status</td>
			</tr>
		</thead>
		<tbody id="viewdata">
			<?php foreach ($ambildata as $datanya) {?>
			<tr>
				<td><?php echo $datanya["pesanan_no"] ?></td>
				<td><?php echo $datanya["cust_nama"] ?></td>
				<td><?php echo $datanya["nama_pengirim"] ?></td>
				<td><?php echo $datanya["nama_penerima"] ?></td>
				<td><?php echo $datanya["kurir"] . " - " . $datanya["service"] ?></td>
				<?php if ($datanya["ongkir"] != 0): ?>
				<td><?php echo number_format($datanya["ongkir"], 2, ",", ".") ?></td>
				<?php else: ?>
				<td><?php echo $datanya["ongkir"] ?></td>
				<?php endif;?>
				<td>
					<?php if ($datanya["no_resi"] != '-'): ?>
					<?php echo $datanya["no_resi"] ?>
					<?php else: ?>
					<form role="form-inline" action="<?php echo URL_PROGRAM_ADMIN . folder . '?u_token=' . $u_token ?>" method="POST">
						<div class="input-group">
							<input type="hidden" name='order_id' value="<?php echo $datanya["pesanan_no"] ?>" />
							<input type="text" class="form-control input-sm" id="datacari" name="resi" placeholder="<?php echo $judul ?> ">
							<span class="input-group-btn">
								<button class="btn btn-hijau btn-sm" type="submit" name="submitInputResi">input</button>
							</span>
						</div>
					</form>
					<?php endif;?>
				</td>
				<td><?php echo $datanya["login_username"] ?></td>
				<td><?php echo $datanya["status"] ?><br>
					<small>
						<?php
						$tanggal_status = $dtFungsi->fcaridata2("_order_status","tanggal","nopesanan=" . $datanya['pesanan_no'] . " and status_id='" . $datanya['status_id'] . "' order by tanggal desc limit 1 ");
    				echo isset($tanggal_status['tanggal']) ? $tanggal_status['tanggal'] : '';
    				?>
					</small>
				</td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php if ($total > 0) {?>
	<!-- Pagging -->
	<div class="col-md-6">
		<div class="row">Showing <?php echo $page ?> of <?php echo $jmlpage ?>
			Page, <?php echo $total ?> data</div>
	</div>
	<div class="col-md-6 text-right">
		<ul class="pagination pagination-sm"><?php echo $dtPaging->GetPaging($total,
    $baris, $page, $jmlpage, $linkpage) ?></ul>
	</div>

	<!-- End Pagging -->
	<?php }?>
</div>

<script>
	$(function () {
		$("#datacari").focus();
		$('#kurir').change(function () {
			caridata();
			return false;
		})
		$('#datacari').keypress(function (e) {
			if (e.which == '13') {
				caridata();
				return false;
			}
		});
		$('#tblcari').click(function () {
			caridata();
			return false;
		});
	});

	function caridata() {
		var zdata = escape($('#datacari').val());
		let kurir = escape($('#kurir').val());
		location = '<?php echo URL_PROGRAM_ADMIN . folder . '/?datacari=' ?>' + zdata + '&kurir=' + kurir + '&u_token=<?php echo $u_token ?>';
	}
</script>