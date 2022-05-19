<?php
class controllerLapOrder
{
	private $model;
	private $Fungsi;
	private $data = array();

	function __construct()
	{
		$this->model = new modelLapOrder();
		$this->Fungsi = new FungsiUmum();
	}

	public function tampilData()
	{

		$result 			= array();
		$filter				= array();
		$data 				= [];

		$data['bulan'] 	= isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
		$data['tahun'] 	= isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
		$data['status'] = isset($_GET['status']) ? $_GET['status'] : '';
		/*
		if($status!='' && $status!='0') $filter[] = " _order.status_id= '".trim(strip_tags(urlencode($status)))."'";
		if($bulan!='') $filter[] = " MONTH(pesanan_tgl)= '".trim(strip_tags(urlencode($bulan)))."'";
		if($tahun!='') $filter[] = " YEAR(pesanan_tgl)= '".trim(strip_tags(urlencode($tahun)))."'";
	
	
		if(!empty($filter))	$where = implode(" and ",$filter);
	
		*/


		return $this->model->getOrder($data);
	}

	public function tampilOrderDaily()
	{

		$modelsetting = new modelSetting();
		$data 				= [];
		/*
		$settings = $modelsetting->getSettingByKey('config_orderselesai');
		$status_order_selesai = $settings['setting_value'];
		*/
		$settings = $modelsetting->getSettingByKeys(array('config_orderselesai', 'config_shippingstatus'));

		foreach ($settings as $setting) {
			if ($setting['setting_key'] == 'config_orderselesai') {
				$status_order_selesai = $setting['setting_value'];
			}
			if ($setting['setting_key'] == 'config_shippingstatus') {
				$status_order_kirim = $setting['setting_value'];
			}
		}


		$data['tanggal1'] 	= isset($_GET['tanggal1']) ? $_GET['tanggal1'] : date('Y-m-d');
		$data['tanggal2'] 	= isset($_GET['tanggal2']) ? $_GET['tanggal2'] : date('Y-m-d');
		$data['customer_id'] 	= isset($_GET['customer_id']) ? $_GET['customer_id'] : '';
		$data['status'] = isset($_GET['status']) ? $_GET['status'] : $status_order_selesai;
		$data['status_order_kirim'] = $status_order_kirim;

		return $this->model->getOrderDaily($data);
	}
	public function getAutoCompleteCustomer()
	{
		$json = array();
		$modelcustomer = new modelCustomer();
		$nama_cust = isset($_GET['customer']) ? $_GET['customer'] : '';
		$results = $modelcustomer->getCustomerByName($nama_cust);
		if ($results) {
			foreach ($results as $result) {
				$json[] = array(
					'cust_id' => $result['cust_id'],
					'cust_nama'        => strip_tags(html_entity_decode($result['cust_nama'], ENT_QUOTES, 'UTF-8'))
				);
			}
			$sort_order = array();

			foreach ($json as $key => $value) {
				$sort_order[$key] = $value['cust_nama'];
			}
			array_multisort($sort_order, SORT_ASC, $json);
		}

		echo json_encode($json);
	}
	public function cetakLaporan()
	{
		$hasil 	= '';
		$bulan 	= isset($_POST['bulan']) ? $_POST['bulan'] : '';
		$tahun 	= isset($_POST['tahun']) ? $_POST['tahun'] : '';

		$data	    = array();
		$settoko 	= $this->Fungsi->fcaridata2("_setting_toko", "toko_nama,status_print,toko_alamat", "setid <> ''");
		$data['toko']			= $settoko[0];
		$t 					= explode(",", $settoko[2]);
		$data['t1']	= $t[0];
		$data['t2']	= trim($t[1]) . ', ' . trim($t[2]);
		$data['t3']	= trim($t[3]) . ', ' . trim($t[4]);
		$data['bulan']		= $this->Fungsi->cariBulan($bulan - 1);
		$data['tahun']		= $tahun;
		$data['judul'] 		= 'LAPORAN KAS MASUK';
		$periode				= strtoupper("Periode " . $data['bulan'] . " " . $data['tahun']);
		$status				= $settoko[1];

		$where = '';
		$filter = array();
		if ($bulan != '') $filter[] = " MONTH(pesanan_tgl)= '" . trim(strip_tags(urlencode($bulan))) . "'";
		if ($tahun != '') $filter[] = " YEAR(pesanan_tgl)= '" . trim(strip_tags(urlencode($tahun))) . "'";
		if ($status != '') $filter[] = " status_id= '" . $status . "'";

		if (!empty($filter))	$where = implode(" and ", $filter);

		$dataReport = $this->model->getKasMasuk($where);


		$tableHeader = "<table width=100%><tr>";
		$tableHeader .= "<td colspan=\"11\" align=\"center\"><font family=\"Times\" style=\"bold\" size=\"15\">" . $data['judul'] . "</font></td></tr><tr>";
		$tableHeader .= "<td colspan=\"11\" align=\"center\"><font family=\"Times\" size=\"12\">" . $periode . "</font></td></tr><tr>";
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

		$pdf = new pdfLapOrder();
		$pdf->AliasNbPages();
		$pdf->setHeader($data);

		$no = 0;
		$limitView = 2;
		$batasprinttotal = 10;
		$numCount = 1;
		$jmltotorder = 0;

		$numItems = count($dataReport);

		if ($numItems == 0) {
			$pdf->AddPage("L", "A4");
			//$pdf->setMargins(50,40,5);
			$pdf->setMargins(20, 20, 20);
			$pdf->defaultFontFamily = 'times';
			$pdf->defaultFontStyle  = '';
			$pdf->defaultFontSize   = 9;
			$pdf->SetFont($pdf->defaultFontFamily, $pdf->defaultFontStyle, $pdf->defaultFontSize);
		}

		foreach ($dataReport as $dtr) {
			if ($no == $limitView) {
				$no = 0;
			}
			if ($no == 0) {
				$pdf->AddPage("L", "A4");
				//$pdf->setMargins(50,40,5);
				$pdf->setMargins(15, 15, 20);
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
			$tableContent .= "<td border=\"1\" align=\"center\">" . $numCount . "</td>";
			$tableContent .= "<td border=\"1\" align=\"center\">" . $this->Fungsi->ftanggalFull2($dtr['tgl']) . "</td>";
			$tableContent .= "<td border=\"1\" align=\"center\">" . $dtr['noorder'] . "</td>";
			$tableContent .= "<td border=\"1\" align=\"right\">" . $dtr['jml'] . "</td>";
			$tableContent .= "<td border=\"1\" align=\"right\">" . $this->Fungsi->fuang($dtr['subtotal']) . "</td>";
			$tableContent .= "<td border=\"1\" align=\"right\">" . $this->Fungsi->fuang($dtr['ongkir']) . "</td>";
			$tableContent .= "<td border=\"1\" align=\"right\">" . $this->Fungsi->fuang($dtr['infaq']) . "</td>";
			$tableContent .= "<td border=\"1\" align=\"right\"></td>";
			$tableContent .= "<td border=\"1\" align=\"right\">" . $this->Fungsi->fuang($dtr['penambahan']) . "</td>";
			$tableContent .= "<td border=\"1\" align=\"right\">" . $this->Fungsi->fuang($dtr['kekurangan']) . "</td>";
			$tableContent .= "<td border=\"1\" align=\"right\">" . $this->Fungsi->fuang($dtr['subtotal'] + $dtr['ongkir'] + $dtr['infaq']) . "</td>";

			if ($numCount == $limitView) {
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
				$pdf->setFont("times", "", 9);
				$pdf->setXY(15, 45);
				$pdf->htmltable($tableContent);
			}
			if ($no == $limitView) {
				$tableContent .= "<tr><td colspan=\"11\">Ini No $no NumCount $numCount</td>";
				$tableContent .= "</table>";

				$pdf->setFont("times", "", 9);
				$pdf->setXY(15, 45);
				$pdf->htmltable($tableContent);
			}
			if ($numItems == $numCount) {
				$pdf->setFont("times", "B", 9);
				$pdf->setXY(15, 45);
				//$pdf->SetAutoPageBreak(true,60);
				$tableContent .= "<tr><td colspan=\"11\">Ini numitems $numItems dan jumlah no nya = $no</td>";
				$tableContent .= "</table>";
				$pdf->htmltable($tableContent);
				if ($no > $batasprinttotal) {
					$pdf->AddPage("L", "A4");
					//$pdf->setMargins(50,40,5);
					$pdf->setMargins(15, 15, 20);
					$pdf->defaultFontFamily = 'times';
					$pdf->defaultFontStyle  = '';
					$pdf->defaultFontSize   = 9;
					$pdf->SetFont($pdf->defaultFontFamily, $pdf->defaultFontStyle, $pdf->defaultFontSize);
					$pdf->setXY(100, 48);
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
				$tableFooter .= "<td border=\"1\" align=\"center\" valign=\"middle\"><font family=\"Times\">" . $this->Fungsi->fuang($jmltotorder) . "</font></td>";
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

		$pdf->Output("Laporan-Kas-Masuk.pdf", "I");
	}
}
