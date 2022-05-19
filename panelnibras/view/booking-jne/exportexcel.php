<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "booking-jne");
include "../../../includes/config.php";include "../../autoloader.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('booking-jne','',0);

include DIR_INCLUDE."controller/controlBookingJNE.php";
include DIR_INCLUDE."controller/controlSettingToko.php";
$dtBookingJNE = new controllerBookingJNE();
$dtSettingToko 	= new controllerSettingToko();

$dataview 	= $dtBookingJNE->tampilData();
$tgl 	= isset($_GET['tgl']) ? $_GET['tgl']:date('Y-m-d');

$datasetting	= $dtSettingToko->getSettingToko();
$nama_toko = $datasetting['toko_nama'];
$alamat_toko = $datasetting['toko_alamat'];
$tlp_toko = $datasetting['toko_telp'];
$pemilik = $datasetting['toko_pemilik'];
//header("Content-Type: application/xls");;
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=BookingJNE_".$tgl.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php echo '<?xml version="1.0"?>' ?>
<?php echo '<?mso-application progid="Excel.Sheet"?>'?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <Styles>
  <Style ss:ID="judul">
	 <Alignment ss:Vertical="Center" ss:Horizontal="Center"/>
     <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="14" ss:Bold="1"/>
  </Style>
  
  <Style ss:ID="subjudul">
	 <Alignment ss:Vertical="Center" ss:Horizontal="Center"/>
     <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="12" ss:Bold="1"/>
  </Style>
  <Style ss:ID="supersubjudul">
	 <Alignment ss:Horizontal="Right"/>
     <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11"/>
  </Style>
  <Style ss:ID="header">
   
   <Alignment ss:Vertical="Center" ss:Horizontal="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#FFFFFF"
    ss:Bold="1"/>
   <Interior ss:Color="#5f891e" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="body">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="kanan">
	<Alignment ss:Vertical="Center" ss:Horizontal="Right"/>
	<Borders>
		<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="kiri">
	<Alignment ss:Vertical="Center" ss:Horizontal="Left"/>
	<Borders>
		<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>	
		<Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="tengah">
	<Alignment ss:Vertical="Center" ss:Horizontal="Center"/>
	<Borders>
		<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
 </Styles>
 <Worksheet ss:Name="LaporanKasMasuk<?php echo $dtFungsi->cariBulan($bulan-1).$tahun ?>">
  <Table>
   <Column ss:AutoFitWidth="0" ss:Width="30.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="60.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="70.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Row ss:Height="25">
    <Cell ss:StyleID="header"><Data ss:Type="String">NAMA PENGIRIM</Data></Cell>
    <Cell ss:StyleID="header"><Data ss:Type="String">ALAMAT PENGIRIM 1</Data></Cell>
    <Cell ss:StyleID="header"><Data ss:Type="String">ALAMAT PENGIRIM 2</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">ALAMAT PENGIRIM 3</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">CONTACT PENGIRIM</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">TLP PENGIRIM</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">NAMA PENERIMA</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">ALAMAT PENERIMA 1</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">ALAMAT PENERIMA 2</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">ALAMAT PENERIMA 3</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">KODE POS</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">CONTACT PENERIMA</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">TLP PENERIMA</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">QTY / JUMLAH BARANG</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">WEIGHT / BERAT</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">NAMA BARANG</Data></Cell>
   </Row>
 <?php 
    $no = 1;
	$jmlOrder = 0;
	$totBelanja = 0;
	$totBarang = 0;
	$totInfaq = 0;
	$totLaba = 0;
	$totDeposit = 0;
	$totKekurangan = 0;
	$totKM = 0;
	foreach($dataview as $datanya) {
	  $pelanggan         = $datanya['pelanggan_id'];
	  $nama_pengirim     = $datanya['nama_pengirim'];
	  $kontak_pengirim   = $nama_pengirim;
	  $telp_pengirim     = $datanya['telp_pengirim'];
	  $hp_pengirim       = $datanya['hp_pengirim'];
	  $propinsi_pengirim = $dtFungsi->fcaridata('_provinsi','provinsi_nama','provinsi_id',$datanya['propinsi_pengirim']);
	  $kota_pengirim     = $dtFungsi->fcaridata('_kabupaten','kabupaten_nama','kabupaten_id',$datanya['kota_pengirim']);
	  $kec_pengirim      = $dtFungsi->fcaridata('_kecamatan','kecamatan_nama','kecamatan_id',$datanya['kecamatan_pengirim']);
	  $kel_pengirim      = $datanya['kelurahan_pengirim'];
	  $alamat_pengirim   = $datanya['alamat_pengirim'].', '.$propinsi_pengirim.', '.$kota_pengirim.', '.$kec_pengirim.'. '.$kel_pengirim;
	  $kodepos_pengirim  = $datanya['kodepos_pengirim'];
	  $nama_penerima     = $datanya['nama_penerima'];
	  $telp_penerima     = $datanya['telp_penerima'];
	  $hp_penerima       = $datanya['hp_penerima'];
					 
	  $propinsi_penerima = $dtFungsi->fcaridata('_provinsi','provinsi_nama','provinsi_id',$datanya['propinsi_penerima']);
	  $kota_penerima     = $dtFungsi->fcaridata('_kabupaten','kabupaten_nama','kabupaten_id',$datanya['kota_penerima']);
	  $kec_penerima      = $dtFungsi->fcaridata('_kecamatan','kecamatan_nama','kecamatan_id',$datanya['kecamatan_penerima']);
	  $kel_penerima      = $datanya['kelurahan_penerima'];
	  $alamat_penerima   = $datanya['alamat_penerima'].', '.$propinsi_penerima.', '.$kota_penerima.', '.$kec_penerima.', '.$kel_penerima;
	  $kodepos_penerima  = $datanya['kodepos_penerima'];
	  $jml 				 = $datanya['jml'];
	  $berat             = $datanya['berat'];
					 
		
	  if($berat < 1) $berat = ceil($berat);
					 
	  if($berat > 1) {
		$berats = floor($berat);
	    $jarakkoma = $berat - $berats;
	    if($jarakkoma > 0.3) $berat = ceil($berat);
		else $berat = floor($berat);
	  } else {
		$jarakkoma = 0;
	  }
					 
	  $field 	    = 'reseller_nama,reseller_toko,rs_dropship,reseller_grup';
	  $tabel 		= '_reseller INNER JOIN _reseller_grup ON _reseller.reseller_grup = _reseller_grup.rs_grupid';
	  $where 		= "reseller_id = '".$pelanggan."'";
	  $reseller 	= $dtFungsi->fcaridata2($tabel,$field,$where);
	  $nmreseller 	= $reseller[0];
	  $tokoreseller	= $reseller[1];
	  $dropship		= $reseller[2];
	  $grupreseller	= $reseller[3];
	  /*				 
	  if ($nama_penerima != $nmreseller && $dropship=='1') {
	     if($tokoreseller != '-' || $tokoreseller != '') {
			$nama_pengirim = $tokoreseller;
	     } 
	  } else {
	     $nama_pengirim = $nama_toko;
		 $alamat_pengirim = $alamat_toko;
		 $hp_pengirim = $tlp_toko;
		 $kontak_pengirim = $pemilik;
                         						 
	 }
	 */
	 				 
	  if ($dropship!='1' || $nama_penerima == $nmreseller){
	     $nama_pengirim = $nama_toko;
		 $alamat_pengirim = $alamat_toko;
		 $hp_pengirim = $tlp_toko;
		 $kontak_pengirim = $pemilik;
     } 
  ?>
  <Row>
    <Cell ss:StyleID="kiri"><Data ss:Type="String"><?php echo $nama_pengirim ?></Data></Cell>
    <Cell ss:StyleID="kiri"><Data ss:Type="String"><?php echo $alamat_pengirim ?></Data></Cell>
    <Cell ss:StyleID="kiri"><Data ss:Type="String"></Data></Cell>
	<Cell ss:StyleID="kiri"><Data ss:Type="String"></Data></Cell>
	<Cell ss:StyleID="kiri"><Data ss:Type="String"><?php echo $kontak_pengirim ?></Data></Cell>
	<Cell ss:StyleID="kiri"><Data ss:Type="String"><?php echo $hp_pengirim ?></Data></Cell>
	<Cell ss:StyleID="kiri"><Data ss:Type="String"><?php echo $nama_penerima ?></Data></Cell>
	<Cell ss:StyleID="kiri"><Data ss:Type="String"><?php echo $alamat_penerima ?></Data></Cell>
	 <Cell ss:StyleID="kiri"><Data ss:Type="String"></Data></Cell>
	<Cell ss:StyleID="kiri"><Data ss:Type="String"></Data></Cell>
	<Cell ss:StyleID="kiri"><Data ss:Type="String"><?php echo $kodepos_penerima ?></Data></Cell>
	<Cell ss:StyleID="kiri"><Data ss:Type="String"><?php echo $nama_penerima ?></Data></Cell>
	<Cell ss:StyleID="kiri"><Data ss:Type="String"><?php echo $hp_penerima ?></Data></Cell>
	<Cell ss:StyleID="kiri"><Data ss:Type="String"><?php echo $jml ?></Data></Cell>
	<Cell ss:StyleID="kiri"><Data ss:Type="String"><?php echo $berat ?></Data></Cell>
	<Cell ss:StyleID="kiri"><Data ss:Type="String"><?php echo 'Jilbab' ?></Data></Cell>
   </Row>
<?php } ?>
	<Row>
     <Cell></Cell>
   </Row>
   <Row>
     <Cell></Cell>
   </Row>
  
  </Table>
  
 </Worksheet>
 
</Workbook>