<div class="container">
	<section class="page-section">
		<h2 class="section-title"><span>Login</span></h2>
	</section>
	<div class="col-md-12">
		
		<form class="form-signin" method="POST" id="formlogin" name="formlogin" action="<?php echo URL_PROGRAM.$folder.'/'?>">
			<input type="hidden" id="url_redirect" value="<?php echo isset($_GET['ref']) ? $_GET['ref']:URL_PROGRAM.$folder ?>">
			<div class="text-center">
				<p>Silahkan masukkan Email dan Password Anda untuk proses Login. </p>
			</div>
			
			<label for="lemailuser">Email</label>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text" id="addonemail"><i class="fa fa-envelope" aria-hidden="true"></i></span>
					 </div>
					<input type="email" class="form-control" name="lemailuser" id="lemailuser" placeholder="Email" aria-describedby="addonemail">
				</div>
			</div>
			<label for="lpassuser">Password</label>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text" id="addonpassword"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
					</div>
					<input type="password" class="form-control" name="lpassuser" id="lpassuser" placeholder="Password" aria-describedby="passwordHelp" aria-describedby="addonpassword">
				
				</div>
					<small id="passwordHelp" class="form-text text-muted text-right"><a href="<?php echo URL_PROGRAM.'lupa-password' ?>">Lupa Password</a></small>
			</div>
			<button id="tbllogin" type="submit" class="btn btn-block btn-outline-primary">Login</button>
			<div class="panel-footer-login text-center">
				Anda belum mendaftar ? klik <a href="<?php echo $urlregister ?>">disini</a> untuk register
			</div>
		</form>
	</div>
</div>
