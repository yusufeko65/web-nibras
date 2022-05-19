<?php
$modul = isset($_GET['modul']) ? $_GET['modul']:'';

$dtProduk 		= new controller_Produk();
$dtSetting = new controller_SettingToko();
$dtBank = new controller_Bank();
$dtCustomer = new controller_Reseller();

switch($modul){
	default:
	case "cart":
		$subtotal 	= 0;
		$i = 0;
		$script = '<script type="text/javascript" src="'.URL_THEMES.'assets/js/cart.js"></script>';
		$file='/keranjang.php';
		$breadcrumbs = '<a href="'.URL_PROGRAM.'">Home</a> | Keranjang Belanja';
		if($jmlerror > 0 ) {
			$pesan = 'Maaf, pesanan Anda melebihi stok yang tersedia <br><b>'. implode("<br>",$msgerror).'</b>';
			$style = '';
			$kelaserror = 'class="alert alert-danger"';
		} else {
			$pesan='';
			$style='style="display:none"';
			$kelaserror = '';
		}
	break;
	case "info":
		$dtCart->keranjangInfo($hcart);
		exit;
	break;
	case "sukses":

		if(isset($_SESSION['sukses'])) {
			$message = $_SESSION['message_order'];
			$session = array('sukses','message_order');
			$dtFungsi->hapussession($session);
			$script = '';
			$breadcrumbs = '<a href="'.URL_PROGRAM.'">Home</a> | Selesai Belanja';
			$file = '/sukses.php';
		} else {
			echo "<script>location='".URL_PROGRAM."'</script>";
		}

	break;
	
	
	case "checkout":
	
		if($jmlerror > 0 ) echo "<script>location='".URL_PROGRAM.'cart'."'</script>";
		if($jmlcart > 0) {
			
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				switch($_POST['aksi']) {
					case "tarifkurir":
						$dtShipping->tarifkurir();
					break;
					case "serviskurir":
						$dtShipping->getAllServicesAndTarifByWilayahJson();
					break;
					case "simpanorder":
						$dtCart->simpanorder();
					break;
				}
				
				//
				exit;
			}
	  
			if(isset($_SESSION['usermember'])) { 
				/* alamat pengirim */
				if(!isset($_SESSION['nama_pengirim'])) {
					$nama_pengirim = $reseller['cust_nama'];
				} else {
					$nama_pengirim = $_SESSION['nama_pengirim'];
				}
	 
				if(!isset($_SESSION['telp_pengirim'])) {
				   $telp_pengirim = $reseller['cust_telp'];
				} else {
				   $telp_pengirim = $_SESSION['telp_pengirim'];
				}

				if(!isset($_SESSION['alamat_pengirim'])) {
					$alamat_pengirim = $reseller['cust_alamat'];
				} else {
					$alamat_pengirim = $_SESSION['alamat_pengirim'];
				}
	 	 
				if(!isset($_SESSION['propinsi_pengirim'])) {
					$propinsi_pengirim = $reseller['cust_propinsi'];
					$propinsi_pengirim_nm = $reseller['provinsi_nama']; 
				} else {
					$propinsi_pengirim = $_SESSION['propinsi_pengirim'];
					$propinsi_pengirim_nm = $reseller['propinsi_nama_pengirim'];
				}
	 
				if(!isset($_SESSION['kabupaten_pengirim'])) {
					$kabupaten_pengirim = $reseller['cust_kota'];
					$kabupaten_pengirim_nm = $reseller['kabupaten_nama'];
				} else {
					$kabupaten_pengirim = $_SESSION['kabupaten_pengirim'];
					$kabupaten_pengirim_nm = $_SESSION['kabupaten_nama_pengirim'];
				}
		 
				if(!isset($_SESSION['kecamatan_pengirim'])) {
					$kecamatan_pengirim = $reseller['cust_kecamatan'];
					$kecamatan_pengirim_nm = $reseller['kecamatan_nama'];
				} else {
					$kecamatan_pengirim = $_SESSION['kecamatan_pengirim'];
					$kecamatan_pengirim_nm = $_SESSION['kecamatan_nama_pengirim'];
				}
		 
				if(!isset($_SESSION['kelurahan_pengirim'])) $kelurahan_pengirim = $reseller['cust_kelurahan'];
				else $kelurahan_pengirim = $_SESSION['kelurahan_pengirim'];
		 
				if(!isset($_SESSION['kodepos_pengirim'])) $kodepos_pengirim = $reseller['cust_kdpos'];
				else $kodepos_pengirim = $_SESSION['kodepos_pengirim'];
				
				/* alamat penerima */
				if(!isset($_SESSION['nama_penerima'])) {
					$nama_penerima = $reseller['cust_nama'];
				} else {
					$nama_penerima = $_SESSION['nama_penerima'];
				}
	 
				if(!isset($_SESSION['telp_penerima'])) {
				   $telp_penerima = $reseller['cust_telp'];
				} else {
				   $telp_penerima = $_SESSION['telp_penerima'];
				}

				if(!isset($_SESSION['alamat_penerima'])) {
					$alamat_penerima = $reseller['cust_alamat'];
				} else {
					$alamat_penerima = $_SESSION['alamat_penerima'];
				}
	 	 
				if(!isset($_SESSION['propinsi_penerima'])) {
					$propinsi_penerima = $reseller['cust_propinsi'];
					$propinsi_penerima_nm = $reseller['provinsi_nama']; 
				} else {
					$propinsi_penerima = $_SESSION['propinsi_penerima'];
					$propinsi_penerima_nm = $_SESSION['propinsi_nama_penerima'];
				}
	 
				if(!isset($_SESSION['kabupaten_penerima'])) {
					$kabupaten_penerima = $reseller['cust_kota'];
					$kabupaten_penerima_nm = $reseller['kabupaten_nama'];
				} else {
					$kabupaten_penerima = $_SESSION['kabupaten_penerima'];
					$kabupaten_penerima_nm = $_SESSION['kabupaten_nama_penerima'];
				}
		 
				if(!isset($_SESSION['kecamatan_penerima'])) {
					$kecamatan_penerima = $reseller['cust_kecamatan'];
					$kecamatan_penerima_nm = $reseller['kecamatan_nama'];
				} else {
					$kecamatan_penerima = $_SESSION['kecamatan_penerima'];
					$kecamatan_penerima_nm = $_SESSION['kecamatan_nama_penerima'];
				}
		 
				if(!isset($_SESSION['kelurahan_penerima'])) $kelurahan_penerima = $reseller['cust_kelurahan'];
				else $kelurahan_penerima = $_SESSION['kelurahan_penerima'];
		 
				if(!isset($_SESSION['kodepos_penerima'])) $kodepos_penerima = $reseller['cust_kdpos'];
				else $kodepos_penerima = $_SESSION['kodepos_penerima'];
				
				$script = '<script type="text/javascript" src="'.URL_THEMES.'assets/js/kasir.js"></script>';
				$breadcrumbs = '<a href="'.URL_PROGRAM.'">Home</a> | <a href="'.URL_PROGRAM.'cart">Keranjang Belanja</a> | Check Out';

				$biaya_packing = $dtSetting->getSettingTokoByKey('config_biayapacking');
				$customer = $dtCustomer->getResellerByID($_SESSION['idmember']);
				$customer_group = $dtCustomer->getGrupCustByID($_SESSION['tipemember']);
				
				//$servis = $dtShipping->getAllServicesAndTarifByWilayah($propinsi_penerima,$kabupaten_penerima,$kecamatan_penerima);
				
				$dataprop = $dtFungsi->cetakcombobox3('- Propinsi -','0',$propinsi_penerima,'propinsi_penerima','_provinsi','provinsi_id','provinsi_nama');
				if($propinsi_penerima != '' && $propinsi_penerima != '0') {
					$optkabupaten = $dtFungsi->cetakcombobox3('- Kotamadya/Kabupaten -','0',$kabupaten_penerima,'kabupaten_penerima','_kabupaten','kabupaten_id','kabupaten_nama','provinsi_id='.$propinsi_penerima);
				}
				if($kabupaten_penerima != '' && $kabupaten_penerima != '0') {
					$optkecamatan = $dtFungsi->cetakcombobox3('- Kecamatan -','0',$kecamatan_penerima,'kecamatan_penerima','_kecamatan','kecamatan_id','kecamatan_nama','kabupaten_id='.$kabupaten_penerima);
				}
				$file='/kasir.php';
				
			} else {
				
				echo "<script>location='".URL_PROGRAM.'login?ref='.URL_PROGRAM.'cart/kasir'."'</script>";
			}
		
		} else {
			echo "<script>location='".URL_PROGRAM.'cart'."'</script>";
		}
	 
	break;
	case "del":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		   if(isset($_POST['aksi'])) {
			   if($_POST['aksi'] == 'del'){
				  $dtCart->delCart();
			   }
		   }
		}
		exit;
	break;
  
	case "update":
		
		$dtCart->updateCart();
		//print_r($_POST);
		exit;
	break;
  
	case "add":
		$dtCart->pesanCart(array("tipemember"=>$tipemember,"grup_totalawal"=>$grup_totalawal,"grup_min_beli"=>$grup_min_beli,"grup_min_beli_syarat"=>$grup_min_beli_syarat,"grup_min_beli_wajib"=>$grup_min_beli_wajib,"grup_diskon"=>$grup_diskon));
		exit;
	break;
  
}
include DIR_THEMES."header.php";
 ?>
<main>
	
	<?php include DIR_THEMES."/module/bannertop.php";?>
	<div class="container"><small><?php echo $breadcrumbs ?></small></div>
	<?php if($file!='') include DIR_THEMES.$folder.$file; ?>

</main>
<?php include DIR_THEMES."script.php";?>
<?php echo $script ?>
<?php include DIR_THEMES."footer.php";?>