<div class="container">
	<section class="page-section">
		<h2 class="section-title"><span>Register</span></h2>
	</section>
	<div class="col-sm-12">
		<div id="hasil" style="display: none;"></div> 
		<form method="POST" name="frmdaftar" id="frmdaftar" action="<?php echo URL_PROGRAM.$folder.'/'?>">
			<input type="hidden" id="url_site" value="<?php echo URL_PROGRAM.$folder ?>">
			<input type="hidden" id="url_ref" value="<?php echo isset($_GET['ref']) ? $_GET['ref']:'' ?>">
			<input type="hidden" id="url_wil" value="<?php echo URL_THEMES.'wilayah/index.php' ?>">
			<div class="area-form align-items-center">
				<h4 class="title-form">Data Login</h4>
				<div class="form-row align-items-center">
					<div class="form-group col-md-4">
						<label for="remail">Email</label>
						<input type="email" class="form-control" id="remail" name="remail" placeholder="Email">
					</div>
					<div class="form-group col-sm-4">
						<label for="rpass">Password</label>
						<input type="password" class="form-control" name="rpass" id="rpass" placeholder="Password">
					</div>
					<div class="form-group col-sm-4">
						<label for="rrepass">Ulangi Password</label>
						<input type="password" class="form-control" name="rrepass" id="rrepass" placeholder="Ulangi Password">
					</div>
						
					<?php if(count($datagrupCust) > 1) { ?>
					<div class="form-group col-sm-12">
						<label>Grup Pelanggan</label>
						 <?php foreach($datagrupCust as $gc) { ?>
							<div class="custom-control custom-radio">
							  <input class="custom-control-input" type="radio" name="rtipereseller" id="rtipereseller<?php echo $gc['cg_id'] ?>" value="<?php echo $gc['cg_nm'] ?>">
							  <label class="custom-control-label" for="rtipereseller<?php echo $gc['cg_id'] ?>">
								<?php echo $gc['cg_nm'] ?>
							  </label>
							</div>
						 <?php } ?>
					</div>
					<?php } else { ?>
						<input type="hidden" name="rtipereseller" value="<?php echo $config_grupcust ?>">
					<?php } ?>
				</div>
				
				<hr class="row col-sm-12">
				<h4 class="title-form">Data Personal</h4>
				<div class="form-row align-items-center">
					
					<div class="form-group col-sm-6">
						<label for="rnama">Nama Lengkap</label>
						<input type="text" class="form-control" name="rnama" id="rnama" placeholder="Nama Lengkap">
					</div>
					<div class="form-group col-sm-6">	
						<label for="rtelp" >Nomor Hp</label>
						<input type="text" class="form-control" name="rtelp" id="rtelp" placeholder="Nomor Hp">
					</div>
					<div class="form-group col-sm-12">
						<label for="ralamat" >Alamat</label>
						<textarea name="ralamat" id="ralamat" class="form-control" placeholder="Alamat"></textarea>
					</div>
					<div class="form-group col-sm-4">
						<label for="rpropinsi" >Propinsi</label>
						<?php echo $dtFungsi->cetakcombobox2('- Propinsi -','',0,'rpropinsi','_provinsi','provinsi_id','provinsi_nama','custom-select form-control') ?>
					</div>
							
					<div class="form-group col-sm-4">
						<label for="rkabupaten" >Kota/Kabupaten</label>
						<select name="rkabupaten" id="rkabupaten" class="custom-select form-control">
							<option value="0">- Kabupaten -</option>
						</select>
					</div>
					
					<div class="form-group col-sm-4">
						<label for="rkecamatan" >Kecamatan</label>
						<select name="rkecamatan" id="rkecamatan" class="custom-select form-control">
							<option value="0">- Kecamatan -</option>
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="rkelurahan" >Kelurahan</label>
						<input type="text" name="rkelurahan" id="rkelurahan" class="form-control" placeholder="Kelurahan">
					</div>
					<div class="form-group col-sm-6">
						<label for="rkdpos" >Kodepos</label>
						<input type="text" name="rkdpos" id="rkdpos" class="form-control" placeholder="Kodepos">
					</div>					
				</div>
				<hr class="row col-sm-12">
				<div class="card">
					<h4 class="title-form">Kebijakan Privasi</h4>
					<div class="form-row align-items-center">
						<div class="form-group">
							<div class="custom-control custom-checkbox">
							  <input class="custom-control-input" type="checkbox" id="rprivasi" name="rprivasi" value="1">
							  <label class="custom-control-label" for="rprivasi">
								Saya telah membaca dan mengerti <a target="_blank" href="<?php echo URL_PROGRAM.$aliasurl?>">kebijakan privasi</a>
							  </label>
							</div>
						</div>
					</div>
				</div>
				<div class="text-right">
					<button type="submit" name="btnok" id="btnok" class="btn btn-info">Register</button>
				</div>
			</div>
		</form>
	</div>
</div>