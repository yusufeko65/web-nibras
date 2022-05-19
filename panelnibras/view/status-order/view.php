<div id="hasil"></div>
<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	<div class="row">
		<div class="col-md-8 bagian-frm-cari ">
			<div class="row">
				<form role="form-inline" id="frmcari" name="frmcari">
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
		<div class="col-md-4 bagian-tombol"><a href="<?php echo URL_PROGRAM_ADMIN . folder . "/?op=add&u_token=" . $u_token ?>" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"></span> Tambah</a> <a class="btn btn-warning btn-sm" onclick="hapusdata()"><span class="glyphicon glyphicon-trash"></span> Hapus</a></div>
	</div>

	<table class="table table-bordered table-striped table-hover tabel-grid">
		<thead>
			<tr>
				<td style="min-width:3%" class="tengah"><input type="checkbox" id="checkall" onchange="cekall()" name="checkall" value="ON"></td>
				<td>Status Order</td>
				<td style="min-width:5%" class="tengah">Ubah</td>
			</tr>
		</thead>
		<tbody id="viewdata">
			<?php foreach ($ambildata as $datanya) { ?>
			<tr>
				<td class="tengah"><input type="checkbox" class="chk" value="<?php echo $datanya['status_id'] ?>" /></td>
				<td><?php echo $datanya["status_nama"] ?></td>
				<td class="tengah"><a href="<?php echo URL_PROGRAM_ADMIN . folder . "/?op=edit&pid=" . $datanya['status_id'] . '&u_token=' . $u_token ?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></a></td>
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
		$('#tblreset').click(function() {
			tampilkan('<?php echo URL_PROGRAM_ADMIN . folder . '/?u_token=' . $u_token ?>');
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
		location = '<?php echo URL_PROGRAM_ADMIN . folder . '/?datacari=' ?>' + zdata + '&u_token=<?php echo $u_token ?>';

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
					success: function(msg) {
						hasilnya = msg.split("|");
						if (hasilnya[0] == "gagal") alert('Error' + hasilnya[1]);
						location.reload();
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