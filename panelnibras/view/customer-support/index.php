<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "customer-support");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('customer-support','',0);


$dtCustomerSupport = new controllerCustomerSupport();
$dtJSupport = new controllerJenisSupport();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtCustomerSupport->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtCustomerSupport->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtCustomerSupport->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = 'Customer Support';
$cs_nama = '';
$cs_jsupport = 0;
$cs_akun='';
$cs_status='';
$iddata = '';
$b = 1;
//Ini untuk tampilan
$jenissupport = $dtJSupport->getJenisSupport();
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtCustomerSupport->tampildata();
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
		$dtFungsi->cekHak("customer-support","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("customer-support","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datarekening = $dtCustomerSupport->dataCustomerSupportByID($iddata);
		if($datarekening){
			$iddata = $datarekening['idsupport'];
			$cs_nama = $datarekening['cs_nama'];
			$cs_jsupport = $datarekening['cs_jsupport'];
			$cs_akun = $datarekening['cs_akun'];
			$cs_status = $datarekening['cs_status'];
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php"); 
?>