<?php
//session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "booking-jne");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_to_language."indonesia.php";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('booking-jne','',0);

include DIR_INCLUDE."controller/controlBookingJNE.php";
include DIR_INCLUDE."controller/controlSettingToko.php";
$dtBookingJNE = new controllerBookingJNE();
$dtSettingToko 	= new controllerSettingToko();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "cetak":
		$dtBookingJNE->cetakLaporan();
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."moduleweb.php");
  headerweb($judulweb);
}
$judul = "Booking JNE";
$warna_nama = "";
$iddata = "";
$b = 1;
//Ini untuk tampilan
switch($menupage){
	case "view": default:
		$dataview 	= $dtBookingJNE->tampilData();
		$tgl 	    = isset($_GET['tgl']) ? $_GET['tgl']:date('Y-m-d');
		
		$datasetting	= $dtSettingToko->getSettingToko();
		$nama_toko = $datasetting['toko_nama'];
		$alamat_toko = $datasetting['toko_alamat'];
		$tlp_toko = $datasetting['toko_telp'];
		$pemilik = $datasetting['toko_pemilik'];
		include "view.php"; 
	break;
	case "cetak":
		include "form.php";
	break;
}
if($stsload!="load") footerweb(); 
?>