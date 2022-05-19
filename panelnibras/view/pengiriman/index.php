<?php
define("path_toincludes", "../../_includes/");
define("folder", "pengiriman");
include "../../../includes/config.php";
include "../../autoloader.php";
include "../../../includes/themes.php";
// if (isset($_SESSION["masukadmin"]) != "xjklmnJk1o~" && isset($_SESSION["userlogin"]) == "") echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
$dtFungsi = new FungsiUmum();
$u_token = isset($_SESSION['u_token']) ? $_SESSION['u_token'] : '';

$cekToken = $dtFungsi->cekTokenValid2();

if (!$cekToken) {
	session_destroy();
	echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
	exit;
}
include path_toincludes . "paging.php";

$dtFungsi->cekHak('pengiriman', '', 0);


$dtPengiriman = new controllerPengiriman();
// $dtGrupUser = new controllerGrupUser();

$menupage = isset($_GET["op"]) ? $_GET["op"] : "view";

// ini untuk aksi
// $aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
// switch($aksipage){
//    case "tambah":
// 		echo $dtUser->simpandata('simpan');
// 		exit;
//    break;
//    case "ubah":
// 		echo $dtUser->simpandata('ubah');
// 		exit;
//    break;
//    case "hapus":
// 		echo $dtUser->hapusdata();
// 		exit;
//    break;
// }

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
  include(DIR_INCLUDE . "header.php");
  include(DIR_INCLUDE . "menu.php");
}
$judul = "Pengiriman";
$user_nama = '';
$iddata = "";
$b = 1;
//Ini untuk tampilan
// $datagrup = $dtGrupUser->getGrupUser();
switch ($menupage) {
  case "view":
  default:
    $dtPaging  = new Paging();
    $result = null;
    $input = isset($_POST['order_id']) && isset($_POST['submit']) ? $_POST['order_id'] : '';
    if ($input != '') {
      $result = $dtPengiriman->editdata($input);
    }
    $dataview  = $dtPengiriman->tampildata();
    $total      = $dataview['total'];
    $baris      = $dataview['baris'];
    $page      = $dataview['page'];
    $jmlpage   = $dataview['jmlpage'];
    $ambildata = $dataview['rows'];
    $list_kurir = $dataview['list_kurir'];
    $cari = isset($_GET['datacari']) ? $_GET['datacari'] : '';
    $kurir_cari = isset($_GET['kurir']) ? $_GET['kurir'] : '';
    $linkpage 	= '&u_token=' . $u_token;
    if ($cari != '') $linkpage .= '&datacari=' . trim(strip_tags($cari));
    if ($kurir_cari != '') $linkpage .= '&kurir=' . trim(strip_tags($kurir_cari));
    include "view.php";
    break;
    // case "add":
    // 	$dtFungsi->cekHak("user","add",0);
    // 	$modul = "tambah";
    // 	$lock = '';
    // 	include "form.php"; 
    // break;
    // case "edit": 
    // 	$dtFungsi->cekHak("user","edit",0);
    // 	$modul = "ubah"; $iddata = $_GET["pid"];
    // 	$datauser = $dtUser->dataUserByID($iddata);
    // 	if(!empty($datauser)){
    // 	    $lock = 'disabled';
    // 		include "form.php";
    // 	}
    // break;
}
if ($stsload != "load") include(DIR_INCLUDE . "footer.php");
