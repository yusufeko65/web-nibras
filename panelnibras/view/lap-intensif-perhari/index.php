<?php
//session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "lap-intensif-perhari");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_to_language."indonesia.php";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('lap-intensif-perhari','',0);

include DIR_INCLUDE."controller/controlLapIntensifPerHari.php";
$dtLapIntensif = new controllerLapIntensifPerhari();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "cetak":
		$dtLapIntensif->cetakLaporan();
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."moduleweb.php");
  headerweb($judulweb);
}
$judul = "Laporan Bonus Admin Order Perhari";
$warna_nama = "";
$iddata = "";
$b = 1;

//Ini untuk tampilan
switch($menupage){
	case "view": default:
	    $dtPaging 	= new Paging();
		$dataview 	= $dtLapIntensif->tampilData();
		
		$tgl 	= isset($_GET['tgl']) ? $_GET['tgl']:date('Y-m-d');
		
		$linkpage 	= '';
		if($tgl != '') $linkpage .= '&tgl='.trim(strip_tags(urlencode($bulan)));
		
		include "view.php"; 
	break;
	case "cetak":
		include "form.php";
	break;
}
if($stsload!="load") footerweb(); 
?>