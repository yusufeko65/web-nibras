<?php
define("path_toincludes", "../../_includes/");
define("folder", "user");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('user','',0);

$dtUser = new controllerUser();
$dtGrupUser = new controllerGrupUser();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtUser->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtUser->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtUser->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = "User";
$user_nama = '';
$iddata = "";
$b = 1;
//Ini untuk tampilan
$datagrup = $dtGrupUser->getGrupUser();
switch($menupage){
	case "view": default:
	    $dtPaging  = new Paging();
		$dataview  = $dtUser->tampildata();
		$total 	   = $dataview['total'];
		$baris 	   = $dataview['baris'];
		$page 	   = $dataview['page'];
		$jmlpage   = $dataview['jmlpage'];
	    $ambildata = $dataview['rows'];
		$cari = isset($_GET['datacari']) ? $_GET['datacari']:'';
		$linkpage = '';
		if($cari != '') $linkpage .= '&datacari='.trim(strip_tags($cari));
		include "view.php"; 
	break;
	case "add":
		$dtFungsi->cekHak("user","add",0);
		$modul = "tambah";
		$lock = '';
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("user","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datauser = $dtUser->dataUserByID($iddata);
		if(!empty($datauser)){
		    $lock = 'disabled';
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php");
?>