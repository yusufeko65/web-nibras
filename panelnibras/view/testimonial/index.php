<?php
define("path_toincludes", "../../_includes/");
define("folder", "testimonial");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('testimonial','',0);

$dtTestimonial = new controllerTestimonial();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
	case "tambah":
		echo $dtTestimonial->simpandata('simpan');
		exit;
	break;
	case "ubah":
		echo $dtTestimonial->simpandata('ubah');
		exit;
	break;
	case "hapus":
		echo $dtTestimonial->hapusdata();
		exit;
	break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = 'Testimonial';
$nama = '';
$email='';
$urlweb='';
$komentar = '';
$status = '1';
$iddata = '';
$b = 1;
$approve = '0';
//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtTestimonial->tampildata();
		$total 	  = $dataview['total'];
		$baris 	  = $dataview['baris'];
		$page 	  = $dataview['page'];
		$jmlpage  = $dataview['jmlpage'];
	    $ambildata= $dataview['rows'];
		$approve 	= isset($_GET['approve']) ? $_GET['approve']:'';
		$cari = isset($_GET['datacari']) ? $_GET['datacari']:'';
		$linkpage = '';
		if($cari!='') $linkpage = '&datacari='.trim(strip_tags($cari));
		if($approve!='') $linkpage .= '&approve='.trim(strip_tags(urlencode($approve)));
		include "view.php"; 
	break;
	case "add":
		$dtFungsi->cekHak("testimonial","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("testimonial","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datatestimonial = $dtTestimonial->dataTestimonialByID($iddata);
		if($datatestimonial){
			$iddata   = $datatestimonial['testimid'];
			$nama     = $datatestimonial['testim_nama'];
			$email    = $datatestimonial['testim_email'];
			$urlweb   = $datatestimonial['testim_url'];
			$komentar = $datatestimonial['testim_komen'];
			$status   = $datatestimonial['testim_status'];
			$approve  = $datatestimonial['testim_approve'];
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php");
?>