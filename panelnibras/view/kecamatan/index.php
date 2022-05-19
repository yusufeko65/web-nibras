<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "kecamatan");
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
$dtFungsi->cekHak('kecamatan', '', 0);




$dtKecamatan = new controllerKecamatan();
$dtKabupaten = new controllerKabupaten();
$dtPropinsi = new controllerPropinsi();

if (isset($_GET['load'])) {
	if ($_GET['load'] == 'kabupaten') {
		$id = isset($_GET['propinsi']) ? $_GET['propinsi'] : '';
		$opt = "<select id=\"kabupaten\" name=\"kabupaten\" class=\"form-control\"><option value=\"0\">- Kotamadya/Kabupaten -</option>";

		if ($id != '' && $id != '0') {
			$kota      = $dtKabupaten->getKabupatenByPropinsi($id);
			foreach ($kota as $kot) {
				$opt .= "<option value='" . $kot['idk'] . "'>" . $kot['nmk'] . "</option>";
			}
		}
		$opt .= "</select>";
		echo $opt;
		exit;
	}
}
$menupage = isset($_GET["op"]) ? $_GET["op"] : "view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"]) ? $_POST["aksi"] : "";
switch ($aksipage) {
	case "tambah":
		echo $dtKecamatan->simpandata('simpan');
		exit;
		break;
	case "ubah":
		echo $dtKecamatan->simpandata('ubah');
		exit;
		break;
	case "hapus":
		echo $dtKecamatan->hapusdata();
		exit;
		break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
	include(DIR_INCLUDE . "header.php");
	include(DIR_INCLUDE . "menu.php");
}
$judul = "Kecamatan";
$kecamatan_nama = '';
$iddata = "";
$idkabupaten = "";
$idpropinsi = "";
$b = 1;
$prop      = $dtPropinsi->getPropinsi();

//Ini untuk tampilan

switch ($menupage) {
	case "view":
	default:
		$dtPaging = new Paging();
		$dataview = $dtKecamatan->tampildata();
		$total 	  = $dataview['total'];
		$baris 	  = $dataview['baris'];
		$page 	  = $dataview['page'];
		$jmlpage  = $dataview['jmlpage'];
		$ambildata = $dataview['rows'];
		$cari = isset($_GET['datacari']) ? $_GET['datacari'] : '';
		$propinsi = isset($_GET['propinsi']) ? $_GET['propinsi'] : '';
		$kabupaten = isset($_GET['kabupaten']) ? $_GET['kabupaten'] : '';
		$linkpage 	= '&u_token=' . $u_token;
		if ($propinsi != '') $linkpage = '&propinsi=' . trim(strip_tags($propinsi));
		if ($kabupaten != '') $linkpage .= '&kabupaten=' . trim(strip_tags($kabupaten));
		if ($cari != '') $linkpage .= '&datacari=' . trim(strip_tags($cari));
		//echo $linkpage;
		include "view.php";
		break;
	case "add":
		$dtFungsi->cekHak("kecamatan", "add", 0);
		$modul = "tambah";
		include "form.php";
		break;
	case "edit":
		$dtFungsi->cekHak("kecamatan", "edit", 0);
		$modul = "ubah";
		$iddata = $_GET["pid"];
		$datakecamatan = $dtKecamatan->dataKecamatanByID($iddata);
		if (!empty($datakecamatan)) {
			$iddata = $datakecamatan['kecamatan_id'];
			$kecamatan_nama = $datakecamatan['kecamatan_nama'];
			$idkabupaten = $datakecamatan['kabupaten_id'];
			$idpropinsi = $datakecamatan['provinsi_id'];
			include "form.php";
		}
		break;
}
if ($stsload != "load") include(DIR_INCLUDE . "footer.php");
