<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "kabupaten");
include "../../../includes/config.php";
include "../../autoloader.php";
if (isset($_SESSION["masukadmin"]) != "xjklmnJk1o~" && isset($_SESSION["userlogin"]) == "") echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
$dtFungsi = new FungsiUmum();
$u_token = isset($_SESSION['u_token']) ? $_SESSION['u_token'] : '';
$cekToken = $dtFungsi->cekTokenValid2();

if (!$cekToken) {
	session_destroy();
	echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
	exit;
}
include path_toincludes . "paging.php";

$dtFungsi->cekHak('kabupaten', '', 0);

$dtKabupaten = new controllerKabupaten();
$dtPropinsi = new controllerPropinsi();
$menupage = isset($_GET["op"]) ? $_GET["op"] : "view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"]) ? $_POST["aksi"] : "";
switch ($aksipage) {
	case "tambah":
		echo $dtKabupaten->simpandata('simpan');
		exit;
		break;
	case "ubah":
		echo $dtKabupaten->simpandata('ubah');
		exit;
		break;
	case "hapus":
		echo $dtKabupaten->hapusdata();
		exit;
		break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
	include(DIR_INCLUDE . "header.php");
	include(DIR_INCLUDE . "menu.php");
}
$judul = "Kotamadya / Kabupaten";
$kabupaten_nama = '';
$iddata = "";
$idpropinsi = "";
$b = 1;
$prop      = $dtPropinsi->getPropinsi();
//Ini untuk tampilan

switch ($menupage) {
	case "view":
	default:
		$dtPaging  = new Paging();
		$dataview  = $dtKabupaten->tampildata();
		$total 	   = $dataview['total'];
		$baris 	   = $dataview['baris'];
		$page 	   = $dataview['page'];
		$jmlpage   = $dataview['jmlpage'];
		$ambildata = $dataview['rows'];
		$cari      = isset($_GET['datacari']) ? $_GET['datacari'] : '';
		$propinsi  = isset($_GET['propinsi']) ? $_GET['propinsi'] : '';
		$linkpage 	= '&u_token=' . $u_token;

		if ($propinsi != '') $linkpage = '&propinsi=' . trim(strip_tags($propinsi));
		if ($cari != '') $linkpage .= '&datacari=' . trim(strip_tags($cari));
		include "view.php";
		break;
	case "add":
		$dtFungsi->cekHak("kabupaten", "add", 0);
		$modul = "tambah";
		include "form.php";
		break;
	case "edit":
		$dtFungsi->cekHak("kabupaten", "edit", 0);
		$modul = "ubah";
		$iddata = $_GET["pid"];
		$datakabupaten = $dtKabupaten->dataKabupatenByID($iddata);
		if (!empty($datakabupaten)) {
			$iddata = $datakabupaten['kabupaten_id'];
			$kabupaten_nama = $datakabupaten['kabupaten_nama'];
			$idpropinsi = $datakabupaten['provinsi_id'];
			include "form.php";
		}
		break;
}
if ($stsload != "load") include(DIR_INCLUDE . "footer.php");
