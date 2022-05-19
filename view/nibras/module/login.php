<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Login User</h3>
  </div>
  <div class="panel-body">
    <form action="/login" method="POST" name="frmlogin" id="frmlogin">
	  <div class="form-group">
       <label>Email</label>
       <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email">
      </div>
      <div class="form-group">
       <label>Password</label>
       <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password">
      </div>
	  <button type="submit" class="btn btn-grey btn-sm btn-block">Login</button>
	  <div class="form-group">
	  <br>
	  <a href="/lupa-password">Lupa Password</a> | <a href="<?php echo URL_PROGRAM ?>daftar">Daftar</a>
	  </div>
	</form>
  </div>
</div>