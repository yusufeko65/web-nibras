<?php
include "../../includes/config.php";

include "../../controller/controlReseller.php";
$dtReseller = new controllerReseller();

include DIR_INCLUDE."controller/controlSettingToko.php";
include DIR_INCLUDE."controller/controlResellerGrup.php";
$dtSettingToko  = new controllerSettingToko();
$dtResellerGrup = new controllerResellerGrup();

$datasetting      = $dtSettingToko->getSettingToko();
$masa_approve     = $datasetting['masa_approve'];
$reseller_premium = $datasetting['reseller_bayar'];
$getGrupReseller   = $dtResellerGrup->dataResellerGrupByID($reseller_premium);
$grpResellertargetmasaaktif = $getGrupReseller['rs_targetmasaaktif'];
$grpResellertargetperbulan  = $getGrupReseller['rs_target'];
$grpResellermasaaktifreseller = $getGrupReseller['rs_masaaktifanggota'];


//var_dump(is_dir('/home/sloki/user/k2041548/sites/hijabsupplier.com/www/controlarea/view/reseller/'));

$dtReseller->ResellerEksekusi($masa_approve);
//$dtReseller->ResellerEksekusiMasaAktif($grpResellermasaaktifreseller,$reseller_premium);


?>