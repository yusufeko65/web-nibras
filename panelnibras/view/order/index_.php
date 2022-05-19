<?php
//session_start();

define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "order");
include "../../autoloader.php";
include "../../../includes/config.php";
include "../../../includes/themes.php";
$dtFungsi = new FungsiUmum();
$u_token = isset($_SESSION['u_token']) ? $_SESSION['u_token'] : '';
/*
if (isset($_SESSION["masukadmin"]) && isset($_SESSION["userlogin"])) {
	if ($_SESSION["masukadmin"] != 'xjklmnJk1o' && $_SESSION["userlogin"] == '') {
		echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
		exit;
	}
} else {
	echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
	exit;
}
*/
$cekToken = $dtFungsi->cekTokenValid2();

if (!$cekToken) {
	session_destroy();
	echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
	exit;
}
include path_toincludes . "paging.php";


$dtFungsi->cekHak('order', '', 0);

$dtOrder 		= new controllerOrder();
$dtSettingToko 	= new controllerSetting();
$dtCustomer 	= new controllerCustomer();
$dtProduk 		= new controllerProduk();
$dtShipping		= new controllerShipping();


$menupage = isset($_GET["op"]) ? $_GET["op"] : "view";

$module = isset($_GET['modul']) ? $_GET['modul'] : '';

