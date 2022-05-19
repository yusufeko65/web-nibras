<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "lap-kas-masuk");
include "../../../includes/config.php";include "../../autoloader.php";
include path_toincludes."PHPExcel.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('lap-kas-masuk','',0);

include DIR_INCLUDE."controller/controlLapKasMasuk.php";
$dtLapKasMasuk = new controllerLapKasMasuk();

$dataview 	= $dtLapKasMasuk->tampilData();
$bulan 	= isset($_GET['bulan']) ? $_GET['bulan']:date('m');
$tahun 	= isset($_GET['tahun']) ? $_GET['tahun']:date('Y');
$tanggal=date("Ymd");

// Create new PHPExcel object
$object = new PHPExcel();
// Set properties
$object->getProperties()->setCreator("HijabSupplier")
               ->setLastModifiedBy("HijabSupplier")
               ->setCategory("Laporan Daftar Reseller ");

// Add some data
$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$object->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$object->getActiveSheet()->mergeCells('A1:E1');
$object->getActiveSheet()->mergeCells('A2:E2');
$object->getActiveSheet()->mergeCells('A3:E3');

$object->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Laporan Daftar Reseller HijabSupplier.com')
			->setCellValue('A2', 'Periode '.$dtFungsi->cariBulan($bulan-1). ' '.$tahun)
            ->setCellValue('A4', 'No')
            ->setCellValue('B4', 'Tgl Register')
            ->setCellValue('C4', 'Register ID')
            ->setCellValue('D4', 'Nama')
            ->setCellValue('E4', 'Grup');
			

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
    
	$object->getActiveSheet()->setSharedStyle($sharedStyle1, "A4:L4");
	$object->getActiveSheet()->getStyle('A1:E4')->getFont()->setBold(true);	
	$object->getActiveSheet()
	       ->getStyle('A1:E4')
		   ->getAlignment()
		   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $counter=5;
    $ex = $object->setActiveSheetIndex(0);
    
    $no = 1;
	foreach($dataview as $datanya) {
	  
      $ex->setCellValue("A".$counter,$no++);
	  $ex->setCellValue("B".$counter,$dtFungsi->ftanggalFull2($datanya['reg_tgl']));
	  $ex->setCellValue("C".$counter,$datanya["kode"]);
	  $ex->setCellValue("D".$counter,$datanya["nama"]);
	  $ex->setCellValue("E".$counter,$datanya["grup"]);

	  $counter=$counter+1;
    }
	$object->getActiveSheet()->setSharedStyle($sharedStyle2, "A5:E$counter");
	
// Rename sheet
$object->getActiveSheet()->setTitle('Lap_Reseller_'.$dtFungsi->cariBulan($bulan-1).'_'.$tahun);
 
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$object->setActiveSheetIndex(0);
 
 
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=LaporanReseller_'.$dtFungsi->cariBulan($bulan-1).$tahun.'.xlsx');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
$objWriter->save('php://output');
exit;
?>