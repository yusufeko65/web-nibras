<?php
include "../../../includes/config.php";
//if (!isset($_SESSION['idmember'])) echo "<script>location='" . URL_PROGRAM . 'login' . "'</script>";
define("path_toincludes", DIR_INCLUDE . "_includes/");
include path_toincludes . "PHPExcel.php";

include "../../../autoloader.php";
$dtLapProduk = new controller_Produk();

$kategori 	= isset($_GET['kat']) ? $_GET['kat']  : 0;
$search		= isset($_GET['s']) ? $_GET['s']  : '';
$dataview 	= $dtLapProduk->getLapProdukByKategori($kategori, $search, 0);
$total		= $dataview['total'];
$listdata	= $dataview['rows'];

$ukuranperkat = $dtLapProduk->getUkuranKategori($kategori);
$datastoks = $dtLapProduk->getStokProdukPerKategoriPerWarnaUkuran($kategori);
$options = '';

// Create new PHPExcel object
$object = new PHPExcel();

// Add some data
$azRange = range('A', 'Z');

$kolom = [];
$i = 0;
$jmlukuran = count($ukuranperkat);

foreach ($azRange as $letter) {
	$kolom[$i] = $letter;
	$i++;
}

$kolommercel1 = $kolom[$jmlukuran + 1] . '1';
$kolommercel2 = $kolom[$jmlukuran + 1] . '2';
$kolommercel3 = $kolom[$jmlukuran + 1] . '3';
$nama_kategori = strip_tags($dataview['rows'][0]['nama_kategori']);

$object->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$object->getActiveSheet()->getColumnDimension('B')->setWidth(15);

$object->getActiveSheet()->mergeCells("A1:$kolommercel1");
$object->getActiveSheet()->mergeCells("A2:$kolommercel2");
$object->getActiveSheet()->mergeCells("A3:$kolommercel3");
$object->getActiveSheet()->mergeCells("A4:A5");
$object->getActiveSheet()->mergeCells("B4:B5");
$object->getActiveSheet()->mergeCells("C4:" . $kolom[$jmlukuran + 1] . '4');

$object->setActiveSheetIndex(0)
	->setCellValue('A1', 'Laporan Data Produk per Kategori')
	->setCellValue('A2', 'Kategori ' . $nama_kategori)
	->setCellValue('A4', 'Nama')
	->setCellValue('B4', 'Warna')
	->setCellValue('C4', 'Size');

$x = 5;
$d = $object->setActiveSheetIndex(0);
$z = 2;
foreach ($ukuranperkat as $uk) {
	$d->setCellValue($kolom[$z] . $x, $uk['ukuran']);
	//$x++;
	$z++;
}

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

//$object->getActiveSheet()->setSharedStyle($sharedStyle1, "A4:$kolommercel3");
$object->getActiveSheet()->getStyle('A1:I4')->getFont()->setBold(true);
$object->getActiveSheet()
	->getStyle("A1:$kolommercel3")
	->getAlignment()
	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()
	->getStyle("C4:" . $kolom[$jmlukuran + 1] . "4")
	->getAlignment()
	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$counter = 6;
$ex = $object->setActiveSheetIndex(0);

$no = 1;
$kolomakhir = 'A';
foreach ($dataview['rows'] as $prod) {

	$ex->setCellValue("A" . $counter, ucwords($prod["nama_produk"]));
	$ex->setCellValue("B" . $counter, ucwords($prod["warna"]));
	$z = 2; // dimulai dengan char 2 atau huruf abjad C sesuai kolom excel
	foreach ($ukuranperkat as $uk) {
		$ids = $prod['idproduk'] . ':' . $prod['idwarna'] . ':' . $uk['idukuran'];
		$ex->setCellValue($kolom[$z] . $counter, isset($datastoks["{$ids}"]) ? $datastoks["{$ids}"] : 0);
		$kolomakhir = $kolom[$z];
		$z++;
	}
	$counter = $counter + 1;
}
$konterakhir = $counter - 1;
// Rename sheet
$object->getActiveSheet()->setTitle('Lap_Produk_' . $nama_kategori);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$object->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Lap_Produk' . str_Replace(' ', '_', $nama_kategori) . '.xlsx');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
$objWriter->save('php://output');
exit;
