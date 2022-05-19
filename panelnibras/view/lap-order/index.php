<?php
define("path_toincludes", "../../_includes/");
define("folder", "lap-order");
include "../../../includes/config.php";
include "../../autoloader.php";
if (isset($_SESSION["masukadmin"]) != "xjklmnJk1o~" && isset($_SESSION["userlogin"]) == "") echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
include path_toincludes . "paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('lap-order', '', 0);

$dtLapOrder = new controllerLapOrder();

$menupage = isset($_GET["op"]) ? $_GET["op"] : "view";

if (isset($_GET['loads'])) {
	switch ($_GET['loads']) {
		case "customer":
			$dtLapOrder->getAutoCompleteCustomer();
			exit;
			break;
	}
}
// ini untuk aksi
$aksipage = isset($_POST["aksi"]) ? $_POST["aksi"] : "";
switch ($aksipage) {
	case "cetak":
		$dtLapOrder->cetakLaporan();
		break;
}


$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
	include(DIR_INCLUDE . 'header.php');
	include(DIR_INCLUDE . 'menu.php');
}

$judul = "Laporan Order";
$iddata = "";
$b = 1;
//Ini untuk tampilan
switch ($menupage) {
	case "view":
	default:
		$dtPaging 	= new Paging();
		$dataview 	= $dtLapOrder->tampilData();
		$bulan 	= isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
		$tahun 	= isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
		$status		= isset($_GET['status']) ? $_GET['status'] : '';
		$linkpage 	= '';
		if ($bulan != '') $linkpage .= '&bulan=' . trim(strip_tags(urlencode($bulan)));
		if ($tahun != '') $linkpage .= '&tahun=' . trim(strip_tags(urlencode($tahun)));
		if ($status != '' && $status != '0') $linkpage .= '&status=' . trim(strip_tags(urlencode($status)));
		include "view.php";
		break;
	case "cetak":
		include "form.php";
		break;
	case "view_daily":
		$dtSetting = new controllerSetting();
		//$settings = $dtSetting->getSettingByKey('config_orderselesai');
		$settings = $dtSetting->getSettingByKeys(array('config_orderselesai', 'config_shippingstatus'));
		//print_r($settings);
		foreach ($settings as $setting) {
			if ($setting['setting_key'] == 'config_orderselesai') {
				$order_selesai = $setting['setting_value'];
			}
			if ($setting['setting_key'] == 'config_shippingstatus') {
				$order_kirim = $setting['setting_value'];
			}
		}
		//$order_selesai = $settings['setting_value'];
		$dataview 	= $dtLapOrder->tampilOrderDaily();
		$tanggal1 	= isset($_GET['tanggal1']) ? $_GET['tanggal1'] : date('Y-m-d');
		$tanggal2 	= isset($_GET['tanggal2']) ? $_GET['tanggal2'] : date('Y-m-d');
		$status		= isset($_GET['status']) ? $_GET['status'] : $order_selesai;
		$customer 	= isset($_GET['customer']) ? $_GET['customer'] : '';
		$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : '';
		$linkpage 	= '';
		/*
		if ($tanggal1 != '') $linkpage .= '&tanggal1=' . trim(strip_tags(urlencode($tanggal1)));
		if ($tanggal2 != '') $linkpage .= '&tanggal2=' . trim(strip_tags(urlencode($tanggal2)));
		if ($status != '' && $status != '0') $linkpage .= '&status=' . trim(strip_tags(urlencode($status)));
		if ($customer != '' && $customer_id != '')  $linkpage .= '&customer=' + $customer + '&customer_id=' + $customer_id;
		*/
		include "view_daily.php";
		break;
}
if ($stsload != "load") include(DIR_INCLUDE . 'footer.php');
