<?php
//session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
define("path_toincludes", "../../_includes/");
define("folder", "lap-customer");
include "../../../includes/config.php";
include "../../autoloader.php";
if (isset($_SESSION["masukadmin"]) != "xjklmnJk1o~" && isset($_SESSION["userlogin"]) == "") echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
include path_toincludes . "paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('lap-customer', '', 0);

$dtLapCustomer = new controllerLapCustomer();

$menupage = isset($_GET["op"]) ? $_GET["op"] : "view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"]) ? $_POST["aksi"] : "";
switch ($aksipage) {
	case "cetak":
		$dtLapCustomer->cetakLaporan();
		break;
}


$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
	include(DIR_INCLUDE . 'header.php');
	include(DIR_INCLUDE . 'menu.php');
}

$judul = "Laporan Data Pelanggan";
$iddata = "";
$b = 1;
//Ini untuk tampilan
switch ($menupage) {
	case "view":
	default:
		$dtPaging 	= new Paging();
		$dataview 	= $dtLapCustomer->tampilData();
		$bulan 	= isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
		$tahun 	= isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
		$grup		= isset($_GET['grup']) ? $_GET['grup'] : '';
		$linkpage 	= '';
		if ($bulan != '') $linkpage .= '&bulan=' . trim(strip_tags(urlencode($bulan)));
		if ($tahun != '') $linkpage .= '&tahun=' . trim(strip_tags(urlencode($tahun)));
		if ($grup != '' && $grup != '0') $linkpage .= '&grup=' . trim(strip_tags(urlencode($grup)));
		include "view.php";
		break;
	case "view_daily":
		$dataview 	= $dtLapCustomer->tampilCustomerDaily();
		$tanggal1 	= isset($_GET['tanggal1']) ? $_GET['tanggal1'] : date('Y-m-d');
		$tanggal2 	= isset($_GET['tanggal2']) ? $_GET['tanggal2'] : date('Y-m-d');
		$grup		= isset($_GET['grup']) ? $_GET['grup'] : '';
		include "view_daily.php";
		break;
	case "cetak":
		include "form.php";
		break;
}
if ($stsload != "load") include(DIR_INCLUDE . 'footer.php');
