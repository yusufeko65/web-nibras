<div id="hasil"></div>
<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	<div><?php echo $result["cek"] ?></div>
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
				<form role="form-inline" method="post" action="<?php echo URL_PROGRAM_ADMIN . folder . '?u_token=' . $u_token ?>" id="frminput">
					<div class="col-md-4">
						<div class="input-group">
							<input type="text" class="form-control input-sm" name="order_id" value="" placeholder="ID Order">
							<span class="input-group-btn">
								<button class="btn btn-hijau btn-sm" name='submit' value="1" type="submit">input</button>
							</span>
						</div>
					</div>
				</form>
				<div class="form-group col-md-4">
							<select class="form-control" id="kurir" name="kurir">
								<option value="0">- Kurir -</option>
								<?php foreach ($list_kurir as $kurir) {?>
								<option value="<?php echo $kurir['kurir'] . "#" . $kurir['service_id'] ?>" <?php if ($kurir_cari == $kurir['kurir'] . "#" . $kurir['service']) {
 echo "selected";
}
 ?>><?php echo $kurir['kurir'] . " - " . $kurir['service'] ?></option>
								<?php }?>
							</select>
						</div>
				<form role="form-inline" action="<?php echo URL_PROGRAM_ADMIN . folder ?>" id="frmcari" name="frmcari">
					<div class="col-md-4">
						<div class="input-group">
							<input type="text" class="form-control input-sm" id="datacari" name="datacari" value="<?php echo isset($_GET['datacari']) ? $_GET['datacari'] : '' ?>" placeholder="Pencarian <?php echo $judul ?> ">
							<span class="input-group-btn">
								<button class="btn btn-hijau btn-sm" type="button" id="tblcari"><span class="glyphicon glyphicon-search"></span></button>
							</span>
						</div>
					</div>
				</form>
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
					<td><?php echo $datanya["login_username"] ?></td>
					<td><?php echo $datanya["status"] ?></td>
				</tr>
			<?php }?>
		</tbody>
	</table>
	<?php if ($total > 0) {?>
		<!-- Pagging -->
		<div class="col-md-6">
			<div class="row">Showing <?php echo $page ?> of <?php echo $jmlpage ?> Page, <?php echo $total ?> data</div>
		</div>
		<div class="col-md-6 text-right">
			<ul class="pagination pagination-sm"><?php echo $dtPaging->GetPaging($total, $baris, $page, $jmlpage, $linkpage) ?></ul>
		</div>

		<!-- End Pagging -->
	<?php }?>
</div>

<script>
	$(function() {
		$("#datacari").focus();
		$('#kurir').change(function(){
			caridata();
			return false;
		})
		$('#datacari').keypress(function(e) {
			if(e.which == '13') {
        caridata();
			return false;
    }
		});
		$('#tblcari').click(function() {
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