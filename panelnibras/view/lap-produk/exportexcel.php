<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "lap-order");
include "../../../includes/config.php";include "../../autoloader.php";
include path_toincludes."PHPExcel.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('lap-produklaris','',0);


$dtLapProduk = new controllerLapProdukLaris();

$dataview 	= $dtLapProduk->tampilData();

$bulan 	= isset($_GET['bulan']) ? $_GET['bulan']:date('m');
$tahun 	= isset($_GET['tahun']) ? $_GET['tahun']:date('Y');
$tanggal=date("Ymd");

// Create new PHPExcel object
$object = new PHPExcel();
// Set properties
$object->getProperties()->setCreator("Goetnikcom")
               ->setLastModifiedBy("Goetnikcom")
               ->setCategory("Laporan 10 Produk Terlaris ");

// Add some data
$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$object->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('D')->setWidth(20);

$object->getActiveSheet()->mergeCells('A1:D1');
$object->getActiveSheet()->mergeCells('A2:D2');
$object->getActiveSheet()->mergeCells('A3:D3');

$object->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Laporan 10 Produk Terlaris Goetnik.com')
			->setCellValue('A2', 'Periode '.$dtFungsi->cariBulan($bulan-1). ' '.$tahun)
            ->setCellValue('A4', 'No')
            ->setCellValue('B4', 'Kode Produk')
            ->setCellValue('C4', 'Nama Produk')
            ->setCellValue('D4', 'Jumlah Terjual');
            
			


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
    
	$object->getActiveSheet()->setSharedStyle($sharedStyle1, "A4:G4");
	$object->getActiveSheet()->getStyle('A1:G4')->getFont()->setBold(true);	
	$object->getActiveSheet()
	       ->getStyle('A1:G4')
		   ->getAlignment()
		   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $counter=5;
    $ex = $object->setActiveSheetIndex(0);
    
    $no = 1;
	$jmlOrder = 0;
	$total = 0;
	$grandtot = 0;
	
	foreach($dataview as $datanya) {
	  
	  $grandtot = $grandtot + $datanya['jml'];
      $ex->setCellValue("A".$counter,$no++);
	  $ex->setCellValue("B".$counter,$datanya["kode"]);
	  $ex->setCellValue("C".$counter,$datanya["nama"]);
	  $ex->setCellValue("D".$counter,$datanya["jml"]);
	  
	  
	  $counter=$counter+1;
    }
	$object->getActiveSheet()->setSharedStyle($sharedStyle2, "A5:D$counter");
	$z = $counter+1;
	$y = $z + 1 ;
	$x = $y + 1;
	$a = $x + 1;
	$object->getActiveSheet()->mergeCells("A$z:D$z");
	$object->getActiveSheet()->mergeCells("A$y:D$y");
	$object->getActiveSheet()->mergeCells("A$x:D$x");
	
    $ex->setCellValue("C".$x,'Total Jumlah Terjual');
    
    $ex->setCellValue("D".$a,"$grandtot");
    
	$object->getActiveSheet()->setSharedStyle($sharedStyle1, "C$x:D$x");
	$object->getActiveSheet()->getStyle("C$x:S$x")->getFont()->setBold(true);
	
	
	$object->getActiveSheet()->setSharedStyle($sharedStyle2, "C$a:S$a");
	
	$object->getActiveSheet()
	       ->getStyle("C$x:D$x")
		   ->getAlignment()
		   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	 
// Rename sheet
$object->getActiveSheet()->setTitle('Lap_10ProdukLaris_'.$dtFungsi->cariBulan($bulan-1).'_'.$tahun);
 
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$object->setActiveSheetIndex(0);
 
 
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Laporan10ProdukLaris_'.$dtFungsi->cariBulan($bulan-1).$tahun.'.xlsx');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
$objWriter->save('php://output');
exit;
?>