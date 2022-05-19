<?php

$dtRegister		= new controller_Register();
$dtCart 		= new controller_Cart();
$dtProduk 		= new controller_Produk();
$dtCaptcha   	= new controller_Captcha();
include path_to_includes.'bootcart.php';
switch($amenu){

	case "lupa-password":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(isset($_POST['email'])) {
				echo $dtRegister->resetpassword();
				exit;
			}
		}
		if(isset($_SESSION['usermember'])) echo "<script>location='".URL_PROGRAM."'</script>";
		$file= '/lupa-pass.php';
		$script = '<script type="text/javascript" src="'.URL_THEMES.'assets/js/lupapass.js"></script>';
	break;
	case "daftar":
	case "register":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(isset($_POST['rnama'])) {
				$dtRegister->simpandata();
				exit;
			}
		}
		if(isset($_GET['load'])) {
	    
			switch($_GET['load']) {
			   case 'propinsi':
				  $id 			= isset($_GET['negara']) ? $_GET['negara']:'';
				  $juduloption 	= '- Propinsi -';
				  $ukuran 		= '230';
				  $idobject 	= 'rpropinsi';
				  $tabel 		= '_provinsi';
				  $fieldoption 	= 'provinsi_id';
				  $fieldisi 	= 'provinsi_nama';
				  $where		= 'negara_id='.$id.' ORDER by provinsi_nama';
			   break;
			   case 'kabupaten':
				  $id = isset($_GET['propinsi']) ? $_GET['propinsi']:'';
				  $juduloption 	= '- Kabupaten -';
				  $ukuran 		= '230';
				  $idobject 	= 'rkabupaten';
				  $tabel 		= '_kabupaten';
				  $fieldoption 	= 'kabupaten_id';
				  $fieldisi 	= 'kabupaten_nama';
				  $where		= 'provinsi_id='.$id. ' ORDER by kabupaten_nama';
			   break;
			   case 'kecamatan':
				  $id = isset($_GET['kabupaten']) ? $_GET['kabupaten']:'';
				  $juduloption 	= '- Kecamatan -';
				  $ukuran 		= '230';
				  $idobject 	= 'rkecamatan';
				  $tabel 		= '_kecamatan';
				  $fieldoption 	= 'kecamatan_id';
				  $fieldisi 	= 'kecamatan_nama';
				  $where		= 'kabupaten_id='.$id.' ORDER by kecamatan_nama';
				 
			   break;
			}
			echo $dtFungsi->cetakcombobox($juduloption,$ukuran,0,$idobject,$tabel,$fieldoption,$fieldisi,$where);
			exit;
		}
		$aliasurl = $dtFungsi->fcaridata('_informasi','aliasurl','id_info',$config_termaccount);
		
		$datagrupCust = $dtReseller->getGrupCustMulti($grupCustReg);
		
		$script = '<script type="text/javascript" src="'.URL_THEMES.'assets/js/register.js"></script>';
		if(!isset($_GET['sukses'])){
			if(isset($_SESSION['usermember'])) echo "<script>location='".URL_PROGRAM."'</script>"; 
			$file= '/daftar.php';
		} else { 
			$file = '/sukses.php';
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