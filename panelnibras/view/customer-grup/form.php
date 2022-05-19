<div class="col-lg-12 main-content">
    <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
    <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
			<div class="widget-content nopadding">
				<form class="form-horizontal" method="POST" name="frmdata" id="frmdata" onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
					<input type="hidden" id="aksi" value="<?php echo $modul ?>">
					<input type="hidden" id="iddata" value="<?php echo $iddata ?>">
					<div id="hasil" style="display: none;"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">Nama Grup</label>
						<div class="col-sm-10">
							<input type="text" id="grup" name="grup" class="form-control" value="<?php echo $grup_nama ?>" size="40">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Keterangan</label>
						<div class="col-sm-10">
							<textarea id="keterangan" name="keterangan" class="form-control"><?php echo $keterangan ?></textarea>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Deposito</label>
						<div class="col-sm-10">
							<div class="checkbox">
								<label> 
									<input type="checkbox" id="chk_deposito" name="chk_deposito" value="1" <?php echo $chk_deposito=="1" ? "checked" : ''?>> Ya/Tidak
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Minimal QTY</label>
						<div class="col-sm-2">
							<input type="text" id="min_beli" name="min_beli" class="form-control" value="<?php echo $min_beli ?>" size="40">
						</div>
						<div class="col-sm-3">
							<select id="minbeli_syarat" name="minbeli_syarat" class="form-control">
								<option value="1"<?php echo $minbeli_syarat=="1" ? "selected" : '' ?>>Per Jenis Produk</option>
								<option value="2"<?php echo $minbeli_syarat=="2" ? "selected" : '' ?>>Bebas Campur Produk</option>
							</select>
						</div>
						<div class="col-sm-3">
							<div class="checkbox">
								<label> 
									<input type="checkbox" id="chk_wjb" name="chk_wjb" value="1" <?php echo $chk_wjb=="1" ? "checked" : ''?>> Wajib 
								</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">Potongan Harga (%)</label>
						<div class="col-xs-2">
							<input type="text" id="diskon" name="diskon" class="form-control" value="<?php echo $diskon ?>"> 
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Dropship</label>
						<div class="col-sm-10">
							<div class="checkbox">
								<label> 
									<input type="checkbox" id="chk_dropship" name="chk_dropship" value="1" <?php echo $chk_dropship=="1" ? "checked" : ''?>> Ya/Tidak
								</label>
							</div>
						</div>
					</div>
					
					<div class="form-group <?php echo $modul=='ubah' ? 'display:block':'display:none'?>">
						<label class="col-sm-2 control-label">Urutan</label>
						<div class="col-sm-2">
							<input type="text" id="urutan" name="urutan" class="form-control chk" value="<?php echo $urutan ?>" size="40">
							<input type="hidden" id="urutan_lama" name="urutan_lama" class="form-control chk" value="<?php echo $urutan ?>" size="40">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<?php if($modul == 'ubah') { ?>
							<a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>?op=add'" class="btn btn-info btn-sm">New</a>
							<?php } ?>
							<a onclick="simpandata()" id="btnsimpan" class="btn btn-sm btn-primary">Simpan</a>
							<a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
						</div>
					</div>
					<div class="clearfix"></div>
				</form>
			</div>
	    </div> 
	</div>
</div>

<script src="<?php echo URL_PROGRAM_ADMIN_VIEW.folder."/validasi.js" ?>"></script>