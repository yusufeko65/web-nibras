<?php
session_start();
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "atribut");
include "../../../includes/config.php";include "../../autoloader.php";
include path_to_language."indonesia.php";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
//$dtFungsi->cekHak('atribut','',0);

include DIR_INCLUDE."controller/controlAtribut.php";
$dtAtribut = new controllerAtribut();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtAtribut->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtAtribut->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtAtribut->hapusdata($_POST);
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."moduleweb.php");
  headerweb($judulweb);
}
$judul = "Atribut";
$nama = "";
$value = '';
$grup = 0;
$iddata = '';
$display = 'style=display:none';
$b = 1;
//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtAtribut->tampildata();
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
		//$dtFungsi->cekHak("atribut","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		//$dtFungsi->cekHak("atribut","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datastatus = $dtAtribut->dataAtributByID($iddata);
		if(!empty($datastatus)){
			$iddata = $datastatus['id_atribut'];
			$nama = $datastatus['nama_atribut'];
			$stswarna = $dtFungsi->fcaridata('_atribut_grup','warna','id_atribut_grup',$datastatus['id_atribut_grup']);
			$grup = $datastatus['id_atribut_grup'].'::'.$stswarna;
			if($stswarna == 1) $display = '';
			$value = $datastatus['value'];
			include "form.php";
		}
	break;
}
if($stsload!="load") footerweb(); 
?>