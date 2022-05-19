<?php
if (!defined('path_toincludes')) define("path_toincludes", "../includes/");
include path_toincludes."config.php";
include "autoloader.php";
if(isset($_SESSION["masukadmin"])=='xjklmnJk1o~' && isset($_SESSION["userlogin"])!='') header("location: home/");
if (!defined('folder')) define("folder", "");
$dtFungsi = new FungsiUmum();
$menupage = isset($_POST['aksi']) ? $_POST['aksi']:'';
if(isset($_GET['keluar']) && isset($_SESSION["userlogin"])!='') {
  session_destroy();
  header("location: index.php");
  exit;
}
if($menupage=='aksilogin') {
	$dtLogin = new controllerLogin();
	echo $dtLogin->checkLogin();
	exit;
}
include("header-login.php");
?>
<div class="col-lg-12 login-content">
   <form class="form-signin" id="frmdata" name="frmdata" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" onKeyPress="return disableEnterKey(event)">
       <input type="hidden" id="aksi" name="aksi" value="aksilogin">
	   <h4 class="form-signin-heading">Login</h4>
       <input type="text" class="form-control" placeholder="Username" name="username" id="username">
       <input type="password" class="form-control" placeholder="Password" name="password" id="password">
       <button class="btn btn-primary" type="button" id="tbllogin">Login</button><p>
       <div id="hasil"></div>
    </form>
		  
</div>
<script src="<?php echo URL_PROGRAM_ADMIN?>login.js" type="text/javascript"></script>
<?php include("footer-login.php"); ?>