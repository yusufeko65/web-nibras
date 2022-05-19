<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "bank");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('bank','',0);

$dtBank = new controllerBank();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		//echo 'tes';
		echo $dtBank->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtBank->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtBank->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = "Bank";
$bank_nama = "";
$bank_logo = "";
$bank_status = "";
$iddata = "";
$b = 1;
//Ini untuk tampilan

switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtBank->tampildata();
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
		$dtFungsi->cekHak("bank","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("bank","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$databank = $dtBank->dataBankByID($iddata);
		if(!empty($databank)){
			$iddata = $databank['bank_id'];
			$bank_nama = $databank['bank_nama'];
			$bank_logo = $databank['bank_logo'];
			$bank_status = $databank['bank_status'];
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php"); 
?>