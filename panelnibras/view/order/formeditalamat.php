<form method="POST" name="frmeditlamat" id="frmeditlamat" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" id="aksi" name="aksi" value="simpanalamat">
	<input type="hidden" id="nopesanan" name="nopesanan" value="<?php echo $nopesan ?>">
	<input type="hidden" name="urlfolder" id="urlfolder" value="<?php echo URL_PROGRAM_ADMIN . folder . '?op=info&pid=' . $nopesan . '&u_token=' . $u_token ?>">
	<input type="hidden" name="idmember" id="idmember" value="<?php echo $pelanggan_id ?>">
	<input type="hidden" name="jenis_alamat" id="jenis_alamat" value="<?php echo $jenis_alamat ?>">
	<input type="hidden" name="totberat" id="totberat" value="<?php echo $totberat ?>">
	<input type="hidden" name="modulform" id="modulform" value="<?php echo $modulform ?>">
	<div class="modal-dialog" style="width:60%">
		<div class="modal-content">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 class="modal-title">Edit Alamat <?php echo $caption_jenis_alamat ?></h4>
			</div>
			<div class="modal-body">
				<div id="hasileditstatus" style="display:none"></div>
				<div role="tabpanel">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#formalamat" aria-controls="formalamat" role="tab" data-toggle="tab">Alamat</a></li>
						<li role="presentation"><a href="#dataalamat" role="tab" data-toggle="tab">Data Alamat</a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="formalamat">
							<h4>Form Alamat</h4>
							<div id="hasiladdress"></div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Nama</label>
									<input type="text" id="add_nama" name="add_nama" class="form-control">

								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>No. Handphone</label>
									<input type="text" id="add_telp" name="add_telp" class="form-control">

								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Alamat</label>
									<textarea id="add_alamat" name="add_alamat" class="form-control"></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Propinsi</label>
									<select id="add_propinsi" name="add_propinsi">
										<option value="0">- Propinsi -</option>
										<?php foreach ($dataprop as $prop) { ?>
										<option value="<?php echo $prop['idp'] ?>"><?php echo $prop['nmp'] ?></option>
										<?php } ?>
										<select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Kota/Kabupaten</label>
									<select id="add_kabupaten" name="add_kabupaten">
										<option value="0">- Kota/Kabupaten -</option>

										<select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Kecamatan</label>
									<select id="add_kecamatan" name="add_kecamatan">
										<option value="0">- Kecamatan -</option>

										<select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Kelurahan</label>
									<input type="text" id="add_kelurahan" name="add_kelurahan" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Kode Pos</label>
									<input type="text" id="add_kodepos" name="add_kodepos" class="form-control">
								</div>
							</div>
							<div class="col-md-12">
								<div class="checkbox">
									<label>
										<input type="checkbox" id="add_check_saveaddress" name="add_check_saveaddress" value="1"> Simpan ke daftar Alamat
									</label>
								</div>
							</div>
							<div class="pull-right">
								<div class="form-group">
									<a onclick="simpanaddress()" id="btnsimpanaddress" class="btn btn-sm btn-primary">Simpan</a>
									<button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div role="tabpanel" class="tab-pane" id="dataalamat">
							<h4>Data Alamat</h4>
							<div class="data-alamat">
								<?php if ($alamat_pelanggan) { ?>
								<?php $i = 0 ?>
								<?php foreach ($alamat_pelanggan as $alamat) { ?>
								<div class="well wel-sm">
									<b><?php echo $alamat['ca_nama'] ?></b><br>
									<?php echo $alamat['ca_alamat'] ?>,
									<?php echo $alamat['ca_kelurahan'] != '' ? $alamat['ca_kelurahan'] . ', ' : '' ?>
									<?php echo $alamat['kecamatan_nama'] ?>,<?php echo $alamat['kabupaten_nama'] ?>,
									<?php echo $alamat['provinsi_nama'] ?>
									<?php echo $alamat['ca_kodepos'] != '' ? ', ' . $alamat['ca_kodepos'] : '' ?>
									<?php echo $alamat['ca_hp'] != '' ? ', Hp. ' . $alamat['ca_hp'] : '' ?><br>
									<button onclick="useAddress('<?php echo $alamat['ca_id'] ?>')" type="button" class="btn btn-sm btn-default">Gunakan Alamat Ini</button>
								</div>
								<?php $i++ ?>
								<?php } ?>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
	$(function() {
		$("#add_propinsi").chosen({
			no_results_text: "Tidak Ada Propinsi!",
			width: "100%"
		});
		$("#add_kabupaten").chosen({
			no_results_text: "Tidak Ada Kota/Kabupaten!",
			width: "100%"
		});
		$("#add_kecamatan").chosen({
			no_results_text: "Tidak Ada Kecamatan!",
			width: "100%"
		});

		$('#add_propinsi').chosen().change(function() {

			$.ajax({
				type: "GET",
				url: '<?php echo URL_THEMES . 'wilayah/index.php' ?>?load=kabupaten',
				data: 'propinsi=' + this.value,
				dataType: 'json',
				success: function(result) {

					var html = '<option value="0">- Kota/Kabupaten -</option>';
					for (i = 0; i < result.length; i++) {
						html += '<option value="' + result[i]['kabupaten_id'] + '"';
						html += '>' + result[i]['kabupaten_nama'] + '</option>';
					}

					$('#add_kabupaten').html(html).trigger("chosen:updated");

				},
				error: function(e) {
					alert('Error: ' + e);
				}
			});

			return false;
		});
		$('#add_kabupaten').chosen().change(function() {

			$.ajax({
				type: "GET",
				url: '<?php echo URL_THEMES . 'wilayah/index.php' ?>?load=kecamatan',
				data: 'kabupaten=' + this.value,
				dataType: 'json',
				success: function(result) {

					var html = '<option value="0">- Kecamatan -</option>';
					for (i = 0; i < result.length; i++) {
						html += '<option value="' + result[i]['kecamatan_id'] + '"';
						html += '>' + result[i]['kecamatan_nama'] + '</option>';
					}

					$('#add_kecamatan').html(html).trigger("chosen:updated");

				},
				error: function(e) {
					alert('Error: ' + e);
				}
			});

			return false;
		});
	});

	function useAddress(idalamat) {
		var frm = $('#frmeditlamat').serialize() + '&idalamat=' + idalamat;
		var url = $('#frmeditlamat').prop("action") + '?modul=useorderalamat&u_token=<?php echo $u_token ?>';
		//alert(url);

		var redirect = $('#urlfolder').val();
		$.ajax({
			type: "POST",
			url: url,
			data: frm,
			dataType: 'json',
			success: function(msg) {
				$('#loadingweb').hide(500);

				if ($.trim(msg['status']) == "error") {
					$('#hasiladdress').addClass("alert alert-danger");
					$('#btnsimpanaddress').button('reset');

				} else {
					$('#hasiladdress').addClass("alert alert-success");

					//location = redirect;
					if ($('#modulform').val() == 'editorder') {
						location = redirect;
					} else {
						var jenis_alamat = msg['result']['jenis_alamat'];
						var nama = msg['result']['nama'];
						var no_hp = msg['result']['telp'];
						var alamat = msg['result']['alamat'];
						var propinsi = msg['result']['propinsi'];
						var propinsi_caption = msg['result']['propinsi_nama'];
						var kabupaten = msg['result']['kabupaten'];
						var kabupaten_caption = msg['result']['kabupaten_nama'];
						var kecamatan = msg['result']['kecamatan'];
						var kecamatan_caption = msg['result']['kecamatan_nama'];
						var kelurahan = msg['result']['kelurahan'];
						var kodepos = msg['result']['kodepos'];
						gantiAlamat(jenis_alamat, nama, no_hp, alamat, propinsi, propinsi_caption, kabupaten, kabupaten_caption, kecamatan, kecamatan_caption, kelurahan, kodepos);
						$('#btnclose').trigger("click");
					}
				}
				$('#hasiladdress').html(msg['result']);
				$('#hasiladdress').show(0);
				return false;
			},
			error: function(e) {
				alert('Error: ' + e);
			}
		});
	}

	function simpanaddress() {
		var frm = $('#frmeditlamat').serialize();

		var redirect = $('#urlfolder').val();
		var nama = $('#add_nama').val();
		var alamat = $('#add_alamat').val();
		var no_hp = $('#add_telp').val();
		var propinsi = $('#add_propinsi').val();
		var propinsi_caption = $('#add_propinsi option:selected').text();
		var kabupaten = $('#add_kabupaten').val();
		var kabupaten_caption = $('#add_kabupaten option:selected').text();
		var kecamatan = $('#add_kecamatan').val();
		var kecamatan_caption = $('#add_kecamatan option:selected').text();
		var kelurahan = $('#add_kelurahan').val();
		var kodepos = $('#add_kodepos').val();
		var jenis_alamat = $('#jenis_alamat').val();
		var modulform = $('#modulform').val();
		var modul;
		if (modulform == 'editorder') {
			modul = 'editorderalamat';
		} else {
			modul = 'addorderalamat';
		}
		var url = $('#frmeditlamat').prop("action") + '?modul=' + modul + '&u_token=<?php echo $u_token ?>';
		$('#btnsimpanaddress').button("loading");
		$('#hasiladdress').removeClass();
		$('#hasiladdress').hide();

		if (nama == '' || (nama.length < 3 && nama.length > 30)) {
			$('#hasiladdress').addClass("alert alert-danger");
			$('#hasiladdress').show();
			$('#hasiladdress').html('Masukkan Nama, Maksimal 30 karakter');
			$('#btnsimpanaddress').button("reset");
			return false
		}

		if (no_hp == '' || (no_hp.length < 5 && no_hp.length > 20)) {
			$('#hasiladdress').addClass("alert alert-danger");
			$('#hasiladdress').show();
			$('#hasiladdress').html('Masukkan No. Handphone, Maksimal 20 Karakter');
			$('#btnsimpanaddress').button("reset");
			return false
		}

		if (propinsi == '' || propinsi == '0') {
			$('#hasiladdress').addClass("alert alert-danger");
			$('#hasiladdress').show();
			$('#hasiladdress').html('Pilih Propinsi');
			$('#btnsimpanaddress').button("reset");
			return false
		}

		if (kabupaten == '' || kabupaten == '0') {
			$('#hasiladdress').addClass("alert alert-danger");
			$('#hasiladdress').show();
			$('#hasiladdress').html('Pilih Kabupaten/Kota');
			$('#btnsimpanaddress').button("reset");
			return false
		}

		if (kecamatan == '' || kecamatan == '0') {
			$('#hasiladdress').addClass("alert alert-danger");
			$('#hasiladdress').show();
			$('#hasiladdress').html('Pilih Kecamatan');
			$('#btnsimpanaddress').button("reset");
			return false
		}
		/*
		if(kodepos != '' && kodepos > 5 || !Number.isInteger(kodepos)) {
			$('#hasiladdress').addClass("alert alert-danger");
			$('#hasiladdress').show();
			$('#hasiladdress').html('Masukkan Kodepos, maksimal 5 karakter berupa angka');
			$('#btnsimpanaddress').button("reset");
			return false
		}
		*/

		$.ajax({
			type: "POST",
			url: url,
			data: frm,
			dataType: 'json',
			success: function(msg) {


				$('#loadingweb').hide(500);

				if ($.trim(msg['status']) == "error") {
					$('#hasiladdress').addClass("alert alert-danger");
					$('#btnsimpanaddress').button('reset');

				} else {
					$('#hasiladdress').addClass("alert alert-success");
					if ($('#modulform').val() == 'editorder') {
						location = redirect;
					} else {
						gantiAlamat(jenis_alamat, nama, no_hp, alamat, propinsi, propinsi_caption, kabupaten, kabupaten_caption, kecamatan, kecamatan_caption, kelurahan, kodepos);
						$('#btnclose').trigger("click");
					}
				}
				$('#hasiladdress').html(msg['result']);
				$('#hasiladdress').show(0);
				return false;
			},
			error: function(e) {
				alert('Error: ' + e);
			}
		});
	}
</script>