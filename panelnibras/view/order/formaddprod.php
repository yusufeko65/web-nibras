<form method="POST" name="frmaddproduk" id="frmaddproduk" action="<?php echo $_SERVER['PHP_SELF'] ?>">

	<input type="hidden" id="product_id" name="product_id" value="<?php echo $produkid ?>">
	<input type="hidden" id="nopesanan" name="nopesanan" value="<?php echo $pesanan_no ?>">
	<input type="hidden" id="grup_member" name="grup_member" value="<?php echo $grup_member ?>">
	<input type="hidden" id="idmember" name="idmember" value="<?php echo $pelanggan_id ?>">
	<input type="hidden" id="aksi" name="aksi" value="<?php echo $aksi ?>">
	<input type="hidden" name="urlfolder" id="urlfolder" value="<?php echo URL_PROGRAM_ADMIN . folder . '?op=info&pid=' . $pesanan_no ?>&u_token=<?php echo $u_token ?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 class="modal-title">Tambah Produk</h4>
			</div>
			<div class="modal-body">
				<div id="hasiladdproduk"></div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Kode Produk</label>
						<input type="text" readonly class="form-control form-control-sm" value="<?php echo $produkkode ?>">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Nama Produk</label>
						<input type="text" readonly class="form-control form-control-sm" value="<?php echo $produknm ?>">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Ukuran</label>
						<select id="idukuran" name="idukuran" class="form-control">
							<option value="0">- Pilih Ukuran -</option>
							<?php echo $ukuran ?>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Warna</label>
						<select id="idwarna" name="idwarna" class="form-control">
							<option value="0">- Pilih Warna -</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Harga Normal</label>
						<input type="text" readonly class="form-control form-control-sm" value="<?php echo $dtFungsi->fuang($hrgsatuan) ?> ">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Harga <?php echo $grup_nama ?></label>
						<input type="text" readonly class="form-control form-control-sm" value="<?php echo $dtFungsi->fuang($harga_member) . ' (' . $minbeli . ' pcs ' . $syarat . ')' ?>">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Berat</label>
						<input type="text" id="berat" readonly class="form-control form-control-sm" value="<?php echo $berat ?> Gr">
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label>Stok</label>
						<input type="text" id="stok" readonly class="form-control form-control-sm" value="<?php echo $stok ?>">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>QTY</label>
						<input type="text" id="qty" name="qty" class="form-control form-control-sm" value="1">
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<div class="form-group">
					<button type="button" id="btnsimpanaddproduk" onclick="simpanaddproduk()" class="btn btn-sm btn-success">Tambahkan ke Keranjang</a>
						<button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
	$(function() {
		$("#idwarna").chosen({
			no_results_text: "Tidak Ada Warna!",
			width: "100%"
		});
		$("#idukuran").chosen({
			no_results_text: "Tidak Ada Warna!",
			width: "100%"
		});

		$('#idukuran').chosen().change(function() {
			var action = $('#frmaddproduk').attr("action");
			var url = action + '?modul=warna&u_token=<?php echo $u_token ?>';
			var idproduk = $('#product_id').val();
			$.ajax({
				type: "GET",
				url: url,
				data: 'idukuran=' + this.value + '&idproduk=' + idproduk,
				dataType: 'json',
				success: function(result) {

					var html = '<option value="0">- Pilih Warna -</option>';
					var stok = 0;
					for (i = 0; i < result.length; i++) {
						html += '<option value="' + result[i]['idwarna'] + '"';
						html += '>' + result[i]['warna'] + '</option>';
						stok += parseInt(result[i]['stok']);
					}

					$('#idwarna').html(html).trigger("chosen:updated");
					$('#stok').val(stok);

				},
				error: function(e) {
					alert('Error: ' + e);
				}
			});

			return false;
		});

		$('#idwarna').chosen().change(function() {
			var action = $('#frmaddproduk').attr("action");
			var url = action + '?modul=stokbywarnaukuran&u_token=<?php echo $u_token ?>';
			var idproduk = $('#product_id').val();
			var idukuran = $('#idukuran').val();


			$.ajax({
				type: "GET",
				url: url,
				data: 'idwarna=' + this.value + '&idproduk=' + idproduk + '&idukuran=' + idukuran,
				dataType: 'json',
				success: function(result) {

					$('#stok').val(result['stok']);

				},
				error: function(e) {
					alert('Error: ' + e);
				}
			});


			return false;
		});
	});

	function simpanaddproduk() {
		var frm = $('#frmaddproduk').serialize();
		var url = $('#frmaddproduk').prop("action") + '?modul=addorderproduk&u_token=<?php echo $u_token ?>';
		var qty = $('#qty').val();
		var aksi = $('#aksi').val();
		var redirect = $('#urlfolder').val();

		$('#btnsimpanaddproduk').button("loading");
		$('#hasiladdproduk').removeClass();
		$('#hasiladdproduk').hide();

		if (qty == '' && !Number.isInteger(qty)) {
			$('#hasiladdproduk').addClas("alert alert-danger");
			$('#hasiladdproduk').html('Masukkan Jumlah QTY');
			$('#hasiladdproduk').show();
			$('#btnsimpanaddproduk').button("reset");

			return false;
		}
		$.ajax({
			type: "POST",
			url: url,
			data: frm,
			dataType: 'json',
			success: function(msg) {


				$('#loadingweb').hide(500);

				if ($.trim(msg['status']) == "error") {
					$('#hasiladdproduk').addClass("alert alert-danger");
					$('#btnsimpanaddproduk').button('reset');
					$('#hasiladdproduk').show();
				} else {
					$('#hasiladdproduk').addClass("alert alert-success");

					if (aksi == 'ubahorder') {

						location = redirect;

					} else {
						listcart('listcart', 'hitung');
						$('#btnsimpanaddproduk').button("reset");
						//$('#btnclose').trigger('click');
						$('#modalfrm').modal('toggle');
					}
				}
				$('#hasiladdproduk').html(msg['result']);
				$('#hasiladdproduk').show(0);
				return false;
			},
			error: function(e) {
				alert('Error: ' + e);
			}
		});
	}
</script>