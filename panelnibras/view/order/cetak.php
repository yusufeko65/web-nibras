<?php
/*
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "order");

include "../../../includes/config.php";
include "../../autoloader.php";
*/
require_once '../../pdf/pdftable/lib/pdftable.inc.php';

$model= new modelOrder();
$Fungsi= new FungsiUmum();

$pdf = new PDFTable();
$pdf->AddPage("P","A4");
$pdf->defaultFontFamily = 'Arial';
$pdf->defaultFontStyle  = '';
$pdf->defaultFontSize   = 7;
$pdf->SetFont($pdf->defaultFontFamily, $pdf->defaultFontStyle, $pdf->defaultFontSize);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->SetLineWidth(0.2);
$width = $pdf->w - $pdf->lMargin-$pdf->rMargin;

$tabelheader = '<table width="100%"><tr><td align="center" valign="middle"><font style="bold" size="15">Nama Toko</font></td></tr>';
$tabelheader .= "<tr><td align=\"center\" valign=\"middle\"><font size=\"8\">Alamat Toko</font></td></tr>";
$tabelheader .= "<tr><td align=\"center\" valign=\"middle\"><font size=\"8\">Alamat Situs</font></td></tr><tr></table>";
$pdf->Line($x,42,$x+$width,42);
$pdf->htmltable($tabelheader);
$tabelheader2 = "<table width=\"100%\">";
$tabelheader2 .= "<tr><td valign=\"middle\" width=\"20%\"><font style=\"bold\">No Order</font></td>";
$tabelheader2 .= "<td valign=\"middle\">: #".sprintf('%08s', '1')."</td>";
$tabelheader2 .= "</tr><tr><td valign=\"middle\" ><font style=\"bold\">Tgl Order</font></td>";
$tabelheader2 .= "<td valign=\"middle\">: 29-10-2019</td>";
$tabelheader2 .= "</tr>
		   <tr>
			 <td valign=\"middle\"><font style=\"bold\">Pelanggan</font></td>
			 <td valign=\"middle\">: tess ( adadada )</td>
		   </tr></table>";
$pdf->htmltable($tabelheader2);
$pdf->Line($x,62,$x+$width,62);
$pdf->setFont('Arial','B',9);
$pdf->Text($x,70,"Alamat Pengirim");
$pdf->Text(111,70,"Alamat Penerima");
$pdf->setFont('Arial','',7);
$nama_report = 'invoice00009.pdf';
$pdf->output($nama_report,"I");