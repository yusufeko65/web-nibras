<?php
require_once DIR_INCLUDE.'pdf/pdf.php';

class pdfLapKasMasuk extends pdftable{
    private $data=array();
	
	
    function setHeader($data){
		$this->data['toko'] = $data['toko'];
		$this->data['judul'] = $data['judul'];
		$this->data['bulan'] = $data['bulan'];
		$this->data['tahun'] = $data['tahun'];
		$this->data['t1'] = $data['t1'];
		$this->data['t2'] = $data['t2'];
		$this->data['t3'] = $data['t3'];
	}
	function Header(){
		$this->Image(DIR_INCLUDE.'images/logoinvoice.jpg',15,15,20,20);
		$this->setFont('Times','B',14);
		$this->Text(40,19,$this->data['toko']);
		$this->setFont('Times','',10);
		$this->Text(40,25,$this->data['t1']);
		$this->Text(40,29,$this->data['t2']);
		$this->Text(40,33,$this->data['t3']);
		//$this->Text(125,43,'Periode '.$this->data['bulan'].' '.$this->data['tahun']);	
		$this->setFont('Times','',8);
		$hal = sprintf("Hal %s dari %s",$this->PageNo(),'{nb}');
		$this->Text(270,10,$hal);
	}
		
	
	
	function drawHTML($html){
		$this->htmltable($html);
	}
	
}

class controllerLapKasMasuk {
   private $model;
   private $Fungsi;
   private $data=array();
      
   function __construct(){
		$this->model= new modelLapKasMasuk();
		$this->Fungsi= new FungsiUmum();
   }
   
    public function tampilData(){

	$result 			= array();
	$filter				= array();
	$where 				= '';
		
	$bulan 	= isset($_GET['bulan']) ? $_GET['bulan']:date('m');
	$tahun 	= isset($_GET['tahun']) ? $_GET['tahun']:date('Y');
	
	$settoko 	= $this->Fungsi->fcaridata2("_setting_toko","status_print","setid <> ''");
	$status 	= $settoko[0];
	
	/*	
	if($bulan!='') $filter[] = " MONTH(tanggal)= '".trim(strip_tags(urlencode($bulan)))."'";
	if($tahun!='') $filter[] = " YEAR(tanggal)= '".trim(strip_tags(urlencode($tahun)))."'";
	*/
	if($bulan!='') $filter[] = " MONTH(_order_status.tanggal)= '".trim(strip_tags(urlencode($bulan)))."'";
	if($tahun!='') $filter[] = " YEAR(_order_status.tanggal)= '".trim(strip_tags(urlencode($tahun)))."'";
	//$filter[] = " _order.status_id = '".$status."'";
	if(!empty($filter))	$where = implode(" and ",$filter);
	
	
	//$result["total"] = 0;
	$result["rows"] = '';
	//$this->offset = ($this->page-1)*$this->rows;

	//$result["total"]   = $this->model->totalOrder($where,$status);
	//$result["rows"]    = $this->model->getOrder($where,$status);
	//$result["rows"]    = $this->model->getOrder($this->offset,$this->rows,$where);
	//$result["page"]    = $this->page; 
	//$result["baris"]   = $this->rows;
	//$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
	
	return $this->model->getOrder($where,$status);;
  }
   
