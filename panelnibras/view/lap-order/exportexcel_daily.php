<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "lap-order");
include "../../../includes/config.php";
include "../../autoloader.php";
include path_toincludes . "PHPExcel.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('lap-order', '', 0);


$dtLapOrder = new controllerLapOrder();

$dataview 	= $dtLapOrder->tampilOrderDaily();

$tanggal1 	= isset($_GET['tanggal']) ? $_GET['tanggal1'] : date('Y-m-d');
$tanggal2 	= isset($_GET['tanggal']) ? $_GET['tanggal2'] : date('Y-m-d');
$status		= isset($_GET['status']) ? $_GET['status'] : '';
$customer_id 	= isset($_GET['customer_id']) ? $_GET['customer_id'] : '';
$tanggal = date("Ymd");

// Create new PHPExcel object
$object = new PHPExcel();

// Add some data
$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$object->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$object->getActiveSheet()->mergeCells('A1:H1');
$object->getActiveSheet()->mergeCells('A2:H2');
$object->getActiveSheet()->mergeCells('A3:H3');

$object->setActiveSheetIndex(0)
	->setCellValue('A1', 'Laporan Order Nibras.com')
	->setCellValue('A2', 'Periode ' . $tanggal)
	->setCellValue('A4', 'No')
	->setCellValue('B4', 'Tgl')
	->setCellValue('C4', 'Tgl')
	->setCellValue('D4', 'Order ID')
	->setCellValue('E4', 'Pelanggan')
	->setCellValue('F4', 'Status')
	->setCellValue('G4', 'Jumlah')
	->setCellValue('H4', 'Total')
	->setCellValue('I4', 'Total+Ongkir');

$sharedStyle1 = new PHPExcel_Style();
$sharedStyle2 = new PHPExcel_Style();
$sharedStyle1->applyFromArray(
	array(
		'fill' 	=> array(
			'type'    => PHPExcel_Style_Fill::FILL_SOLID,
			'color'   => array('argb' => 'FFCCFFCC')
		),
		'borders' => array(
			'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
			'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		)
	)
);
$sharedStyle2->applyFromArray(
	array(
		'fill' 	=> array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FFFFFFFF')
		),
		'borders' => array(
			'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
			'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		)
	)
);

$object->getActiveSheet()->setSharedStyle($sharedStyle1, "A4:I4");
$object->getActiveSheet()->getStyle('A1:I4')->getFont()->setBold(true);
$object->getActiveSheet()
	->getStyle('A1:I4')
	->getAlignment()
	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$counter = 5;
$ex = $object->setActiveSheetIndex(0);

$no = 1;
$jmlOrder = 0;
$total = 0;
$grandtot = 0;
$grandtotplusongkir = 0;
foreach ($dataview as $datanya) {
	$total = ($datanya["pesanan_kurir"] + $datanya["subtotal"]) - $datanya['dari_poin'];
	$grandtotplusongkir = $grandtotplusongkir + $total;
	$grandtot = $grandtot + $datanya['subtotal'];
	$jmlOrder = $jmlOrder + $datanya['jml'];
	$ex->setCellValue("A" . $counter, $no++);
	$ex->setCellValue("B" . $counter, $datanya["tgl"]);
	$ex->setCellValue("C" . $counter, $datanya["tgl_kirim"]);
	$ex->setCellValueExplicit("D" . $counter, sprintf('%08s', (int) $datanya["pesanan_no"]), PHPExcel_Cell_DataType::TYPE_STRING);
	$ex->setCellValue("E" . $counter, $datanya["cust_nama"]);
	$ex->setCellValue("F" . $counter, $datanya['status']);
	$ex->setCellValue("G" . $counter, $datanya["jml"]);
	$ex->setCellValue("H" . $counter, $datanya['subtotal']);
	$ex->setCellValue("I" . $counter, $total);

	$counter = $counter + 1;
}
$object->getActiveSheet()->setSharedStyle($sharedStyle2, "A5:H$counter");
$z = $counter + 1;
$y = $z + 1;
$x = $y + 1;
$a = $x + 1;
$object->getActiveSheet()->mergeCells("A$z:I$z");
$object->getActiveSheet()->mergeCells("A$y:I$y");
$object->getActiveSheet()->mergeCells("A$x:D$x");

$ex->setCellValue("G" . $x, 'Total QTY');
$ex->setCellValue("H" . $x, 'Grand Total');
$ex->setCellValue("I" . $x, 'Grand Total + Total Ongkir');

$ex->setCellValue("G" . $a, "$jmlOrder");
$ex->setCellValue("H" . $a, "$grandtot");
$ex->setCellValue("I" . $a, "$grandtotplusongkir");

$object->getActiveSheet()->setSharedStyle($sharedStyle1, "G$x:I$x");
$object->getActiveSheet()->getStyle("G$x:I$x")->getFont()->setBold(true);
$object->getActiveSheet()->getStyle('G5:I' . $counter)->getNumberFormat()->setFormatCode("#,##0");

$object->getActiveSheet()->setSharedStyle($sharedStyle2, "G$a:I$a");
$object->getActiveSheet()->getStyle("G$a:I$a")->getNumberFormat()->setFormatCode("#,##0");
$object->getActiveSheet()
	->getStyle("G$x:I$x")
	->getAlignment()
	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Rename sheet
$object->getActiveSheet()->setTitle('Lap_Order_' . $tanggal);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$object->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=LaporanOrder_' . $tanggal . '.xlsx');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
$objWriter->save('php://output');
exit;
