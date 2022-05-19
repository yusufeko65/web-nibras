<?php

define("path_toincludes", "../../_includes/");
define("folder", "ukuran");
include "../../../includes/config.php";
include "../../autoloader.php";
if (isset($_SESSION["masukadmin"]) != "xjklmnJk1o~" && isset($_SESSION["userlogin"]) == "") echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
include path_toincludes . "paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('ukuran', '', 0);

$dtUkuran = new controllerUkuran();

$menupage = isset($_GET["op"]) ? $_GET["op"] : "view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"]) ? $_POST["aksi"] : "";
switch ($aksipage) {
	case "tambah":
		echo $dtUkuran->simpandata('simpan');
		exit;
		break;
	case "ubah":
		echo $dtUkuran->simpandata('ubah');
		exit;
		break;
	case "hapus":
		echo $dtUkuran->hapusdata();
		exit;
		break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
	include(DIR_INCLUDE . "header.php");
	include(DIR_INCLUDE . "menu.php");
}
$judul = "Ukuran";
$ukuran_nama = "";
$ukuran_alias = "";
$iddata = "";
$urutan = 0;
$b = 1;
//Ini untuk tampilan

switch ($menupage) {
	
	case "view":
	default:
		$dtPaging = new Paging();
		$dataview = $dtUkuran->tampildata();
		$total 	  = $dataview['total'];
		$baris 	  = $dataview['baris'];
		$page 	  = $dataview['page'];
		$jmlpage  = $dataview['jmlpage'];
		$ambildata = $dataview['rows'];
		$cari = isset($_GET['datacari']) ? $_GET['datacari'] : '';
		$linkpage = '';
		if ($cari != '') $linkpage = '&datacari=' . trim(strip_tags($cari));
		include "view.php";
		break;
	case "add":
		$dtFungsi->cekHak("ukuran", "add", 0);
		$modul = "tambah";
		include "form.php";
		break;
	case "edit":
		$dtFungsi->cekHak("ukuran", "edit", 0);
		$modul = "ubah";
		$iddata = $_GET["pid"];
		$dataukuran = $dtUkuran->dataUkuranByID($iddata);
		if (!empty($dataukuran)) {
			$iddata = $dataukuran['idukuran'];
			$ukuran_nama = $dataukuran['ukuran'];
			$ukuran_alias = $dataukuran['alias'];
			$ukuran_urutan = $dataukuran['order_by'];
			include "form.php";
		}
		break;
}
if ($stsload != "load") include(DIR_INCLUDE . "footer.php");
 