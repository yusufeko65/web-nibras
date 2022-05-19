 <div class="container">
	<section class="page-section">
		<h2 class="section-title"><span>Lupa Password</span></h2>
	</section>
	<div class="col-sm-12">
		 <form method="POST" class="form-signin" name="frmpassword" id="frmpassword" action="<?php echo URL_PROGRAM.$amenu.'/'?>">
			<div id="hasil"></div>
			<p>Masukkan Email Anda untuk mereset password, sistem akan otomatis mereset password Anda dan mengirimkan ke email Anda password yang telah di Reset.</p>
			<div class="area-form align-items-center">
				 <h2 class="alert alert-info"><?php echo $dtCaptcha->generateCaptcha();  ?></h2>
				 <div class="form-row align-items-center">
					<div class="form-group col-md-12">
						<label for="capcaku"> Masukan Kode Sekuriti diatas</label>
						 <input type="text" name="capcaku" id="capcaku" class="form-control input-sm" autocomplete="Off">
					</div>
				</div>
			</div>
			<div class="area-form align-items-center">
				 <div class="form-row align-items-center">
					<div class="form-group col-md-12">
						<label for="email">Email</label>
						  <input type="email" class="form-control input-sm" name="email" id="email" placeholder="Email" autocomplete="Off">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="pull-right">
					 <p>
					<input type="button" value="RESET PASSWORD" name="btnok" id="btnok" class="btn btn-info btn-sm">
				</div>
			</div>
		 </form>
	</div>
</div>	