<?php
define("path_toincludes", "../../_includes/");
define("folder", "resi");
include "../../../includes/config.php";
include "../../autoloader.php";
include "../../../includes/themes.php";
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

$dtFungsi->cekHak('resi', '', 0);


$dtResi = new controllerResi();

$menupage = isset($_GET["op"]) ? $_GET["op"] : "view";

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
  include(DIR_INCLUDE . "header.php");
  include(DIR_INCLUDE . "menu.php");
}
$judul = "Input Resi";
$user_nama = '';
$iddata = "";
$b = 1;

switch ($menupage) {
  case "view":
  default:
    $dtPaging  = new Paging();
    $result = null;
    $no_resi = isset($_POST['resi']) && isset($_POST['submitInputResi']) ? $_POST['resi'] : '';
    $order_id = isset($_POST['order_id']) && isset($_POST['submitInputResi']) ? $_POST['order_id'] : '';
    if ($no_resi != '') {
      $result = $dtResi->editResi($order_id,$no_resi);
    }
    $dataview  = $dtResi->tampildataresi();
    $total      = $dataview['total'];
    $baris      = $dataview['baris'];
    $page      = $dataview['page'];
    $jmlpage   = $dataview['jmlpage'];
    $ambildata = $dataview['rows'];
    $list_kurir = $dataview['list_kurir'];
    $cari = isset($_GET['datacari']) ? $_GET['datacari'] : '';
    $kurir_cari = isset($_GET['kurir']) ? $_GET['kurir'] : '';
    $linkpage 	= '&u_token=' . $u_token;
    if ($cari != '') $linkpage .= '&datacari=' . trim(strip_tags(urlencode($cari)));
    if ($kurir_cari != '' && $kurir_cari != '0') $linkpage .= '&kurir=' . trim(strip_tags(urlencode($kurir_cari)));
    include "view.php";
    break;
}
if ($stsload != "load") include(DIR_INCLUDE . "footer.php");
