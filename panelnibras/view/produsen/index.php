<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "produsen");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_to_language."indonesia.php";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('produsen','',0);

include DIR_INCLUDE."controller/controlProdusen.php";
$dtProdusen = new controllerProdusen();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		//echo 'tes';
		echo $dtProdusen->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtProdusen->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtProdusen->hapusdata();
		exit;
   break;
   case "delgbr":
		echo $dtProdusen->hapusgambar();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE.'moduleweb.php');
  headerweb($judulweb);
}
$judul = 'Produsen';
$produsen_nama = '';
$produsen_logo = '';
$produsen_telp = '';
$produsen_email = '';
$produsen_alamat = '';
$produsen_keterangan = '';
$produsen_web = '';
$produsen_fb = '';
$iddata = '';
$b = 1;
//Ini untuk tampilan

switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtProdusen->tampildata();
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
		$dtFungsi->cekHak("produsen","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("produsen","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$dataprodusen = $dtProdusen->dataProdusenByID($iddata);
		if(!empty($dataprodusen)){
			$iddata 				= $dataprodusen['produsen_id'];
			$produsen_nama 			= $dataprodusen['produsen_nama'];
			$produsen_logo 			= $dataprodusen['produsen_logo'];
			$produsen_telp 			= $dataprodusen['produsen_telp'];
			$produsen_email 		= $dataprodusen['produsen_email'];
			$produsen_alamat 		= $dataprodusen['produsen_alamat'];
			$produsen_keterangan 	= $dataprodusen['produsen_keterangan'];
			$produsen_web 			= $dataprodusen['produsen_web'];
			$produsen_fb 			= $dataprodusen['produsen_facebook'];
			$produsen_kapasitas 	= $dataprodusen['produsen_kapasitas'];
			$produsen_grosir	 	= $dataprodusen['produsen_grosir'];
			$produsen_alias		 	= $dataprodusen['produsen_alias'];
			include "form.php";
		}
	break;
}
if($stsload!="load") footerweb();