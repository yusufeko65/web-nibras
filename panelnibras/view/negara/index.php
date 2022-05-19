<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "negara");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('negara','',0);


$dtNegara = new controllerNegara();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtNegara->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtNegara->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtNegara->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = "Negara";
$negara_nama = "";
$iddata = "";
$b = 1;
//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtNegara->tampildata();
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
		$dtFungsi->cekHak("negara","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("negara","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datanegara = $dtNegara->dataNegaraByID($iddata);
		if(!empty($datanegara)){
			$iddata = $datanegara['negara_id'];
			$negara_nama = $datanegara['negara_nama'];
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php");; 

?>