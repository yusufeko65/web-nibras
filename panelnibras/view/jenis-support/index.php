<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "jenis-support");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_to_language."indonesia.php";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('jenis-support','',0);



$dtJenisSupport = new controllerJenisSupport();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtJenisSupport->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtJenisSupport->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtJenisSupport->hapusdata($_POST);
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."moduleweb.php");
  headerweb($judulweb);
}
$judul = "JenisSupport";
$jenis_nama = "";
$link_sumber = "";
$tampil = 0;
$iddata = "";
$b = 1;
//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtJenisSupport->tampildata();
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
		$dtFungsi->cekHak("jenis-support","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("jenis-support","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datajenissupport = $dtJenisSupport->dataJenisSupportByID($iddata);
		if(!empty($datajenissupport)){
			$iddata = $datajenissupport['idjsupport'];
			$jenis_nama = $datajenissupport['jenis_support'];
			$link_sumber = $datajenissupport['link_sumber'];
			$tampil = $datajenissupport['tampil'];
			include "form.php";
		}
	break;
}
if($stsload!="load") footerweb(); 
?>