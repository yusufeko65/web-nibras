<?php
//session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "grap-produk-laris");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_to_language."indonesia.php";
include path_toincludes."paging.php";
include path_toincludes."chartnya.php";
$dtFungsi = new FungsiUmum();
//$dtFungsi->cekHak('warna','',0);

include DIR_INCLUDE."controller/controlGrapProdukLaris.php";
$dtGrapProdukLaris = new controllerGrapProdukLaris();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "cetak":
		$dtGrapProdukLaris->cetakLaporan();
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."moduleweb.php");
  headerweb($judulweb);
}
$judul = "Grafik 10 Produk Terlaris";
$warna_nama = "";
$iddata = "";
$b = 1;
//Ini untuk tampilan
switch($menupage){
	case "view": default:
	    $dtPaging 	= new Paging();
		$dataview 	= $dtGrapProdukLaris->tampilData();
		$bulan 	= isset($_GET['bulan']) ? $_GET['bulan']:date('m');
		$tahun 	= isset($_GET['tahun']) ? $_GET['tahun']:date('Y');
		$linkpage 	= '';
		if($bulan != '') $linkpage .= '&bulan='.trim(strip_tags(urlencode($bulan)));
		if($tahun != '') $linkpage .= '&tahun='.trim(strip_tags(urlencode($tahun)));
		include "view.php"; 
	break;
}
if($stsload!="load") footerweb(); 
?>