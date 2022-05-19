<?php
define("path_toincludes", "../../_includes/");
define("folder", "informasi");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('informasi','',0);

$dtInformasi = new controllerInformasi();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtInformasi->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtInformasi->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtInformasi->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = "Informasi";
$judulinfo = '';
$keterangan = '';
$aliasurl = '';
$status = 1;
$headline = 0;
$menuatas = 0;
$iddata = "";
$b = 1;
//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtInformasi->tampildata();
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
		$dtFungsi->cekHak("informasi","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("informasi","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datainformasi = $dtInformasi->dataInformasiByID($iddata);
		if($datainformasi){
			$iddata = $datainformasi['id_info'];
			$judulinfo = $datainformasi['info_judul'];
			$keterangan = $datainformasi['info_detail'];
			$aliasurl = $datainformasi['aliasurl'];
			$status = $datainformasi['sts_info'];
			
			include "form.php";
		}
	break;
}

if($stsload!="load") include(DIR_INCLUDE."footer.php");
?>