switch ($module) {
	case "tarifkurir":
		$dtShipping->tarifkurir();
		exit;
		break;
	case "cetaklabel":
	case "cetak":
		//require_once '../../fpdf/mypdf.php';
		if ($module == 'cetak') $dtOrder->cetakInvoice();
		if ($module == 'cetaklabel') {

			$dtOrder->cetakLabel();
		}

		exit;
		break;

	case "frmAlamat":
		$data = isset($_POST['data']) ? $_POST['data'] : '';
		if ($data != '') {
			$dtPropinsi = new controllerPropinsi();
			$dtKabupaten = new controllerKabupaten();
			$dtKecamatan = new controllerKecamatan();

			$dataprop = $dtPropinsi->getPropinsi();

			$cari = false;

			$zdata = explode("::", $data);
			$nopesan = $zdata[0];
			$pelanggan_id = $zdata[1];
			$pelanggan_grup = $zdata[2];
			$jenis_alamat = $zdata[3];
			$caption_jenis_alamat = $zdata[4];
			$totberat = $zdata[5];
			$modulform = $zdata[6];
			$alamat_pelanggan = $dtCustomer->getAlamatCustomer($pelanggan_id);

			include DIR_INCLUDE . "view/order/formeditalamat.php";
			exit;
		}
		break;
	case "cariNama":
		$name = isset($_POST['name']) ? $_POST['name'] : '';
		$data = isset($_POST['data']) ? $_POST['data'] : '';
		if ($data != '') {
			$dtPropinsi = new controllerPropinsi();
			$dtKabupaten = new controllerKabupaten();
			$dtKecamatan = new controllerKecamatan();

			$dataprop = $dtPropinsi->getPropinsi();

			$cari = true;

			$zdata = explode("::", $data);
			$nopesan = $zdata[0];
			$pelanggan_id = $zdata[1];;
			$jenis_alamat = $zdata[2];
			$caption_jenis_alamat = $zdata[3];
			$totberat = $zdata[4];
			$modulform = $zdata[5];
		
			$alamat_pelanggan = $dtCustomer->getAlamatCustomerByName($pelanggan_id, $name);

			include DIR_INCLUDE . "view/order/formeditalamat.php";
			exit;
		}
		break;
	case "frmEditStatus":
		$data = isset($_POST['data']) ? $_POST['data'] : '';
		if ($data != '') {

			$zdata = explode(",", $data);

			$nopesan = $zdata[0];
			$stsnow = $zdata[1];
			$stsshipping = $zdata[2];
			$stsdone = $zdata[13]; //aar 22-06-2020
			$stskonfirm = $zdata[3];
			$stssudahbayar = $zdata[10];
			$datashipping = explode(':', $zdata[4]);

			$servis		 = $datashipping[0];
			$shipping	 = $datashipping[1];
			$tglshipping = $zdata[5];
			$awbshipping = $zdata[6];
			$stsgetpoin  = $zdata[7];
			$pelangganid  = $zdata[8];
			$totpoin  = $zdata[9];
			$grandtotal = $zdata[11];
			$stscancel = $zdata[12];
			$datastatus 	= $dtOrder->dataOrderStatus($nopesan);
			$datakonfirmasi = $dtOrder->dataOrderKonfirmasi($nopesan);

			if ($datakonfirmasi) {
				$modekonfirm    = "updatekonfirm";
				$jmlbayar       = $datakonfirmasi['jml_bayar'];
				$bankto         = $datakonfirmasi['bank_rek_tujuan'];
				$bankfrom       = $datakonfirmasi['bank_dari'];
				$norekfrom      = $datakonfirmasi['bank_rek_dari'];
				$atasnamafrom   = $datakonfirmasi['bank_atasnama_dari'];
				$tglbayar       = $datakonfirmasi['tgl_transfer'];
				$buktitransfer  = $datakonfirmasi['buktitransfer'];
				//$status         = $datakonfirmasi['status_bayar'];
			} else {
				$modekonfirm = "inputkonfirm";
				$jmlbayar       = $grandtotal;
				$bankto         = '';
				$bankfrom       = '';
				$norekfrom      = '';
				$atasnamafrom   = '';
				$tglbayar       = '';
				$buktitransfer  = '';
			}
			include DIR_INCLUDE . "view/order/formeditstatus.php";
			exit;
		}
		break;
	case "frmAddProduk":
		$data = isset($_POST['data']) ? $_POST['data'] : '';
		if ($data != '') {

			$zdata    		= explode(":", $data);

			$produkid		= $zdata[0];
			$produkkode		= $zdata[1];
			$produknm 		= $zdata[2];
			$ukuran 		= $zdata[3];
			$berat  		= $zdata[4];
			$hrgsatuan 		= $zdata[5];
			$diskonsatuan 	= $zdata[6];
			$stok		 	= $zdata[7];
			$minbeli     	= $zdata[8];
			$diskon_member	= $zdata[9];
			$pesanan_no		= $zdata[10];
			$grup_member	= $zdata[11];
			$pelanggan_id	= $zdata[12];
			$aksi			= $zdata[13];
			$grup_nama		= $zdata[14];
			$minbelisyarat	= $zdata[15];
			if ($minbelisyarat == '2') {
				$syarat = 'Bebas Campur';
			} else {
				$syarat = 'Per Jenis Produk';
			}
			if ($diskonsatuan > 0) {

				$harga_member	= ($diskonsatuan - (($diskonsatuan * $diskon_member) / 100));
			} else {
				$harga_member = ($hrgsatuan - (($hrgsatuan * $diskon_member) / 100));
			}
			include DIR_INCLUDE . "view/order/formaddprod.php";
			exit;
		}
		break;
	case "frmEditProduk":
		$data = isset($_POST['data']) ? $_POST['data'] : '';
		if ($data != '') {

			$zdata    	= explode("::", $data);
			$nopesan  	= $zdata[0];
			$iddetail 	= $zdata[1];
			$produkid	= $zdata[2];
			$produknm 	= $zdata[3];
			$idwarna  	= $zdata[4];
			$warna  	= $zdata[5];
			$idukuran 	= $zdata[6];
			$ukuran 	= $zdata[7];
			$qty     	= $zdata[8];
			$idgrup   	= $zdata[9];
			$idmember 	= $zdata[10];
			$dataproduk	= $dtProduk->dataProdukByID($produkid);
			if ($idwarna != '0' && $idukuran != '0') {
				$stok	= $dtProduk->getStokWarnaUkuran($produkid, $idukuran, $idwarna);
			} else {
				$stok 	= $dataproduk['jml_stok'];
			}
			include DIR_INCLUDE . "view/order/formeditprod.php";
			exit;
		}
		break;
	case "frmEditKurir":
		$data = isset($_POST['data']) ? $_POST['data'] : '';
		if ($data != '') {

			$setting    = $dtSettingToko->getSettingByKeys(array('config_lokasiorigin', 'config_apiurlongkir', 'config_apikeyongkir'));
			$dataset = array();
			foreach ($setting as $st) {
				$key = $st['setting_key'];
				$value = $st['setting_value'];
				$$key = $value;
			}
			//$servis = $dtShipping->getAllServicesAndTarifByWilayahRajaOngkir($config_lokasiorigin,$kec,$totberat,$config_apiurlongkir,$config_apikeyongkir);

			$formkurir = $dtOrder->formEditKurir();

			include DIR_INCLUDE . "view/order/formeditkurir.php";
			exit;
		}
		break;
	case "frmEditDeposito":

		$formdeposito = $dtOrder->formEditDeposito();

		include DIR_INCLUDE . "view/order/formeditdeposito.php";
		exit;

		break;
	case "frmDelProduk":
		$data = isset($_POST['data']) ? $_POST['data'] : '';
		if ($data != '') {

			$zdata    = explode("::", $data);
			$nopesan  = $zdata[0];
			$iddetail = $zdata[1];
			$produkid = $zdata[2];
			$produknm = $zdata[3];
			$warna    = $zdata[4];
			$ukuran   = $zdata[5];
			$qty      = $zdata[6];
			$idgrup   = $zdata[7];
			$idmember = $zdata[8];
			$nmwarna  = $zdata[9];
			$nmukuran = $zdata[10];
			$subtotal = $zdata[11];
			$poin     = $zdata[12];
			$jmlproduk = $zdata[13];

			include DIR_INCLUDE . "view/order/formdelprod.php";
			exit;
		}
		break;

	case "simpanpelanggan":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$dtCustomer->simpandata('simpan');
			exit;
		}
		break;

	case "cariproduk":
		$dtProduk->produkAutocomplete();
		exit;
		break;
	case "caripelanggan":
		$dtCustomer->customerAutocomplete();
		exit;
		break;
	case "warna":
		$dtProduk->getWarnaProdukByProdukAndUkuran();
		exit;
		break;
	case "stokbywarnaukuran":
		$dtProduk->getStokWarnaUkuranJson();
		exit;
		break;
	case "hapusorderproduk":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$dtOrder->hapusprodukorder();
		}
		exit;
		break;
	case "addorderalamat":

		$dtOrder->addOrderAlamat();

		exit;
		break;
	case "editorderalamat":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$dtOrder->editOrderAlamat();
		}
		exit;
		break;
	case "useorderalamat":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$dtOrder->useOrderAlamat();
		}
		exit;
		break;
	case "editorderproduk":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$dtOrder->editprodukorder();
		}
		exit;
		break;
	case "addorderproduk":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if ($_POST['aksi'] == 'editorder') {
				$dtOrder->addprodukorder();
			} else {
				$dtOrder->pesanCart();
			}
		}
		exit;
		break;

	case "updatecart":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if (isset($_POST['jumlah']) && isset($_POST['idmember'])) {
				$dtOrder->updateCart();
			}
		}
		exit;
		break;

	case "delcart":

		$dtOrder->delCart();

		exit;
		break;

	case "listcart":
		$dtOrder->listCart();
		exit;
		break;
	case "simpaneditkurir":
		$dtOrder->editKurir();
		exit;
		break;
	case "simpaneditpotongandeposit":
		$dtOrder->editPotonganDeposito();
		exit;
		break;
	case "serviskurir":
		$dtShipping->getAllServicesTarifByWilayahJSON();
		exit;
		break;
	case "tarifkurir":
		$dtShipping->tarifkurir();
		break;
	case "updateketeranganorder":
		$dtOrder->updateketeranganorder();
		break;
}
// ini untuk aksi
$aksipage = isset($_POST["aksi"]) ? $_POST["aksi"] : "";
switch ($aksipage) {
	case "tambah":
		$dtOrder->simpanneworder();
		exit;
		break;
	case "ubah":
		echo $dtOrder->simpandata('ubah');
		exit;
		break;
	case "ubahorder":
		echo $dtOrder->simpandata('ubahorder');
		exit;
		break;
	case "hapus":
		echo $dtOrder->hapusdata();
		exit;
		break;
	case "generate":

		$nopesan = isset($_POST['id']) ? $_POST['id'] : '';
		if ($nopesan != '' && $nopesan != '-') {
			echo $dtOrder->generateinvoice($nopesan);
		}
		exit;
		break;
	case "simpanstatusorder":
		$dtOrder->simpanstatusorder();
		exit;
		break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
	include(DIR_INCLUDE . 'header.php');
	include(DIR_INCLUDE . 'menu.php');
}

