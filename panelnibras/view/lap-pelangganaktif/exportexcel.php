<?php
define("path_toincludes", "../../_includes/");
define("folder", "lap-customer");
include "../../../includes/config.php";include "../../autoloader.php";
include path_toincludes."PHPExcel.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('lap-customer','',0);

include DIR_INCLUDE."controller/controlLapCustomer.php";
$dtLapCustomer = new controllerLapCustomer();

$dataview 	= $dtLapCustomer->tampilData();

$bulan 	= isset($_GET['status']) ? $_GET['status']:'';
$bulan 	= isset($_GET['bulan']) ? $_GET['bulan']:date('m');
$tahun 	= isset($_GET['tahun']) ? $_GET['tahun']:date('Y');
$tanggal=date("Ymd");

// Create new PHPExcel object
$object = new PHPExcel();
// Set properties
$object->getProperties()->setCreator("Goetnikcom")
               ->setLastModifiedBy("Goetnikcom")
               ->setCategory("Laporan Data Pelanggan ");

// Add some data
$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$object->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$object->getActiveSheet()->mergeCells('A1:I1');
$object->getActiveSheet()->mergeCells('A2:I2');
$object->getActiveSheet()->mergeCells('A3:I3');

$object->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Laporan Data Pelanggan Goetnik.com')
			->setCellValue('A2', 'Periode '.$dtFungsi->cariBulan($bulan-1). ' '.$tahun)
            ->setCellValue('A4', 'No')
            ->setCellValue('B4', 'Kode')
            ->setCellValue('C4', 'Nama')
            ->setCellValue('D4', 'Email')
            ->setCellValue('E4', 'Telp')
            ->setCellValue('F4', 'Kota')
			->setCellValue('G4', 'Alamat')
			->setCellValue('H4', 'Grup')
			->setCellValue('I4', 'Tgl Regis');

$sharedStyle1 = new PHPExcel_Style();
$sharedStyle2 = new PHPExcel_Style();
$sharedStyle1->applyFromArray(
	array('fill' 	=> array(
		  'type'    => PHPExcel_Style_Fill::FILL_SOLID,
		  'color'   => array('argb' => 'FFCCFFCC')),
		  'borders' => array(
						'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
						'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		  )
	));
$sharedStyle2->applyFromArray(
	array('fill' 	=> array(
		  'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
		  'color'	=> array('argb' => 'FFFFFFFF')),
		  'borders' => array(
						'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
						'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		  )
    ));
    
	$object->getActiveSheet()->setSharedStyle($sharedStyle1, "A4:I4");
	$object->getActiveSheet()->getStyle('A1:I4')->getFont()->setBold(true);	
	$object->getActiveSheet()
	       ->getStyle('A1:I4')
		   ->getAlignment()
		   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $counter=5;
    $ex = $object->setActiveSheetIndex(0);
    
    $no = 1;
	
	foreach($dataview as $datanya) {
	
      $ex->setCellValue("A".$counter,$no++);
	  $ex->setCellValueExplicit("B".$counter,sprintf('%08s', (int)$datanya["cust_kode"]),PHPExcel_Cell_DataType::TYPE_STRING);
	  $ex->setCellValue("C".$counter,$datanya["cust_nama"]);
	  $ex->setCellValue("D".$counter,$datanya['cust_email']);
	  $ex->setCellValue("E".$counter,$datanya["cust_telp"]);
	  $ex->setCellValue("F".$counter,$datanya["kabupaten_nama"]);
	  $ex->setCellValue("G".$counter,$datanya["cust_alamat"]);
	  $ex->setCellValue("H".$counter,$datanya["cg_nm"]);
	  $ex->setCellValue("I".$counter,$datanya["cust_tgl_add"]);
	  $counter=$counter+1;
    }
	$object->getActiveSheet()->setSharedStyle($sharedStyle2, "A5:I$counter");
	
	 
// Rename sheet
$object->getActiveSheet()->setTitle('Lap_Pelanggan_'.$dtFungsi->cariBulan($bulan-1).'_'.$tahun);
 
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$object->setActiveSheetIndex(0);
 
 
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=LaporanPelanggan_'.$dtFungsi->cariBulan($bulan-1).$tahun.'.xlsx');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
$objWriter->save('php://output');
exit;
?>