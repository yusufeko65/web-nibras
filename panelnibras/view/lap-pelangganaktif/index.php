<?php
//session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
define("path_toincludes", "../../_includes/");
define("folder", "lap-pelangganaktif");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('lap-pelangganaktif','',0);

$dtLapPelangganAktif = new controllerLapPelangganAktif();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "cetak":
		$dtLapPelangganAktif->cetakLaporan();
   break;
}


$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
	include(DIR_INCLUDE.'header.php');
	include(DIR_INCLUDE.'menu.php');
}

$judul = "Laporan Data Pelanggan Aktif/Tidak";
$iddata = "";
$b = 1;
//Ini untuk tampilan
switch($menupage){
	case "view": default:
	    $dtPaging 	= new Paging();
		$dataview 	= $dtLapPelangganAktif->tampilData();
		
		$grup		= isset($_GET['grup']) ? $_GET['grup']:'';
		$status		= isset($_GET['status']) ? $_GET['status']:'1';
		$linkpage 	= '';
		if($grup != '' && $grup != '0') $linkpage .= '&grup='.trim(strip_tags(urlencode($grup)));
		include "view.php"; 
	break;
	case "cetak":
		include "form.php";
	break;
}
if($stsload!="load") include(DIR_INCLUDE.'footer.php');
?>