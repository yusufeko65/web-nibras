<div id="hasil"></div>
<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	<div class="row">
		<div class="col-md-8 bagian-frm-cari ">
			<div class="row">
				<form role="form-inline" id="frmcari" name="frmcari">
					<div class="col-md-4">
						<?php echo $dtFungsi->cetakcombobox2('- Status -', '', $status, 'fstatus', '_status_order', 'status_id', 'status_nama', 'input-sm form-control') ?>
					</div>
					<div class="col-md-6">
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
		<div class="col-md-4 bagian-tombol"><a href="<?php echo URL_PROGRAM_ADMIN . folder . "/?op=add&u_token=" . $u_token ?>" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"></span> Tambah</a> <a class="btn btn-warning btn-sm" onclick="hapusdata()"><span class="glyphicon glyphicon-trash"></span> Cancel Order</a></div>
	</div>

	<table class="table table-bordered table-striped table-hover tabel-grid">
		<thead>
			<tr>
				<td style="min-width:3%" class="tengah"><input type="checkbox" id="checkall" onchange="cekall()" name="checkall" value="ON"></td>
				<td>Order ID</td>
				<td>Customer</td>
				<td>Penerima</td>
				<td class="text-right">Jumlah</td>
				<td class="text-right">Total</td>
				<td class="text-center">Tgl</td>
				<td>Status</td>
				<!-- update - aar - show ip address - 10-06-2020 -->
				<td>IP Address</td>
				<td>Admin Order</td>
				<td style="min-width:5%" class="tengah">Ubah</td>
			</tr>
		</thead>
		<tbody id="viewdata">

			<?php foreach ($ambildata as $datanya) { ?>
				<?php
				$kelasgrid = '';
				if ($datanya['status_id'] == $config_orderstatus) {
					$kelasgrid = 'class="kuning"';
				} elseif ($datanya['status_id'] == $config_konfirmstatus) {
					$kelasgrid = 'class="pink"';
				} elseif ($datanya['status_id'] == $config_sudahbayarstatus) {
					$kelasgrid = 'class="hijau"';
				} elseif ($datanya['status_id'] == $config_ordercancel) {
					$kelasgrid = 'class="merah"';
				} elseif ($datanya['status_id'] == $config_shippingstatus) {
					$kelasgrid = 'class="ungu"';
				}
				?>
				<?php if ($datanya["pesanan_kurir"] < 0) $datanya["pesanan_kurir"] = 0 ?>
				<tr <?php echo $kelasgrid ?>>
					<td class="tengah"><input type="checkbox" class="chk" value="<?php echo $datanya['pesanan_no'] ?>" /></td>
					<td>
						<?php echo (int) $datanya["pesanan_no"] ?>
					</td>
					<td><?php echo $datanya["cust_nama"] ?></td>
					<td><?php echo $datanya["nama_penerima"] ?></td>
					<td class="text-right"><?php echo $datanya["jml"] ?></td>
					<td class="text-right"><?php echo $dtFungsi->fFormatuang($datanya["pesanan_kurir"] + $datanya["subtotal"] + $datanya["biaya_packing"] - $datanya['dari_poin']) ?></td>
					<td class="text-center"><?php echo $dtFungsi->ftanggalFull1($datanya["tgl"]) ?></td>
					<td><?php echo $datanya["status"] ?> <br>
						<small>
							<?php
							$tanggal_status = $dtFungsi->fcaridata2(
								"_order_status",
								"tanggal",
								"nopesanan=" . $datanya['pesanan_no'] . " and status_id='" . $datanya['status_id'] . "' order by tanggal desc limit 1 "
							);
							echo isset($tanggal_status['tanggal']) ? $tanggal_status['tanggal'] : '';
							?>
						</small></td>
						<!-- update - aar - show ip address - 10-06-2020 -->
					<td><?php echo $datanya["iporder"] ?></td> 
					<td><?php echo $datanya["login_username"] != null || $datanya["login_username"] != '' ? $datanya["login_username"] : '-' ?></td>
					<td class="tengah"><a href="<?php echo URL_PROGRAM_ADMIN . folder . "/?op=info&pid=" . $datanya['pesanan_no'] . "&u_token=" . $u_token ?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></a></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php if ($total > 0) { ?>
		<!-- Pagging -->
		<div class="col-md-6">
			<div class="row">Showing <?php echo $page ?> of <?php echo $jmlpage ?> Page, <?php echo $total ?> data</div>
		</div>
		<div class="col-md-6 text-right">
			<ul class="pagination pagination-sm"><?php echo $dtPaging->GetPaging($total, $baris, $page, $jmlpage, $linkpage) ?></ul>
		</div>

		<!-- End Pagging -->
	<?php } ?>
</div>

<script>
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



	});

	function caridata() {
		var zdata = escape($('#datacari').val());
		var status = escape($('#fstatus').val());
		location = '<?php echo URL_PROGRAM_ADMIN . folder . '/?datacari=' ?>' + zdata + '&status=' + status + '&u_token=<?php echo $u_token ?>';

	}

	function hapusdata() {
		var ids = [];
		var dataId;
		var datahapus;
		$('.chk').each(function() {
			if (this.checked) {
				if ($(this).val() != "ON") {
					ids.push($(this).val());
				}
			}
		});
		dataId = ids.join(':');
		datahapus = 'aksi=hapus&id=' + dataId;
		if (dataId == "") {
			alert('Tidak Ada Pilihan');
			return false;
		} else {
			var a = confirm('Apakah ingin menghapus data yang terpilih?');
			if (a == true) {
				$.ajax({
					type: "POST",
					url: "<?php echo $_SERVER['PHP_SELF'] . '?u_token=' . $u_token ?>",
					data: datahapus,
					dataType: 'json',
					success: function(msg) {

						if (msg['status'] == "error") alert('Error \n' + msg['result']);
						location = '<?php echo URL_PROGRAM_ADMIN . folder . '/?u_token=' . $u_token ?>';
						return false;
					},
					error: function(e) {
						alert('Error: ' + e);
					}
				});
			}
		}
	}
</script>