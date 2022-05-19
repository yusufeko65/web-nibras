<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "customer-grup");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak(folder,'',0);


$dtResellerGrup = new controllerCustomerGrup();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtResellerGrup->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtResellerGrup->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtResellerGrup->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = 'Grup Pelanggan';
$grup_nama = '';
$min_beli = 0;
$total_awal = '';
$min_beli = 0;
$keterangan = '';
$minbeli_syarat = '1';
$chk_wjb = '1';
$approval = '0';
$urutan = 0;
$iddata = '';
$chk_deposito = '0';
$diskon = 0;
$chk_dropship = 0;
//Ini untuk tampilan
 
switch($menupage){
   
	case "view": default:
	    $dtFungsi->cekHak("customer-grup","view",0);
	    $dtPaging 	= new Paging();
		$dataview 	= $dtResellerGrup->tampildata();
		$total 	  	= $dataview['total'];
		$baris 	  	= $dataview['baris'];
		$page 	  	= $dataview['page'];
		$jmlpage  	= $dataview['jmlpage'];
	    $ambildata	= $dataview['rows'];
		$cari 		= isset($_GET['datacari']) ? $_GET['datacari']:'';
		$linkpage 	= '';
		if($cari!='') $linkpage = '&datacari='.trim(strip_tags($cari));
		include "view.php"; 
	break;
	
	case "add":
		$dtFungsi->cekHak("customer-grup","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("customer-grup","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$dataresellergrup = $dtResellerGrup->dataResellerGrupByID($iddata);
		if(!empty($dataresellergrup)){
			$iddata     	= $dataresellergrup['cg_id'];
			$grup_nama  	= $dataresellergrup['cg_nm'];
			$keterangan 	= $dataresellergrup['cg_ket'];
			$total_awal 	= $dataresellergrup['cg_total_awal'];
			$min_beli   	= $dataresellergrup['cg_min_beli'];
			$minbeli_syarat = $dataresellergrup['cg_min_beli_syarat'];
			$chk_wjb 		= $dataresellergrup['cg_min_beli_wajib'];
			$urutan 		= $dataresellergrup['cg_urutan'];
			$chk_deposito 	= $dataresellergrup['cg_deposito'];
			$diskon 		= $dataresellergrup['cg_diskon'];
			$chk_dropship 	= $dataresellergrup['cg_dropship'];
			$biaya_packing = $dataresellergrup['cg_biaya_packing'];
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php");
?>