<?php
define("path_toincludes", "../../_includes/");
define("folder", "lap-in-booking");
include "../../../includes/config.php";
include "../../autoloader.php";
include "../../../includes/themes.php";
if (isset($_SESSION["masukadmin"]) != "xjklmnJk1o~" && isset($_SESSION["userlogin"]) == "") {
    echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
}

$dtFungsi = new FungsiUmum();
$u_token = isset($_SESSION['u_token']) ? $_SESSION['u_token'] : '';

$cekToken = $dtFungsi->cekTokenValid2();

if (!$cekToken) {
    session_destroy();
    echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
    exit;
}
include path_toincludes . "paging.php";

$dtFungsi->cekHak('lap-in-booking', '', 0);

$dtLapBooking = new controllerLapInBooking();

$menupage = isset($_GET["op"]) ? $_GET["op"] : "view";

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
    include DIR_INCLUDE . "header.php";
    include DIR_INCLUDE . "menu.php";
}
$judul = "In-Booking";
$user_nama = '';
$iddata = "";
$b = 1;

switch ($menupage) {
    case "view":
    default:
        $dtPaging = new Paging();
        $dataview = $dtLapBooking->tampildata();
        $total = $dataview['total'];
        $baris = $dataview['baris'];
        $page = $dataview['page'];
        $jmlpage = $dataview['jmlpage'];
        $ambildata = $dataview['rows'];
        $cari = isset($_GET['datacari']) ? $_GET['datacari'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $linkpage = '&u_token=' . $u_token;
        if ($cari != '') {
            $linkpage .= '&datacari=' . trim(strip_tags(urlencode($cari)));
        }

        if ($status != '' && $status != '0') {
            $linkpage .= '&status=' . trim(strip_tags(urlencode($status)));
        }

        include "view.php";
        break;
}
if ($stsload != "load") {
    include DIR_INCLUDE . "footer.php";
}
