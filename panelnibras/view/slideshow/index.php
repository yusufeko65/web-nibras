<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "slideshow");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('slideshow','',0);

$dtSlide = new controllerSlideShow();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtSlide->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtSlide->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtSlide->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = "Slideshow";
$slide_nama = '';
$slide_gbr ='';
$panjang = '';
$lebar   = '';
$url_link = '';
$slide_status = '';
$slot = '';
$iddata = "";
$urutan = '';
$b = 1;
//Ini untuk tampilan

switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtSlide->tampildata();
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
		$dtFungsi->cekHak("slideshow","add",0);
		$modul = "tambah"; 
		$readonly = '';
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("slideshow","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$dataSlide = $dtSlide->dataSlideShowByID($iddata);
		if($dataSlide){
			$iddata = $dataSlide['id_slide'];
			$slide_nama = $dataSlide['nama_slide'];
			$slide_gbr = $dataSlide['gbr_slide'];
			$panjang = $dataSlide['panjang'];
			$lebar   = $dataSlide['lebar'];
			$url_link = $dataSlide['link_slide'];
			$slide_status = $dataSlide['sts_slide'];
			$urutan = $dataSlide['urutan'];
			$readonly = 'disabled';
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php");
?>