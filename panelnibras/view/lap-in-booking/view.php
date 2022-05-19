<div id="hasil"></div>
<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	<div class="row">
		<div class="col-md-8 bagian-frm-cari ">
			<div class="row">
				<form role="form-inline" id="frmcari" name="frmcari">
					<div class="col-md-4">
						<?php echo $dtFungsi->cetakcombobox2('- Status -', '', $status, 'status', '_status_order', 'status_id', 'status_nama', 'input-sm form-control', "_status_order.status_id = 9 or _status_order.status_id = 15") ?>
					</div>
					<div class="col-md-4">
						<div class="input-group">
							<input type="text" class="form-control input-sm" id="datacari" name="datacari"
								value="<?php echo isset($_GET['datacari']) ? $_GET['datacari'] : '' ?>"
								placeholder="Pencarian <?php echo $judul ?> ">
							<span class="input-group-btn">
								<button class="btn btn-hijau btn-sm" type="button" id="tblcari"><span
										class="glyphicon glyphicon-search"></span></button>
							</span>
						</div>
					</div>
			</div>
		</div>
		</form>
	</div>

	<table class="table table-bordered table-striped table-hover tabel-grid">
		<thead>
			<tr>
				<td>No. Order</td>
				<td>Customer</td>
				<td>Item</td>
				<td>Warna</td>
				<td>Ukuran</td>
				<td>Jumlah</td>
				<td>Status</td>
			</tr>
		</thead>
		<tbody id="viewdata">
			<?php foreach ($ambildata as $datanya) {?>
			<tr>
				<td><?php echo $datanya["order_id"] ?></td>
				<td><?php echo $datanya["customer"] ?></td>
				<td><?php echo $datanya["produk"] ?></td>
				<td><?php echo $datanya["warna"] ?></td>
				<td><?php echo $datanya["ukuran"] ?></td>
				<td><?php echo $datanya["jumlah"] ?></td>
				<td><?php echo $datanya["status"] ?></td>
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
		$('#status').change(function () {
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
		let status = escape($('#status').val());
		location = '<?php echo URL_PROGRAM_ADMIN . folder . '/?datacari=' ?>' + zdata + '&status=' + status + '&u_token=<?php echo $u_token ?>';

	}
</script>