<?php
define("path_toincludes", "../../_includes/");
define("folder", "biaya-packing");
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

$dtFungsi->cekHak('biaya-packing', '', 0);


$dtSetting = new controllerSetting();

$menupage = isset($_GET["op"]) ? $_GET["op"] : "view";

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
  include(DIR_INCLUDE . "header.php");
  include(DIR_INCLUDE . "menu.php");
}
$judul = "Biaya Packing";
$user_nama = '';
$iddata = "";
$b = 1;
//Ini untuk tampilan
// $datagrup = $dtGrupUser->getGrupUser();
switch ($menupage) {
  case "view":
  default:
  $dtFungsi->cekHak("biaya-packing","view",0);
    // $input = isset($_POST['order_id']) && isset($_POST['submit']) ? $_POST['order_id'] : '';
    // if ($input != '') {
    //   $result = $dtPengiriman->editdata($input);
    // }
    $biaya_packing_value = isset($_POST['biaya_packing']) ? $_POST['biaya_packing'] : '';
    $setting_key =  $_POST['setting_key'] ? $_POST['setting_key'] : '';
    $status = '';
    if($biaya_packing_value != ''){
        $result = $dtSetting->setSettingByKey($setting_key, $biaya_packing_value);
        $status = $result ? 'success' : 'error';
    }
    $data  = $dtSetting->getSettingByKey("config_biayapacking");
    // $linkpage 	= '&u_token=' . $u_token;
    include "view.php";
    break;
}
if ($stsload != "load") include(DIR_INCLUDE . "footer.php");
