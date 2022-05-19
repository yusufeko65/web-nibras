<?php
define("path_toincludes", "../../_includes/");
define("folder", "produk-head");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('produk-head','',0);

$dtProduk = new controllerProduk();


$aksipage = isset($_POST["actiondata"])? $_POST["actiondata"]:"";
//$aksipage = isset($_GET["aksi"])? $_GET["aksi"]:"";

switch($aksipage){
	case "uploadwarna":
		$dtProduk->uploadWarnaHeadProduk();
	break;
	case "hapuswarnagambar":
		$dtProduk->hapusWarnaHeadProduk();
	break;
}