<div id="hasil"></div>
<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	<div class="row">
		<div class="col-md-8 bagian-frm-cari ">
			<div class="row">
				<form role="form-inline" id="frmcari" name="frmcari">
					<div class="col-md-12">
						<div class="form-group col-md-4">
							<select class="form-control input-sm" id="propinsi" name="propinsi">
								<option value="0">- Propinsi -</option>
								<?php foreach ($prop as $key => $dprop) { ?>
								<option value="<?php echo $dprop['idp'] ?>" <?php if ($propinsi == $dprop['idp']) echo "selected" ?>><?php echo $dprop['nmp'] ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group col-md-4">
							<select class="form-control input-sm" id="kabupaten" name="kabupaten">
								<?php
								$kota      = $dtKabupaten->getKabupatenByPropinsi($propinsi);
								$opt = "<option value=\"0\">- Kotamadya/Kabupaten -</option>";

								if ($propinsi != '') {
									foreach ($kota as $kot) {
										if ($kot['idk'] == $kabupaten) {
											$selected = 'selected';
										} else {
											$selected = '';
										}
										$opt .= "<option value=\"" . $kot['idk'] . "\" $selected>" . $kot['nmk'] . "</option>";
									}
								}
								echo $opt;
								?>
							</select>
						</div>
						<div class="input-group col-md-4">
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
				<td>Kecamatan</td>
				<td>Kotamadya/Kabupaten</td>
				<td>Propinsi</td>
				<td style="min-width:5%" class="tengah">Ubah</td>
			</tr>
		</thead>
		<tbody id="viewdata">
			<?php foreach ($ambildata as $datanya) { ?>
			<tr>
				<td class="tengah"><input type="checkbox" class="chk" value="<?php echo $datanya['kecamatan_id'] ?>" /></td>
				<td><?php echo $datanya["kecamatan_nama"] ?></td>
				<td><?php echo $datanya["kabupaten_nama"] ?></td>
				<td><?php echo $datanya["provinsi_nama"] ?></td>
				<td class="tengah"><a href="<?php echo URL_PROGRAM_ADMIN . folder . "/?op=edit&pid=" . $datanya['kecamatan_id'] . '&u_token=' . $u_token ?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></a></td>
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

		$('#propinsi').change(function() {
			$('#kabupaten').load('<?php echo $_SERVER['PHP_SELF'] ?>?load=kabupaten&propinsi=' + this.value + '&u_token=<?php echo $u_token ?>');
			return false;
		});
		$('#kabupaten').change(function() {
			caridata();
			return false;
		});
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
		var propinsi = escape($('#propinsi').val());
		var kabupaten = escape($('#kabupaten').val());
		location = '<?php echo URL_PROGRAM_ADMIN . folder . '/?datacari=' ?>' + zdata + '&propinsi=' + propinsi + '&kabupaten=' + kabupaten + '&u_token=<?php echo $u_token ?>';
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
						if (hasilnya[0] == "gagal") alert(hasilnya[1]);
						//window.location = '<?php //echo URL_PROGRAM_ADMIN . folder 
												?>';
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