<?php
if(!isset($_SESSION['idmember'])) echo "<script>location='".URL_PROGRAM.'login'."'</script>";
$modul = isset($_GET['modul']) ? $_GET['modul']:'';
$dtCart 		= new controller_Cart();
$dtProduk 		= new controller_Produk();
$dtReseller 	= new controller_Reseller();

$reseller = array();
if($idmember != '') {
  $reseller = $dtReseller->getResellerByID($idmember);
} 
include path_to_includes.'bootcart.php';

switch($modul){
  
	case "konfirmasi":
		 
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$model = isset($_POST['mode']) ? $_POST['mode'] : '';
			if($model == 'formkonfirm') {
				$dtKonfirmasi	= new controller_Konfirmasi();
				$dtKonfirmasi->simpandata();
				exit;
			}
		}
		
		$load = isset($_GET['load']) ? $_GET['load'] : '';
		switch($load) {
			case "searchnoorder":
				
				$dtOrder->SearchOrderAutocomplete();
				exit;
			break;
		}
		
		$noorder = isset($_GET['order']) ? $_GET['order'] : '';
		if($noorder != '') {
			$dataorder 	= $dtOrder->dataOrderByID($noorder);
			
		} else {
			$dataorder = false;
		}
		$file = '/konfirmasi.php';
		$script='<script type="text/javascript" src="'.URL_THEMES.'assets/jqueryui/jquery-ui.min.js"></script>';
		$script.='<script type="text/javascript" src="'.URL_THEMES.'assets/js/konfirmasi.js"></script>';
	break;  
	default:
	case "account":
		if(isset($_GET['keluar'])) {
			session_destroy();
			echo "<script>location='".URL_PROGRAM."'</script>";
			exit;
		}
		if(isset($_REQUEST['load'])) {
			switch($_GET['load']) {
				case "listAlamat":
					$dtAkun = new controller_Account();
					
					$tipe = isset($_POST['tipe']) ? $_POST['tipe'] : null;
					$listalamat = $dtAkun->listAlamat();
					include DIR_THEMES.$folder.'/listalamat.php';
				break;
				case "frmEditAlamat":
				case "frmAddAlamat":
					$id = isset($_POST['id']) ? $_POST['id'] : '';
					$modul = isset($_POST['modul']) ? $_POST['modul'] : 'input';
					$tipe = isset($_POST['tipe']) ? $_POST['tipe'] : null;
					if($idmember != '') {
						if($modul == 'input') {
							$titleform = 'Tambah';
							$nama = '';
							$hp = '';
							$alamat = '';
							$propinsi = '';
							$kabupaten = '';
							$optkabupaten = '';
							$optkecamatan = '';
							$kelurahan = '';
							$kodepos = '';
							$default = '';
							$dataprop = $dtFungsi->cetakcombobox3('- Propinsi -','0',$propinsi,'add_propinsi','_provinsi','provinsi_id','provinsi_nama');
							
						} else {
							$titleform = 'Ubah';
							$dataalamat = $dtReseller->getAlamatCustomerByID($id);
							
							$nama = isset($dataalamat['ca_nama']) ? $dataalamat['ca_nama'] : '';
							$hp = isset($dataalamat['ca_hp']) ? $dataalamat['ca_hp'] : '';
							$alamat = isset($dataalamat['ca_alamat']) ? $dataalamat['ca_alamat'] : '';
							$propinsi = isset($dataalamat['ca_propinsi']) ? $dataalamat['ca_propinsi'] : 0;
							$kabupaten = isset($dataalamat['ca_kabupaten']) ? $dataalamat['ca_kabupaten'] : 0;
							$kecamatan = isset($dataalamat['ca_kecamatan']) ? $dataalamat['ca_kecamatan'] : 0;
							$dataprop = $dtFungsi->cetakcombobox3('- Propinsi -','0',$propinsi,'add_propinsi','_provinsi','provinsi_id','provinsi_nama');
							if($propinsi != '0' || $propinsi != '') {
								$optkabupaten = $dtFungsi->cetakcombobox3('- Kotamadya/Kabupaten -','0',$kabupaten,'add_kabupaten','_kabupaten','kabupaten_id','kabupaten_nama','provinsi_id='.$propinsi);
							}
							
							if($kabupaten != '0' || $kabupaten != '') {
								$optkecamatan = $dtFungsi->cetakcombobox3('- Kecamatan -','0',$kecamatan,'add_kecamatan','_kecamatan','kecamatan_id','kecamatan_nama','kabupaten_id='.$kabupaten);
							}
							$kelurahan = isset($dataalamat['ca_kelurahan']) ? $dataalamat['ca_kelurahan'] : '';
							$kodepos = isset($dataalamat['ca_kodepos']) ? $dataalamat['ca_kodepos'] : '';
							$default = isset($dataalamat['ca_default']) ? $dataalamat['ca_default'] : '';
						}
						
						
						include DIR_THEMES.$folder.'/formalamat.php';
					}
				break;
				case "frmHapusAlamat":
					$id = isset($_POST['id']) ? $_POST['id'] : '';
					$modul = isset($_POST['modul']) ? $_POST['modul'] : 'input';
					if($modul == 'hapus'){
						$dataalamat = $dtReseller->getAlamatCustomerByID($id);
						$titleform = 'Hapus';
						$nama = isset($dataalamat['ca_nama']) ? $dataalamat['ca_nama'] : '';
						$hp = isset($dataalamat['ca_hp']) ? $dataalamat['ca_hp'] : '';
						$alamat = isset($dataalamat['ca_alamat']) ? $dataalamat['ca_alamat'] : '';
						$propinsi = isset($dataalamat['provinsi_nama']) ? $dataalamat['provinsi_nama'] : '';
						$kabupaten = isset($dataalamat['kabupaten_nama']) ? $dataalamat['kabupaten_nama'] : '';
						$kecamatan = isset($dataalamat['kecamatan_nama']) ? $dataalamat['kecamatan_nama'] : '';
						$kelurahan = isset($dataalamat['kelurahan']) ? $dataalamat['kelurahan'] : '';
						$kodepos = isset($dataalamat['kodepos']) ? $dataalamat['kodepos'] : '';
						include DIR_THEMES.$folder.'/formhapusalamat.php';
					}
				break;
				case "frmEditPassword":
					$titleform = 'Ubah Password';
					$modul = 'ubah';
					
					include DIR_THEMES.$folder.'/formeditpassword.php';
				break;
			}
			exit;
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$dtAkun = new controller_Account();
			if(isset($_POST['action'])) {
				
				$dtAkun->simpandata();
				exit;
			}
			
			if(isset($_POST['aksi'])) {
				switch($_POST['aksi']){
					case "inputalamat":
						$dtAkun->simpanalamat('input');
						exit;
					break;
					case "updatealamat":
						$dtAkun->simpanalamat('update');
						exit;
					break;
					case "hapusalamat":
						$dtAkun->hapusalamat();
						exit;
					break;
					case "ubahpassword":
						$dtAkun->ubahpassword();
						exit;
					break;
				}
			}
		}
		$alamats = $dtReseller->getAlamatCustomer($idmember);
		$file = '/akun.php';
		$script = '<script type="text/javascript" src="'.URL_THEMES.'assets/js/akun.js"></script>';
	break;
	case "orderhistory":
	    $link  = array();
	    $orders 		= $dtOrder->tampildata();
		
		$totals	   		= $orders['total'];
		
		$baris 	   		= $orders['baris'];
		$page 	   		= $orders['page'];
		$jmlpage   		= $orders['jmlpage'];
		$ambildata 		= $orders['rows'];
		$cari 			= isset($_GET['datacari']) ? $_GET['datacari']:'';
		$linkpage 		= '/'.$modul.'/';
		$linkcari 		= '';
		$sort			= isset($_GET['sort']) ? $_GET['sort']:'';
		if($sort!='') $link[] = 'sort='.trim(strip_tags($sort));
		if($cari!='') $link[] = 'datacari='.trim(strip_tags($cari));
		$dtPaging 		= new Paging();
		if(!empty($link)){
		   $linkcari = implode("&",$link);
		   $linkcari = '?'.$linkcari;
		}
		
		$file   = '/orderhistory.php';
		$script = '';
	break;
	case "orderedit":
	case "orderdetail":
		
		if(isset($_REQUEST['load'])) {
			
			switch($_GET['load']) {
				case "cariproduk":
					$dtProduk->produkAutocomplete();
					exit;
				break;
				case "frmAddProduk":
					$formproduk = $dtOrder->formAddProduk();
					include DIR_THEMES.$folder.'/formeditorderaddproduk.php';
				break;
				case "simpanaddproduk":
					$dtOrder->addprodukorder();
				break;
				case "warna":
					$dtProduk->warnaProduk();
				break;
				case "frmEditOrderKurir":
					$formkurir = $dtOrder->formEditKurir();
					
					include DIR_THEMES.$folder.'/formeditorderkurir.php';
				break;
				
				case "tarifkurir":
					$dtShipping->tarifkurir();
					exit;
				break;
				
				case "simpaneditkurir":
					$dtOrder->editKurir();
					exit;
				break;
				
				case "frmEditOrderProduk":
					$formproduk = $dtOrder->formEditProdukOrder();
					include DIR_THEMES.$folder.'/formeditorderproduk.php';
				break;
				
				case "simpaneditorderproduk":
					
					$dtOrder->editprodukorder();

					exit;
				break;
				
				case "frmHapusOrderProduk":
					$formproduk = $dtOrder->formHapusProdukOrder();
					include DIR_THEMES.$folder.'/formdelorderproduk.php';
				break;
				
				case "simpandelorderproduk":
					$dtOrder->hapusprodukorder();
				break;
				
				case "frmEditOrderAlamat":
					
					$formalamat = $dtOrder->formEditOrderAlamat();
				
					include DIR_THEMES.$folder.'/formeditorderalamat.php';
					
				break;
				
				case "useorderalamat":
					$dtOrder->useOrderAlamat();
				break;
				
				case "saveorderalamat":
					$dtOrder->saveOrderAlamat();
				break;
				case "updateketerangan":
					$dtOrder->updateketerangan();
				break;

				case "potongSaldo":
					$noorder = isset($_GET['order']) ? $_GET['order']:'0';
					$datastatus 	= $dtOrder->dataOrderStatus($noorder);

					if($config_orderstatus == $datastatus[0]['id']){
						$dtOrder->potongSaldo();
					}
					goto orderdetail;
				break;
			}
			exit;
		}
		orderdetail:

		$noorder = isset($_GET['order']) ? $_GET['order']:'0';
		
		$dataorder 	= $dtOrder->dataOrderByID($noorder);
		if(!$dataorder) echo "<script>location='".URL_PROGRAM."orderhistory'</script>";
		
		$datadetail 	= $dtOrder->dataOrderDetail($noorder);
		
		$datastatus 	= $dtOrder->dataOrderStatus($noorder);

		$modelreseller = new model_Reseller();
		$checkdeposit = $modelreseller->gettotalDeposito($dataorder['pelanggan_id']);

		$tagihan = ($dataorder['pesanan_subtotal'] + $dataorder['pesanan_kurir']) - $dataorder['dari_deposito'];
		
		if($modul == 'orderdetail') {
			$file			= '/orderdetail.php';
		} elseif ($modul == 'orderedit') {
			$file			= '/orderedit.php';

		}
		$script  = '<script type="text/javascript" src="'.URL_THEMES.'assets/js/order.js"></script>';
		$script .= '<script type="text/javascript" src="'.URL_THEMES.'assets/jqueryui/jquery-ui.min.js"></script>';
	break;
	case "poin":
		$datapoin  = $dtReseller->getPoin($idmember);
		$totalpoin = $dtReseller->totalPoin($idmember);
		$file		= '/datapoin.php';
		$script    = '';
	break;
	case "saldo":
		$datadeposito  = $dtReseller->getDeposito($idmember);
		$totaldeposito = $dtReseller->totalDeposito($idmember);
		$file		= '/datadeposito.php';
		$script    = '';
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