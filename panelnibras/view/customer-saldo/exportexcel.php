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
//foreach($reseller as $r){
//	$line = '';
//	foreach($r as $value) {
//		if ((!isset($value)) OR ($value == "")) {
//			$value = "\t";
//		} else {
//			$value = str_replace('"', '""', $value);
//		$value = '"' . $value . '"' . "\t";
//		}
//		$line .= $value;
//	}
//	$data .= trim($line)."\n";
//}
//$data = str_replace("\r","",$data);
//if ($data == "") {
//$data = "n(0) record found!\n";
//}
$tanggal=date("Ymd");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=Reseller_".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<style id="tes_6213_Styles">
<!--table
	{mso-displayed-decimal-separator:"\.";
	mso-displayed-thousand-separator:"\,";}
.xl156213
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl656213
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	border:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
-->
</style>
<div id="tes_6213" align=center x:publishsource="Excel">

<table border=0 cellpadding=0 cellspacing=0 width=557 style='border-collapse:
 collapse;table-layout:fixed;width:418pt'>
 <col width=64 style='mso-width-source:userset;mso-width-alt:2340;width:48pt'>
 <col width=65 style='mso-width-source:userset;mso-width-alt:2377;width:49pt'>
 <col width=155 style='mso-width-source:userset;mso-width-alt:5668;width:116pt'>
 <col width=227 style='mso-width-source:userset;mso-width-alt:8301;width:170pt'>
 <col width=171 style='mso-width-source:userset;mso-width-alt:6253;width:128pt'>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl156213 width=64 style='height:15.0pt;width:48pt'></td>
  <td class=xl156213 width=65 style='width:49pt'></td>
  <td class=xl156213 width=155 style='width:116pt'></td>
  <td class=xl156213 width=136 style='width:102pt'></td>
  <td class=xl156213 width=137 style='width:103pt'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl156213 style='height:15.0pt'></td>
  <td class=xl656213>Kode</td>
  <td class=xl656213 style='border-left:none'>Nama</td>
  <td class=xl656213 style='border-left:none'>Email</td>
  <td class=xl656213 style='border-left:none'>Grup</td>
 </tr>
  <?php foreach($reseller as $r){ ?>
  <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl156213 style='height:15.0pt'></td>
  <td class=xl656213 style='border-top:none'><?php echo $r['reseller_kode'] ?></td>
  <td class=xl656213 style='border-top:none;border-left:none'><?php echo $r['reseller_nama'] ?></td>
  <td class=xl656213 style='border-top:none;border-left:none'><span style='color:black;
  text-decoration:none'><?php echo $r['reseller_email'] ?></span></td>
  <td class=xl656213 style='border-top:none;border-left:none'><?php echo $r['reseller_grup'] ?></td>
 </tr>   
  <?php } ?>
</table>

</div>
