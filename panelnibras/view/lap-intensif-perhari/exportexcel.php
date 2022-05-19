<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "lap-kas-masuk");
include "../../../includes/config.php";include "../../autoloader.php";
include path_toincludes."PHPExcel.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('lap-intensif-perhari','',0);

include DIR_INCLUDE."controller/controlLapIntensifPerhari.php";
$dtLapKasMasuk = new controllerLapIntensifPerhari();

$dataview 	= $dtLapKasMasuk->tampilData();
$tgl 	= isset($_GET['tgl']) ? $_GET['tgl']:date('Y-m-d');

$tanggal=date("Ymd");

// Create new PHPExcel object
$object = new PHPExcel();
// Set properties
$object->getProperties()->setCreator("HijabSupplier")
               ->setLastModifiedBy("HijabSupplier")
               ->setCategory("Laporan Bonus Admin Order Perhari ");

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
            ->setCellValue('A1', 'Laporan Bonus Admin Order perhari HijabSupplier.com')
			->setCellValue('A2', 'Periode '.$tgl)
            ->setCellValue('A4', 'No')
            ->setCellValue('B4', 'Tgl Konfirmasi')
            ->setCellValue('C4', 'Username')
            ->setCellValue('D4', 'Nama Admin Order')
            ->setCellValue('E4', 'Bonus');
            
			
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
	$object->getActiveSheet()->getStyle('A1:L4')->getFont()->setBold(true);	
	$object->getActiveSheet()
	       ->getStyle('A1:E4')
		   ->getAlignment()
		   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $counter=5;
    $ex = $object->setActiveSheetIndex(0);
    
    $no = 1;
	
	foreach($dataview as $datanya) {
	 
      $ex->setCellValue("A".$counter,$no++);
	  $ex->setCellValue("B".$counter,$datanya['tglkonfirm']);
	  $ex->setCellValueExplicit("C".$counter,$datanya["login"]);
	  $ex->setCellValue("D".$counter,$datanya["nama"]);
	  $ex->setCellValue("E".$counter,$datanya["bonus"]);
	  
	  $counter=$counter+1;
    }
	$object->getActiveSheet()->setSharedStyle($sharedStyle2, "A5:E$counter");
	
	 
// Rename sheet
$object->getActiveSheet()->setTitle('BonusPerhari'.$tgl);
 
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$object->setActiveSheetIndex(0);
 
 
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=BonusAdminOrderPerhari_'.$tgl.'.xlsx');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
$objWriter->save('php://output');
exit;
?>