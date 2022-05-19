<?php
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
define("path_toincludes", "../../_includes/");
define("folder", "lap-produk");
include "../../../includes/config.php";
include "../../autoloader.php";
if (isset($_SESSION["masukadmin"]) != "xjklmnJk1o~" && isset($_SESSION["userlogin"]) == "") echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
include path_toincludes . "paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('lap-produk', '', 0);


$dtLapProduk = new controllerLapProduk();

$menupage = isset($_GET["op"]) ? $_GET["op"] : "view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"]) ? $_POST["aksi"] : "";
switch ($aksipage) {
	case "cetak":
		$dtLapProduk->cetakLaporan();
		break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
	include(DIR_INCLUDE . 'header.php');
	include(DIR_INCLUDE . 'menu.php');
}

$judul = "Laporan Produk";
$iddata = "";
$b = 1;
//Ini untuk tampilan
switch ($menupage) {
	case "view":
	default:
		$dtPaging 	= new Paging();
		$dataview 	= $dtLapProduk->tampilData();
		$total		= $dataview['total'];
		$baris 	  	= $dataview['baris'];
		$page 	  	= $dataview['page'];
		$jmlpage  	= $dataview['jmlpage'];
		$linkpage 	= '';

		include "view.php";
		break;
	case "cetak":
		include "form.php";
		break;
	case "bykategori":
		$judul 		.= ' per Kategori';
		$dtPaging 	= new Paging();
		$dtKategori = new controllerKategori();
		$kategories = $dtKategori->getListKategori(0);

		$kategori 	= isset($_GET['kat']) ? $_GET['kat']  : 0;
		$search		= isset($_GET['search_kode']) ? $_GET['search_kode']  : '';
		$dataview 	= $dtLapProduk->getLapProdukByKategori($kategori, $search, 10);

		$total		= $dataview['total'];
		$baris 	  	= $dataview['baris'];
		$page 	  	= $dataview['page'];
		$jmlpage  	= $dataview['jmlpage'];
		$linkpage 	= '';

		$kategori_id 	= isset($dataview['rows'][0]['category_id']) ? $dataview['rows'][0]['category_id'] : 0;
		if ($kategori_id != '' && $kategori_id != '0') $linkpage .= '&kat=' . trim(strip_tags(urlencode($kategori)));

		$ukuranperkat = $dtLapProduk->getUkuranKategori($kategori_id);
		$datastoks = $dtLapProduk->getStokProdukPerKategoriPerWarnaUkuran($kategori_id);
		$options = '';
		include "view_by_kategori.php";
		break;
}
if ($stsload != "load") include(DIR_INCLUDE . 'footer.php');
