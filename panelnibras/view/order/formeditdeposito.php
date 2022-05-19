<form method="POST" name="frmeditdeposito" id="frmeditdeposito" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" id="dnopesanan" name="dnopesanan" value="<?php echo isset($formdeposito['order']['pesanan_no']) ? $formdeposito['order']['pesanan_no'] : 0 ?>">
	<input type="hidden" name="urlfolder" id="urlfolder" value="<?php echo isset($formdeposito['order']['pesanan_no']) ? URL_PROGRAM_ADMIN . folder . '?op=info&pid=' . $formdeposito['order']['pesanan_no'] . '&u_token=' . $token : '' ?>">
	<input type="hidden" name="didmember" id="didmember" value="<?php echo isset($formdeposito['order']['pelanggan_id']) ? $formdeposito['order']['pelanggan_id'] : 0 ?>">

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 class="modal-title">Form Potongan Saldo</h4>
			</div>

			<div class="modal-body">
				<div id="hasileditdeposito" style="display:none"></div>

				<div class="well">
					Pelanggan dapat mengubah jumlah potongan saldo dengan jumlah maksimal
					<h3>Rp. <?php echo $dtFungsi->fuang($formdeposito['totaldeposito']) ?></h3>
				</div>
				<?php if ($formdeposito['status'] == 'success') { ?>
				<div class="col-lg-9">
					<div class="form-group">
						<div class="row">
							<label>Masukkan Jumlah Penggunaan dari Saldo</label>
							<input placeholder="Masukkan Jumlah Penggunaan dari Saldo" type="number" name="potongandeposit" id="potongandeposit" class="form-control form-control-sm">
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<?php } else { ?>
				<div class="alert alert-danger">
					<?php echo $formdeposito['msg'] ?>
				</div>
				<?php } ?>
			</div>
			<div class="modal-footer">
				<?php if ($formdeposito['status'] == 'success') { ?>
				<button type="button" onclick="simpaneditdeposito()" data-loading-text="Tunggu..." id="btnsimpandeposito" class="btn btn-sm btn-primary">Simpan</button>
				<?php } ?>
				<button type="button" class="btn btn-default btn-sm" onclick="location.reload()" id="btnclose">Tutup</button>
			</div>

		</div>
	</div>
</form>