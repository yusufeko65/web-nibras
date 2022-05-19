<?php
session_start();
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "reseller");
include "../../../includes/config.php";include "../../autoloader.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('reseller','',0);

include DIR_INCLUDE."controller/controlReseller.php";
$dtReseller = new controllerReseller();

$reseller = $dtReseller->getResellerExport();
$header = '';
$header .= "Kode \t";
$header .= "Nama \t";
$header .= "Email \t";
$header .= "Grup \t";
//$header .= "Tgl Upgrade \t";
$data = '';
foreach($reseller as $r){
	$line = '';
	foreach($r as $value) {
		if ((!isset($value)) OR ($value == "")) {
			$value = "\t";
		} else {
			$value = str_replace('"', '""', $value);
		$value = '"' . $value . '"' . "\t";
		}
		$line .= $value;
	}
	$data .= trim($line)."\n";
}
$data = str_replace("\r","",$data);
if ($data == "") {
$data = "n(0) record found!\n";
}
$tanggal=date("Ymd");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=Reseller_".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$data";
?>