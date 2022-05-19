<?php
define("path_toincludes", "../../_includes/");
define("folder", "produk");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('produk','',0);

$dtProduk = new controllerProduk();


$aksipage = isset($_POST["actiondata"])? $_POST["actiondata"]:"";
//$aksipage = isset($_GET["aksi"])? $_GET["aksi"]:"";

switch($aksipage){
	case "uploadwarna":
		$dtProduk->uploadWarna();
	break;
	case "hapuswarnagambar":
		$dtProduk->hapusWarna();
	break;
	case "uploadgambardetail":
		$dtProduk->uploadGambarDetail();
	break;
	case "hapusgambardetail":
		$dtProduk->hapusGambarDetail();
	break;
	case "savestokoption":
		$dtProduk->saveStokOption();
	break;
	case "hapusstokoption":
		$dtProduk->hapusstokoption();
	break;
	case "savehargatambahan":
		$dtProduk->savehargatambahan();
	break;
	case "hapustambahharga":
		$dtProduk->hapustambahharga();
	break;
	case "editstok":
		$dtProduk->editstokoption();
	break;
}