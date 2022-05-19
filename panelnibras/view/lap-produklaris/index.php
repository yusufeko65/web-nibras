<?php
//session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
define("path_toincludes", "../../_includes/");
define("folder", "lap-produklaris");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('lap-produklaris','',0);


$dtLapProduk = new controllerLapProdukLaris();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
	case "cetak":
		$dtLapProduk->cetakLaporan();
	break;
}


$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
	include(DIR_INCLUDE.'header.php');
	include(DIR_INCLUDE.'menu.php');
}

$judul = "Laporan 10 Produk Terlaris";
$iddata = "";
$b = 1;
//Ini untuk tampilan
switch($menupage){
	case "view": default:
	    $dtPaging 	= new Paging();
		$dataview 	= $dtLapProduk->tampilData();
		$bulan 	= isset($_GET['bulan']) ? $_GET['bulan']:date('m');
		$tahun 	= isset($_GET['tahun']) ? $_GET['tahun']:date('Y');
		$status		= isset($_GET['status']) ? $_GET['status']:'';
		$linkpage 	= '';
		if($bulan != '') $linkpage .= '&bulan='.trim(strip_tags(urlencode($bulan)));
		if($tahun != '') $linkpage .= '&tahun='.trim(strip_tags(urlencode($tahun)));
		if($status != '' && $status != '0') $linkpage .= '&status='.trim(strip_tags(urlencode($status)));
		include "view.php"; 
	break;
	case "cetak":
		include "form.php";
	break;
}
if($stsload!="load") include(DIR_INCLUDE.'footer.php');
?>