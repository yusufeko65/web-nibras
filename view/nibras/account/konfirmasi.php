<div class="container">
	<section class="page-section">
		<h1 class="section-title"><span>Konfirmasi Pembayaran</span></h1>
	</section>
	<form method="POST" name="frmkonfirmasi" enctype="multipart/form-data" id="frmkonfirmasi" action="<?php echo URL_PROGRAM . $amenu . '/' ?>">
		<input type="hidden" id="url_site" value="<?php echo URL_PROGRAM . $folder ?>">
		<input type="hidden" id="mode" name="mode" value="formkonfirm">
		<div id="hasil" style="display: none;"></div>
		<div class="area-form align-items-center">
			<?php if ($dataorder['jml_bayar'] > 0) { ?>
				<div class="text-center">
					<h3>Order sudah diisi form konfirmasi</h3>
				</div>
			<?php } else { ?>
				<div class="form-group row">
					<label class="col-md-4">No. Order</label>
					<div class="col-md-4">
						<input type="text" class="form-control elmi" name="noorder" id="noorder" placeholder="Masukkan No. Order" value="<?php echo $dataorder != false ? (int)$dataorder['pesanan_no'] : '' ?>">
						<cite>ex : No Order #1, cukup masukkan angka 1 tanpa tanda pagar (#)</cite>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-4">Jumlah Pembayaran</label>
					<div class="col-md-4">
						<input type="number" class="form-control elmi" name="jmlbayar" id="jmlbayar" placeholder="Jumlah Pembayaran" value="<?php echo $dataorder != false ? ($dataorder['pesanan_subtotal'] + $dataorder['pesanan_kurir']) - $dataorder['dari_deposito'] - $dataorder['dari_poin'] : '' ?>">
						<input type="hidden" id="totalbelanja" name="totalbelanja" value="<?php echo $dataorder != false ? ($dataorder['pesanan_subtotal'] + $dataorder['pesanan_kurir']) - $dataorder['dari_deposito'] - $dataorder['dari_poin'] : '' ?>">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-4">Bank Anda</label>
					<div class="col-md-8">
						<input type="text" class="form-control elmi" name="bankfrom" id="bankfrom" placeholder="Nama Bank Anda">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-4">No. Rekening Anda</label>
					<div class="col-md-8">
						<input type="text" class="form-control elmi" name="norekfrom" id="norekfrom" placeholder="No. Rekening Anda">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-4">Atas Nama Bank Anda</label>
					<div class="col-md-8">
						<input type="text" class="form-control elmi" name="atasnamafrom" id="atasnamafrom" placeholder="Atas Nama Bank Anda">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-4">Transfer Ke</label>
					<div class="col-md-5">
						<select id="bankto" name="bankto" class="form-control elmi">
							<?php foreach ($bank as $rb) { ?>
								<option value="<?php echo $rb['idr'] ?>"><?php echo $rb['nms'] ?> / <?php echo $rb['rek'] ?> / <?php echo $rb['an'] ?> / <?php echo $rb['cabang'] ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-4">Tgl Tranfer</label>
					<div class="col-md-4">
						<input type='text' class="form-control col-md-8 elmi" id="tglbayar" name="tglbayar" data-format="yyyy/MM/dd" readonly />
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-4">Bukti Transfer</label>
					<div class="col-md-4">
						<input name="buktitransfer" type="file" id="buktitransfer">
					</div>
				</div>

				<div class="form-group">
					<div class="text-right">
						<button type="submit" name="btnsimpan" id="btnsimpan" class="btn btn-success btn-lg btn-block">Konfirmasi Pembayaran</button>
					</div>
				</div>
			<?php } ?>
		</div>

	</form>
</div>