   public function cetakLaporan(){
	  $hasil 	= '';
      $bulan 	= isset($_POST['bulan']) ? $_POST['bulan']:'';
	  $tahun 	= isset($_POST['tahun']) ? $_POST['tahun']:'';
	  
	  $data	    = array();
	  $settoko 	= $this->Fungsi->fcaridata2("_setting_toko","toko_nama,status_print,toko_alamat","setid <> ''");
	  $data['toko']			= $settoko[0];
	  $t 					= explode(",",$settoko[2]);
	  $data['t1']	= $t[0];
	  $data['t2']	= trim($t[1]).', '.trim($t[2]);
	  $data['t3']	= trim($t[3]).', '.trim($t[4]);
	  $data['bulan']		= $this->Fungsi->cariBulan($bulan-1);
	  $data['tahun']		= $tahun;
	  $data['judul'] 		= 'LAPORAN KAS MASUK';
	  $periode				= strtoupper("Periode ".$data['bulan']." ".$data['tahun']);
	  $status				= $settoko[1];
	  
	  $where = '';
	  $filter= array();
	  if($bulan!='') $filter[] = " MONTH(pesanan_tgl)= '".trim(strip_tags(urlencode($bulan)))."'";
	  if($tahun!='') $filter[] = " YEAR(pesanan_tgl)= '".trim(strip_tags(urlencode($tahun)))."'";
	  if($status!='') $filter[] = " status_id= '".$status."'";
	  
	  if(!empty($filter))	$where = implode(" and ",$filter);
      
	  $dataReport = $this->model->getKasMasuk($where);
	  
	 
	  $tableHeader = "<table width=100%><tr>";
	  $tableHeader .= "<td colspan=\"11\" align=\"center\"><font family=\"Times\" style=\"bold\" size=\"15\">".$data['judul']."</font></td></tr><tr>";
	  $tableHeader .= "<td colspan=\"11\" align=\"center\"><font family=\"Times\" size=\"12\">".$periode."</font></td></tr><tr>";
	  $tableHeader .= "<td colspan=\"11\" align=\"center\" height=\"10\"></td></tr><tr>";
	  $tableHeader .= "<td border=\"1\" bgcolor=\"#92d050\" width=\"3%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">No.</font></td>";
	  $tableHeader .= "<td border=\"1\" bgcolor=\"#92d050\" width=\"15%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Tanggal</font></td>";
	  $tableHeader .= "<td border=\"1\" bgcolor=\"#92d050\" width=\"15%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Order ID</font></td>";
	  $tableHeader .= "<td border=\"1\" bgcolor=\"#92d050\" width=\"20%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Total Barang</font></td>";
	  $tableHeader .= "<td border=\"1\" bgcolor=\"#92d050\" width=\"30%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Total Belanja</font></td>";
	  $tableHeader .= "<td border=\"1\" bgcolor=\"#92d050\" width=\"20%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Ongkos Kirim</font></td>";
	  $tableHeader .= "<td border=\"1\" bgcolor=\"#92d050\" width=\"15%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Infaq</font></td>";
	  $tableHeader .= "<td border=\"1\" bgcolor=\"#92d050\" width=\"15%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Keuntungan</font></td>";
	  $tableHeader .= "<td border=\"1\" bgcolor=\"#92d050\" width=\"20%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Penambahan</font></td>";
	  $tableHeader .= "<td border=\"1\" bgcolor=\"#92d050\" width=\"15%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Kekurangan</font></td>";
	  $tableHeader .= "<td border=\"1\" bgcolor=\"#92d050\" width=\"15%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Kas Masuk</font></td>";
  	  $tableHeader .= "</tr>";
	  
	  $pdf = new pdfLapKasMasuk();
	  $pdf->AliasNbPages();
	  $pdf->setHeader($data);
	  
	  $no = 0;
	  $limitView = 2;
	  $batasprinttotal = 10;
	  $numCount = 1;
	  $jmltotorder = 0;
	  
	  $numItems = count($dataReport);
	  
	  if($numItems==0){
		$pdf->AddPage("L","A4");
		//$pdf->setMargins(50,40,5);
		$pdf->setMargins(20,20,20);
		$pdf->defaultFontFamily = 'times';
		$pdf->defaultFontStyle  = '';
		$pdf->defaultFontSize   = 9;
		$pdf->SetFont($pdf->defaultFontFamily, $pdf->defaultFontStyle, $pdf->defaultFontSize);
	  }
	
	  foreach($dataReport as $dtr){
	      if($no == $limitView){
			 $no = 0;
			 
		  }
		  if($no == 0){
				$pdf->AddPage("L","A4");
				//$pdf->setMargins(50,40,5);
				$pdf->setMargins(15,15,20);
				$pdf->defaultFontFamily = 'times';
				$pdf->defaultFontStyle  = '';
				$pdf->defaultFontSize   = 9;
				$pdf->SetFont($pdf->defaultFontFamily, $pdf->defaultFontStyle, $pdf->defaultFontSize);
				$tableContent = "";
				$tableContent .= $tableHeader;
		  }
		  $no++;
		  $jmltotorder = $jmltotorder + $dtr['subtotal'];
		  $tableContent .= "<tr>";
		  $tableContent .= "<td border=\"1\" align=\"center\">".$numCount."</td>";
		  $tableContent .= "<td border=\"1\" align=\"center\">".$this->Fungsi->ftanggalFull2($dtr['tgl'])."</td>";
		  $tableContent .= "<td border=\"1\" align=\"center\">".$dtr['noorder']."</td>";
		  $tableContent .= "<td border=\"1\" align=\"right\">".$dtr['jml']."</td>";
		  $tableContent .= "<td border=\"1\" align=\"right\">".$this->Fungsi->fuang($dtr['subtotal'])."</td>";
		  $tableContent .= "<td border=\"1\" align=\"right\">".$this->Fungsi->fuang($dtr['ongkir'])."</td>";
		  $tableContent .= "<td border=\"1\" align=\"right\">".$this->Fungsi->fuang($dtr['infaq'])."</td>";
		  $tableContent .= "<td border=\"1\" align=\"right\"></td>";
		  $tableContent .= "<td border=\"1\" align=\"right\">".$this->Fungsi->fuang($dtr['penambahan'])."</td>";
		  $tableContent .= "<td border=\"1\" align=\"right\">".$this->Fungsi->fuang($dtr['kekurangan'])."</td>";
		  $tableContent .= "<td border=\"1\" align=\"right\">".$this->Fungsi->fuang($dtr['subtotal'] + $dtr['ongkir'] + $dtr['infaq'])."</td>";
		  
		 if($numCount == $limitView){
				//$tableContent .= "</table>";
				//$tableContent .="<tr>";
				//$tableContent .="<td colspan=\"3\"></td>"
				//$tableContent .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">".$this->Fungsi->fuang($jmltotorder)."</font></td>";
				//$tableContent .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">Total Barang</font></td>";
				//$tableContent .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">Total Infaq</font></td>";
				//$tableContent .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">Total Keuntungan</font></td>";
				//$tableContent .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">Total Penambahan</font></td>";
				//$tableContent .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">Total Kekurangan</font></td>";
				//$tableContent .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">Total Kas Masuk</font></td>";
				//$tableContent .= "</tr>";
				$tableContent .= "<tr><td colspan=\"11\">Ini Numcount $numCount</td>";
				$tableContent .= "</table>";
				$pdf->setFont("times","",9);
				$pdf->setXY(15,45);	
				$pdf->htmltable($tableContent);
          } 
		  if($no == $limitView){
		        $tableContent .= "<tr><td colspan=\"11\">Ini No $no NumCount $numCount</td>";
				$tableContent .= "</table>";
			
				$pdf->setFont("times","",9);
				$pdf->setXY(15,45);		
				$pdf->htmltable($tableContent);
		  } 
          if($numItems == $numCount){
				$pdf->setFont("times","B",9);
				$pdf->setXY(15,45);
				//$pdf->SetAutoPageBreak(true,60);
				$tableContent .= "<tr><td colspan=\"11\">Ini numitems $numItems dan jumlah no nya = $no</td>";
				$tableContent .= "</table>";
				$pdf->htmltable($tableContent);
				if($no > $batasprinttotal) {
				    $pdf->AddPage("L","A4");
				//$pdf->setMargins(50,40,5);
					$pdf->setMargins(15,15,20);
					$pdf->defaultFontFamily = 'times';
					$pdf->defaultFontStyle  = '';
					$pdf->defaultFontSize   = 9;
					$pdf->SetFont($pdf->defaultFontFamily, $pdf->defaultFontStyle, $pdf->defaultFontSize);
					$pdf->setXY(100,48);
				} 
				$tableFooter = "<table width='70%'><tr>";
				$tableFooter .= "<td colspan=\"11\"></td></tr><tr>";
				$tableFooter .= "<td border=\"1\" bgcolor=\"#00b0f0\" width=\"3%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Total Order</font></td>";
				$tableFooter .= "<td border=\"1\" bgcolor=\"#00b0f0\" width=\"15%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Total Barang</font></td>";
				$tableFooter .= "<td border=\"1\" bgcolor=\"#00b0f0\" width=\"15%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Total Infaq</font></td>";
				$tableFooter .= "<td border=\"1\" bgcolor=\"#00b0f0\" width=\"20%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Total Keuntungan</font></td>";
				$tableFooter .= "<td border=\"1\" bgcolor=\"#00b0f0\" width=\"30%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Total Penambahan</font></td>";
				$tableFooter .= "<td border=\"1\" bgcolor=\"#00b0f0\" width=\"20%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Total Kekurangan</font></td>";
				$tableFooter .= "<td border=\"1\" bgcolor=\"#00b0f0\" width=\"15%\" align=\"center\" valign=\"middle\"><font family=\"Times\" style=\"bold\">Total Kas Masuk</font></td>";
				$tableFooter .= "</tr><tr>";
				$tableFooter .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">".$this->Fungsi->fuang($jmltotorder)."</font></td>";
				$tableFooter .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">Total Barang</font></td>";
				$tableFooter .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">Total Infaq</font></td>";
				$tableFooter .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">Total Keuntungan</font></td>";
				$tableFooter .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">Total Penambahan</font></td>";
				$tableFooter .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">Total Kekurangan</font></td>";
				$tableFooter .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">Total Kas Masuk</font></td>";
				$tableFooter .= "</tr></table>";
				//$pdf->setMargins(100,15,20);
				$pdf->htmltable($tableFooter);
		  }
		  $numCount++;
	  }
	  	
	  $pdf->Output("Laporan-Kas-Masuk.pdf","I");
   } 

}
?>
