<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	<div class="col-md-3">

		<div class="panel panel-default">
			<div class="panel-body">

				<form id="frmcari" name="frmcari">
					<div class="form-group">
						<label for="fstatus">Status</label>
						<?php echo $dtFungsi->cetakcombobox2('- Status -', '', $status, 'fstatus', '_status_order', 'status_id', 'status_nama', 'input-sm form-control') ?>
					</div>
					<div class="form-group">
						<label for="tanggal1">Pelanggan</label>
						<input type="text" class="form-control input-sm" name="customer" id="customer" autocomplete="off" value="<?php echo $customer ?>" placeholder="Pelanggan">
						<input type="hidden" id="customer_id" name="customer_id" value="<?php echo $customer_id ?>">
					</div>
					<div class="form-group">
						<label for="tanggal1">Tanggal Awal</label>
						<input type="text" class="form-control input-sm" name="tanggal1" id="tanggal1" value="<?php echo $tanggal1 ?>" placeholder="Tanggal Awal">
					</div>
					<div class="form-group">
						<label for="tanggal2">Tanggal Akhir</label>
						<input type="text" class="form-control input-sm" name="tanggal2" id="tanggal2" value="<?php echo $tanggal2 ?>" placeholder="Tanggal Akhir">

					</div>

					<button class="btn btn-hijau btn-sm" type="button" id="tblcari"><span class="glyphicon glyphicon-search"></span> Filter</button>
					<a class="btn btn-sm btn-default" id="tblexport" onclick="location='<?php echo URL_PROGRAM_ADMIN . "view/" . folder ?>'+'/exportexcel_daily.php?tanggal1='+$('#tanggal1').val()+'&tanggal2='+$('#tanggal2').val()+'&status='+$('#fstatus').val()+'&customer_id='+$('#customer_id').val()">Export to excel</a>
				</form>

			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-body">
				<table class="table table-bordered table-striped table-hover tabel-grid table-report">
					<thead>
						<tr>
							<td style="min-width:3%" class="tengah">No</td>
							<td width="15%" class="text-center">Tgl Transaksi</td>
							<td width="15%" class="text-center">Tgl Terkirim</td>
							<td>Order ID</td>
							<td>Customer</td>
							<td>Status</td>
							<td class="text-right">QTY</td>
							<td class="text-right">Total</td>
							<td class="text-right">Total + Ongkir</td>
						</tr>
					</thead>
					<tbody id="viewdata">
						<?php $no = 0 ?>
						<?php $jmlord = 0 ?>
						<?php $grandtotplusongkir = 0 ?>
						<?php $grandtot = 0 ?>

						<?php foreach ($dataview as $datanya) { ?>
							<?php $jmlord = $jmlord + (int) $datanya["jml"]; ?>
							<?php $total = ((int)$datanya["pesanan_kurir"] + (int)$datanya["subtotal"]) - (int)$datanya['dari_poin'] ?>
							<?php $grandtotplusongkir = $grandtotplusongkir + $total ?>
							<?php $grandtot = $grandtot + $datanya['subtotal'] ?>
							<tr>
								<td class="text-center"><?php echo $no = $no + 1 ?></td>
								<td class="text-center"><?php echo $datanya["tgl"] ?></td>
								<td class="text-center"><?php echo $datanya["tgl_kirim"] ?></td>
								<td class="text-center"><?php echo sprintf('%08s', (int) $datanya["pesanan_no"]); ?></td>
								<td><?php echo $datanya["cust_nama"] ?></td>
								<td><?php echo $datanya["status"] ?></td>
								<td class="text-right"><?php echo $datanya["jml"] ?></td>
								<td class="text-right"><?php echo $dtFungsi->fuang($datanya['subtotal']) ?></td>
								<td class="text-right"><?php echo $dtFungsi->fuang($total) ?></td>
							</tr>
						<?php } ?>
					</tbody>
					<tfooter>
						<tr>
							<td colspan="6" class="text-right">Total</td>
							<td class="text-right"><?php echo $jmlord ?></td>
							<td class="text-right"><?php echo $dtFungsi->fuang($grandtot) ?></td>
							<td class="text-right"><?php echo $dtFungsi->fuang($grandtotplusongkir) ?></td>
						</tr>
					</tfooter>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	$("#tanggal1").datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$("#tanggal2").datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$(function() {
		$("#datacari").focus();



		$('#fstatus').change(function() {
			caridata();
			return false;
		});
		$('#tblcari').click(function() {
			caridata();
			return false;
		});
		$("#datacari").keypress(function(event) {
			if (event.which == 13) {
				caridata();
				return false;
			} else {
				return true;
			}
		});

		/* autocomplete */
		$('#customer').autocomplete({
			delay: 0,
			source: function(request, response) {
				$.ajax({
					url: '<?php echo URL_PROGRAM_ADMIN . folder ?>',
					dataType: "json",
					data: {
						loads: 'customer',
						customer: request.term
					},
					success: function(data) {
						response($.map(data, function(item) {
							return {
								label: item.cust_nama,
								value: item.cust_id,

							}
						}));
					},
					error: function(e) {
						alert('Error: ' + e);
					}
				});
			},
			minLength: 1,
			select: function(event, ui) {
				$('#customer_id').val(ui.item.value);
				$('#customer').val(ui.item.label);
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});

		/* @end autocomplete */

	});

	function caridata() {

		var status = escape($('#fstatus').val());
		var tanggal1 = escape($('#tanggal1').val());
		var tanggal2 = escape($('#tanggal2').val());
		var customer = escape($('#customer').val());
		var customer_id = escape($('#customer_id').val());
		if (tanggal1.length < 10) {
			alert('Masukkan Tanggal Awal');
			$('#tanggal1').focus();
			return false;
		}
		if (tanggal2.length < 10) {
			alert('Masukkan Tanggal Akhir');
			$('#tanggal2').focus();
			return false;
		}
		if (customer == '') {
			customer_id = '';
		}
		location = '<?php echo URL_PROGRAM_ADMIN . folder . '/?op=view_daily&status=' ?>' + status + '&tanggal1=' + tanggal1 + '&tanggal2=' + tanggal2 + '&customer=' + customer + '&customer_id=' + customer_id;
	}
</script>