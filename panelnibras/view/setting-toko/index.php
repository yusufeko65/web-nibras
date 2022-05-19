<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "setting-toko");
include "../../../includes/config.php";
include "../../autoloader.php";
if (isset($_SESSION["masukadmin"]) != "xjklmnJk1o~" && isset($_SESSION["userlogin"]) == "") echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
include path_toincludes . "paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('setting-toko', '', 0);

$dtSettingToko = new controllerSetting();
$dtInformasi   = new controllerInformasi();
$dtStatus   = new controllerOrderStatus();
$dtCgrup   = new controllerCustomerGrup();
$dtKota		= new controllerKabupaten();
$menupage = isset($_GET["op"]) ? $_GET["op"] : "view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"]) ? $_POST["aksi"] : "";
switch ($aksipage) {
	case "simpan":
		echo $dtSettingToko->simpandata();
		exit;
		break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
	include(DIR_INCLUDE . "header.php");
	include(DIR_INCLUDE . "menu.php");
}
$judul = "SettingToko";
$iddata = "";
$b = 1;

$setting    = $dtSettingToko->getSetting();
$informasi  = $dtInformasi->getInformasi();
$datastatus = $dtStatus->getOrderStatus();
$datagrup   = $dtCgrup->getResellerGrup();
$datacity		= $dtKota->getKabupaten();
$data	 = array();
//print_r($setting);
$data['config_jdlsite']			= '';
$data['config_tagline']			= '';
$data['config_alamatsite']		= '';
$data['config_deskripsitag']	= '';
$data['config_keywordtag']		= '';
$data['config_namatoko']		= '';
$data['config_namapemilik']		= '';
$data['config_alamattoko']		= '';
$data['config_email']		    = '';
$data['config_emailnotif']	    = '';
$data['config_telp'] 		    = '';
$data['config_termcheckout']	= '';
$data['config_termaccount']		= '';
$data['config_termbelanja']		= '';
$data['config_orderstatus']		= '';
$data['config_ordercancel'] 	= '';
$data['config_orderselesai']	= '';
$data['config_statuskomplit']	= '';
$data['config_konfirmstatus']	= '';
$data['config_memberdefault']	= '';
$data['config_maintenanceket']	= '';
$data['config_googleanalisis']	= '';
$data['config_pagefb']			= '';
$data['config_fb']				= '';
$data['config_twitter']			= '';
$data['config_instagram']		= '';
$data['config_metalain']		= '';
$data['config_welcomemessage']	= '';
$data['config_chkwelcome']		= '';
$data['config_masabayar']		= '0';
$data['config_grupcust']	    = array();
$data['config_headernotaemail']		= '';
$data['config_notaregisweb']	= '';
$data['config_notabelanja']		= '';
$data['config_notabelanjaweb']	= '';
$data['config_getpoincust']     = array();
$data['config_editorder']       = array();
$data['config_shippingstatus']  = '';
$data['config_sudahbayarstatus']  = '';
$data['config_infoshipping']    = '';
$data['config_infosudahbayar']    = '';
$data['config_targetaktif']    = '0';
$data['config_slideshow']    = '0';
$data['config_slideall']    = '0';
$data['config_kategorithumbnail_p'] = 0;
$data['config_kategorithumbnail_l'] = 0;
$data['config_kategorismall_p'] = 0;
$data['config_kategorismall_l'] = 0;
$data['config_produkthumbnail_p'] = 0;
$data['config_produkthumbnail_l'] = 0;
$data['config_produkdetail_p'] = 0;
$data['config_produkdetail_l'] = 0;
$data['config_produksmall_p'] = 0;
$data['config_produksmall_l'] = 0;
$data['config_produkzoom_p'] = 0;
$data['config_produkzoom_l'] = 0;
$data['config_logobank_p'] = 0;
$data['config_logobank_l'] = 0;
$data['config_logokurir_p'] = 0;
$data['config_logokurir_l'] = 0;
$data['config_produkhome'] = 8;
$data['config_produklist'] = 8;
$data['config_produkkategori'] = 8;
$data['config_produksalehome'] = 8;
$data['config_produksalelist'] = 8;
$data['config_openingtime'] = '';
$data['config_slogansite'] = '';
$data['config_apikeyongkir'] = '';
$data['config_lokasiorigin'] = '';
$data['config_apiurlongkir'] = '';
$data['config_note_maintenance'] = '';
$data['config_maintenance'] = '';
$data['config_apisignature_cekmutasi'] = '';
$datavalue = array();
foreach ($setting as $st) {
	$key = $st['setting_key'];
	$value = $st['setting_value'];
	if ($key == 'config_grupcust' || $key == 'config_getpoincust' || $key == 'config_editorder') {
		$data["$key"]	= explode("::", $value);
	} else {
		$data["$key"] = $value;
	}
}
include "form.php";

if ($stsload != "load") include(DIR_INCLUDE . "footer.php");
