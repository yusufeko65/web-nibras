<form method="POST" name="frmeditstatus" id="frmeditstatus" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" id="aksi" name="aksi" value="simpanstatusorder">
	<input type="hidden" id="nopesanan" name="nopesanan" value="<?php echo $nopesan ?>">
	<input type="hidden" name="urlfolder" id="urlfolder" value="<?php echo URL_PROGRAM_ADMIN . folder . '?op=info&pid=' . $nopesan . '&u_token=' . $u_token ?>">
	<input type="hidden" name="stsnow" id="stsnow" value="<?php echo $stsnow ?>">
	<input type="hidden" name="stskonfirm" id="stskonfirm" value="<?php echo $stskonfirm ?>">
	<input type="hidden" name="stsshipping" id="stsshipping" value="<?php echo $stsshipping ?>">
	<input type="hidden" name="stssudahbayar" id="stssudahbayar" value="<?php echo $stssudahbayar ?>">
	<input type="hidden" name="modekonfirm" id="modekonfirm" value="<?php echo $modekonfirm ?>">
	<input type="hidden" name="stsgetpoin" id="stsgetpoin" value="<?php echo $stsgetpoin ?>">
	<input type="hidden" name="pelangganid" id="pelangganid" value="<?php echo $pelangganid ?>">
	<input type="hidden" name="totpoindapat" id="totpoindapat" value="<?php echo $totpoin ?>">
	<?php
	$cekstatus = $dtFungsi->fcaridata2('_order_status', 'idostatus', "status_id='" . $stsnow . "' AND nopesanan='" . $nopesan . "' order by tanggal desc limit 1 ");
	$idstatushistory = $cekstatus['idostatus'];
	?>
	<input type="hidden" name="idstatushistory" value="<?php echo $idstatushistory ?>">
	<div class="modal-dialog" style="width:60%">
		<div class="modal-content">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 class="modal-title">Edit Status Order <?php echo sprintf('%08s', (int) $nopesan) ?></h4>
			</div>
			<div class="modal-body">
				<div id="hasileditstatus" style="display:none"></div>
				<div role="tabpanel">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<?php
						if ($stsnow == $stscancel) {
							$class_hidden = ' style="display:none"';
							$class_aktif1 = '';
							$class_aktif2 = ' class="active" ';
							$class_aktif_tab1 = '';
							$class_aktif_tab2 = ' active';
						} else {
							$class_hidden = '';
							$class_aktif1 = ' class="active" ';
							$class_aktif2 = '';
							$class_aktif_tab1 = ' active';
							$class_aktif_tab2 = '';
						}

						?>

						<li role="presentation" <?php echo $class_aktif1 ?> <?php echo $class_hidden ?>><a href="#updatestatus" aria-controls="updatestatus" role="tab" data-toggle="tab">Update Status</a></li>
						<li role="presentation" <?php echo $class_aktif2 ?>><a href="#riwayatstatus" role="tab" data-toggle="tab">Riwayat Status</a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane <?php echo $class_aktif_tab1 ?>" id="updatestatus" <?php echo $class_hidden ?>>
							<div class="form-group">
								<label>Status</label>
								<?php if (($stsnow == $stsshipping) || ($stsnow == $stsdone) || ($stsnow == 18) ) { ?>
								<?php echo $dtFungsi->cetakcombobox4('- Status Order -', '', $stsnow, 'orderstatuss', '_status_order', 'status_id', 'status_nama', 'form-control', 'disabled') ?>
								<input type="hidden" name="orderstatus" id="orderstatus" value="<?php echo $stsnow ?>">
								<?php } else { ?>
								<?php echo $dtFungsi->cetakcombobox2('- Status Order -', '', $stsnow, 'orderstatus', '_status_order', 'status_id', 'status_nama', 'form-control', 'status_id <> 18') ?> <!-- aar 22-06-2020 -->
								<?php } ?>

							</div>
							<?php
							if ($stskonfirm != $stsnow && $stsshipping != $stsnow && $stssudahbayar != $stsnow) {
								$style = '';
							} else {
								$style = ' style="display:none"';
							}
							?>


							<?php
							if ($stssudahbayar == $stsnow) {
								$style = '';
							} else {
								$style = ' style="display:none"';
							}
							?>

							<div id="datasudahbayar" <?php echo $style ?>>

								<div class="checkbox">
									<label>
										<input type="checkbox" id="kirimmailsudahbayar" name="kirimmailsudahbayar" value="1"> <span class="label label-default">Kirim Email ke Pelanggan ?</span>
									</label>
								</div>
							</div>

							<?php
							if ($stskonfirm == $stsnow) {
								$style = '';
							} else {
								$style = ' style="display:none"';
							}
							?>
							<div id="datapembayaran" <?php echo $style ?>>
								<h4>Data Pembayaran</h4>
								<hr>
								<div class="col-md-6">
									<div class="alert alert-info"><b>Data Bank Pembeli</b></div>
									<div class="form-group">
										<label>Nama Bank</label>
										<input type="text" id="namabankdari" name="namabankdari" value="<?php echo $bankfrom ?>" class="form-control">
									</div>
									<div class="form-group">
										<label>Rekening Bank</label>
										<input type="text" id="rekbankdari" name="rekbankdari" value="<?php echo $norekfrom  ?>" class="form-control">
									</div>
									<div class="form-group">
										<label>Atas Nama Bank</label>
										<input type="text" id="atasnamabankdari" name="atasnamabankdari" value="<?php echo $atasnamafrom ?>" class="form-control">
									</div>
								</div>
								<div class="col-md-6">
									<div class="alert alert-info"><b>Data Bank Transaksi</b></div>
									<div class="form-group">
										<label>Bank Tujuan</label>
										<?php
										$tabelbanktujuan = "_bank INNER JOIN _bank_rekening ON _bank.bank_id = _bank_rekening.bank_id";
										$fieldisibanktujuan = "concat(_bank.bank_nama,' / ',_bank_rekening.rekening_no,' / ',rekening_atasnama, ' / ',rekening_cabang)";
										$wherebanktujuan = "1=1 ORDER BY _bank.bank_nama asc";

										echo $dtFungsi->cetakcombobox2('- Bank Tujuan Transfer -', '', $bankto, 'bankto', $tabelbanktujuan, '_bank.bank_id', $fieldisibanktujuan, 'form-control', $wherebanktujuan);
										?>
									</div>
									<div class="form-group">
										<div class="col-md-6 row">
											<label>Tanggal Transfer</label>
											<input type="text" id="tglbayar" name="tglbayar" value="<?php echo $tglbayar ?>" class="form-control">
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="form-group">
										<label>Jumlah Bayar</label>
										<input type="text" id="jmlbayar" name="jmlbayar" value="<?php echo $jmlbayar ?>" class="form-control">
									</div>
								</div>
								<div class="col-md-12">
									<?php if ($buktitransfer) { ?>
									<h4>Bukti Transfer</h4>
									<a href="<?php echo URL_IMAGE . '_other/other_' . $buktitransfer ?>" class="zoom-produk"><img src="<?php echo URL_IMAGE . '_other/other_' . $buktitransfer ?>" width="50%"></a>
									<?php } ?>
								</div>
							</div>
							<?php
							if ($stsshipping == $stsnow) {
								$style = '';
								$readonly = ' readonly ';
							} else {
								$style = ' style="display:none"';
								$readonly = '';
							}
							?>

							<div id="datapengiriman" <?php echo $style ?>>

								<h4>Data Pengiriman</h4>
								<hr>
								<div class="form-group">
									<label>Shipping</label>
									: <input type="hidden" id="shipping" name="shipping" value="<?php echo $shipping ?>"><?php echo $shipping . '-' . $servis ?>
									<input type="hidden" id="servis" name="servis" value="<?php echo $servis ?>">
								</div>
								<div class="col-md-4 row">
									<div class="form-group">
										<label>Tanggal Kirim</label>
										<input type="text" id="tglkirim" name="tglkirim" value="<?php echo $tglshipping ?>" class="form-control">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="form-group">
									<label>No. Resi/ No. AWB</label>
									<input type="text" id="noawb" name="noawb" value="<?php echo $awbshipping ?>" class="form-control">
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" id="kirimmailship" name="kirimmailship" value="1"> <span class="label label-default">Kirim Email ke Pelanggan ?</span>
									</label>
								</div>



							</div>
							<div class="form-group" id="divketerangan">

								<label>Keterangan</label>
								<textarea id="keterangan" name="keterangan" class="form-control"></textarea>
							</div>
							<div class="pull-right">
								<div class="form-group">
									<a onclick="simpanstatus()" id="btnsimpanstatus" class="btn btn-sm btn-primary">Simpan</a>
									<button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div role="tabpanel" class="tab-pane <?php echo $class_aktif_tab2 ?>" id="riwayatstatus">
							<h4>Riwayat Status Order</h4>
							<table class="table table-bordered">
								<thead>
									<tr>
										<th class="text-center" width="20%">Tanggal</th>
										<th class="text-left" width="20%">Status</th>
										<th class="text-left" width="40%">Keterangan</th>
										<th class="text-left" width="20%">Admin</th>
									</tr>
								</thead>
								<tbody>

									<?php foreach ($datastatus as $dts) { ?>
									<tr>
										<td class="text-center"><?php echo $dts['tanggal'] ?></td>
										<td><?php echo $dts['status_nama'] ?></td>
										<td><?php echo $dts['keterangan'] ?></td>
										<td>
											<?php
												if ($dts['admin_id'] > 0) {
													echo empty($dts['login_nama'])?$dts['cust_nama']:$dts['login_nama'];
												} elseif ($dts['admin_id'] <= 0 || $dts['admin_id'] == null) {
													echo 'Sistem ';
												} else {
													echo '';
												}
												?>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
	$(function() {
		var stsnow = $('#stsnow').val();
		var stsshipping = $('#stsshipping').val();
		var stskonfirm = $('#stskonfirm').val();
		var stssudahbayar = $('#stssudahbayar').val();
		$(".zoom-produk").colorbox({
			rel: 'zoom-produk'
		});
		$('#orderstatus').change(function() {

			if ($(this).val() == stskonfirm) {
				$('#datapembayaran').show();
			} else {
				$('#datapembayaran').hide();
			}

			if ($(this).val() == stsshipping) {
				$('#datapengiriman').show();
			} else {
				$('#datapengiriman').hide();
			}

			if ($(this).val() == stssudahbayar) {
				$('#datasudahbayar').show();
			} else {
				$('#datasudahbayar').hide();
			}
			/*
			if($(this).val() != stskonfirm && $(this).val() != stsshipping && $(this).val() != stssudahbayar) {
			   $('#divketerangan').show();
			} else {
			   $('#divketerangan').hide();
			}
			*/

		});
		$("#tglbayar").datepicker({
			dateFormat: 'yy-mm-dd'
		});
		$("#tglkirim").datepicker({
			dateFormat: 'yy-mm-dd'
		});
	});
</script>