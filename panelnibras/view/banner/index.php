<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "banner");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_to_language."indonesia.php";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
//$dtFungsi->cekHak('banner','',0);

include DIR_INCLUDE."controller/controlBanner.php";
$dtBanner = new controllerBanner();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		//echo 'tes';
		echo $dtBanner->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtBanner->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtBanner->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."moduleweb.php");
  headerweb($judulweb);
}
$judul = "Banner";
$banner_nama = '';
$banner_gbr ='';
$panjang = '';
$lebar   = '';
$url_link = '';
$banner_status = '';
$slot = '';
$iddata = "";
$b = 1;
//Ini untuk tampilan

switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtBanner->tampildata();
		$total 	  = $dataview['total'];
		$baris 	  = $dataview['baris'];
		$page 	  = $dataview['page'];
		$jmlpage  = $dataview['jmlpage'];
	    $ambildata= $dataview['rows'];
		$cari = isset($_GET['datacari']) ? $_GET['datacari']:'';
		$linkpage = '';
		if($cari!='') $linkpage = '&datacari='.trim(strip_tags($cari));
		include "view.php"; 
	break;
	case "add":
		//$dtFungsi->cekHak("banner","add",0);
		$modul = "tambah"; 
		$readonly = '';
		include "form.php"; 
	break;
	case "edit": 
		//$dtFungsi->cekHak("banner","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$dataBanner = $dtBanner->dataBannerByID($iddata);
		if(!empty($dataBanner)){
			$iddata = $dataBanner['idbanner'];
			$banner_nama = $dataBanner['nama_banner'];
			$banner_gbr = $dataBanner['gbr_banner'];
			$panjang = $dataBanner['panjang_banner'];
			$lebar   = $dataBanner['lebar_banner'];
			$url_link = $dataBanner['link_banner'];
			$banner_status = $dataBanner['tampil'];
			$slot = $dataBanner['slot_banner'];
			$readonly = 'disabled';
			include "form.php";
		}
	break;
}
if($stsload!="load") footerweb(); 
?>