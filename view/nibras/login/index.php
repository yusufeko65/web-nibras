<?php
$dtLogin		= new controller_Login();
$dtCart 		= new controller_Cart();
$dtProduk 		= new controller_Produk();
include path_to_includes.'bootcart.php';
if(isset($_SESSION['usermember'])) echo "<script>location='".URL_PROGRAM."'</script>";	
switch($amenu){
	case "login":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(isset($_POST['lemailuser'])) {
				$dtLogin->getLogin();
				exit;
			}
		}
		$file= '/loginuser.php';
		$script = '<script type="text/javascript" src="'.URL_THEMES.'assets/js/login.js"></script>';
		$ref = isset($_GET['ref']) ? $_GET['ref']:'';
		$urlregister = URL_PROGRAM.'register';
		if($ref != '') {
			$urlregister .= '?ref='.$ref;
		} 
	break;
}
include DIR_THEMES."header.php";
?>
<main>
	
	<?php include DIR_THEMES."/module/bannertop.php";?>
	<?php if($file!='') include DIR_THEMES.$folder.$file; ?>

</main>
<?php include DIR_THEMES."script.php";?>
<?php echo $script ?>
<?php include DIR_THEMES."footer.php";?>