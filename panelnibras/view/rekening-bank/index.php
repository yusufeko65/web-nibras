<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "rekening-bank");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('rekening-bank','',0);

$dtRekening = new controllerRekening();
$dtBank = new controllerBank();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtRekening->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtRekening->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtRekening->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = 'Rekening Bank';
$rekening_no = '';
$bank_id = 0;
$rekening_atasnama='';
$rekening_cabang='';
$iddata = '';
$b = 1;
$rekening_status = '1';
$databank = $dtBank->getBank();
//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtRekening->tampildata();
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
		$dtFungsi->cekHak("rekening-bank","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("rekening-bank","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datarekening = $dtRekening->dataRekeningByID($iddata);
		if(!empty($datarekening)){
			$iddata = $datarekening['rekening_id'];
			$bank_id = $datarekening['bank_id'];
			$rekening_no = $datarekening['rekening_no'];
			$rekening_atasnama = $datarekening['rekening_atasnama'];
			$rekening_cabang = $datarekening['rekening_cabang'];
			$rekening_status = $datarekening['rekening_status'];
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php"); 
?>