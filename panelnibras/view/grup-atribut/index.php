<?php
session_start();
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "grup-atribut");
include "../../../includes/config.php";include "../../autoloader.php";
include path_to_language."indonesia.php";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
//$dtFungsi->cekHak('grup-atribut','',0);

include DIR_INCLUDE."controller/controlGrupAtribut.php";
$dtGrupAtribut = new controllerGrupAtribut();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtGrupAtribut->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtGrupAtribut->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtGrupAtribut->hapusdata($_POST);
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."moduleweb.php");
  headerweb($judulweb);
}
$judul = "Grup Atribut";
$grup_nama = "";
$warna = 0;
$iddata = "";
$b = 1;
//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtGrupAtribut->tampildata();
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
		//$dtFungsi->cekHak("grup-atribut","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		//$dtFungsi->cekHak("grup-atribut","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datastatus = $dtGrupAtribut->dataGrupAtributByID($iddata);
		if(!empty($datastatus)){
			$iddata    = $datastatus['id_atribut_grup'];
			$grup_nama = $datastatus['nama_atribut_grup'];
			$warna     = $datastatus['warna'];
			include "form.php";
		}
	break;
}
if($stsload!="load") footerweb(); 
?>