<div class="container">
	<section class="page-section">
		<h2 class="section-title"><span>Biodata</span></h2>
	</section>
	<div class="col-md-12">
		<form method="POST" name="frmakun" id="frmakun" action="<?php echo URL_PROGRAM.$folder.'/'?>">
			<input type="hidden" id="url_site" value="<?php echo URL_PROGRAM.$folder ?>">
			<input type="hidden" id="url_ref" value="<?php echo isset($_GET['ref']) ? $_GET['ref']:'' ?>">
			<input type="hidden" id="url_wil" value="<?php echo URL_THEMES.'wilayah/index.php' ?>">
			<input type="hidden" id="tab" value="<?php echo isset($_GET['tb']) ? $_GET['tb'].'-tab' : 'profil-tab' ?>">
			<input type="hidden" name="action" value="saveaccount">
			<div class="area-form align-items-center">
				<ul class="nav nav-tabs" id="tabaccount">
					<li class="nav-item">
						<a class="nav-link active" href="#dataprofil" role="tab" data-toggle="tab" id="profil-tab" aria-controls="dataprofil" aria-selected="true">Profil</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="alamat-tab" data-toggle="tab" href="#dataalamat" role="tab" aria-controls="dataalamat" aria-selected="false">Daftar Alamat</a>
					</li>
				</ul>
				<div class="tab-content" id="tabdata">
					<div class="tab-pane fade show active" id="dataprofil" role="tabpanel" aria-labelledby="profil-tab">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="rnama">Nama Lengkap</label>
								<input type="text" class="form-control" name="rnama" id="rnama" placeholder="Nama Lengkap" value="<?php echo isset($reseller['cust_nama']) ? $reseller['cust_nama']:'' ?>">
							</div>
							<div class="form-group col-md-6">	
								<label for="rtelp" >Nomor Hp</label>
								<input type="text" class="form-control" name="rtelp" id="rtelp" placeholder="Nomor Hp" value="<?php echo isset($reseller['cust_telp']) ? $reseller['cust_telp']:'' ?>">
							</div>
							<div class="form-group col-md-6">
								<label for="remail">Email</label>
								<input type="email" class="form-control" id="remail" name="remail" placeholder="Email" value="<?php echo isset($reseller['cust_email']) ? $reseller['cust_email']:'' ?>">
							</div>
							<div class="form-group col-md-6">
								<label for="remail">Password</label>
								<button id="btngantipassword" class="form-control btn btn-outline-primary" type="button"><i class="fa fa-lg fa-lock" aria-hidden="true"></i>Ganti Password</button>
							</div>
							<div class="form-group col-md-12 text-right">
								<button id="btnsimpan" type="submit" class="btn btn-success ">Simpan</button>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="dataalamat" role="tabpanel" aria-labelledby="alamat-tab">
						<button id="btnaddalamat" type="button" class="btn btn-sm btn-outline-success"><i class="fa fa-plus" aria-hidden="true"></i>Tambah Alamat</button>
						
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th scope="col" width="3%">#</th>
										<th scope="col">Nama</th>
										<th scope="col">Alamat</th>
										<th scope="col">Status</th>
									
									</tr>
								</thead>
								<tbody>
								<?php $no = 1; ?>
								<?php if($alamats) { ?>
									<?php foreach($alamats as $alamat)  { ?>
										<tr>
											<td><?php echo $no ?></td>
											<td><?php echo '<b>'.$alamat['ca_nama'].'</b><br> <cite><small>Hp. '. $alamat['ca_hp'].'</small></cite>' ?></td>
											<td>
												<?php echo $alamat['ca_alamat'].', '.$alamat['ca_kelurahan'].', '.$alamat['kecamatan_nama'].'<br> '.$alamat['kabupaten_nama'].', '.$alamat['provinsi_nama'].', '.$alamat['ca_kodepos'] ?>
												<br>
												<button id="btnubah<?php echo $no ?>" type="button" class="btn btn-sm btn-outline-primary" onclick="ubahAlamat('<?php echo $alamat['ca_id'] ?>','<?php echo $no ?>')">Ubah</button> 
												<button id="btnhapus<?php echo $no ?>" type="button" class="btn btn-sm btn-outline-danger" onclick="formhapusAlamat('<?php echo $alamat['ca_id'] ?>','<?php echo $no ?>')">Hapus</button>
											</td>
											<td class="text-center"><?php echo $alamat['ca_default'] == '1' ? '<i class="fa fa-check fa-lg text-success" aria-hidden="true"></i><br><small><cite>Utama</cite></small>' : '' ?></td>
											
										</tr>
										<?php $no++ ?>
									<?php } ?>
								<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>