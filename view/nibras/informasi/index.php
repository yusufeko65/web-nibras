<?php
$check  = $dtInformasi->checkDataInformasiByID($pid,$alias);

$dtCart 		= new controller_Cart();
include path_to_includes.'bootcart.php';

switch($amenu){
	case "informasi":
		if($check) {
			$detail = $dtInformasi->getInformasiByID($pid);
			$config_jdlsite = $config_namatoko.' | '.stripslashes($detail['judul']);
			$file= '/detailinfo.php';
		} else {
			$folder = 'error/';
			$file = 'error.php';
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
<?php include DIR_THEMES."footer.php";?>
