<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "status-order");
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

$dtFungsi->cekHak('status-order', '', 0);

$dtOrderStatus = new controllerOrderStatus();

$menupage = isset($_GET["op"]) ? $_GET["op"] : "view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"]) ? $_POST["aksi"] : "";
switch ($aksipage) {
	case "tambah":
		echo $dtOrderStatus->simpandata('simpan');
		exit;
		break;
	case "ubah":
		echo $dtOrderStatus->simpandata('ubah');
		exit;
		break;
	case "hapus":
		echo $dtOrderStatus->hapusdata();
		exit;
		break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
	include(DIR_INCLUDE . "header.php");
	include(DIR_INCLUDE . "menu.php");
}
$judul = "Status Order";
$status_nama = "";
$status_keterangan = "";
$iddata = "";
$b = 1;
//Ini untuk tampilan

switch ($menupage) {
	case "view":
	default:
		$dtPaging = new Paging();
		$dataview = $dtOrderStatus->tampildata();
		$total 	  = $dataview['total'];
		$baris 	  = $dataview['baris'];
		$page 	  = $dataview['page'];
		$jmlpage  = $dataview['jmlpage'];
		$ambildata = $dataview['rows'];
		$cari = isset($_GET['datacari']) ? $_GET['datacari'] : '';
		$linkpage 	= '&u_token=' . $u_token;
		if ($cari != '') $linkpage = '&datacari=' . trim(strip_tags($cari));
		include "view.php";
		break;
	case "add":
		$dtFungsi->cekHak("status-order", "add", 0);
		$modul = "tambah";
		include "form.php";
		break;
	case "edit":
		$dtFungsi->cekHak("status-order", "edit", 0);
		$modul = "ubah";
		$iddata = $_GET["pid"];
		$datastatus = $dtOrderStatus->dataOrderStatusByID($iddata);

		if (!empty($datastatus)) {
			$iddata = $datastatus['status_id'];
			$status_nama        = $datastatus['status_nama'];
			$status_keterangan  = $datastatus['status_keterangan'];
			include "form.php";
		}
		break;
}
if ($stsload != "load") include(DIR_INCLUDE . "footer.php");
