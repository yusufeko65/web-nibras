<?php
//if (!defined('path_toincludes')) define("path_toincludes", "../includes/");
//include path_toincludes."config.php";
include "config.php";

include "autoloader.php";

$dtProduk 		= new controller_Produk();
$dtKategori 	= new controller_Kategori();
$kategori 		= $dtKategori->GetKategori(0);

include "header.php";
$dtPaging 		= new Paging();

$link = [];
$kats = isset($_GET['kat']) ? $_GET['kat']  : 0;

$search		= isset($_GET['s']) ? trim($_GET['s'])  : '';


$datalistproduk = $dtProduk->getLapProdukByKategori($kats, $search, 10);

$dataproduk		= $datalistproduk['rows'];
$totalproduk		= $datalistproduk['total'];
$baris 	  	= $datalistproduk['baris'];
$page 	  	= $datalistproduk['page'];
$jmlpage  	= $datalistproduk['jmlpage'];

$kategori_id 	= isset($dataproduk[0]['category_id']) ? $dataproduk[0]['category_id'] : 0;

$ukuranperkat 	= $dtProduk->getUkuranKategori($kategori_id);

$datastoks 		= $dtProduk->getStokProdukPerKategoriPerWarnaUkuran($kategori_id);
$options = '';
$script = '';
$linkpage = '';

$linkcari 		= '?';
if ($kategori_id != '' && $kategori_id != '0') $link[] = 'kat=' . trim(strip_tags(urlencode($kats)));
if ($search != '') $link[] = 's=' . trim(stripslashes(strip_tags($search)));
if (!empty($link)) {
	$linkcari .=  implode("&", $link);
}

$linkpage = '';
$amenu = '';


include 'list_status_produk.php';
include "footer.php";