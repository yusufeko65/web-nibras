<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	<div class="row">
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-body">
					<form id="frmcari" name="frmcari">
						<div class="form-group">
							<label for="tanggal1">Tanggal Register Awal</label>
							<input type="text" class="form-control input-sm" name="tanggal1" id="tanggal1" value="<?php echo $tanggal1 ?>" placeholder="Tanggal Awal">
						</div>
						<div class="form-group">
							<label for="tanggal2">Tanggal Register Akhir</label>
							<input type="text" class="form-control input-sm" name="tanggal2" id="tanggal2" value="<?php echo $tanggal2 ?>" placeholder="Tanggal Akhir">
						</div>
						<div class="form-group">
							<label for="fstatus">Grup</label>
							<?php echo $dtFungsi->cetakcombobox2('- Grup -', '', $grup, 'fgrup', '_customer_grup', 'cg_id', 'cg_nm', 'input-sm form-control') ?>
						</div>
						<button class="btn btn-hijau btn-sm" type="button" id="tblcari"><span class="glyphicon glyphicon-search"></span> Filter</button>
						<a class="btn btn-sm btn-default" id="tblexport" onclick="location='<?php echo URL_PROGRAM_ADMIN . "view/" . folder ?>'+'/exportexcel_daily.php?tanggal1='+$('#tanggal1').val()+'&tanggal2='+$('#tanggal2').val()+'&grup='+$('#fgrup').val()">Export to excel</a>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-9">

			<table class="table table-bordered table-striped table-hover tabel-grid table-report">

				<thead>
					<tr>

						<td class=" text-center">No</td>

						<td s>Nama</td>
						<td>Email</td>
						<td>Telp</td>
						<td>Kota/Kabupaten</td>
						<td>Grup</td>
						<td>Tgl Regis</td>

					</tr>
				</thead>
				<tbody>
					<?php $no = 0 ?>

					<?php foreach ($dataview as $datanya) { ?>
						<tr>
							<td class="text-center"><?php echo $no = $no + 1 ?></td>
							<td><?php echo trim($datanya["cust_nama"]) ?></td>
							<td><?php echo trim($datanya["cust_email"]) ?></td>
							<td><?php echo $datanya["cust_telp"] ?></td>
							<td><?php echo trim($datanya["kabupaten_nama"]) ?></td>
							<td><?php echo $datanya["cg_nm"] ?></td>
							<td><?php echo $dtFungsi->ftanggalFull2($datanya["cust_tgl_add"]) ?></td>

						</tr>
					<?php } ?>

				</tbody>

			</table>


		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="clearfix"></div>

<script>
	$("#tanggal1").datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$("#tanggal2").datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$(function() {
		$("#datacari").focus();

		$('#fgrup').change(function() {
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



	});

	function caridata() {

		var grup = escape($('#fgrup').val());
		var tanggal1 = escape($('#tanggal1').val());
		var tanggal2 = escape($('#tanggal2').val());

		location = '<?php echo URL_PROGRAM_ADMIN . folder . '/?op=view_daily&grup=' ?>' + grup + '&tanggal1=' + tanggal1 + '&tanggal2=' + tanggal2;

	}
</script>