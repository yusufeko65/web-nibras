<?php
define("path_toincludes", "../../_includes/");
define("folder", "grup-user");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('grup-user','',0);

$dtGrupUser = new controllerGrupUser();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtGrupUser->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtGrupUser->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtGrupUser->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = "Grup User";
$iddata = "";
$b = 1;
$hakakses = array();
//Ini untuk tampilan
$menunya = $dtGrupUser->getMenu();
$urut=0;
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtGrupUser->tampildata();
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
		$dtFungsi->cekHak("grup-user","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("grup-user","edit",0);
		$modul = "ubah"; $iddata = isset($_GET["pid"]) ? urlencode($_GET["pid"]):0;
		$datagrup = $dtGrupUser->dataGrupUserByID($iddata);
		if($datagrup){
			$iddata 	= $datagrup['lg_id'];
			$grup_nama 	= $datagrup['lg_nama'];
			$grup_ket	= $datagrup['lg_desc'];
			$hakakses 	= $dtGrupUser->dataHakAkses($iddata);
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php"); 
?>