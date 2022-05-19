<?php
if (!defined('path_to_includes')) define("path_to_includes", "includes/");
include "autoloader.php";
include path_to_includes."config.php";

if(isset($_GET['keluar'])) {
   session_destroy(); 
   echo "<script>location='".URL_PROGRAM."'</script>";
}

$dtSEOurl 		= new controller_Seo();
$dtKategori 	= new controller_Kategori();
$dtInformasi 	= new controller_Informasi();
$dtBank 		= new controller_Bank();
$dtSetting 		= new controller_SettingToko();
$dtShipping 	= new controller_Shipping();
$dtReseller 	= new controller_Reseller();
$dtCart 		= new controller_Cart();
$dtAtribut 		= new controller_Atribut();

$kategori 		= $dtKategori->GetKategori(0);
$menuinformasi  = $dtInformasi->GetMenuInformasi();
$bank 			= $dtBank->getBankInRekening();
$setting		= $dtSetting->getSettingToko();
$shipping 		= $dtShipping->getShipping();
$listwarna		= $dtAtribut->getWarna();
$listukuran		= $dtAtribut->getUkuran();
$config_jdlsite			= '';
$config_alamatsite		= '';
$config_deskripsitag	= '';
$config_keywordtag		= '';
$config_googleanalisis	= '';
$config_termcheckout    = '';
$config_termaccount     = '';
$config_termbelanja     = '';
$config_orderstatus     = '';
$config_ordercancel     = '';
$config_konfirmstatus   = '';
$config_memberdefault   = '';
$config_namatoko        = '';
$config_namapemilik     = '';
$config_alamattoko      = '';
$config_email           = '';
$config_telp            = '';
$config_masabayar       = '';
$config_pagefb          = '';
$config_twitter         = '';
$config_grupcust        = '';
$config_notaregis       = '';
$config_notaregisweb    = '';
$config_slideshow       = '0'; //0 = tidak tampil, 1 = tampil
$config_slideall        = '0'; //0 = halaman home aja, 1 = halaman all
$config_produkhome = 8;
$config_produklist = 8;
$config_produkkategori = 8;
$config_produksalehome = 4;
$config_produksalelist = 8;
$config_produkthumbnail_p = 247;
$config_produkthumbnail_l = 300;
$config_openingtime = '';
$config_slogansite = '';
$config_lokasiorigin = '';
$config_apikeyongkir = '';
$config_apiurlongkir = '';
$config_maintenance = '0';
$config_note_maintenance = '';
foreach($setting as $st){
	$key 	= $st['setting_key'];
	$value 	= $st['setting_value'];
	$$key	= $value;
}
if($config_slogansite != '' ) {
	$slogan = explode(" ",$config_slogansite);
} else {
	$slogan = false;
}
flush();
$gambarshare	= $config_alamatsite.URL_IMAGE.'gbrshare.jpg';
$grupCustReg 	= explode("::",$config_grupcust);

$tipemember    = isset($_SESSION['tipemember']) ? $_SESSION['tipemember']:$config_memberdefault;

include path_to_includes."themes.php";

$zmenu = $dtSEOurl->seourl();
$amenu = isset($zmenu['menu']) ? $zmenu['menu']:'';

$_GET['pid'] = '';
$_GET['j'] = '';
if($amenu=='kategori') {
	$_GET['pid'] = isset($zmenu['idkategori']) ? $zmenu['idkategori'] :'';
	$_GET['j'] = isset($zmenu['alias']['kategori']) ? $zmenu['alias']['kategori'] : '';
}
if($amenu=='detail') {
	$_GET['pid'] = $zmenu['idproduk'];
	$_GET['j'] = $zmenu['alias']['produk'];
}
if($amenu=='informasi') {
	$_GET['pid'] = $zmenu['idinformasi'];
	$_GET['j'] = $zmenu['alias']['informasi'];
}

if($amenu=='warna') {
	$_GET['pid'] = $zmenu['idwarna'];
	$_GET['j'] = $zmenu['alias']['warna'];
}

if($amenu=='ukuran') {
	$_GET['pid'] = $zmenu['idukuran'];
	$_GET['j'] = $zmenu['alias']['ukuran'];
}

if($amenu=='produkhead') {
	$_GET['pid'] = $zmenu['idprodukhead'];
	$_GET['j'] = $zmenu['alias']['produk-head'];
}

$_GET['modul'] = isset($zmenu['modul']) ? $zmenu['modul'] :'';;
$namamember = isset($_SESSION['namamember']) ? $_SESSION['namamember']:'';
$idmember = isset($_SESSION['idmember']) ? $_SESSION['idmember']:'';

$aliasurlmember   = $dtFungsi->fcaridata('_informasi','aliasurl','id_info',$config_termaccount);
$aliasurlcheckout = $dtFungsi->fcaridata('_informasi','aliasurl','id_info',$config_termcheckout);
$aliasurlbelanja  = $dtFungsi->fcaridata('_informasi','aliasurl','id_info',$config_termbelanja);

$grup_nama = '';
$grup_totalawal = 0;
$grup_min_beli = 0;
$grup_min_beli_syarat = 0;
$grup_min_beli_wajib = 0;
$grup_deposito = 0;
$grup_diskon = 0;
$grup_dropship = 0;
$reseller = false;
if($idmember != '') {
	$dtOrder = new controller_Order();
	$datapesanan = $dtOrder->getLastOrder($idmember,'',3);

	$reseller = $dtReseller->getResellerByID($idmember);
	$grup_nama = $reseller['cg_nm'];
	$grup_totalawal = $reseller['cg_total_awal'];
	$grup_min_beli = $reseller['cg_min_beli'];
	$grup_min_beli_syarat = $reseller['cg_min_beli_syarat'];
	$grup_min_beli_wajib = $reseller['cg_min_beli_wajib'];
	$grup_deposito = $reseller['cg_deposito']!=Null ? $reseller['cg_deposito'] : 0;
	$grup_diskon = $reseller['cg_diskon'];
	$grup_dropship = $reseller['cg_dropship'];
	$poinmember = $dtReseller->totalPoin($idmember);
	$depositmember = $dtReseller->totalDeposito($idmember);
}

if($config_maintenance  == '1') {
	
	$amenu = 'maintenance';
}

switch($amenu){
	default:
		$folder = $zmenu['folder'];
	break;
	case "maintenance":
		$folder = 'maintenance';
	break;
	case '':
		$folder = 'home';
	break;
	case 'daftar':
	case 'lupa-password':
		$folder = 'register';
	break;
	case 'stok-produk':
	case 'produk-sale':
	case 'list-produk':
		$folder = 'produk';
	break;
	case 'katalog':
		$folder = 'katalog';
	break;
	case "konfirmasi":
	case "account":
	case "orderhistory":
	case "orderdetail":
	case "orderedit":
	case "saldo":
	case "poin":
		$_GET['modul'] = $amenu;
		$folder = 'account';
	break;
}

$pid 	= isset($_GET['pid']) ? $_GET['pid']:'';
$alias 	= isset($_GET['j']) ? $_GET['j']:'';

$currentUrl = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

include path_to_includes.'bootcart.php';

if(!file_exists(DIR_THEMES.$folder.'/index.php')) {
   $folder = 'home';
}
include DIR_THEMES.$folder.'/index.php';
