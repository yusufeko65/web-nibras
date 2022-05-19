<?php

define("path_toincludes", "../../_includes/");
define("folder", "shipping");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('shipping','',0);

$dtShipping = new controllerShipping();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:'';
//print_r($_POST);
//exit;
switch($aksipage){
	case "tambah":
		$dtShipping->simpandata('tambah');
		exit;
	break;
   
	case "ubah":
		$dtShipping->simpandata('ubah');
		
		exit;
	break;
	case "hapus":
		echo $dtShipping->hapusdata();
		exit;
	break;
	case "tambahservis":
		$dtShipping->simpanservis('tambah');
		exit;
	break;
	case "ubahservis":
		$dtShipping->simpanservis('ubah');
		exit;
	break;
	case "hapusservis":
		$dtShipping->hapusservis();
		exit;
	break;
	case "importservis":
		//echo json_encode(array("status"=>"success","result"=>"tes"));
		$dtShipping->importservis();
		exit;
	break;
  
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = "Shipping";

//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtShipping->tampildata();
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
		$dtFungsi->cekHak("shipping","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	
	case "edit": 
		$dtFungsi->cekHak("shipping","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datashipping = $dtShipping->datashippingByID($iddata);
		if(!empty($datashipping)){
			foreach($datashipping as $key=>$value){
				${$key} = $value;
			}
		
			include "form.php";
		}
	break;
	case "servis":
		$dtPaging = new Paging();
		$dataview = [];
		$total = 0;
		$baris = 0;
		$page = 0;
		$jmlpage = 0;
		$ambildata = [];
		$rajaongkir = 0;
		$kodeshipping = '';
		$pid = isset($_GET['pid']) ? $_GET['pid'] : 0;
		$datashipping = $dtShipping->datashippingByID($pid);
		
		if($datashipping){
			$judul = 'Servis Kurir: '.$datashipping ['shipping_nama'];
			$dataview = $dtShipping->getServisAllByKurir($pid);
			$total 	  = $dataview['total'];
			$baris 	  = $dataview['baris'];
			$page 	  = $dataview['page'];
			$jmlpage  = $dataview['jmlpage'];
			$ambildata= $dataview['rows'];
			$rajaongkir = isset($datashipping['shipping_rajaongkir']) ? $datashipping['shipping_rajaongkir'] : 0;
			$kodeshipping = $datashipping['shipping_kode'];
		} 
		$cari = isset($_GET['datacari']) ? $_GET['datacari']:'';
		$linkpage = '';
		if($cari!='') $linkpage = '&datacari='.trim(strip_tags($cari));
		include "view-servis.php"; 
	break;
	
	case "addservis":
		$dtFungsi->cekHak("shipping","add",0);
		$modul = "tambahservis"; 
		$servis_shipping = $_GET["pid"];
		include "form-servis.php"; 
	break;
	case "editservis": 
		$dtFungsi->cekHak("shipping","edit",0);
		$modul = "ubahservis"; $iddata = $_GET["pid"];
		$dataservis = $dtShipping->dataservisByID($iddata);
		$judul = 'Servis '.$dataservis['shipping_nama'];
		if(!empty($dataservis)){
			foreach($dataservis as $key=>$value){
				${$key} = $value;
			}
		
			include "form-servis.php";
		}
		
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php"); 
?>