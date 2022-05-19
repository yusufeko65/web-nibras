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
               ->setCategory("Laporan Kas Masuk ");

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
$object->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('K')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('L')->setWidth(20);
$object->getActiveSheet()->mergeCells('A1:L1');
$object->getActiveSheet()->mergeCells('A2:L2');
$object->getActiveSheet()->mergeCells('A3:L3');

$object->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Laporan Kas Masuk HijabSupplier.com')
			->setCellValue('A2', 'Periode '.$dtFungsi->cariBulan($bulan-1). ' '.$tahun)
            ->setCellValue('A4', 'No')
            ->setCellValue('B4', 'Tgl')
            ->setCellValue('C4', 'Order ID')
            ->setCellValue('D4', 'Total Belanja')
            ->setCellValue('E4', 'Potongan')
            ->setCellValue('F4', 'Total Barang')
			->setCellValue('G4', 'Ongkos Kirim')
			->setCellValue('H4', 'Infaq')
			->setCellValue('I4', 'Keuntungan')
			->setCellValue('J4', 'Deposit')
			->setCellValue('K4', 'Kekurangan')
			->setCellValue('L4', 'Kas Masuk');
			


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
	       ->getStyle('A1:L4')
		   ->getAlignment()
		   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $counter=5;
    $ex = $object->setActiveSheetIndex(0);
    
    $no = 1;
	$jmlOrder = 0;
	$totBelanja = 0;
	$totBarang = 0;
	$totInfaq = 0;
	$totLaba = 0;
	$totDeposit = 0;
	$totKekurangan = 0;
	$totKM = 0;
	$hrgpotongan = 0;
	$totPotongan = 0;
	foreach($dataview as $datanya) {
	  $laba = ($datanya["subtotal"] - $datanya["hrgbeli"]) - $datanya["kekurangan"];
	  $total= ($datanya["subtotal"] + $datanya["ongkir"] + $datanya["infaq"]);
	  $hrgpotongan = $datanya["hrgsatuan"] - $datanya["subtotal"];
	  
	  $totBelanja = $totBelanja + $datanya["hrgsatuan"];
	  $totBarang = $totBarang + (int)$datanya["hrgbeli"];
	  $totInfaq = $totInfaq + $datanya["infaq"];
	  $totLaba = $totLaba + $laba;
	  $totDeposit = $totDeposit + $datanya["penambahan"];
	  $totPotongan = $totPotongan + $hrgpotongan;
	  $KM	= ((int)$datanya["hrgbeli"] + (($datanya["subtotal"] - $datanya["hrgbeli"])-$datanya["kekurangan"]) + $datanya["infaq"] + $datanya["ongkir"]) - $datanya["penambahan"];
	  $totKekurangan = $totKekurangan + $datanya["kekurangan"];
	
	  $totKM = $totKM + $KM;
	  $jmlOrder = $jmlOrder+1;
      $ex->setCellValue("A".$counter,$no++);
	  $ex->setCellValue("B".$counter,$dtFungsi->ftanggalFull2($datanya['tglkomplet']));
	  $ex->setCellValueExplicit("C".$counter,$datanya["noorder"],PHPExcel_Cell_DataType::TYPE_STRING);
	  $ex->setCellValue("D".$counter,$datanya["hrgsatuan"]);
	  $ex->setCellValue("E".$counter,$hrgpotongan);
	  $ex->setCellValue("F".$counter,$datanya["hrgbeli"]);
	  $ex->setCellValue("G".$counter,$datanya["ongkir"]);
	  $ex->setCellValue("H".$counter,$datanya["infaq"]);
	  $ex->setCellValue("I".$counter,$laba);
	  $ex->setCellValue("J".$counter,$datanya["penambahan"]);
	  $ex->setCellValue("K".$counter,$datanya["kekurangan"]);
	  $ex->setCellValue("L".$counter,$KM);
	
	  
	  $counter=$counter+1;
    }
	$object->getActiveSheet()->setSharedStyle($sharedStyle2, "A5:L$counter");
	$z = $counter+1;
	$y = $z + 1 ;
	$x = $y + 1;
	$a = $x + 1;
	$object->getActiveSheet()->mergeCells("A$z:L$z");
	$object->getActiveSheet()->mergeCells("A$y:L$y");
	$object->getActiveSheet()->mergeCells("A$x:C$x");
	
    $ex->setCellValue("D".$x,'Total Order');
    $ex->setCellValue("E".$x,'Total Belanja');
    $ex->setCellValue("F".$x,'Total Potongan');
    $ex->setCellValue("G".$x,'Total Barang');
    $ex->setCellValue("H".$x,'Infaq');
    $ex->setCellValue("I".$x,'Keuntungan');
    $ex->setCellValue("J".$x,'Deposit');
    $ex->setCellValue("K".$x,'Kekurangan');
    $ex->setCellValue("L".$x,'Kas Masuk');
    $ex->setCellValue("D".$a,"$jmlOrder");
    $ex->setCellValue("E".$a,"$totBelanja");
    $ex->setCellValue("F".$a,"$totPotongan");
    $ex->setCellValue("G".$a,"$totBarang");
    $ex->setCellValue("H".$a,"$totInfaq");
    $ex->setCellValue("I".$a,"$totLaba");
	$ex->setCellValue("J".$a,"$totDeposit");
    $ex->setCellValue("K".$a,"$totKekurangan");
	$ex->setCellValue("L".$a,"$totKM");
	$object->getActiveSheet()->setSharedStyle($sharedStyle1, "D$x:L$x");
	$object->getActiveSheet()->getStyle("D$x:L$x")->getFont()->setBold(true);
	$object->getActiveSheet()->getStyle('D5:L'.$counter)->getNumberFormat()->setFormatCode("#,##0");
	
	$object->getActiveSheet()->setSharedStyle($sharedStyle2, "D$a:L$a");
	$object->getActiveSheet()->getStyle("E$a:L$a")->getNumberFormat()->setFormatCode("#,##0");
	$object->getActiveSheet()
	       ->getStyle("D$x:L$x")
		   ->getAlignment()
		   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	 
// Rename sheet
$object->getActiveSheet()->setTitle('Lap_Kas_Masuk_'.$dtFungsi->cariBulan($bulan-1).'_'.$tahun);
 
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$object->setActiveSheetIndex(0);
 
 
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=LaporanKasMasuk_'.$dtFungsi->cariBulan($bulan-1).$tahun.'.xlsx');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
$objWriter->save('php://output');
exit;
?>