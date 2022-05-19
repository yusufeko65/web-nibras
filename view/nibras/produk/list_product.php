<?php

include "../../../includes/config.php";
include "../../../autoloader.php";

$dtProduk 		= new controller_Produk();

if(isset($_GET['loads'])) {
	switch($_GET['loads']) {
		case "kode_produk":
			$_GET['limit'] = '';
			if($_GET['category']>0){
				$_GET['limit'] = "AND _produk_kategori.idkategori='".$_GET['category']."' ";
			}

			$datalistproduk = $dtProduk->produkAutocomplete();
			//$dataproduk		= $datalistproduk['rows'];
			//echo json_encode($dataproduk);
			exit;
		break;
	}
}