$judul = 'Order';

$iddata = '';
$b = 1;
$lock = '';
//Ini untuk tampilan
$setting    = $dtSettingToko->getSettingByKeys(array('config_orderstatus', 'config_ordercancel', 'config_konfirmstatus', 'config_sudahbayarstatus', 'config_shippingstatus'));

foreach ($setting as $st) {
	$key = $st['setting_key'];
	$value = $st['setting_value'];
	$$key = $value;
}

switch ($menupage) {
	case "view":
	default:
		$dtPaging 	= new Paging();
		$dataview 	= $dtOrder->tampildata();
		$total 	  	= $dataview['total'];
		$baris 	  	= $dataview['baris'];
		$page 	  	= $dataview['page'];
		$jmlpage  	= $dataview['jmlpage'];
		$ambildata	= $dataview['rows'];
		$cari 		= isset($_GET['datacari']) ? $_GET['datacari'] : '';
		$status		= isset($_GET['status']) ? $_GET['status'] : '';
		$linkpage 	= '&u_token=' . $u_token;
		if ($cari != '') $linkpage .= '&datacari=' . trim(strip_tags(urlencode($cari)));
		if ($status != '' && $status != '0') $linkpage .= '&status=' . trim(strip_tags(urlencode($status)));

		include "view.php";
		break;
	case "add":
		$dtFungsi->cekHak("order", "add", 0);
		$modul      = "tambah";
		$setting    = $dtSettingToko->getSetting();
		$dataset = array();
		foreach ($setting as $st) {
			$key = $st['setting_key'];
			$value = $st['setting_value'];
			if ($key == 'config_grupcust' || $key == 'config_editorder') {
				$dataset["$key"]	= explode("::", $value);
			} else {
				$dataset["$key"] = $value;
			}
		}
		$m_prop = new modelPropinsi();
		$dataprop = $m_prop->getPropinsi();
		$session = array('hsadmincart', 'wrnadmincart', 'ukradmincart', 'qtyadmincart');
		$dtFungsi->hapusSession($session);
		include "formorder.php";
		break;
	case "invoice":

	case "info":
		$dtFungsi->cekHak("order", "edit", 0);
		$modul = "ubahorder";
		$iddata = urlencode($_GET["pid"]);
		$order = $dtOrder->dataOrderByID($iddata);
		if (!empty($order)) {
			$dtGrupCust 	= new controllerCustomerGrup();
			$datagrup		= $dtGrupCust->dataResellerGrupByID($order['grup_member']);
			$grup_droship	= $datagrup['cg_dropship'];
			$datadetail 	= $dtOrder->dataOrderDetail($iddata);

			$whereprint		= "nopesanan='" . $order['pesanan_no'] . "' AND status_id='" . $order['status_id'] . "'";
			$tglprint		= $dtFungsi->fcaridata2('_order_status', 'tanggal', $whereprint);


			$setting    = $dtSettingToko->getSetting();
			$dataset = array();
			foreach ($setting as $st) {
				$key = $st['setting_key'];
				$value = $st['setting_value'];
				if ($key == 'config_grupcust' || $key == 'config_editorder') {
					$dataset["$key"]	= explode("::", $value);
				} else {
					$dataset["$key"] = $value;
				}
			}
			$session = array('hsadmincart', 'wrnadmincart', 'ukradmincart', 'qtyadmincart');
			$dtFungsi->hapusSession($session);
			$regis 	= 0;

			if ($menupage == "info") {
				include "form.php";
			} else {
				include "invoice.php";
			}
		}
		break;
}
if ($stsload != "load") include(DIR_INCLUDE . 'footer.php');
