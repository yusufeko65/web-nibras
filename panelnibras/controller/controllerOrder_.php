<?php

require_once('../../../includes/phpmailer/class.phpmailer.php');



class controllerOrder

{

	private $page;

	private $rows;

	private $offset;

	private $model;

	private $Fungsi;

	private $data = array();

	private $idlogin;



	private $dataProduk;

	private $bank;

	private $dataShipping;

	private $kirimemail;

	private $error;

	//private $tipemember;



	public function __construct()

	{

		$this->model = new modelOrder();

		$this->Fungsi = new FungsiUmum();

		$this->idlogin = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : '';

		$this->dataProduk = new modelProduk();

		$this->dataShipping = new modelShipping();

		$this->dataCustomer = new modelCustomer();

		$this->bank = new modelBank();

	}



	public function cekOrder($noorder)

	{

		return $this->model->cekOrder($noorder, $this->idlogin);

	}

	public function simpanneworder()

	{

		$status = '';

		$pesan = '';

		$data = [];

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {



			foreach ($_POST as $key => $value) {

				if (!is_array($value)) {

					$value = trim($value);

				}

				$data["{$key}"] = $value;

			}

			$data['idgrup'] = $data['gruppelanggan'];

			$customer = $this->dataCustomer->getResellerByID($data['idmember']);



			$kodeakhir 	= $this->Fungsi->fIdAkhir('_order', 'CONVERT(pesanan_no,SIGNED)');

			$data['nopesanan'] = sprintf('%08s', $kodeakhir + 1);



			$data['cust_id'] = $customer['cust_id'];



			$data['cust_grup'] = $customer['cust_grup_id'];

			$data['ipaddress'] = $this->Fungsi->get_client_ip();

			$data['potongan_kupon'] = 0;

			$data['kode_kupon'] = '-';

			$data['poin'] = isset($data['poin']) && $data['poin'] != '' ? $data['poin'] : 0;

			$data['potdeposito'] = isset($data['potdeposito']) && $data['potdeposito'] != '' ? $data['potdeposito'] : 0;

			/*

			$dataservis 			= $this->dataShipping->getServisByIdserv($data);

			$datashipping 			= $this->dataShipping->getShippingByIdServ($data);

			$data['servis_id'] 		= isset($dataservis['servis_id']) && !empty($dataservis['servis_id'])? $dataservis['servis_id'] : $data['serviskurir'];

			$data['servis_code'] 	= isset($dataservis['servis_code']) ? $dataservis['servis_code'] : $datashipping['servis_code'];

			$data['shipping']		= $datashipping['shipping_kode'];

			$data['hrgkurir_perkilo'] = isset($dataservis['hrg_perkilo']) ? $dataservis['hrg_perkilo'] : 0;

			$data['kurir_konfirm'] = $datashipping['shipping_konfirmadmin'];

			*/



			$serviskurir 	= isset($data['serviskurir']) ? explode("::", $data['serviskurir']) : array();

			$servis_id	 	= isset($serviskurir[0]) ? $serviskurir[0] : 0;

			$tarif		 	= isset($serviskurir[1]) ? $serviskurir[1] : 'Konfirmasi Admin';

			$shipping_kode	= isset($serviskurir[2]) ? $serviskurir[2] : 0;

			$servis_kode	= isset($serviskurir[3]) ? $serviskurir[3] : '';



			$data['servis_id']			= $servis_id;

			$data['shipping']			= $shipping_kode;

			$data['hrgkurir_perkilo']	= 0;

			$data['servis_code']		= $servis_kode;



			$data['tgltransaksi'] = date('Y-m-d H:i:s');

			$data['tglkirim']	= '0000-00-00 00:00';

			$data['nomor_awb']  = '-';



			/*

			$totberat = (int)$data['totberat'] / 1000;

			if($totberat < 1) $totberat = 1;

			

			$jarakkoma = 0;

			if($totberat > 1) {

				$berat = floor($totberat);

				$jarakkoma = $totberat - $berat;

			}

			

			$batas = isset($dataservis['shipping_bataskoma']) ? $dataservis['shipping_bataskoma'] : 0;

			if($datashipping['shipping_konfirmadmin'] == '0') {

				

				if($jarakkoma > $batas) $totberat = ceil($totberat);

				else $totberat = floor($totberat);

				$tarif = $totberat * $data['hrgkurir_perkilo'];

				$captiontarif = 'Rp. '.$this->Fungsi->fuang($tarif);

			} else {

				$tarif = 0;

				$captiontarif = 'Konfirmasi Admin';

			}

			$data['tarifkurir'] = $tarif;

			*/





			if ($tarif == 'Konfirmasi Admin') {

				$data['kurir_konfirm'] = '1';

				$captiontarif = 'Konfirmasi Admin';

				$data['tarifkurir'] = 0;

			} else {

				$data['kurir_konfirm'] = '0';

				$captiontarif = 'Rp. ' . $this->Fungsi->fuang($tarif);

				$data['tarifkurir'] = $tarif;

			}





			$subtotalbelanja = $data['subtotal'] + $data['tarifkurir'];



			$keranjang 		= $this->showminiCart($_SESSION['hsadmincart'][$data['idmember']], $data['idgrup'], $data['idmember']);

			$totalitem 		= $keranjang['items'];

			$carts 			= $keranjang['carts'];

			$subtotal 		= 0;

			$i 				= 0;

			$totberat 		= 0;

			$totjumlah		= 0;

			$totgetpoin 	= 0;

			$datacart 		= $this->Fungsi->urutkan($carts, 'product_id');



			$tabel = "	<table style=\"font-size:13px;

									   font-family:'Helvetica Neue',

													'Helvetica',Helvetica,Arial,sans-serif;

									   max-width:100%;

									   border-collapse:collapse;

									   border-spacing:0;

									   width:100%;

									   background-color:#ffffff;

									   margin:0;

									   padding:0\" width=\"100%\" bgcolor=\"#ffffff\" 

									  border=\"1\" cellpadding=\"3\" cellspacing=\"3\">";

			$tabel .= "<thead style=\"margin:0;padding:0\">";

			$tabel .= "<tr style=\"margin:0;padding:0\">";

			$tabel .= "<th style=\"text-align:center;background-color:#ffffff;margin:0;padding:5px 10px\" align=\"center\" 

						 bgcolor=\"#ffffff\">Nama Produk</th>";

			$tabel .= "<th style=\"text-align:center;background-color:#ffffff;margin:0;padding:5px 10px\" align=\"center\" bgcolor=\"#ffffff\">Jumlah</th>";

			$tabel .= "<th style=\"text-align:center;background-color:#ffffff;margin:0;padding:5px 10px\" align=\"center\" bgcolor=\"#ffffff\">Berat</th>";

			$tabel .= "<th style=\"text-align:center;background-color:#ffffff;margin:0;padding:5px 10px\" align=\"center\" bgcolor=\"#ffffff\">Harga Normal</th>";

			$tabel .= "<th style=\"text-align:center;background-color:#ffffff;margin:0;padding:5px 10px\" align=\"center\" bgcolor=\"#ffffff\">Diskon</th>";

			$tabel .= "<th style=\"text-align:center;background-color:#ffffff;margin:0;padding:5px 10px\" align=\"center\" bgcolor=\"#ffffff\">Harga</th>";

			$tabel .= "<th style=\"text-align:center;background-color:#ffffff;margin:0;padding:5px 10px\" align=\"center\" bgcolor=\"#ffffff\">Subtotal</th>";

			$tabel .= "</tr></thead>";



			$tabel .= "<tbody style=\"margin:0;padding:0\">";



			foreach ($datacart as $dc) {



				$data['orderdetail'][] = array(

					"product_id" => $dc["product_id"],

					"qty" => $dc["qty"],

					"harga" => $dc["harga"],

					"hrgsatuan" => $dc["hargasatuan"],

					"berat" => $dc['berat'],

					"persen_diskon_satuan" => $dc['persen_diskon_prod'],

					"sale" => $dc['sale'],

					"warna" => $dc['warna'],

					"ukuran" => $dc['ukuran'],

					"get_poin" => $dc['poin'],

					"hrgtambahan" => $dc["hargatambahan"]

				);



				$data['updatestokoption'][] = array(

					"product_id" => $dc["product_id"],

					"qty" => $dc["qty"],

					"idwarna" => $dc['warna'],

					"idukuran" => $dc['ukuran']

				);



				$data['updatestok'][] = array("product_id" => $dc['product_id'], "qty" => $dc["qty"]);



				$poin = (int) $dc['poin'] * (int) $dc['qty'];

				$totgetpoin += (int) $poin;

				$subtotal	+= $dc['total'];

				$totberat   += $dc['berat'];

				$totjumlah  += (int) $dc['qty'];



				$tabel .= "<tr style=\"margin:0;padding:0\">";

				$tabel .= "<td style=\"margin:0;padding:10px;\" valign=\"top\">" . $dc['product'];

				if ($dc['warna'] != '') {

					$tabel  .= '<br style="margin:0;padding:0">' . $dc['warna_nama'];

				}

				if ($dc['ukuran'] != '') {

					$tabel  .= '<br style="margin:0;padding:0">' . $dc['ukuran_nama'];

				}

				$persentotal = (int) $dc['persen_diskon_prod'] + (int) $dc['diskon_cust'];

				$harganormal = $this->Fungsi->fFormatuang($dc["hargasatuan"]);

				if ($dc["hargatambahan"] > 0) {

					$harganormal .= '<br><small> + ' . $this->Fungsi->fFormatuang($dc["hargatambahan"]) . '<br>(Tambahan Harga)</small>';

				}

				$tabel .= "</td>";

				$tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\">" . $dc['qty'] . "</td>";

				$tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\">" . $dc['berat'] . " Gram</td>";

				$tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\">" . $harganormal . "</td>";

				$tabel .= "<td style=\"text-align:center;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\">" . $persentotal . "% </td>";

				$tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\">" . $this->Fungsi->fFormatuang($dc['harga']) . "</td>";

				$tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\">" . $this->Fungsi->fFormatuang($dc['total']) . "</td>";

				$tabel .= "</tr>";

			}



			$data['subtotal'] = $subtotal;

			$data['totjumlah'] = $totjumlah;

			$data['totgetpoin'] = $totgetpoin;



			$data['updatedeposito'] = false;

			$data['InsertDepositoDetail'] = false;



			$data['dropship'] = '0';

			if ($customer['cg_dropship'] == '1') {

				if (trim($data['nama_pengirim']) != trim($data['nama_penerima']) && trim($data['alamat_pengirim']) != trim($data['alamat_penerima']) && trim($data['telp_pengirim']) != trim($data['telp_penerima'])) {

					$data['dropship'] = '1';

				}

			}

			$simpan = $this->model->simpanneworder($data);

			if ($simpan) {

				$status = 'success';

				$pesan  = 'Order Anda akan segera diproses';

				$modelsetting = new modelSetting();

				$datasetting  = $modelsetting->getSetting();

				if ($datasetting) {

					foreach ($datasetting as $st) {

						$key 	= $st['setting_key'];

						$value 	= $st['setting_value'];

						$$key	= $value;

					}

				}

				/* kirim email */

				$from 			= isset($config_emailnotif) ? $config_emailnotif : '';

				$from_name 		= isset($config_namatoko) ? $config_namatoko : '';

				$subject		= 'Nota Tagihan ' . $data['nopesanan'];

				$to 			= $customer['cust_email'];

				$headernota 	= isset($config_headernotaemail) ? $config_headernotaemail : '';

				$notabelanja	= isset($config_notabelanja) ? $config_notabelanja : '';



				/* table subtotal */

				$tabel .= "<tr style=\"margin:0;padding:0\">";

				$tabel .= "<td colspan=\"6\" style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>Sub Total</b></td>";

				$tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>" . $this->Fungsi->fFormatuang($subtotal) . "</b></td>";

				$tabel .= '</tr>';



				/* kurir */

				$tabel	.= "<tr style=\"margin:0;padding:0\">";

				$tabel .= "<td colspan=\"6\" style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>" . $servis_kode . ' - ' . $shipping_kode . "</b> </td>";

				$tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>" . $captiontarif . "</b></td>";

				$tabel .= '</tr>';

				/* biaya packing */

				if($data['biaya_packing'] != 0){
					
					$tabel	.= "<tr style=\"margin:0;padding:0\">";

					$tabel .= "<td colspan=\"6\" style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>Biaya Packing</b> </td>";

					$tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>" . $data['biaya_packing'] . "</b></td>";

					$tabel .= '</tr>';
				}

				/* total */

				$tabel	.= '<tr>';

				$tabel .= "<td colspan=\"6\" style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>Total</b></td>";

				if ($captiontarif == 'Konfirmasi Admin') {

					$grandtotal = $captiontarif;

				} else {

					$grandtotal = 'Rp. ' . $this->Fungsi->fuang(($subtotal + $data['tarifkurir'] + $data['biaya_packing']) - $data['poin'] - $data['potdeposito']);

				}

				$tabel 	.= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>" . $grandtotal . "</b></td>";

				$tabel	.= '</tr>';

				$tabel 	.= '</tbody>';

				$tabel 	.= '</table>';



				/* pengirim */

				$tablewilayah = "_kecamatan kc left join _kabupaten kb on kc.kabupaten_id = kb.kabupaten_id 

								left join _provinsi p on kb.provinsi_id = p.provinsi_id";



				$wilayah_pengirim = $this->Fungsi->fcaridata2($tablewilayah, "kecamatan_nama,kabupaten_nama,provinsi_nama", "kc.kecamatan_id='" . $data['kecamatan_pengirim'] . "'");



				$alamatpengirim	  = "<b>Alamat Pengirim</b> <br>";

				$alamatpengirim  .= trim($data['nama_pengirim']) . '<br>';

				$alamatpengirim  .= 'Hp. ' . $data['telp_pengirim'] . '<br>';

				/*

				$alamatpengirim  .= $wilayah_pengirim['provinsi_nama'].', ';

				$alamatpengirim  .= $wilayah_pengirim['kabupaten_nama'].', ';

				$alamatpengirim  .= 'Kec. '.$wilayah_pengirim['kecamatan_nama'].', ';

				

				if($data['kelurahan_pengirim'] != '') {

				

					$alamatpengirim .= 'Kelurahan. '.$data['kelurahan_pengirim'];

					

				}

				$alamatpengirim .= '<br>';

				if($data['kodepos_pengirim'] != '') {

					$alamatpengirim .= 'Kode Pos '.$data['kodepos_pengirim'];

				}

				$alamatpengirim .= '<br>';

				$alamatpengirim .= 'Hp. '. $data['telp_pengirim'];

				*/

				/* penerima */

				$tablewilayah = "_kecamatan kc left join _kabupaten kb on kc.kabupaten_id = kb.kabupaten_id 

								left join _provinsi p on kb.provinsi_id = p.provinsi_id";



				$wilayah_penerima = $this->Fungsi->fcaridata2($tablewilayah, "kecamatan_nama,kabupaten_nama,provinsi_nama", "kc.kecamatan_id='" . $data['kecamatan_penerima'] . "'");



				$alamatpenerima   = '<b>Alamat Penerima</b> <br>';

				$alamatpenerima  .= $data['nama_penerima'] . ' <br>';

				$alamatpenerima  .= $data['alamat_penerima'] . ' <br>';

				$alamatpenerima  .= $wilayah_penerima['provinsi_nama'] . ', ';

				$alamatpenerima  .= $wilayah_penerima['kabupaten_nama'] . ', ';

				$alamatpenerima  .= 'Kec. ' . $wilayah_penerima['kecamatan_nama'];



				if ($data['kelurahan_penerima'] != '') {



					$alamatpenerima .= ', Kelurahan. ' . $data['kelurahan_penerima'];

				}

				$alamatpenerima .= '<br>';

				if ($data['kodepos_penerima'] != '') {

					$alamatpenerima .= 'Kode Pos ' . $data['kodepos_penerima'];

				}

				$alamatpenerima .= '<br>';

				$alamatpenerima .= 'Hp. ' . $data['telp_penerima'];



				$rekeningbank = new modelRekening();

				$databanks = '';



				$rekening = $rekeningbank->getRekening();

				foreach ($rekening as $rek) {

					$databanks .= '<b>' . $rek['bank'] . '</b><br>No. Rek. ' . $rek['norek'] . '<br>A/n ' . $rek['atasnama'] . '<br>Cabang. ' . $rek['cabang'] . '<br><br>';

				}



				$message   = str_replace("[PELANGGAN]", $customer['cust_nama'], $notabelanja);

				$message   = str_replace("[No Order]", $data['nopesanan'], $message);

				$message   = str_replace("[DATA ORDER]", $tabel, $message);

				if ($data['dropship'] == '1') {

					$message   = str_replace("[ALAMAT PENGIRIM]", $alamatpengirim, $message);

				} else {

					$message   = str_replace("[ALAMAT PENGIRIM]", "", $message);

				}

				$message   = str_replace("[ALAMAT PENERIMA]", $alamatpenerima, $message);

				$message   = str_replace("[DATA BANK]", $databanks, $message);

				$message   = str_replace("[NAMAWEBSITE]", $from_name, $message);



				$message_order = $message;

				$session = array("hsadmincart", "qtyadmincart", "wrnadmincart", "ukradmincart");

				$this->Fungsi->hapussession($session);

			} else {

				$status = 'error';

				$pesan  = 'Proses Order Anda tidak berhasil';

				$message_order = '';

			}

		} else {

			$status = 'error';

			$pesan = 'Data tidak valid';

			$message_order = '';

		}

		echo json_encode(array("status" => $status, "result" => $pesan, "msgorder" => $message_order));

	}

	public function hapusprodukorder()

	{

		$delall = '0';

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$data = [];

			foreach ($_POST as $key => $value) {

				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';

			}



			$dataorder = $this->model->getOrderByID($data['nopesanan']);



			if ($dataorder) {

				if ((int) $data['idwarna'] > 0 || (int) $data['idukuran'] > 0) {

					$data['stokoptionbertambah'] = array(

						"nopesanan" => $data['nopesanan'],

						"qty" => $data['qty'],

						"idukuran" => $data['idukuran'],

						"idwarna" => $data['idwarna'],

						"idproduk" => $data['product_id']

					);

				}

				$data['updatestokbertambah'] = array(

					"idproduk" => $data['product_id'],

					"qty" => $data['qty']

				);

				$data['hapusprodukorder'] = $data['iddetail'];

				$produk_in_order = $this->model->getProdukOrderOption('', '', '', $data['nopesanan']);



				foreach ($produk_in_order as $order) {

					if ((int) $order['iddetail'] != (int) $data['iddetail']) {

						$produk = $this->dataProduk->getProdukByID($order['idproduk']);

						$produk_to_cart['stok'] = $produk['jml_stok'];

						$produk_to_cart['produk'] = $produk['nama_produk'];

						$produk_to_cart['product_id']  = $order['idproduk'];

						$produk_to_cart['jumlah']	= $order['jml'];

						$produk_to_cart['idmember'] = $data['idmember'];

						$produk_to_cart['tipe'] 	= $data['idgrup'];

						$produk_to_cart['persen_diskon'] = $produk['persen_diskon'];

						if ($order['ukuran'] == '' && $order['ukuran'] == null) {

							$order['ukuran'] = '0';

						}

						if ($order['warna'] == '' && $order['warna'] == null) {

							$order['warna'] = '0';

						}

						$produk_to_cart['option']   = array($order['ukuran'], $order['warna']);

						$this->addCartFromProdukOrder($produk_to_cart);

					}

				}

				if (isset($_SESSION['hsadmincart'])) {

					$carts = $this->showminiCart($_SESSION['hsadmincart'][$data['idmember']], $data['idgrup'], $data['idmember']);

					$totalitem	= $carts['items'];

					$datacart 	= $carts['carts'];

					$subtotal 	= 0;

					$i      	= 0;

					$totberat 	= 0;

					$totjumlah	= 0;

					$totgetpoin = 0;

					$zprod = [];

					$dtproduk = [];

					foreach ($datacart as $c) {

						if (!in_array($c['product_id'], $zprod)) {

							$zprod[] = $c['product_id'];

							$dtproduk[$c['product_id']] = $this->dataProduk->getProdukByID($c['product_id']);

						}

						//if(($c['warna'] != '' && $c['warna'] != '0')  || ($c['ukuran'] != '' && $c['ukuran'] != '0')) {

						$data['orderprodukoption'][] = array(

							"idwarna" => $c['warna'],

							"idukuran" => $c['ukuran'],

							"qty" => $c['qty'],

							"harga" => $c['harga'],

							"satuan" => $dtproduk[$c['product_id']]['hrg_jual'],

							"persen_diskon_satuan" => $dtproduk[$c['product_id']]['persen_diskon'],

							"berat" => $c['berat'],

							"nopesanan" => $data['nopesanan'],

							"idproduk" => $c['product_id']

						);





						$poinku  = (int) $c['poin'] * (int) $c['qty'];

						$subtotal	+= $c['total'];

						$totberat   += $c['berat'];

						$totjumlah  += (int) $c['qty'];

						$totgetpoin += (int) $poinku;



						$i++;

					}

					$data['subtotal'] = $subtotal;

					$data['totjumlah'] = $totjumlah;

					$data['totgetpoin'] = $totgetpoin;

					$data['totberat'] 	= $totberat;







					$data['kecamatan_penerima'] = $dataorder['kecamatan_penerima'];

					$data['kabupaten_penerima'] = $dataorder['kota_penerima'];

					$data['propinsi_penerima'] 	= $dataorder['propinsi_penerima'];

					$data['serviskurir']		= $dataorder['servis_kurir'];



					$data['shipping_kode']		= $dataorder['shipping_kode'];

					$data['shipping'] 			= $dataorder['shipping_kdrajaongkir'];

					$data['kurir_konfirm'] 		= $dataorder['shipping_konfirmadmin'];

					$data['hrgkurir_perkilo'] 	= 0;

					$data['servis_code']		= $dataorder['servis_code'];

					$data['servis_id']			= $dataorder['servis_kurir'];



					$totalberat = (int) $totberat / 1000;

					if ($totalberat < 1) $totalberat = 1;



					if ($data['kurir_konfirm'] != '1') {

						$modelsetting = new modelSetting();

						$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin', 'config_apiurlongkir', 'config_apikeyongkir'));

						foreach ($setting as $st) {

							$key 	= $st['setting_key'];

							$value 	= $st['setting_value'];

							$$key = $value;

						}

						$shipping = $this->dataShipping->getShippingRajaOngkir();

						foreach ($shipping as $ship) {



							$kurir["{$ship['shipping_kode']}"]["{$ship['servis_code']}"] = array("servis" => $ship['servis_id'], "shipping_code" => $ship['shipping_kdrajaongkir']);

						}





						$data['curl'] 		= curl_init();

						$data['urlcurl'] 	= $config_apiurlongkir . 'cost';

						$data['postfield'] 	= "origin=$config_lokasiorigin&originType=city&destination=" . $data['kecamatan_penerima'] . "&destinationType=subdistrict&weight=$totberat&courier=" . strtolower($data['shipping']);



						$data['httpheader'] =	array(

							"content-type: application/x-www-form-urlencoded",

							"key: " . $config_apikeyongkir

						);

						$grab = $this->Fungsi->grabData($data);

						$datagrab = json_decode($grab, true);



						$jmldata 		= count($datagrab['rajaongkir']['results']);

						if ($jmldata > 0) {

							for ($i = 0; $i < $jmldata; $i++) {



								$jmlservis = count($datagrab['rajaongkir']['results'][$i]['costs']);

								for ($x = 0; $x < $jmlservis; $x++) {

									$kode_ship = strtoupper($datagrab['rajaongkir']['results'][$i]['code']);

									if ($data['serviskurir'] == $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"]) {

										$dataship[] = array(

											"shipping_code_rajaongkir" => $kode_ship,

											"shipping_code" => $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["shipping_code"],

											"servis_id" => $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"],

											"servis_code" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['service'],

											"tarif" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['value'],

											"etd" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['etd'],

											"shipping_konfirmadmin" => 0

										);

										break;

									}

								}

							}

						}

						$data['tarifkurir'] = isset($dataship[0]['tarif']) ? $dataship[0]['tarif'] : 0;

					} else {

						$data['tarifkurir'] = 0;

					}

				} else {

					$data['subtotal'] = 0;

					$data['totjumlah'] = 0;

					$data['totgetpoin'] = 0;

					$data['totberat'] = 0;

					$data['tarifkurir'] = 0;

					$data['hrgkurir_perkilo'] = 0;

				}

				$data['sisa_dari_poin'] = 0;

				$data['dari_poin'] = 0;

				$subtotalbelanja = $data['subtotal'] + $data['tarifkurir'];

				if ($dataorder['dari_poin'] > 0) {

					if ($dataorder['dari_poin'] > $subtotalbelanja) {

						$data['dari_poin'] = $subtotalbelanja;

						$data['sisa_dari_poin'] = $dataorder['dari_poin'] - $subtotalbelanja;

					} else {

						$data['dari_poin'] = $dataorder['dari_poin'];

					}

				}

				$data['sisa_dari_potdeposito'] = 0;

				$data['potdeposito'] = $dataorder['dari_deposito'];

				if ($data['jmlproduk'] > 1) {

					if ($dataorder['dari_deposito'] > 0) {

						$subtotalbelanja = $subtotalbelanja - $data['dari_poin'];

						if ($dataorder['dari_deposito'] > $subtotalbelanja) {

							$data['potdeposito'] = $subtotalbelanja;

						} else {

							$data['potdeposito'] = $dataorder['dari_deposito'];

						}

						$data['keterangan'] = 'Menggunakan Saldo di Order ' . sprintf('%08s', (int) $data["nopesanan"]);

					} else {

						$data['keterangan'] = '';

					}

				} else {

					$data['keterangan_cancel'] = 'Pembatalan Order ' . sprintf('%08s', (int) $data["nopesanan"]);

					$data['dari_deposito'] = 0;

					$data['dari_poin'] = 0;

				}



				$where = "setting_key IN ('config_orderstatus','config_ordercancel')";

				$status  = $this->Fungsi->fcaridata3('_setting', 'setting_key,setting_value', $where);

				$datastatus = [];

				if ($status) {

					foreach ($status as $sts) {

						$datastatus["{$sts['setting_key']}"] = $sts['setting_value'];

					}

				}

				$data['status_cancel'] = $datastatus['config_ordercancel'];

				$simpan = $this->model->deleteProdukOrder($data);

				if ($simpan['status'] == 'success') {

					$status = 'success';

					$result  = 'Berhasil menyimpan data';

					$delall = $simpan['delall'];

					$session = array("hsadmincart", "qtyadmincart", "qtylamaadmincart", "wrnadmincart", "ukradmincart");

					$this->Fungsi->hapusSession($session);

				} else {

					$status = 'error';

					$result  = 'Proses menyimpan data tidak berhasil';

				}

			} else {

				$status = 'error';

				$result = 'No. Order tidak ada';

			}

		} else {

			$status = 'error';

			$result = 'Data tidak valid';

		}

		echo json_encode(array("status" => $status, "result" => $result, "delall" => $delall));

	}



	public function formEditDeposito()

	{

		$totaldeposito = 0;

		$dataorder = [];

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$post  = isset($_POST['data']) ? $_POST['data'] : '';



			$zdata    = explode("::", $post);



			$pesanan_no 	= $zdata[0];

			$pelanggan_id	= $zdata[1];

			$pelanggan_grup	= $zdata[1];



			$dataorder = $this->model->getOrderByID($pesanan_no);

			if ($dataorder && (isset($dataorder['pelanggan_id']) && $dataorder['pelanggan_id'] == $pelanggan_id)) {

				if ($dataorder['cg_deposito'] == '1') {

					$totaldeposito = (int) $this->dataCustomer->totalDepositoById($dataorder['pelanggan_id']) + (int) $dataorder['dari_deposito'];

					$status = 'success';

					$msg = '';

				} else {

					$status = 'error';

					$msg = 'Pelanggan tidak memiliki fasilitas deposito';

				}

			} else {

				$status = 'error';

				$msg = 'No. Order tidak dimiliki Pelanggan yang bersangkutan';

			}

		} else {

			$status = 'error';

			$msg = 'Data invalid';

		}

		return array("status" => $status, "msg" => $msg, "order" => $dataorder, "totaldeposito" => $totaldeposito);

	}



	public function formEditKurir()

	{

		$data = [];

		$dataorder = [];

		$servis = [];

		$services = [];

		$totberat = 0;

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			/*

			foreach ($_POST as $key => $value) {

				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';

			}

			print_r($data);

			*/





			$post  = isset($_POST['data']) ? $_POST['data'] : '';



			$zdata    = explode("::", $post);

			$data['pesanan_no'] = $zdata[0];

			$data['totberat'] = $zdata[1];

			$dataorder = $this->model->getOrderByID($data['pesanan_no']);



			if ($dataorder) {



				$propinsi_penerima = $dataorder['propinsi_penerima'];

				$kabupaten_penerima = $dataorder['kota_penerima'];

				$kecamatan_penerima = $dataorder['kecamatan_penerima'];



				//$servis = $this->dataShipping->getAllServicesAndTarifByWilayah($propinsi_penerima,$kabupaten_penerima,$kecamatan_penerima);

				$modelsetting = new modelSetting();

				$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin', 'config_apiurlongkir', 'config_apikeyongkir'));

				foreach ($setting as $st) {

					$key 	= $st['setting_key'];

					$value 	= $st['setting_value'];

					$$key = $value;

				}



				//$servis_rajaongkir = $this->getAllServicesAndTarifByWilayahRajaOngkir($config_lokasiorigin,$kecamatan,$totberat,$config_apiurlongkir,$config_apikeyongkir);

				$shipping = $this->dataShipping->getShippingRajaOngkir();

				$kurir = [];

				$cekKurir = [];

				//print_r($shipping);

				foreach ($shipping as $ship) {

					//$kurir[] = $ship['shipping_kode'];

					if (!in_array($ship['shipping_kdrajaongkir'], $cekKurir)) {

						$kurir["shipping_kdrajaongkir"][] = $ship['shipping_kdrajaongkir'];



						$cekKurir[] = $ship['shipping_kdrajaongkir'];

					}



					$kurir["shipping_id"][] = $ship['shipping_id'];

					$kurir["{$ship['shipping_kode']}"]["{$ship['servis_code']}"] = array("servis" => $ship['servis_id'], "shipping_code" => $ship['shipping_kdrajaongkir']);

					$kurir["servis_nama"][] = $ship['servis_nama'];

				}



				$kurir["shipping_kdrajaongkir"] = isset($kurir["shipping_kdrajaongkir"]) ? $kurir["shipping_kdrajaongkir"] : array();



				$shipping_code = implode(":", $kurir["shipping_kdrajaongkir"]);



				$data['curl'] 		= curl_init();

				$data['urlcurl'] 	= $config_apiurlongkir . 'cost';

				$data['postfield'] 	= "origin=$config_lokasiorigin&originType=city&destination=$kecamatan_penerima&destinationType=subdistrict&weight=" . $data['totberat'] . "&courier=" . strtolower($shipping_code);



				$data['httpheader'] =	array(

					"content-type: application/x-www-form-urlencoded",

					"key: " . $config_apikeyongkir

				);

				$grab = $this->Fungsi->grabData($data);

				$datagrab = json_decode($grab, true);



				//$jmldata 		= count($datagrab['rajaongkir']['results'][0]['costs']);

				$jmldata 		= isset($datagrab['rajaongkir']['results']) ? count($datagrab['rajaongkir']['results']) : 0;

				$servis_rajaongkir = [];

				if ($jmldata > 0) {

					for ($i = 0; $i < $jmldata; $i++) {



						$jmlservis = count($datagrab['rajaongkir']['results'][$i]['costs']);

						for ($x = 0; $x < $jmlservis; $x++) {

							$kode_ship = strtoupper($datagrab['rajaongkir']['results'][$i]['code']);



							$servis_rajaongkir[] = array(

								"shipping_code_rajaongkir" => $kode_ship,

								"shipping_code" => $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["shipping_code"],

								"servis_id" => $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"],

								"servis_code" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['service'],

								"tarif" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['value'],

								"etd" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['etd'],

								"shipping_konfirmadmin" => 0,

								"shipping_rajaongkir"=>1

							);

						}

					}

				}

				$servis_ondb = $this->dataShipping->getAllServisKonfirmAdmin();



				//$services = $this->model->getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan);



				foreach ($servis_rajaongkir as $ship) {

					$services[] = $ship;

				}

				foreach ($servis_ondb as $servdb) {

					$services[] = $servdb;

				}



				if (count($services) > 0) {

					$status = 'success';

					$msg = 'Data diterima';

				} else {

					$status = 'error';

					$msg = 'Data tidak diterima';

				}

				/*

				$status = 'success';

				$msg = 'Data diterima';

				*/

				$totberat = $data['totberat'];

			} else {

				$status = 'error';

				$msg = 'Order tidak valid';

			}

		} else {

			$status = 'error';

			$msg = 'Data tidak ada';

		}

		return array("status" => $status, "msg" => $msg, "order" => $dataorder, "servis" => $services, "totberat" => $totberat);

	}



	public function addprodukorder()

	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$data = [];

			foreach ($_POST as $key => $value) {

				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';

			}

			if ($data['nopesanan'] == '' || $data['nopesanan'] == '0') {

				$status = 'error';

				$result = 'No. Order tidak ditemukan';

			} else {

				$dataproduk = $this->dataProduk->getProdukByID($data['product_id']);

				if ($data['idwarna'] == '0' && $data['idwarna'] == '' && $data['idukuran'] == '' && $data['idukuran'] == '0') {

					$stok = $dataproduk['jml_stok'];

				} else {

					$stok = $this->dataProduk->getStokWarnaUkuran($data['product_id'], $data['idukuran'], $data['idwarna']);

				}

				$cekorder = $this->model->JumlahOrder($data['nopesanan'], $data['product_id'], $data['idwarna'], $data['idukuran']);

				if ($stok < 1) {

					$status = 'error';

					$result  = 'Stok produk tersebut sedang kosong';

				} elseif ($cekorder > 0) {

					$status = 'error';

					$result = 'Produk sudah ada di List Order';

				} elseif ($stok < $data['qty']) {

					$status = 'error';

					$result  = 'Stok produk tersebut hanya tersedia ' . $stok . ' pcs';

				} else {



					$cart_add['tipe'] 			= $data['grup_member'];

					$cart_add['stok']			= $dataproduk['jml_stok'];

					$cart_add['produk']			= $dataproduk['nama_produk'];

					$cart_add['product_id']		= $data['product_id'];

					$cart_add['jumlah']			= $data['qty'];

					$cart_add['option']			= array($data['idukuran'], $data['idwarna']);

					$cart_add['idmember']		= $data['idmember'];

					$cart_add['persen_diskon'] 	= $dataproduk['persen_diskon'];

					$addCart = $this->addCart($cart_add);

					if ($addCart['status'] == 'success') {

						if ((int) $data['idwarna'] > 0 || (int) $data['idukuran'] > 0) {

							$data['updatestokoptionberkurang'] = array(

								"nopesanan" => $data['nopesanan'],

								"qty" => $data['qty'],

								"idukuran" => $data['idukuran'],

								"idwarna" => $data['idwarna'],

								"idproduk" => $data['product_id']

							);

						}

						$data['updatestokberkurang'] = array(

							"idproduk" => $data['product_id'],

							"qty" => $data['qty']

						);



						$data['addprodukorder']	= array(

							"nopesanan" => $data['nopesanan'],

							"idproduk" => $data['product_id'],

							"qty" => $data['qty'],

							"idukuran" => $data['idukuran'],

							"idwarna" => $data['idwarna'],

							"berat" => $data['qty'] * $dataproduk['berat_produk'],

							"persen_diskon" => $dataproduk['persen_diskon'],

							"sale" => $dataproduk['sale'],

							"getpoin" => $dataproduk['poin'],

							"harga_satuan" => $dataproduk['hrg_jual'],

							"harga" => $dataproduk['hrg_jual']

						);

						$produk_in_order = $this->model->getProdukOrderOption('', '', '', $data['nopesanan']);

						$produk_cart = [];

						foreach ($produk_in_order as $order) {



							if (!in_array($order['idproduk'], $produk_cart)) {

								$produk_cart[] = $order['idproduk'];

								$produk[$order['idproduk']] = $this->dataProduk->getProdukByID($order['idproduk']);

							}

							$produk_to_cart['stok'] = $produk[$order['idproduk']]['jml_stok'];

							$produk_to_cart['produk'] = $produk[$order['idproduk']]['nama_produk'];

							$produk_to_cart['product_id']  = $order['idproduk'];

							$produk_to_cart['jumlah']	= $order['jml'];

							$produk_to_cart['idmember'] = $data['idmember'];

							$produk_to_cart['tipe'] 	= $data['grup_member'];

							$produk_to_cart['persen_diskon'] = $produk[$order['idproduk']]['persen_diskon'];

							if ($order['ukuran'] == '' && $order['ukuran'] == null) {

								$order['ukuran'] = '0';

							}

							if ($order['warna'] == '' && $order['warna'] == null) {

								$order['warna'] = '0';

							}

							$produk_to_cart['option']   = array($order['ukuran'], $order['warna']);

							$this->addCartFromProdukOrder($produk_to_cart);

						}

						$carts = $this->showminiCart($_SESSION['hsadmincart'][$data['idmember']], $data['grup_member'], $data['idmember']);

						$totalitem	= $carts['items'];

						$datacart 	= $carts['carts'];

						$subtotal 	= 0;

						$i      	= 0;

						$totberat 	= 0;

						$totjumlah	= 0;

						$totgetpoin = 0;

						$zprod = [];

						$dtproduk = [];

						foreach ($datacart as $c) {



							if (!in_array($c['product_id'], $zprod)) {

								$zprod[] = $c['product_id'];

								$dtproduk[$c['product_id']] = $this->dataProduk->getProdukByID($c['product_id']);

							}

							//if(($c['warna'] != '' && $c['warna'] != '0')  || ($c['ukuran'] != '' && $c['ukuran'] != '0')) {

							$data['updateorderprodukoption'][] = array(

								"idwarna" => $c['warna'],

								"idukuran" => $c['ukuran'],

								"qty" => $c['qty'],

								"harga" => $c['harga'],

								"satuan" => $dtproduk[$c['product_id']]['hrg_jual'],

								"persen_diskon_satuan" => $dtproduk[$c['product_id']]['persen_diskon'],

								"berat" => $c['berat'],

								"nopesanan" => $data['nopesanan'],

								"idproduk" => $c['product_id'],

								"get_poin" => $c['poin'],

								"harga_tambahan" => $c['hargatambahan']

							);

							//} else {

							/*

								$data['updateorderproduk'][] = array("qty"=>$c['qty'],

								

																	 "harga"=>$c['harga'],

																	 "satuan"=>$dtproduk[$c['product_id']]['hrg_jual'],

																	 "persen_diskon_satuan"=>$dtproduk[$c['product_id']]['persen_diskon'],

																	 "berat"=>$c['berat'],

																	 "nopesanan"=>$data['nopesanan'],

																	 "idproduk"=>$c['product_id']);

								*/

							//}

							$poinku  = (int) $c['poin'] * (int) $c['qty'];

							$subtotal	+= $c['total'];

							$totberat   += $c['berat'];

							$totjumlah  += (int) $c['qty'];

							$totgetpoin += (int) $poinku;



							$i++;

						}

						$data['subtotal'] = $subtotal;

						$data['totjumlah'] = $totjumlah;

						$data['totgetpoin'] = $totgetpoin;

						$data['totberat'] 	= $totberat;

						$dataorder = $this->model->getOrderByID($data['nopesanan']);

						$data['kecamatan_penerima'] = $dataorder['kecamatan_penerima'];

						$data['kabupaten_penerima'] = $dataorder['kota_penerima'];

						$data['propinsi_penerima'] 	= $dataorder['propinsi_penerima'];

						$data['serviskurir']		= $dataorder['servis_kurir'];



						/*

						$dataservis 			= $this->dataShipping->getServisByIdserv($data);

						$datashipping 			= $this->dataShipping->getShippingByIdServ($data);

						$data['servis_id'] 		= isset($dataservis['servis_id']) && !empty($dataservis['servis_id'])? $dataservis['servis_id'] : $data['serviskurir'];

						$data['servis_code'] 	= isset($dataservis['servis_code']) ? $dataservis['servis_code'] : $datashipping['servis_code'];

						$data['shipping']		= $datashipping['shipping_kode'];

						$data['hrgkurir_perkilo'] = isset($dataservis['hrg_perkilo']) ? $dataservis['hrg_perkilo'] : 0;

						$data['kurir_konfirm'] = $datashipping['shipping_konfirmadmin'];

						*/



						$data['shipping_kode']		= $dataorder['shipping_kode'];

						$data['shipping'] 			= $dataorder['shipping_kdrajaongkir'];

						$data['kurir_konfirm'] 		= $dataorder['shipping_konfirmadmin'];

						$data['hrgkurir_perkilo'] 	= 0;

						$data['servis_code']		= $dataorder['servis_code'];

						$data['servis_id']			= $dataorder['servis_kurir'];



						$totalberat = (int) $totberat / 1000;

						if ($totalberat < 1) $totalberat = 1;



						/*

						$jarakkoma = 0;

						if($totalberat > 1) {

							$berat = floor($totalberat);

							$jarakkoma = $totalberat - $berat;

						}

						$batas = isset($dataservis['shipping_bataskoma']) ? $dataservis['shipping_bataskoma'] : 0;

						if($datashipping['shipping_konfirmadmin'] == '0') {

						

							if($jarakkoma > $batas) $totalberat = ceil($totalberat);

							else $totalberat = floor($totalberat);

							$tarif = $totalberat * $data['hrgkurir_perkilo'];

							

						} else {

							$tarif = 0;

							

						}

						$data['tarifkurir'] = $tarif;

						*/



						if ($data['kurir_konfirm'] != '1') {

							$modelsetting = new modelSetting();

							$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin', 'config_apiurlongkir', 'config_apikeyongkir'));

							foreach ($setting as $st) {

								$key 	= $st['setting_key'];

								$value 	= $st['setting_value'];

								$$key = $value;

							}

							$shipping = $this->dataShipping->getShippingRajaOngkir();

							foreach ($shipping as $ship) {

								$kurir["{$ship['shipping_kode']}"]["{$ship['servis_code']}"] = array("servis" => $ship['servis_id'], "shipping_code" => $ship['shipping_kdrajaongkir']);

							}



							$data['curl'] 		= curl_init();

							$data['urlcurl'] 	= $config_apiurlongkir . 'cost';

							$data['postfield'] 	= "origin=$config_lokasiorigin&originType=city&destination=" . $data['kecamatan_penerima'] . "&destinationType=subdistrict&weight=$totberat&courier=" . strtolower($data['shipping']);



							$data['httpheader'] =	array(

								"content-type: application/x-www-form-urlencoded",

								"key: " . $config_apikeyongkir

							);

							$grab = $this->Fungsi->grabData($data);

							$datagrab = json_decode($grab, true);



							$jmldata 		= count($datagrab['rajaongkir']['results']);

							if ($jmldata > 0) {

								for ($i = 0; $i < $jmldata; $i++) {



									$jmlservis = count($datagrab['rajaongkir']['results'][$i]['costs']);

									for ($x = 0; $x < $jmlservis; $x++) {

										$kode_ship = strtoupper($datagrab['rajaongkir']['results'][$i]['code']);

										$servis_kurir = isset($kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"]) ? $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"] : '';

										if ($data['serviskurir'] == $servis_kurir) {

											$dataship[] = array(

												"shipping_code_rajaongkir" => $kode_ship,

												"shipping_code" => $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["shipping_code"],

												"servis_id" => $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"],

												"servis_code" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['service'],

												"tarif" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['value'],

												"etd" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['etd'],

												"shipping_konfirmadmin" => 0

											);

											break;

										}

									}

								}

							}

							$data['tarifkurir'] = isset($dataship[0]['tarif']) ? $dataship[0]['tarif'] : 0;

						} else {

							$data['tarifkurir'] = 0;

						}



						$data['sisa_dari_poin'] = 0;

						$data['dari_poin'] = 0;

						$subtotalbelanja = $data['subtotal'] + $data['tarifkurir'];

						if ($dataorder['dari_poin'] > 0) {

							if ($dataorder['dari_poin'] > $subtotalbelanja) {

								$data['dari_poin'] = $subtotalbelanja;

								$data['sisa_dari_poin'] = $dataorder['dari_poin'] - $subtotalbelanja;

							} else {

								$data['dari_poin'] = $dataorder['dari_poin'];

							}

						}

						$data['sisa_dari_potdeposito'] = 0;

						$data['potdeposito'] = 0;

						if ($dataorder['dari_deposito'] > 0) {

							$subtotalbelanja = $subtotalbelanja - $data['dari_poin'];

							if ($dataorder['dari_deposito'] > $subtotalbelanja) {

								$data['potdeposito'] = $subtotalbelanja;

								$data['sisa_dari_potdeposito'] = $dataorder['dari_deposito'] - $subtotalbelanja;

							} else {

								$data['potdeposito'] = $dataorder['dari_deposito'];

							}

							$data['keterangan'] = 'Menggunakan Saldo di Order ' . sprintf('%08s', (int) $data["nopesanan"]);

						}

						$simpan = $this->model->simpanaddprodukorder($data);

						if ($simpan['status'] == 'success') {

							$status = 'success';

							$result  = 'Berhasil menyimpan data';

							$session = array("hsadmincart", "qtyadmincart", "qtylamaadmincart", "wrnadmincart", "ukradmincart");

							$this->Fungsi->hapusSession($session);

						} else {

							$status = 'error';

							$result  = 'Proses menyimpan data tidak berhasil';

						}

					} else {

						$status = 'error';

						$result = 'Proses Gagal';

					}

				}

			}

		} else {

			$status = 'error';

			$result = 'Data Error';

		}



		echo json_encode(array("status" => $status, "result" => $result));

	}



	public function useOrderAlamat()

	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$data = [];

			foreach ($_POST as $key => $value) {

				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';

			}

			$data['savetoaddress'] = isset($data['add_check_saveaddress']) ? $data['add_check_saveaddress'] : '0';

			$dataalamat = $this->dataCustomer->getAlamatCustomerByID($data['idalamat']);



			if ($data['modulform'] == 'editorder') {

				$dataorder = $this->model->getOrderByID($data['nopesanan']);

				if ($dataorder) {

					$grup_member = $dataorder['grup_member'];

					$modelGrup	= new modelCustomerGrup();

					$datagrup = $modelGrup->getResellerGrupByID($grup_member);



					$getdropship = isset($datagrup['cg_dropship']) ? $datagrup['cg_dropship'] : '0';

					$data['dari_poin'] = $dataorder['dari_poin'];

					$data['dari_deposito'] = $dataorder['dari_deposito'];

					$data['sisa_dari_poin'] = 0;



					if ($data['jenis_alamat'] == 'alamatpengirim') {

						$data['nama_pengirim']		= $dataalamat['ca_nama'];

						$data['alamat_pengirim']	= $dataalamat['ca_alamat'];

						$data['propinsi_pengirim']	= $dataalamat['ca_propinsi'];

						$data['kabupaten_pengirim']	= $dataalamat['ca_kabupaten'];

						$data['kecamatan_pengirim']	= $dataalamat['ca_kecamatan'];

						$data['kelurahan_pengirim']	= $dataalamat['ca_kelurahan'];

						$data['kodepos_pengirim']	= $dataalamat['ca_kodepos'];

						$data['hp_pengirim']		= $dataalamat['ca_hp'];



						$data['nama_penerima']		= $dataorder['nama_penerima'];

						$data['alamat_penerima']	= $dataorder['alamat_penerima'];

						$data['propinsi_penerima']	= $dataorder['propinsi_penerima'];

						$data['kabupaten_penerima']	= $dataorder['kota_penerima'];

						$data['kecamatan_penerima']	= $dataorder['kecamatan_penerima'];

						$data['kelurahan_penerima']	= $dataorder['kelurahan_penerima'];

						$data['kodepos_penerima']	= $dataorder['kodepos_penerima'];

						$data['hp_penerima']		= $dataorder['hp_penerima'];



						$data['hrgkurir_perkilo'] 	= $dataorder['kurir_perkilo'];

						$data['tarifkurir']			= $dataorder['pesanan_kurir'];

					} elseif ($data['jenis_alamat'] == 'alamatpenerima') {

						$data['nama_pengirim']		= $dataorder['nama_pengirim'];

						$data['alamat_pengirim']	= $dataorder['alamat_pengirim'];

						$data['propinsi_pengirim']	= $dataorder['propinsi_pengirim'];

						$data['kabupaten_pengirim']	= $dataorder['kota_pengirim'];

						$data['kecamatan_pengirim']	= $dataorder['kecamatan_pengirim'];

						$data['kelurahan_pengirim']	= $dataorder['kelurahan_pengirim'];

						$data['kodepos_pengirim']	= $dataorder['kodepos_pengirim'];

						$data['hp_pengirim']		= $dataorder['hp_pengirim'];



						$data['nama_penerima']		= $dataalamat['ca_nama'];

						$data['alamat_penerima']	= $dataalamat['ca_alamat'];

						$data['propinsi_penerima']	= $dataalamat['ca_propinsi'];

						$data['kabupaten_penerima']	= $dataalamat['ca_kabupaten'];

						$data['kecamatan_penerima']	= $dataalamat['ca_kecamatan'];

						$data['kelurahan_penerima']	= $dataalamat['ca_kelurahan'];

						$data['kodepos_penerima']	= $dataalamat['ca_kodepos'];

						$data['hp_penerima']		= $dataalamat['ca_hp'];



						/* mencari tarif kurir berdasarkan wilayah penerima yang di ubah */

						$data['serviskurir']		= $dataorder['servis_kurir'];

						/*

						$dataservis 			= $this->dataShipping->getServisByIdserv($data);

						$datashipping 			= $this->dataShipping->getShippingByIdServ($data);

						$data['servis_id'] 		= isset($dataservis['servis_id']) && !empty($dataservis['servis_id'])? $dataservis['servis_id'] : $data['serviskurir'];

						$data['servis_code'] 	= isset($dataservis['servis_code']) ? $dataservis['servis_code'] : $datashipping['servis_code'];

						$data['shipping']		= $datashipping['shipping_kode'];

						$data['hrgkurir_perkilo'] = isset($dataservis['hrg_perkilo']) ? $dataservis['hrg_perkilo'] : 0;

						$data['kurir_konfirm'] = $datashipping['shipping_konfirmadmin'];

						

						$totalberat = (int)$data['totberat'] / 1000;

						if($totalberat < 1) $totalberat = 1;

						*/

						$data['serviskurir']			= $dataorder['servis_kurir'];

						$data['shipping_kode']			= $dataorder['shipping_kode'];

						$data['shipping'] 				= $dataorder['shipping_kdrajaongkir'];

						$data['kurir_konfirm'] 			= $dataorder['shipping_konfirmadmin'];

						$data['hrgkurir_perkilo'] 		= 0;

						/*

						$jarakkoma = 0;

						if($totalberat > 1) {

							$berat = floor($totalberat);

							$jarakkoma = $totalberat - $berat;

						}

						$batas = isset($dataservis['shipping_bataskoma']) ? $dataservis['shipping_bataskoma'] : 0;

						if($datashipping['shipping_konfirmadmin'] == '0') {

						

							if($jarakkoma > $batas) $totalberat = ceil($totalberat);

							else $totalberat = floor($totalberat);

							

							$tarif = $totalberat * $data['hrgkurir_perkilo'];

							

						} else {

							$tarif = 0;

							

						}

						$data['tarifkurir'] = $tarif;

						*/



						if ($data['kurir_konfirm'] != '1') {

							$modelsetting = new modelSetting();

							$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin', 'config_apiurlongkir', 'config_apikeyongkir'));

							foreach ($setting as $st) {

								$key 	= $st['setting_key'];

								$value 	= $st['setting_value'];

								$$key = $value;

							}

							$shipping = $this->dataShipping->getShippingRajaOngkir();



							foreach ($shipping as $ship) {

								$kurir["{$ship['shipping_kode']}"]["{$ship['servis_code']}"] = array("servis" => $ship['servis_id'], "shipping_code" => $ship['shipping_kdrajaongkir']);

							}



							$data['curl'] 		= curl_init();

							$data['urlcurl'] 	= $config_apiurlongkir . 'cost';

							$data['postfield'] 	= "origin=$config_lokasiorigin&originType=city&destination=" . $data['kecamatan_penerima'] . "&destinationType=subdistrict&weight=" . $data['totberat'] . "&courier=" . strtolower($data['shipping']);



							$data['httpheader'] =	array(

								"content-type: application/x-www-form-urlencoded",

								"key: " . $config_apikeyongkir

							);

							$grab = $this->Fungsi->grabData($data);

							$datagrab = json_decode($grab, true);



							$jmldata 		= count($datagrab['rajaongkir']['results']);

							if ($jmldata > 0) {

								for ($i = 0; $i < $jmldata; $i++) {



									$jmlservis = count($datagrab['rajaongkir']['results'][$i]['costs']);

									for ($x = 0; $x < $jmlservis; $x++) {

										$kode_ship = strtoupper($datagrab['rajaongkir']['results'][$i]['code']);

										if ($data['serviskurir'] == $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"]) {

											$dataship[] = array(

												"shipping_code_rajaongkir" => $kode_ship,

												"shipping_code" => $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["shipping_code"],

												"servis_id" => $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"],

												"servis_code" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['service'],

												"tarif" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['value'],

												"etd" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['etd'],

												"shipping_konfirmadmin" => 0

											);

											break;

										}

									}

								}

							}

							$data['tarifkurir'] = isset($dataship[0]['tarif']) ? $dataship[0]['tarif'] : 0;

						} else {

							$data['tarifkurir'] = 0;

						}

						$total = ($dataorder['pesanan_subtotal'] + $data['tarifkurir']) - $dataorder['dari_deposito'];

						if ($total < $data['dari_poin']) {

							$data['dari_poin'] = $total;

							$data['sisa_dari_poin'] = $data['dari_poin'] - $total;

						}

						if (($dataorder['pesanan_subtotal'] + $data['tarifkurir']) < $dataorder['dari_deposito']) {

							$data['dari_deposito'] = $dataorder['pesanan_subtotal'] + $data['tarifkurir'];

						} else {

							$data['dari_deposito'] = $dataorder['dari_deposito'];

						}

					}

					$data['keterangan'] = 'Menggunakan Saldo di Order ' . sprintf('%08s', (int) $data["nopesanan"]);

					if ($getdropship == '1') {

						if (

							$data['nama_penerima'] != $data['nama_pengirim'] ||

							$data['alamat_penerima'] != $data['alamat_pengirim'] ||

							$data['hp_penerima'] != $data['hp_pengirim']

						) {

							$data['dropship'] = '1';

						} else {

							$data['dropship'] = '0';

						}

					} else {

						$data['dropship'] = '0';

					}



					$simpan = $this->model->simpanEditAlamat($data);

					if ($simpan['status'] == 'success') {

						$status = 'success';

						$result = 'Berhasil mengubah Alamat Pengirim';

					} else {

						$status = 'error';

						$result = 'Proses mengubah Alamat Pengirim Gagal';

					}

				} else {



					$status = 'error';

					$result = 'No. Order tidak ada';

				}

			} else {



				$data['nama']			= $dataalamat['ca_nama'];

				$data['alamat']			= $dataalamat['ca_alamat'];

				$data['propinsi']		= $dataalamat['ca_propinsi'];

				$data['propinsi_nama']	= $dataalamat['provinsi_nama'];

				$data['kabupaten']		= $dataalamat['ca_kabupaten'];

				$data['kabupaten_nama']	= $dataalamat['kabupaten_nama'];

				$data['kecamatan']		= $dataalamat['ca_kecamatan'];

				$data['kecamatan_nama']	= $dataalamat['kecamatan_nama'];

				$data['kelurahan']		= $dataalamat['ca_kelurahan'];

				$data['kodepos']		= $dataalamat['ca_kodepos'];

				$data['telp']			= $dataalamat['ca_hp'];





				$status = 'success';

				$result = $data;

			}

		} else {

			$status = 'error';

			$result = 'Data tidak valid';

		}

		echo json_encode(array("status" => $status, "result" => $result));

	}



	public function addOrderAlamat()

	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$data = [];

			foreach ($_POST as $key => $value) {

				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';

			}



			$data['savetoaddress'] = isset($data['add_check_saveaddress']) ? $data['add_check_saveaddress'] : '0';

			if ($data['savetoaddress'] == '1') {

				$simpan = $this->model->simpanAddAlamat($data);

				if ($simpan['status'] == 'success') {

					$status = 'success';

					$result = 'Berhasil menyimpan Alamat';

				} else {

					$status = 'error';

					$result = 'Proses menyimpan Alamat Gagal';

				}

			} else {

				$status = 'success';

				$result = 'Berhasil mengubah Alamat';

			}

		} else {

			$status = 'error';

			$result = 'Data tidak valid';

		}

		echo json_encode(array("status" => $status, "result" => $result));

	}



	public function editOrderAlamat()

	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$data = [];

			foreach ($_POST as $key => $value) {

				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';

			}



			$data['savetoaddress'] = isset($data['add_check_saveaddress']) ? $data['add_check_saveaddress'] : '0';

			$dataorder = $this->model->getOrderByID($data['nopesanan']);

			if ($dataorder) {

				$grup_member = $dataorder['grup_member'];

				$modelGrup	= new modelCustomerGrup();

				$datagrup = $modelGrup->getResellerGrupByID($grup_member);



				$getdropship = isset($datagrup['cg_dropship']) ? $datagrup['cg_dropship'] : '0';

				$data['dari_poin'] = $dataorder['dari_poin'];

				$data['dari_deposito'] = $dataorder['dari_deposito'];

				$data['sisa_dari_poin'] = 0;

				if ($data['jenis_alamat'] == 'alamatpengirim') {

					$data['nama_pengirim']		= $data['add_nama'];

					$data['alamat_pengirim']	= $data['add_alamat'];

					$data['propinsi_pengirim']	= $data['add_propinsi'];

					$data['kabupaten_pengirim']	= $data['add_kabupaten'];

					$data['kecamatan_pengirim']	= $data['add_kecamatan'];

					$data['kelurahan_pengirim']	= $data['add_kelurahan'];

					$data['kodepos_pengirim']	= $data['add_kodepos'];

					$data['hp_pengirim']		= $data['add_telp'];



					$data['nama_penerima']		= $dataorder['nama_penerima'];

					$data['alamat_penerima']	= $dataorder['alamat_penerima'];

					$data['propinsi_penerima']	= $dataorder['propinsi_penerima'];

					$data['kabupaten_penerima']	= $dataorder['kota_penerima'];

					$data['kecamatan_penerima']	= $dataorder['kecamatan_penerima'];

					$data['kelurahan_penerima']	= $dataorder['kelurahan_penerima'];

					$data['kodepos_penerima']	= $dataorder['kodepos_penerima'];

					$data['hp_penerima']		= $dataorder['hp_penerima'];



					$data['hrgkurir_perkilo'] 	= $dataorder['kurir_perkilo'];

					$data['tarifkurir']			= $dataorder['pesanan_kurir'];

				} elseif ($data['jenis_alamat'] == 'alamatpenerima') {

					$data['nama_pengirim']		= $dataorder['nama_pengirim'];

					$data['alamat_pengirim']	= $dataorder['alamat_pengirim'];

					$data['propinsi_pengirim']	= $dataorder['propinsi_pengirim'];

					$data['kabupaten_pengirim']	= $dataorder['kota_pengirim'];

					$data['kecamatan_pengirim']	= $dataorder['kecamatan_pengirim'];

					$data['kelurahan_pengirim']	= $dataorder['kelurahan_pengirim'];

					$data['kodepos_pengirim']	= $dataorder['kodepos_pengirim'];

					$data['hp_pengirim']		= $dataorder['hp_pengirim'];



					$data['nama_penerima']		= $data['add_nama'];

					$data['alamat_penerima']	= $data['add_alamat'];

					$data['propinsi_penerima']	= $data['add_propinsi'];

					$data['kabupaten_penerima']	= $data['add_kabupaten'];

					$data['kecamatan_penerima']	= $data['add_kecamatan'];

					$data['kelurahan_penerima']	= $data['add_kelurahan'];

					$data['kodepos_penerima']	= $data['add_kodepos'];

					$data['hp_penerima']		= $data['add_telp'];



					/* mencari tarif kurir berdasarkan wilayah penerima yang di ubah */

					$data['serviskurir']		= $dataorder['servis_kurir'];

					/*

					$dataservis 			= $this->dataShipping->getServisByIdserv($data);

					$datashipping 			= $this->dataShipping->getShippingByIdServ($data);

					$data['servis_id'] 		= isset($dataservis['servis_id']) && !empty($dataservis['servis_id'])? $dataservis['servis_id'] : $data['serviskurir'];

					$data['servis_code'] 	= isset($dataservis['servis_code']) ? $dataservis['servis_code'] : $datashipping['servis_code'];

					$data['shipping']		= $datashipping['shipping_kode'];

					$data['hrgkurir_perkilo'] = isset($dataservis['hrg_perkilo']) ? $dataservis['hrg_perkilo'] : 0;

					$data['kurir_konfirm'] = $datashipping['shipping_konfirmadmin'];

					*/



					$data['serviskurir']			= $dataorder['servis_kurir'];

					$data['shipping_kode']			= $dataorder['shipping_kode'];

					$data['shipping'] 				= $dataorder['shipping_kdrajaongkir'];

					$data['kurir_konfirm'] 			= $dataorder['shipping_konfirmadmin'];

					$data['hrgkurir_perkilo'] 		= 0;



					$totalberat = (int) $data['totberat'] / 1000;

					//if($totalberat < 1) $totalberat = 1;

					//print_r($totalberat);

					/*

					$jarakkoma = 0;

					if($totalberat > 1) {

						$berat = floor($totalberat);

						$jarakkoma = $totalberat - $berat;

					}

					$batas = isset($dataservis['shipping_bataskoma']) ? $dataservis['shipping_bataskoma'] : 0;

					if($datashipping['shipping_konfirmadmin'] == '0') {

					

						if($jarakkoma > $batas) $totalberat = ceil($totalberat);

						else $totalberat = floor($totalberat);

						

						$tarif = $totalberat * $data['hrgkurir_perkilo'];

						

					} else {

						$tarif = 0;

						

					}

					$data['tarifkurir'] = $tarif;

					*/

					if ($data['kurir_konfirm'] != '1') {

						$modelsetting = new modelSetting();

						$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin', 'config_apiurlongkir', 'config_apikeyongkir'));

						foreach ($setting as $st) {

							$key 	= $st['setting_key'];

							$value 	= $st['setting_value'];

							$$key = $value;

						}

						$shipping = $this->dataShipping->getShippingRajaOngkir();

						foreach ($shipping as $ship) {



							//if($data['shipping']  == $ship['shipping_kode']) {

							$kurir["{$ship['shipping_kode']}"]["{$ship['servis_code']}"] = array("servis" => $ship['servis_id'], "shipping_code" => $ship['shipping_kdrajaongkir']);

							//break;

							//}







						}





						$data['curl'] 		= curl_init();

						$data['urlcurl'] 	= $config_apiurlongkir . 'cost';

						$data['postfield'] 	= "origin=$config_lokasiorigin&originType=city&destination=" . $data['kecamatan_penerima'] . "&destinationType=subdistrict&weight=" . $data['totberat'] . "&courier=" . strtolower($data['shipping']);



						$data['httpheader'] =	array(

							"content-type: application/x-www-form-urlencoded",

							"key: " . $config_apikeyongkir

						);

						$grab = $this->Fungsi->grabData($data);

						$datagrab = json_decode($grab, true);



						$jmldata 		= count($datagrab['rajaongkir']['results']);

						if ($jmldata > 0) {

							for ($i = 0; $i < $jmldata; $i++) {



								$jmlservis = count($datagrab['rajaongkir']['results'][$i]['costs']);

								for ($x = 0; $x < $jmlservis; $x++) {

									$kode_ship = strtoupper($datagrab['rajaongkir']['results'][$i]['code']);

									if ($data['serviskurir'] == $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"]) {

										$dataship[] = array(

											"shipping_code_rajaongkir" => $kode_ship,

											"shipping_code" => $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["shipping_code"],

											"servis_id" => $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"],

											"servis_code" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['service'],

											"tarif" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['value'],

											"etd" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['etd'],

											"shipping_konfirmadmin" => 0

										);

										break;

									}

								}

							}

						}

						$data['tarifkurir'] = isset($dataship[0]['tarif']) ? $dataship[0]['tarif'] : 0;

					} else {

						$data['tarifkurir'] = 0;

					}

					$total = ($dataorder['pesanan_subtotal'] + $data['tarifkurir']) - $dataorder['dari_deposito'];

					if ($total < $data['dari_poin']) {

						$data['dari_poin'] = $total;

						$data['sisa_dari_poin'] = $data['dari_poin'] - $total;

					}



					if (($dataorder['pesanan_subtotal'] + $data['tarifkurir']) < $dataorder['dari_deposito']) {

						$data['dari_deposito'] = $dataorder['pesanan_subtotal'] + $data['tarifkurir'];

					} else {

						$data['dari_deposito'] = $dataorder['dari_deposito'];

					}

				}

				$data['keterangan'] = 'Menggunakan Saldo di Order ' . sprintf('%08s', (int) $data["nopesanan"]);



				if ($getdropship == '1') {

					if (

						$data['nama_penerima'] != $data['nama_pengirim'] &&

						$data['alamat_penerima'] != $data['alamat_pengirim'] &&

						$data['hp_penerima'] != $data['hp_pengirim']

					) {

						$data['dropship'] = '1';

					} else {

						$data['dropship'] = '0';

					}

				} else {

					$data['dropship'] = '0';

				}



				$simpan = $this->model->simpanEditAlamat($data);

				if ($simpan['status'] == 'success') {

					$status = 'success';

					$result = 'Berhasil mengubah Alamat Pengirim';

				} else {

					$status = 'error';

					$result = 'Proses mengubah Alamat Pengirim Gagal';

				}

			} else {

				$status = 'error';

				$result = 'No. Order tidak ada';

			}

		} else {

			$status = 'error';

			$result = 'Data tidak valid';

		}

		echo json_encode(array("status" => $status, "result" => $result));

	}



	public function editprodukorder()

	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$data = [];

			foreach ($_POST as $key => $value) {

				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';

			}



			$dataproduk = $this->dataProduk->getProdukByID($data['product_id']);

			if ($data['idwarna'] == '0' && $data['idwarna'] == '' && $data['idukuran'] == '' && $data['idukuran'] == '0') {

				$stok = $dataproduk['jml_stok'];

			} else {

				$stok = $this->dataProduk->getStokWarnaUkuran($data['product_id'], $data['idukuran'], $data['idwarna']);

			}

			if ($stok + $data['qty'] < 1) {

				$status = 'error';

				$pesan  = 'Stok produk tersebut sedang kosong';

			} else {



				$jml = abs($data['qty'] - $data['qtylama']);



				if ($stok + $data['qtylama'] < $data['qty']) {

					$status = 'error';

					$pesan  = 'Stok produk tersebut hanya tersedia ' . $stok . ' pcs';

				} else {

					$cart_edit['tipe'] 		= $data['idgrup'];

					$cart_edit['stok']		= $dataproduk['jml_stok'];

					$cart_edit['produk']	= $dataproduk['nama_produk'];

					$cart_edit['product_id'] = $data['product_id'];

					$cart_edit['jumlah']	= $data['qty'];

					//$cart_edit['jumlah']	= $jml;

					$cart_edit['qty_lama']	= $data['qtylama'];

					$cart_edit['option']	= array($data['idukuran'], $data['idwarna']);

					$cart_edit['idmember']	= $data['idmember'];

					$cart_edit['persen_diskon'] = $dataproduk['persen_diskon'];

					$addCart = $this->addCart($cart_edit);



					if ($addCart['status'] == 'success') {

						if ($data['qty'] > $data['qtylama']) {

							if ((int) $data['idwarna'] > 0 || (int) $data['idukuran'] > 0) {

								$data['updatestokoptionberkurang'] = array(

									"nopesanan" => $data['nopesanan'],

									"qty" => $data['qty'] - $data['qtylama'],

									"idukuran" => $data['idukuran'],

									"idwarna" => $data['idwarna'],

									"idproduk" => $data['product_id']

								);

							}





							$data['updatestokberkurang'] = array(

								"idproduk" => $data['product_id'],

								"qty" => $data['qty'] - $data['qtylama']

							);

						} elseif ($data['qty'] < $data['qtylama']) {

							if ((int) $data['idwarna'] > 0 || (int) $data['idukuran'] > 0) {

								$data['updatestokoptionbertambah'] = array(

									"nopesanan" => $data['nopesanan'],

									"qty" => $data['qtylama'] - $data['qty'],

									"idukuran" => $data['idukuran'],

									"idwarna" => $data['idwarna'],

									"idproduk" => $data['product_id']

								);

							}



							$data['updatestokbertambah'] = array(

								"idproduk" => $data['product_id'],

								"qty" => $data['qtylama'] - $data['qty']

							);

						}

						$produk_in_order = $this->model->getProdukOrderOption('', '', '', $data['nopesanan']);

						foreach ($produk_in_order as $order) {

							if ((int) $order['iddetail'] != (int) $data['iddetail']) {

								$produk = $this->dataProduk->getProdukByID($order['idproduk']);

								$produk_to_cart['stok'] = $produk['jml_stok'];

								$produk_to_cart['produk'] = $produk['nama_produk'];

								$produk_to_cart['product_id']  = $order['idproduk'];

								$produk_to_cart['jumlah']	= $order['jml'];

								$produk_to_cart['idmember'] = $data['idmember'];

								$produk_to_cart['tipe'] 	= $data['idgrup'];

								$produk_to_cart['persen_diskon'] = $produk['persen_diskon'];

								if ($order['ukuran'] == '' && $order['ukuran'] == null) {

									$order['ukuran'] = '0';

								}

								if ($order['warna'] == '' && $order['warna'] == null) {

									$order['warna'] = '0';

								}

								$produk_to_cart['option']   = array($order['ukuran'], $order['warna']);

								//$this->addCart($produk_to_cart);

								$this->addCartFromProdukOrder($produk_to_cart);

							}

						}



						$carts = $this->showminiCart($_SESSION['hsadmincart'][$data['idmember']], $data['idgrup'], $data['idmember']);

						$totalitem	= $carts['items'];

						$datacart 		= $carts['carts'];



						$subtotal 	= 0;

						$i      	= 0;

						$totberat 	= 0;

						$totjumlah	= 0;

						$totgetpoin = 0;

						$zprod = [];

						$dtproduk = [];

						/*

						$data['updateorderprodukoption'] = [];

						$data['updateorderproduk'] = [];

						*/

						foreach ($datacart as $c) {



							if (!in_array($c['product_id'], $zprod)) {

								$zprod[] = $c['product_id'];

								$dtproduk[$c['product_id']] = $this->dataProduk->getProdukByID($c['product_id']);

							}

							//if(($c['warna'] != '' && $c['warna'] != '0')  || ($c['ukuran'] != '' && $c['ukuran'] != '0')) {

							$data['updateorderprodukoption'][] = array(

								"idwarna" => $c['warna'],

								"idukuran" => $c['ukuran'],

								"qty" => $c['qty'],

								"harga" => $c['harga'],

								"satuan" => $dtproduk[$c['product_id']]['hrg_jual'],

								"persen_diskon_satuan" => $dtproduk[$c['product_id']]['persen_diskon'],

								"berat" => $c['berat'],

								"nopesanan" => $data['nopesanan'],

								"idproduk" => $c['product_id'],

								"harga_tambahan" => $c['hargatambahan'],

								"get_poin" => $dtproduk[$c['product_id']]['poin']

							);

							//} else {

							/*

								$data['updateorderproduk'][] = array("qty"=>$c['qty'],

								

																	 "harga"=>$c['harga'],

																	 "satuan"=>$dtproduk[$c['product_id']]['hrg_jual'],

																	 "persen_diskon_satuan"=>$dtproduk[$c['product_id']]['persen_diskon'],

																	 "berat"=>$c['berat'],

																	 "nopesanan"=>$data['nopesanan'],

																	 "idproduk"=>$c['product_id'],

																	 "get_poin"=>$dtproduk[$c['product_id']]['poin']);

								*/

							//}

							$poinku  = (int) $c['poin'] * (int) $c['qty'];

							$subtotal	+= $c['total'];

							$totberat   += $c['berat'];

							$totjumlah  += (int) $c['qty'];

							$totgetpoin += (int) $poinku;



							$i++;

						}

						//print_r($data['updateorderprodukoption']);

						$data['subtotal'] = $subtotal;

						$data['totjumlah'] = $totjumlah;

						$data['totgetpoin'] = $totgetpoin;

						$data['totberat'] 	= $totberat;



						$dataorder = $this->model->getOrderByID($data['nopesanan']);

						$data['kecamatan_penerima'] = $dataorder['kecamatan_penerima'];

						$data['kabupaten_penerima'] = $dataorder['kota_penerima'];

						$data['propinsi_penerima'] 	= $dataorder['propinsi_penerima'];

						$data['serviskurir']		= $dataorder['servis_kurir'];



						/*

						$dataservis 			= $this->dataShipping->getServisByIdserv($data);

						$datashipping 			= $this->dataShipping->getShippingByIdServ($data);

						$data['servis_id'] 		= isset($dataservis['servis_id']) && !empty($dataservis['servis_id'])? $dataservis['servis_id'] : $data['serviskurir'];

						$data['servis_code'] 	= isset($dataservis['servis_code']) ? $dataservis['servis_code'] : $datashipping['servis_code'];

						$data['shipping']		= $datashipping['shipping_kode'];

						$data['hrgkurir_perkilo'] = isset($dataservis['hrg_perkilo']) ? $dataservis['hrg_perkilo'] : 0;

						$data['kurir_konfirm'] = $datashipping['shipping_konfirmadmin'];

						*/

						$data['shipping_kode']		= $dataorder['shipping_kode'];

						$data['shipping'] 			= $dataorder['shipping_kdrajaongkir'];

						$data['kurir_konfirm'] 		= $dataorder['shipping_konfirmadmin'];

						$data['hrgkurir_perkilo'] 	= 0;

						$data['servis_code']		= $dataorder['servis_code'];

						$data['servis_id']			= $dataorder['servis_kurir'];



						$totalberat = (int) $totberat / 1000;

						if ($totalberat < 1) $totalberat = 1;

						/*

						$jarakkoma = 0;

						if($totalberat > 1) {

							$berat = floor($totalberat);

							$jarakkoma = $totalberat - $berat;

						}

						$batas = isset($dataservis['shipping_bataskoma']) ? $dataservis['shipping_bataskoma'] : 0;

						if($datashipping['shipping_konfirmadmin'] == '0') {

						

							if($jarakkoma > $batas) $totalberat = ceil($totalberat);

							else $totalberat = floor($totalberat);

							$tarif = $totalberat * $data['hrgkurir_perkilo'];

							

						} else {

							$tarif = 0;

							

						}

						$data['tarifkurir'] = $tarif;

						*/



						if ($data['kurir_konfirm'] != '1') {

							$modelsetting = new modelSetting();

							$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin', 'config_apiurlongkir', 'config_apikeyongkir'));

							foreach ($setting as $st) {

								$key 	= $st['setting_key'];

								$value 	= $st['setting_value'];

								$$key = $value;

							}

							$shipping = $this->dataShipping->getShippingRajaOngkir();

							foreach ($shipping as $ship) {

								$kurir["{$ship['shipping_kode']}"]["{$ship['servis_code']}"] = array("servis" => $ship['servis_id'], "shipping_code" => $ship['shipping_kdrajaongkir']);

							}



							$data['curl'] 		= curl_init();

							$data['urlcurl'] 	= $config_apiurlongkir . 'cost';

							$data['postfield'] 	= "origin=$config_lokasiorigin&originType=city&destination=" . $data['kecamatan_penerima'] . "&destinationType=subdistrict&weight=$totberat&courier=" . strtolower($data['shipping']);



							$data['httpheader'] =	array(

								"content-type: application/x-www-form-urlencoded",

								"key: " . $config_apikeyongkir

							);

							$grab = $this->Fungsi->grabData($data);

							$datagrab = json_decode($grab, true);



							$jmldata 		= count($datagrab['rajaongkir']['results']);

							if ($jmldata > 0) {

								for ($i = 0; $i < $jmldata; $i++) {



									$jmlservis = count($datagrab['rajaongkir']['results'][$i]['costs']);

									for ($x = 0; $x < $jmlservis; $x++) {

										$kode_ship = strtoupper($datagrab['rajaongkir']['results'][$i]['code']);

										if ($data['serviskurir'] == $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"]) {

											$dataship[] = array(

												"shipping_code_rajaongkir" => $kode_ship,

												"shipping_code" => $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["shipping_code"],

												"servis_id" => $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"],

												"servis_code" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['service'],

												"tarif" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['value'],

												"etd" => $datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['etd'],

												"shipping_konfirmadmin" => 0

											);

											break;

										}

									}

								}

							}

							$data['tarifkurir'] = isset($dataship[0]['tarif']) ? $dataship[0]['tarif'] : 0;

						} else {

							$data['tarifkurir'] = 0;

						}



						$data['sisa_dari_poin'] = 0;

						$data['dari_poin'] = 0;

						$subtotalbelanja = $data['subtotal'] + $data['tarifkurir'];

						if ($dataorder['dari_poin'] > 0) {

							if ($dataorder['dari_poin'] > $subtotalbelanja) {

								$data['dari_poin'] = $subtotalbelanja;

								$data['sisa_dari_poin'] = $dataorder['dari_poin'] - $subtotalbelanja;

							} else {

								$data['dari_poin'] = $dataorder['dari_poin'];

							}

						}

						$data['sisa_dari_potdeposito'] = 0;

						$data['potdeposito'] = 0;

						if ($dataorder['dari_deposito'] > 0) {

							$subtotalbelanja = $subtotalbelanja - $data['dari_poin'];

							if ($dataorder['dari_deposito'] > $subtotalbelanja) {

								$data['potdeposito'] = $subtotalbelanja;

								$data['sisa_dari_potdeposito'] = $dataorder['dari_deposito'] - $subtotalbelanja;

							} else {

								$data['potdeposito'] = $dataorder['dari_deposito'];

							}

						}

						$simpan = $this->model->simpaneditprodukorder($data);

						if ($simpan['status'] == 'success') {

							$status = 'success';

							$pesan  = 'Berhasil menyimpan data';

							$session = array("hsadmincart", "qtyadmincart", "qtylamaadmincart", "wrnadmincart", "ukradmincart");

							$this->Fungsi->hapusSession($session);

						} else {

							$status = 'error';

							$pesan  = 'Proses menyimpan data tidak berhasil';

						}

					} else {

						$status = 'error';

						$pesan = $addCart['pesan'];

					}

				}

			}

		} else {

			$status = 'error';

			$pesan = 'Data tidak valid';

		}





		echo json_encode(array("status" => $status, "result" => $pesan));

	}

	public function tampildata()

	{

		$this->page 	    = isset($_GET['page']) ? intval($_GET['page']) : 1;

		$this->rows			=  10;



		$result 			= array();

		$filter				= array();

		$where 				= '';



		$data['sortir']				= isset($_GET['sort']) ? $_GET['sort'] : '';



		$data['status']		= isset($_GET['status']) ? $_GET['status'] : '';

		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari'] : '';



		$result["total"] = 0;

		$result["rows"] = '';

		$this->offset = ($this->page - 1) * $this->rows;



		$result["total"]   = $this->model->totalOrder($data);

		$result["rows"]    = $this->model->getOrder($this->offset, $this->rows, $data);

		$result["page"]    = $this->page;

		$result["baris"]   = $this->rows;

		$result["jmlpage"] = ceil(intval($result["total"]) / intval($result["baris"]));



		return $result;

	}



	public function dataOrderByID($noorder)

	{

		return $this->model->getOrderByID($noorder);

	}



	public function dataOrderDetail($noorder)

	{

		return $this->model->getOrderDetail($noorder);

	}



	public function dataOrderStatus($noorder)

	{

		return $this->model->getOrderStatus($noorder);

	}



	public function dataOrderKonfirmasi($noorder)

	{

		return $this->model->getOrderKonfirmasi($noorder);

	}



	public function dataOrderAlamat($noorder)

	{

		return $this->model->getOrderAlamat($noorder);

	}

	public function dataOrderPoin($noorder, $customer)

	{

		return $this->model->getOrderPoin($noorder, $customer);

	}



	public function getProdukOrderOption($idproduk, $idwarna, $idukuran, $nopesan)

	{

		return $this->model->getProdukOrderOption($idproduk, $idwarna, $idukuran, $nopesan);

	}

	public function generateinvoice($noorder)

	{



		$prefix					= $this->Fungsi->fcaridata('_setting_toko', 'prefix_inv', '', '');

		$kodeakhir 				= $this->Fungsi->fIdAkhir('_order', "CONVERT(RIGHT(no_invoice,5),SIGNED)");

		$kodenext					= sprintf('%05s', (int) $kodeakhir + 1);

		$this->data['invoice'] 	= $prefix . $kodenext;

		$this->data['noorder']	= $noorder;

		$this->model->generateInvoice($this->data);



		return $this->data['invoice'];

	}

	function hapusdata()

	{

		$id = isset($_POST['id']) ? $_POST['id'] : '';

		$dataId = explode(":", $id);

		/*

		$dataError = array();

		$modul = "hapus";

		*/

		$pesan = '';

		//$data = [];

		$cek = $this->Fungsi->cekHak(folder, "del", 1);





		if ($cek) {

			$pesan = " Anda tidak mempunyai Akses untuk menghapus ";

			$status = 'error';

		} else {



			$cancelorder = $this->cancelOrder($dataId, '1', '1');

			$status = $cancelorder['status'];

			$pesan = $cancelorder['result'];

		}



		echo json_encode(array("status" => $status, "result" => $pesan));

	}



	private function cancelOrder($dataorder, $bysystem = 0, $simpanstatushistory = 0)

	{

		$where = "setting_key IN ('config_orderstatus','config_ordercancel')";

		$status  = $this->Fungsi->fcaridata3('_setting', 'setting_key,setting_value', $where);

		$datastatus = [];

		if ($status) {

			foreach ($status as $sts) {

				$datastatus["{$sts['setting_key']}"] = $sts['setting_value'];

			}

		}

		$data['status_pending']	= $datastatus['config_orderstatus'];

		$data['status_cancel']		= $datastatus['config_ordercancel'];

		$data['by_sistem'] = $bysystem;

		$data['simpan_history'] = $simpanstatushistory;

		$tgl	   = date('Y-m-d H:i:s');



		foreach ($dataorder as $pesanan_no) {



			$datadetail = $this->model->getQytOrder($pesanan_no);

			$totaldaripoin = $datadetail[0]['dari_poin'];

			$totaldarideposito = $datadetail[0]['dari_deposito'];

			$pelanggan_id = $datadetail[0]['pelanggan_id'];

			if ($datadetail) {

				foreach ($datadetail as $dt) {

					if ($dt['status_id'] == $data['status_pending']) {

						if ($dt['warnaid'] > 0 || $dt['ukuranid'] > 0) {

							$data['stokoptionbertambah'][] = array(

								"pesanan_no" => $pesanan_no,

								"qty" => $dt['jml'],

								"product_id" => $dt['produk_id'],

								"idukuran" => $dt['ukuranid'],

								"idwarna" => $dt['warnaid']

							);

						}

						$data['stokbertambah'][] = array("product_id" => $dt['produk_id'], "qty" => $dt['jml']);

					}

				}

			}

			if ($dt['status_id'] == $data['status_pending']) {

				$data['cancelorder'][] = array("pesanan_no" => $pesanan_no, "status_cancel" => $data['status_cancel']);

				if ($totaldaripoin > 0) {

					$data['updatedaripoincust'][] = array("idmember" => $pelanggan_id, "dari_poin" => $totaldaripoin, "pesanan_no" => $pesanan_no, "keterangan" => "Pembatalan Order " . sprintf('%08s', (int) $pesanan_no));

				}

				if ($totaldarideposito > 0) {

					$data['updatedaridepositcust'][] = array("idmember" => $pelanggan_id, "dari_deposito" => $totaldarideposito, "pesanan_no" => $pesanan_no, "keterangan" => "Pembatalan Order " . sprintf('%08s', (int) $pesanan_no));

				}

			}

		}



		$cancelorder = $this->model->CancelOrder($data);



		if ($cancelorder['status'] == 'error') {

			$status = 'error';

			$pesan = 'Pembatalan Order Gagal';

		} else {

			$status = 'success';

			$pesan = 'Berhasil membatalkan Order';

		}

		return array("status" => $status, "result" => $pesan);

	}

	function OrderEksekusi()

	{

		$data = array();

		$data['tgl']		= date('Y-m-d H:i:s');

		$where = "setting_key IN ('config_orderstatus','config_masabayar')";

		$setting  = $this->Fungsi->fcaridata3('_setting', 'setting_key,setting_value', $where);

		$datasetting = [];

		if ($setting) {

			foreach ($setting as $sts) {

				$datasetting["{$sts['setting_key']}"] = $sts['setting_value'];

			}

		}

		$order = $this->model->getOrderEksekusi($datasetting['config_masabayar'], $datasetting['config_orderstatus'], $data['tgl']);



		$eksekusi = $this->cancelOrder($order);

		echo json_encode(array("status" => $eksekusi['status'], "result" => $eksekusi['result']));

	}



	public function pesanCart()

	{

		$pesan       = array();

		$hasilpesan  = array();

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {



			foreach ($_POST as $key => $value) {

				$data["$key"]	= isset($_POST["$key"]) ? $value : '';

			}

			if ($data['qty'] < 0) {

				$pesan[] = 'Masukkan Jumlah Pesanan Anda';

			}

			if ($data['product_id'] < 1) {

				$pesan[] = 'Tidak Ada Produk';

			}

			if (isset($data['idwarna']) && $data['idwarna'] == '0') {

				$pesan[] = 'Pilih Warna';

			}



			if (isset($data['idukuran']) && $data['idukuran'] == '0') {

				$pesan[] = 'Pilih Jenis Ukuran';

			}

			if (count($pesan) > 0) {

				$hasil = implode("<br>", $pesan);

				$status = 'error';

				$qty = '';

				$total = 0;

			} else {

				$datacart        = array();



				$produk       	= $this->dataProduk->getProdukByID($data['product_id']);

				$modelGrup	= new modelCustomerGrup();

				$datagrup = $modelGrup->getResellerGrupByID($data['grup_member']);

				$data['tipe'] 	= $data['grup_member'];

				$data['stok']	= $produk['jml_stok'];

				$data['produk']	= $produk['nama_produk'];

				$data['jumlah']	= $data['qty'];

				$data['option']  = array($data['idukuran'], $data['idwarna']);

				$data['total_awal'] = $datagrup['cg_total_awal'];

				$data['min_beli'] = $datagrup['cg_min_beli'];

				$data['min_beli_syarat'] = $datagrup['cg_min_beli_syarat']; /* Jika 1, per jenis produk. Jika 2, Bebas campur produk */

				$data['min_beli_wajib'] = $datagrup['cg_min_beli_wajib']; /* jika wajib, misal qty 3. maka ia harus beli minimal 3 */

				$data['diskon_grup'] = $datagrup['cg_diskon'];

				$datacart = $this->addCart($data);



				$status = $datacart['status'];

				$hasil = $datacart['pesan'];

				$qty = isset($datacart['qty']) ? $datacart['qty'] : '';

				$total = $datacart['total'];

			}

		} else {

			$status = 'error';

			$qty = '';

			$total = 0;

			$hasil = 'Data tidak valid';

		}



		echo json_encode(array("status" => $status, "result" => $hasil, "qty" => $qty, "total" => $this->Fungsi->fuang($total)));

	}

	//Cart

	public function addCartFromProdukOrder($data)

	{

		$pesan = '';

		$jml = 0;

		$total = 0;

		$item = $data['product_id'];

		$qty  = $data['jumlah'];



		$idmember  = $data['idmember'];



		if (!$data['option']) {

			$key = (int) $item;

		} else {

			$option = implode(",", $data['option']);

			$key = (int) $item . ':' . $option;

		}



		if ((int) $qty && ((int) $qty > 0)) {



			if (!isset($_SESSION['qtyadmincart'][$idmember][$item])) {

				$_SESSION['qtyadmincart'][$idmember][$item] = (int) $qty;

			} else {

				$_SESSION['qtyadmincart'][$idmember][$item] += (int) $qty;

			}



			$_SESSION['qtylamaadmincart'][$idmember][$key] = $qty;



			if (!isset($_SESSION['hsadmincart'][$idmember][$key])) {

				$_SESSION['hsadmincart'][$idmember][$key] = (int) $qty;

			} else {

				$_SESSION['hsadmincart'][$idmember][$key] += (int) $qty;

			}

		}

	}



	public function addCart($data)

	{

		$pesan = '';

		$jml = 0;

		$total = 0;

		$item = $data['product_id'];

		$qty  = $data['jumlah'];

		$qty_lama = isset($data['qty_lama']) ? $data['qty_lama'] : 0;

		$idmember  = $data['idmember'];



		if (!$data['option']) {

			$key = (int) $item;

		} else {

			$option = implode(",", $data['option']);

			$key = (int) $item . ':' . $option;

		}

		if ((int) $qty && ((int) $qty > 0)) {



			if (!isset($_SESSION['qtyadmincart'][$idmember][$item])) {

				$_SESSION['qtyadmincart'][$idmember][$item] = (int) $qty;

			} else {

				$_SESSION['qtyadmincart'][$idmember][$item] += (int) $qty;

			}





			$_SESSION['qtylamaadmincart'][$idmember][$key] = (int) $qty_lama;





			if (!isset($_SESSION['hsadmincart'][$idmember][$key])) {

				$_SESSION['hsadmincart'][$idmember][$key] = (int) $qty;

			} else {

				$_SESSION['hsadmincart'][$idmember][$key] += (int) $qty;

			}

			$miniCart = $this->showminiCart($_SESSION['hsadmincart'][$idmember], $data['tipe'], $idmember);

			$totalitem = $miniCart['items'];

			$cart = $miniCart['carts'];

			$jmlerror = $miniCart['jmlerror'];

			$jml = 0;

			$total = 0;



			foreach ($cart as $c) {

				$jml += $c['qty'];

				$total += $c['total'];

			}

			if ($jmlerror == 0) {

				$status = 'success';

				$pesan	= 'Berhasil menambah produk';

			} else {

				$status = 'error';

				$pesan = implode("<br>", $miniCart['pesan']);

				$_SESSION['qtyadmincart'][$idmember][$item] -= (int) $qty;

				$_SESSION['hsadmincart'][$idmember][$key] -= (int) $qty;

				if ($_SESSION['qtyadmincart'][$idmember][$item] == 0) {

					unset($_SESSION['qtyadmincart'][$idmember][$item]);

				}

				if ($_SESSION['hsadmincart'][$idmember][$key] == 0) {

					unset($_SESSION['hsadmincart'][$idmember][$key]);

				}

			}

		} else {



			$_SESSION['qtyadmincart'][$idmember][$item] -= (int) $qty;

			$_SESSION['hsadmincart'][$idmember][$key] -= (int) $qty;

			if ($_SESSION['qtyadmincart'][$idmember][$item] == 0) {

				unset($_SESSION['qtyadmincart'][$idmember][$item]);

			}

			if ($_SESSION['hsadmincart'][$idmember][$key] == 0) {

				unset($_SESSION['hsadmincart'][$idmember][$key]);

			}



			$status = 'error';

			$pesan  = 'Masukkan Jumlah';

		}





		return array("status" => $status, "pesan" => $pesan, "total" => $total);

	}

	public function listCart()

	{

		$html = '';

		$tipemember = isset($_GET['tipemember']) ? $_GET['tipemember'] : '';

		$idmember = isset($_GET['idmember']) ? $_GET['idmember'] : '';

		$total = 0;

		$h = isset($_GET['hitung']) ? $_GET['hitung'] : ''; // h = hitung

		$jmlerror = 0;

		$pesanerror = '';

		if (isset($_SESSION['hsadmincart'][$idmember])) {

			$keranjang 		= $this->showminiCart($_SESSION['hsadmincart'][$idmember], $tipemember, $idmember);

			$totalitem   	= $keranjang['items'];

			$cart 			= $keranjang['carts'];

			$jmlerror		= $keranjang['jmlerror'];

			if ($jmlerror > 0) {

				if (isset($keranjang['pesan'])) {

					$pesanerror = 'Maaf, produk yang dipesan telah di order terlebih dahulu oleh pelanggan lain. <br><br> <b>' . implode("<br>", $keranjang['pesan']) . '</b>';

				}

			}

			$subtotal 	= 0;

			$i = 0;

			$totberat = 0;

			$jml = 0;

			$tothrgsatuan = 0;

			if ($totalitem > 0) {

				foreach ($cart as $c) {

					$pid 				= $c['product_id'];

					$nama_produk 		= $c['product'];

					$jml 		 	 	= $c['qty'];

					$satuanberat 	 	= $c['satuanberat'];

					$berat 		 		= $c['berat'];

					$harga 		 		= $c['harga'];

					$hargasatuan    	= $c['hargasatuan'];

					$total 		 		= $c['total'];

					$subtotal		   += $total;

					$totberat   	   += $berat;

					$tothrgsatuan 	   += $jml * $hargasatuan;



					$idwarna			= $c['warna'];

					$nama_warna			= $c['warna_nama'];

					$idukuran 	 		= $c['ukuran'];

					$nama_ukuran		= $c['ukuran_nama'];

					$options     		= array($idukuran, $idwarna);

					$idhapus = $pid . '::' . base64_encode(serialize($options)) . '::' . $idmember . '::' . $jml;



					$img = "<img src='" . URL_PROGRAM_ADMIN . "/images/minus.png'>";

					if ($h == 'hitung') {

						$l = '<a id="hapus' . $i . '" onclick="hapusCart(\'' . $idhapus . '\')" class="btn btn-sm btn-danger">Hapus</a>';

						$j = '<input type="text" size="1" value="' . $jml . '" name="jumlah[' . $pid . '::' . base64_encode(serialize($options)) . '::' . $idmember . ']" id="jumlah" class="form-control jumlahqty">';

						$updt = ' <tr><td colspan="2"></td><td class="text-center"><button type="button" class="btn btn-sm btn-default" id="tblupdate" onclick="updatecart()">Update</button></td><td colspan="3"></td></tr>';

					} else {

						$l = '';

						$j = $jml;

						$updt = '';

					}

					$persentotal = $c['diskon_cust'] + $c['persen_diskon_prod'];

					if ($c['hargatambahan'] > 0) {

						$totalhargasatuan = $this->Fungsi->fFormatuang($hargasatuan) . ' <br><small> + ' . $this->Fungsi->fFormatuang($c['hargatambahan']) . ' (tambahan harga)</small>';

					} else {

						$totalhargasatuan = $this->Fungsi->fFormatuang($hargasatuan);

					}

					$html .= "<tr>";

					$html .= '<td class="text-center">' . $l . '</td>';

					$html .= '<td>' . $nama_produk . '<br>' . $nama_warna . '<br>' . $nama_ukuran . '</td>';

					$html .= '<td class="text-center">';

					$html .= $j;

					$html .= '</td>';

					$html .= '<td class="text-right">' . $berat . ' Gram</td>';

					$html .= '<td class="text-right">' . $totalhargasatuan . '</td>';

					$html .= '<td class="text-center">' . $persentotal . '% </td>';

					$html .= '<td class="text-right">' . $this->Fungsi->fFormatuang($harga) . '</td>';

					$html .= '<td class="text-right">' . $this->Fungsi->fFormatuang($total) . '</td>';

					$html .= '</tr>';



					$i++;

				}

				$html .= $updt;

				$html .= '<tr><td colspan="6"><td class="text-right">';

				$html .= '<b>Sub Total</b></td><td class="text-right">' . $this->Fungsi->fFormatuang($subtotal) . '<input type="hidden" value="' . $totberat . '" name="totberat" id="totberat"><input type="hidden" value="' . $subtotal . '" name="subtotal" id="subtotal"></td></tr>';

			} else {

				$html = '<tr><td colspan="9" class="text-center">Tidak ada data</td></tr>';

			}

		} else {

			$html = '<tr><td colspan="9" class="text-center">Tidak ada data</td></tr>';

		}

		echo json_encode(array("jmlerror" => $jmlerror, "msgerror" => $pesanerror, "html" => $html, "total" => $total, "totallabel" => $this->Fungsi->fFormatuang($subtotal)));

	}

	public function showminiCart($cartitem, $tipe, $idmember)

	{

		$options = array();

		$carts = array();

		$i = 0;

		$stoks = 0;

		$jmlerror = 0;

		$pesan = [];

		$xx = 0;

		$wheretipe = "cg_id='" . $tipe . "'";

		$grupcust = $this->Fungsi->fcaridata2('_customer_grup', 'cg_min_beli,cg_min_beli_syarat,cg_diskon', $wheretipe);



		$minbeli = $grupcust['cg_min_beli'];

		$syarat = $grupcust['cg_min_beli_syarat'];

		$diskoncust = $grupcust['cg_diskon'];



		$jmlall = array_sum($cartitem);



		foreach ($cartitem as $key => $quantity) {

			$product = explode(':', $key);

			$id = $product[0];

			$dataoption = $product[1];

			$ukuran = '';

			$warna = '';

			$options = explode(",", $dataoption);



			if ($dataoption == '') {

				$key = (int) $id;

			} else {



				$key = (int) $id . ':' . $dataoption;

			}



			/*

			if(!isset($_SESSION['ukradmincart'][$idmember][$id]))

				$_SESSION['ukradmincart'][$idmember][$id] = array();

		

			if(!isset($_SESSION['wrnadmincart'][$idmember][$id])) 

				 $_SESSION['wrnadmincart'][$idmember][$id] = array();

		

			$_SESSION['ukradmincart'][$idmember][$id][$i] = $options[0];

			$_SESSION['wrnadmincart'][$idmember][$id][$i] = $options[1];

			*/

			$qtylama = isset($_SESSION['qtylamaadmincart'][$idmember][$key]) ? $_SESSION['qtylamaadmincart'][$idmember][$key] : $quantity;

			$jmlitem = (int) $_SESSION['qtyadmincart'][$idmember][$id];

			$jmlcart = $quantity;

			$xx = $xx + $jmlitem;





			$prod = $this->dataProduk->getProdukByID($id);



			$getpoin = $prod['poin'] === null ? 0 : $prod['poin'];

			$hrgdiskon = $prod['hrg_diskon'];

			$persendiskon = $prod['persen_diskon'];

			$hrgsatuan = $prod['hrg_jual'];

			/*

			$hrgjual = $hrgsatuan - (($hrgsatuan * $diskoncust)/100);

			$hrgjualdiskon = $hrgdiskon - (($hrgdiskon * $diskoncust)/100);

			*/



			$persen = $diskoncust + $prod['persen_diskon'];



			$allstok    = $prod['jml_stok'];

			$stok       = $this->dataProduk->getOption($id, $options[1], $options[0]);

			$stok_option = isset($stok['stok']) ? $stok['stok'] : 0;

			$stoks = $allstok;



			$tambahanhrg = isset($stok['tambahan_harga']) ? $stok['tambahan_harga'] : 0;



			$harganormal = $hrgsatuan + $tambahanhrg;



			if ($syarat == '1') {

				$jmlsyarat = $jmlitem;

			} else {

				$jmlsyarat = $jmlall;

			}



			if ($minbeli < $jmlsyarat + 1) {

				/*

				if($hrgdiskon > 0) {

					//$harga = $hrgjualdiskon;

					//$harga = ($hrgsatuan + $tambahanhrg) - (($hrgsatuan * $persen)/100);

					//$harga = $harganormal - (($harganormal * $persen) / 100);

				} else {

					$harga = $harganormal;

				}

				*/

				$harga = $harganormal - (($harganormal * $persen) / 100);

			} else {

				if ($hrgdiskon > 0) {

					//$harga = $hrgdiskon;

					$harga = $harganormal - (($harganormal * $persendiskon) / 100);

				} else {

					//$harga = $hrgsatuan + $tambahanhrg;

					$harga = $harganormal;

				}

			}

			//$harga = $harga + $tambahanhrg;



			$item_total = ((int) $harga) * (int) $jmlcart;

			$berat = ((int) $prod['berat_produk']) * (int) $jmlcart;



			if ($stok_option < 1) {

				$stoknya = $allstok;

			} else {

				$stoknya = $stok_option;

			}



			if ($stoknya + $qtylama < $jmlcart) {

				$pesan[] = 'Stok ' . $prod['nama_produk'] . ' tersedia ' . $stoknya . ' Pcs';

				$jmlerror++;

			}



			$carts[] = array(

				"product_id"			=> $id,

				"product"				=> $prod['nama_produk'],

				"qty"					=> $jmlcart,

				"harga"					=> $harga,

				"hargasatuan"  	 		=> $hrgsatuan,

				"hargatambahan"			=> $tambahanhrg,

				"persen_diskon_prod"	=> $prod['persen_diskon'],

				"diskon_cust"			=> $diskoncust,

				"sale"					=> $prod['sale'],

				"total"					=> $item_total,

				"satuanberat"			=> (int) $prod['berat_produk'],

				"berat"					=> $berat,

				"stok"					=> $stoks,

				"poin"          		=> $getpoin,

				"warna"					=> $options[1],

				"warna_nama"			=> $this->Fungsi->fcaridata('_warna', 'warna', 'idwarna', $options[1]),

				"ukuran"	    		=> $options[0],

				"ukuran_nama"			=> $this->Fungsi->fcaridata('_ukuran', 'ukuran', 'idukuran', $options[0]),

			);



			$i++;

		}

		$carts = array(

			'carts'	=> $carts,

			'items'	=> count($_SESSION['hsadmincart'][$idmember]),

			'idmember' => $idmember,

			'jmlerror' => $jmlerror,

			'pesan' => $pesan

		);



		return $carts;

	}

	public function updateCart()

	{

		$totbeli = array();

		$pesan = '';

		$hasil = '';

		if (isset($_POST['jumlah'])) {

			foreach ($_POST['jumlah'] as $key => $value) {

				$data 		= explode("::", $key);

				$pid 		= $data[0];



				$options 	= unserialize(base64_decode($data[1]));

				$option 	= implode(",", $options);

				//print_r($option);

				if (!$options) {

					$keys = $pid;

				} else {

					$keys = $pid . ':' . $option;

				}

				$qty 		= $value;

				$idmember 	= $data[2];

				$produk  	= $this->dataProduk->getProdukByID($pid);

				$stokall 	= $produk['jml_stok'];

				if ($options[1] != '') {

					$zwarna = ' warna ' . $this->Fungsi->fcaridata('_warna', 'warna', 'idwarna', $options[1]) . ' ';

				} else {

					$zwarna = '';

				}

				if ($options[0] != '')

					$zukuran = ' ukuran ' . $this->Fungsi->fcaridata('_ukuran', 'ukuran', 'idukuran', $options[0]) . ' ';

				else

					$zukuran = '';



				if ((int) $qty && ((int) $qty > 0)) {

					$stokwarna = $this->dataProduk->getStokWarnaUkuran($pid, $options[0], $options[1]);

					if ($stokwarna > 0) {

						$stok = $stokwarna;

					} else {

						$stok = $stokall;

					}



					if (!isset($totbeli[$pid]))  $totbeli[$pid] = $qty;

					else $totbeli[$pid] += $qty;



					if (!isset($totbeli[$keys]))  $totbeli[$keys] = $qty;

					else $totbeli[$keys] += $qty;



					if ($stok < $totbeli[$keys]) {

						$pesan = 'stok ' . $produk['nama_produk'] . $zwarna . $zukuran . ' tinggal tersedia ' . $stok . ' item';

						break;

					}

				} else {





					$pesan = 'Produk ' . $produk['nama_produk'] . $zwarna . $zukuran . ', silahkan masukkan jumlah';

					break;

				}

			}

			if ($pesan == '') {

				$_SESSION['hsadmincart'][$idmember][$keys] = $qty;

				$_SESSION['qtyadmincart'][$idmember][$pid] = $totbeli[$pid];

				$hasil = 'Berhasil mengubah jumlah beli';

				$status = 'success';

			} else {

				//$hasil = implode("<br>",$pesan);

				$hasil = $pesan;

				$status = 'error';

			}

		} else {

			$status = 'error';

			$hasil = 'Tidak ada data';

		}

		echo json_encode(array("status" => $status, "msg" => $hasil));

	}



	public function delCart()

	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$data = [];

			foreach ($_POST as $key => $value) {

				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';

			}

			$data = explode("::", $data['data']);

			$pid = $data[0];

			$options = unserialize(base64_decode($data[1]));

			$option = implode(",", $options);

			$jml    = $data[3];

			$idmember = $data[2];

			if (!$options) {

				$key = $pid;

			} else {

				$key = $pid . ':' . $option;

			}

			unset($_SESSION['hsadmincart'][$idmember][$key]);



			$_SESSION['qtyadmincart'][$idmember][$pid] = $_SESSION['qtyadmincart'][$idmember][$pid] - $jml;



			if (count($_SESSION['qtyadmincart'][$idmember]) == 0) {

				unset($_SESSION['qtyadmincart'][$idmember][$pid]);

			}

			$status = 'success';

			$result = 'Berhasil menghapus item produk';

		} else {

			$status = 'error';

			$result = 'Data tidak valid';

		}

		echo json_encode(array("status" => $status, "msg" => $result));

	}



	public function buyCart($orderstatus)

	{

		$pesan = array();

		$hasil = array();

		$result = array();

		$proses = array();

		//if(isset($idmember)) {



		/* Petugas */

		//$this->data['userid']	   = isset($_SESSION["idlogin"]) ? $_SESSION["idlogin"]:'';

		$this->data['userid']	    = isset($_POST['user_idlogin']) ? $_POST['user_idlogin'] : '';



		//Penerima



		$this->data['nama'] 		= isset($_POST['pnama']) ? mysql_real_escape_string($_POST['pnama']) : '';

		$this->data['telp'] 		= isset($_POST['pnotelp']) ? $_POST['pnotelp'] : '';

		$this->data['hp']   		= isset($_POST['pnohp']) ? $_POST['pnohp'] : '';

		$this->data['alamat']		= isset($_POST['palamat']) ? mysql_real_escape_string($_POST['palamat']) : '';

		$this->data['negara']		= isset($_POST['pnegara']) ? $_POST['pnegara'] : '';

		$this->data['propinsi']	= isset($_POST['ppropinsi']) ? $_POST['ppropinsi'] : '';

		$this->data['kabupaten'] 	= isset($_POST['pkabupaten']) ? $_POST['pkabupaten'] : '';

		$this->data['kecamatan']	= isset($_POST['pkecamatan']) ? $_POST['pkecamatan'] : '';

		$this->data['kelurahan']	= isset($_POST['pkelurahan']) ? $_POST['pkelurahan'] : '';

		$this->data['kodepos']	= isset($_POST['pkodepos']) ? $_POST['pkodepos'] : '';



		//Pengirim

		$this->data['idmember']			= isset($_POST['idreseller']) ? $_POST['idreseller'] : '';

		$this->data['kdreseller']			= isset($_POST['kdreseller']) ? $_POST['kdreseller'] : '';

		$this->data['namapengirims'] 		= isset($_POST['namareseller']) ? mysql_real_escape_string($_POST['namareseller']) : '';

		$this->data['telppengirims'] 		= isset($_POST['notelp']) ? $_POST['notelp'] : '';

		$this->data['hppengirims'] 		= isset($_POST['nohp']) ? $_POST['nohp'] : '';

		$this->data['alamatpengirims'] 	= isset($_POST['alamat']) ? mysql_real_escape_string($_POST['alamat']) : '';

		$this->data['negarapengirims'] 	= isset($_POST['idnegara']) ? $_POST['idnegara'] : '0';

		$this->data['propinsipengirims'] 	= isset($_POST['idpropinsi']) ? $_POST['idpropinsi'] : '0';

		$this->data['kabupatenpengirims'] = isset($_POST['idkabupaten']) ? $_POST['idkabupaten'] : '0';

		$this->data['kecamatanpengirims'] = isset($_POST['idkecamatan']) ? $_POST['idkecamatan'] : '0';

		$this->data['kelurahanpengirims'] = isset($_POST['kelurahan']) ? $_POST['kelurahan'] : '0';

		$this->data['kodepospengirims'] 	= isset($_POST['kodepos']) ? $_POST['kodepos'] : '';

		$this->data['emailpengirims'] 	= isset($_POST['email']) ? $_POST['email'] : '';



		$this->data['cust_grup'] 		= isset($_POST['jenis']) ? $_POST['jenis'] : '';



		$zfield 			= 'rs_dropship';

		$ztabel 			= '_reseller INNER JOIN _cust_grup ON _reseller.cust_grup = _cust_grup.rs_grupid';

		$zwhere 			= "cust_id = '" . $this->data['idmember'] . "'";

		$zreseller 		= $this->Fungsi->fcaridata2($ztabel, $zfield, $zwhere);



		$dropship		    = $zreseller[0];





		//Infaq

		$this->data['infaq'] = isset($_POST['infaq']) ? $_POST['infaq'] : 0;



		//Deposit

		$this->data['deposit'] = isset($_POST['deposit']) ? $_POST['deposit'] : 0;



		$servis 		= isset($_POST['serviskurir']) ? $_POST['serviskurir'] : '';

		$zzhrgkurir  	= isset($_POST['zhrgkurir']) ? $_POST['zhrgkurir'] : 0;



		$zdata		= explode(":", $servis);

		$idservis		= $zdata[0];

		$idkurir		= $zdata[1];

		$kurir		= $this->dataShipping->getShippingbyName($idkurir);

		$namashipping = $kurir['nama_shipping'];

		$tabelservis	= $kurir['tabel_servis'];

		$tabeltarif	= $kurir['tabel_tarif'];

		$tabeldiskon	= $kurir['tabel_diskon'];

		$logoshipping	= $kurir['logo'];

		$detekkdpos   = $kurir['detek_kdpos'];



		$servisdata   = $this->dataShipping->getServisbyId($tabelservis, $idservis);

		$namaservis	= $servisdata[1];

		$servisid		= $servisdata[0];

		$minkilo		= $servisdata[3];



		$this->data['iddetail'] 	= (int) $this->Fungsi->fIdAkhir('_order_detail', 'iddetail') + 1;

		$kodeakhir 	= $this->Fungsi->fIdAkhir('_order', 'CONVERT(nopesanan,SIGNED)');

		$this->data['nopesanan'] = sprintf('%08s', $kodeakhir + 1);



		$keranjang 	= $this->showminiCart($_SESSION['hsadmincart'][$this->data['idmember']], $this->data['cust_grup'], $this->data['idmember']);

		$totalitem 	= $keranjang['items'];

		$cart 		= $keranjang['carts'];

		$subtotal 	= 0;

		$i 			= 0;

		$totberat 	= 0;

		$totjumlah	= 0;

		$tabel 		= '<table style="border-collapse: collapse;width: 100%;margin-bottom: 15px;border-top: 1px solid #DDDDDD;border-left: 1px solid #DDDDDD;border-right: 1px solid #DDDDDD;">';

		$tabel	   .= '<thead><tr><td style="font-weight: bold;border-bottom: 1px solid #DDDDDD;text-align: center;"></td>';

		$tabel	   .= '<td style="font-weight: bold;border-bottom: 1px solid #DDDDDD;text-align: left;">Nama Produk</td>';

		$tabel	   .= '<td style="font-weight: bold;border-bottom: 1px solid #DDDDDD;text-align: center;">Jumlah</td>';

		$tabel	   .= '<td style="font-weight: bold;border-bottom: 1px solid #DDDDDD;text-align: right;">Berat</td>';

		$tabel	   .= '<td style="font-weight: bold;border-bottom: 1px solid #DDDDDD;text-align: right;">Harga</td>';

		$tabel       .= '<td style="font-weight: bold;border-bottom: 1px solid #DDDDDD;text-align: right;">Sub Total</td></tr></thead><tbody>';



		foreach ($cart as $c) {

			$this->data['pid']   	= $c['product_id'];

			$this->data['jml'] 	 	= $c['qty'];

			$this->data['berat'] 	= $c['berat'];

			$this->data['harga'] 	= $c['harga'];

			$this->data['total'] 	= $c['total'];



			$this->data['idwarna']  = $_SESSION['wrnadmincart'][$this->data['idmember']][$this->data['pid']][$i];

			$this->data['idukuran'] = $_SESSION['ukradmincart'][$this->data['idmember']][$this->data['pid']][$i];

			if ($this->data['idwarna'] != '') $warna	= $this->Fungsi->fcaridata('_warna', 'warna', 'idwarna', $this->data['idwarna']);

			else $warna = '';



			if ($this->data['idukuran'] != '') $ukuran = $this->Fungsi->fcaridata('_ukuran', 'ukuran', 'idukuran', $this->data['idukuran']);

			else $ukuran = '';



			//$gbr 		 			= $c['gbr'];

			$nama_produk 			= $c['product'];

			$where 					= "idproduk='" . $this->data['pid'] . "'";

			$prods					= $this->Fungsi->fcaridata2('_produk', 'hrg_jual,hrg_beli', $where);

			$this->data['satuan']	= $prods[0];

			$this->data['hrgbeli']	= $prods[1];

			//$this->data['satuan'] 	= $this->Fungsi->fcaridata('_produk','hrg_jual','idproduk',$this->data['pid']);

			//$this->data['hrgbeli'] 	= $this->Fungsi->fcaridata('_produk','hrg_beli','idproduk',$this->data['pid']);

			if (!$this->model->SimpanOrderDetail($this->data)) {

				$proses[] = $this->model->SimpanOrderDetail($this->data);

				$pesan[] = 'error simpan detail';

			}



			if ($this->data['idwarna'] != '' || $this->data['idwarna'] != '0' || $this->data['idukuran'] != '' || $this->data['idukuran'] != '0') {

				if (!$this->model->SimpanOrderDetailOption($this->data)) {

					$proses[] = $this->model->SimpanOrderDetailOption($this->data);

					$pesan[] = 'error simpan detail option';

				}

				if (!$this->model->UpdateStokOptionberKurang($this->data)) {

					$proses[] = $this->model->UpdateStokOptionberKurang($this->data);

					$pesan[] = 'error update stok option';

				}

			}



			if (!$this->model->UpdateStokberKurang($this->data)) {

				$proses[] = $this->model->UpdateStokberKurang($this->data);

				$pesan[] = 'error update stok';

			}



			$subtotal	+= $this->data['total'];

			$totberat   += $this->data['berat'];

			$totjumlah  += (int) $c['qty'];



			//$tabel		.= '<tr><td style="vertical-align: top;border-bottom: 1px solid #DDDDDD;font-size:11px;text-align: center;"><img src="http://hijabsupplier.com'.URL_IMAGE.'_small/small_'.$gbr.'"></td>';

			//$tabel		.= '<td style="vertical-align: top;border-bottom: 1px solid #DDDDDD;font-size:11px;text-align: left;">'.$nama_produk.'<br>'.$warna.'<br>'.$ukuran.'</td>';

			//$tabel		.= '<td style="vertical-align: top;border-bottom: 1px solid #DDDDDD;font-size:11px;text-align: right;">'.$this->data['jml'].'</td>';

			//$tabel		.= '<td style="vertical-align: top;border-bottom: 1px solid #DDDDDD;font-size:11px;text-align: right;">'. $this->data['berat'].'Gram</td>';

			//$tabel		.= '<td style="vertical-align: top;border-bottom: 1px solid #DDDDDD;font-size:11px;text-align: right;">'. $this->Fungsi->fFormatuang($this->data['harga']).'</td>';

			//$tabel		.= '<td style="vertical-align: top;border-bottom: 1px solid #DDDDDD;font-size:11px;text-align: right;">'. $this->Fungsi->fFormatuang($this->data['total']).'</td>';

			//$tabel		.= '</tr>';



			$this->data['iddetail']++;

			$i++;

		}





		$this->data['subtotal'] = $subtotal;

		$this->data['totjumlah'] = $totjumlah;



		$tarifkurir = $this->dataShipping->getTarif($servisid, $this->data['negara'], $this->data['propinsi'], $this->data['kabupaten'], $this->data['kecamatan'], $totberat, $minkilo, $tabeltarif, $detekkdpos, $namashipping);

		if ($tabeldiskon != Null || $tabeldiskon != '') {

			$zzdizkon = explode("::", $tabeldiskon);

			$tabel = $zzdizkon[0];

			$fieldambil = $zzdizkon[1];

			$where = " $zzdizkon[2]='" . $servisid . "' AND $zzdizkon[3]=1";



			$dtdiskon = $this->Fungsi->fcaridata2($tabel, $fieldambil, $where);

			$zdiskon = $dtdiskon[0] / 100;

		} else {

			$zdiskon = 0;

		}



		/* $tabel = "_servis_jne_diskon";

	  $fieldambil = 'jml_disk';

	  $where = " servis_id='".$servisid."' AND stsdisk=1";

	  $dtdiskon = $this->Fungsi->fcaridata2($tabel,$fieldambil,$where);

	  $zdiskon = $dtdiskon[0] / 100;

	  

	  */



		//if($this->data['nama'] != $this->data['namapengirims'] && $this->data['alamat'] != $this->data['alamatpengirims'] && $this->data['propinsi'] != $this->data['propinsipengirims'] && $this->data['kabupaten'] != $this->data['kabupatenpengirims'] && $this->data['kecamatan'] != $this->data['kecamatanpengirims'] && $dropship=='1') {

		//	$zdiskon = 0;

		//} else {

		//	$zdiskon = $dtdiskon[0] / 100;

		//}





		$tarifkurir[1] = $tarifkurir[1] - ($tarifkurir[1] * $zdiskon);

		$tarifkurir[4] = $tarifkurir[4] - ($tarifkurir[4] * $zdiskon);

		if ($tarifkurir[1] > 0) {

			$this->data['tarifkurir'] = $tarifkurir[1];

			$this->data['satuantarifkurir'] = $tarifkurir[4];

		} else {

			$this->data['tarifkurir'] = $zzhrgkurir;

			$this->data['satuantarifkurir'] = 0;

		}



		$this->data['kurir'] = $namashipping;

		$this->data['kurirservis'] = $servisid;

		$this->data['orderstatus'] = $orderstatus;

		//$this->data['idmember'] = $idmember;

		$this->data['tgltrans'] = date('Y-m-d H:i:s');

		$this->data['bayaregis']	= $this->Fungsi->fcaridata2('_cust_invoice', 'biaya', "stsbayar='0' AND idmember='" . $this->data['idmember'] . "'");

		//$this->data['bayaregis']	= $this->fcaridata2('_cust_invoice','biaya',"stsbayar='0' AND idmember='".$this->data['idmember']."' AND member_grup='".$this->data['cust_grup']."'");



		if (!$this->model->SimpanOrder($this->data)) {

			$proses[] = $this->model->SimpanOrder($this->data);

			$pesan[] = 'error simpan order';

		}

		if (!$this->model->SimpanOrderPenerima($this->data)) {

			$proses[] = $this->model->SimpanOrderPenerima($this->data);

			$pesan[] = 'error simpan order penerima';

		}

		if (!$this->model->SimpanOrderPengirim($this->data)) {

			$proses[] = $this->model->SimpanOrderPengirim($this->data);

			$pesan[] = 'error simpan order pengirim';

		}

		if (!$this->model->SimpanOrderStatus($this->data)) {

			$proses[] = $this->model->SimpanOrderStatus($this->data);

			$pesan[] = 'error simpan order status';

		}



		/* tambahan untuk intensif bonus admin input */

		$zfieldbns 			    = 'intensif_per_order,intensif_batas';

		$ztabelbns 			    = '_setting_toko';

		$jmlbonus					= $this->Fungsi->fcaridata2($ztabelbns, $zfieldbns, "1=1");

		//$this->data['jmlbonus'] = $this->Fungsi->fcaridata($ztabelbns,$zfieldbns,'1','1');

		$this->data['jmlbonus']   = $jmlbonus[0];

		$this->data['btsbonus']   = $jmlbonus[1];



		if (!$this->model->SimpanIntensif($this->data)) {

			$proses[] = $this->model->SimpanIntensif($this->data);

			$pesan[] = 'error simpan intensif bonus';

		}

		/* @end intensif bonus admin input */



		if ((int) $this->data['deposit'] > 0) {

			if (!$this->model->UpdateDepositBerkurang($this->data)) {

				$proses[] = $this->model->UpdateDepositBerkurang($this->data);

				$pesan[] = 'error update deposit';

			}

			if (!$this->model->InsertDepositDetail($this->data)) {

				$proses[] = $this->model->InsertDepositDetail($this->data);

				$pesan[] = 'error insert deposit detail';

			}

		}

		$result['idmember'] = $this->data['idmember'];

		$result['nama']	= $this->data['namapengirims'];

		$result['totalbelanja'] = ((int) $this->data['subtotal'] + (int) $this->data['tarifkurir'] + (int) $this->data['bayaregis'][0] + (int) $this->data['infaq']) - (int) $this->data['deposit'];

		$result['noorder'] = $this->data['nopesanan'];

		$result['kdreseller'] = $this->data['kdreseller'];

		//$result['sukseskonfirm'] = 'ya';

		//print_r($pesan);

		if (count($pesan) == 0) {

			//$hasil = "sukses";

			$this->model->prosesTransaksi($proses);

			$hasil['sts'] = "sukses";

			$hasil['data'] = $result;

		} else {

			$hasil = implode("<br>", $pesan);

			//$hasil = 'gagal|'.$hasil;

			$hasil['sts'] = "gagal";

			$hasil['data'] = implode("\n", $pesan);

		}

		return $hasil;

		//exit;



	}

	public function editPotonganDeposito()

	{

		$data = [];

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			foreach ($_POST as $key => $value) {

				$data["$key"]	= isset($_POST["$key"]) ? $value : '';

			}



			$sisadeposito 	= $this->dataCustomer->totalDepositoById($data['pelanggan_id']);

			$depositoold  	= $data['potongandepositold'];

			$depositnew   	= $data['jmldeposit'];

			$subtotal		= $data['subtotal'];

			$tarifkurir		= $data['tarifkurir'];

			$totalbelanja   = $subtotal + $tarifkurir;

			if (($depositoold + $sisadeposito) < $depositnew) {



				$pesan = "Jumlah Deposit Anda tidak mencukupi";

				$status = 'error';

			} else {

				if ($totalbelanja < $depositnew) {

					$data['jmldeposit'] = $totalbelanja;

				}

				//5000    6000        12000 - -1000

				//$data['deposit'] = $data['jmldeposit'] - $depositoold;

				$data['keterangan'] = 'Menggunakan Saldo di Order ' . sprintf('%08s', (int) $data["pesanan_no"]);

				

				$modelsetting = new modelSetting();

				$setting = $modelsetting->getSettingByKeys(array('config_sudahbayarstatus','config_orderstatus'));

				foreach ($setting as $st) {

					$key 	= $st['setting_key'];

					$value 	= $st['setting_value'];

					$$key = $value;

				}

				

				if($totalbelanja > $data['jmldeposit']) {

					$data['order_status'] = $config_orderstatus;

					$data['insert_status'] = '0';

				} else {

					$data['order_status'] = $config_sudahbayarstatus;

					$data['insert_status'] = '1';

				}

				

				$simpan = $this->model->simpanEditPotonganDeposito($data);

				if ($simpan) {

					$status = 'success';

					$pesan = 'Berhasil mengubah potongan dari deposito pelanggan';

				} else {

					$status = 'error';

					$pesan = 'Proses mengubah potongan dari deposito pelanggan tidak berhasil';

				}

				/*

				if($potdepositold < $data['jmldeposit']) {

					

					$data['deposit'] = $data['jmldeposit'] - $depositoold;

					$j = "kurang";

					

				} else {

					

					$data['deposit'] = $potdepositold - $data['jmldeposit'];

					$j = "tambah";

				}

				*/

			}

		} else {

			$status = 'error';

			$pesan = 'Data tidak valid';

		}

		echo json_encode(array("status" => $status, "result" => $pesan));

	}

	public function editKurir()

	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$data = [];

			foreach ($_POST as $key => $value) {

				$data["$key"]	= isset($_POST["$key"]) ? $value : '';

			}

			

			//print_r($data);



			if ($this->validasisimpankurir($data)) {



				$serviskurir 	= isset($data['serviskurir']) ? explode("::", $data['serviskurir']) : array();

				$servis_id	 	= isset($serviskurir[0]) ? $serviskurir[0] : 0;

				$tarif		 	= isset($serviskurir[1]) ? $serviskurir[1] : 'Konfirmasi Admin';

				$shipping_kode	= isset($serviskurir[2]) ? $serviskurir[2] : 0;

				$servis_kode	= isset($serviskurir[3]) ? $serviskurir[3] : '';

				$tarifkurirs	= isset($data['tarifkurir']) ? $data['tarifkurir'] : 0;



				//$totalbelanja 	= $data['subtotal'] + $data['tarifkurir'];





				$data['serviskurir']		= $servis_id;

				$data['kurir']				= $shipping_kode;

				$data['kurir_perkilo']		= 0;

				$data['servis_code']		= $servis_kode;

				$totberat = (int) $data['totberat'] / 1000;

				if ($totberat < 1) $totberat = 1;



				if($tarif == 'Konfirmasi Admin') {

				//if ($tarif == '0') {

					$data['konfirm_admin'] = '1';

					$captiontarif = 'Konfirmasi Admin';

					$data['tarifkurir'] = $tarifkurirs;

				} else {

					$data['konfirm_admin'] = '0';

					$captiontarif = 'Rp. ' . $this->Fungsi->fuang($tarif);

					$data['tarifkurir'] = $tarif;

				}

				

				$totalbelanja 	= $data['subtotal'] + $data['tarifkurir'];



				if ($totalbelanja < $data['jmldeposit']) {

					$data['jmldeposit'] = $totalbelanja;

				}

				$data['keterangan'] = 'Menggunakan Saldo di Order ' . sprintf('%08s', (int) $data["nopesanan"]);

				$simpan = $this->model->simpanEditKurir($data);

				if ($simpan['status'] == 'success') {

					$status = 'success';

					$pesan = 'Berhasil mengubah tarif kurir';

				} else {

					$status = 'error';

					$pesan = 'Proses mengubah tarif kurir gagal';

				}

			} else {

				$status = 'error';

				$pesan = implode("<br>", $this->error);

			}

		} else {

			$status = 'error';

			$pesan = 'Data tidak valid';

		}

		/*

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$data = [];

			foreach ($_POST as $key => $value) {

				$data["$key"]	= isset($_POST["$key"]) ? $value : '';

			}

			if($this->validasisimpankurir($data)){

				$dataservis 	= $this->dataShipping->getServisByIdserv($data);

				$datashipping 	= $this->dataShipping->getShippingByIdServ($data);

				

				$data['kurir'] 	= $datashipping['shipping_kode'];

				$data['kurir_perkilo'] = isset($dataservis['hrg_perkilo']) ? $dataservis['hrg_perkilo'] : 0;

				

				$totberat = (int)$data['totberat'] / 1000;

				if($totberat < 1) $totberat = 1;

				$jarakkoma = 0;

				if($totberat > 1) {

					$berat = floor($totberat);

					$jarakkoma = $totberat - $berat;

				}

				$batas = isset($dataservis['shipping_bataskoma']) ? $dataservis['shipping_bataskoma'] : 0;

				if($datashipping['shipping_konfirmadmin'] == '0') {

					if($jarakkoma > $batas) $totberat = ceil($totberat);

					else $totberat = floor($totberat);

					$tarif = $totberat * $data['kurir_perkilo'];

					$data['tarifkurir'] = $tarif;

					$data['konfirm_admin'] = '0';

				} else {

					if($data['tarifkurir'] != '' && $data['tarifkurir'] != 0) {

						$data['konfirm_admin'] = '0';

					} else {

						$data['konfirm_admin'] = '1';

						$data['tarifkurir'] = 0;

					}

					

				}

				

				$simpan = $this->model->simpanEditKurir($data);

				if($simpan) {

					$status = 'success';

					$pesan = 'Berhasil mengubah tarif kurir';

				} else {

					$status = 'error';

					$pesan = 'Proses mengubah tarif kurir gagal';

				}

			} else {

				$status = 'error';

				$pesan = implode("<br>",$this->error);

			}

		} else {

			$status = 'error';

			$pesan = 'Data tidak valid';

		}

		*/

		echo json_encode(array("status" => $status, "result" => $pesan));

	}



	private function validasisimpankurir($data)

	{

		if ($data['nopesanan'] == '' || !$this->model->cekOrder($data['nopesanan'])) {

			$this->error[] = 'No. Pesanan tidak valid';

		}

		if ($data['serviskurir'] == '' && $data['serviskurir'] == '0') {

			$this->error[] = 'Pilih Servis Kurir';

		}

		if ($data['propinsi_penerima'] == '' && $data['propinsi_penerima'] == '0') {

			$this->error[] = 'Propinsi tidak ada';

		}

		if ($data['kabupaten_penerima'] == '' && $data['kabupaten_penerima'] == '0') {

			$this->error[] = 'Kotamadya/Kabupaten tidak ada';

		}

		if ($data['kecamatan_penerima'] == '' && $data['kecamatan_penerima'] == '0') {

			$this->error[] = 'Kecamatan tidak ada';

		}

		if (count($this->error) > 0) {

			return false;

		} else {

			return true;

		}

	}



	public function dataOrderNolKurir()

	{

		return $this->model->getOrderNolKurir();

	}

	public function getTotalOrderPending($status)

	{

		return $this->model->getTotalOrderPending($status);

	}

	public function simpanstatusorder()

	{

		$data = [];

		$pesan = '';

		$status = '';

		foreach ($_POST as $key => $value) {

			$data["$key"]	= isset($_POST["$key"]) ? $value : '';

		}



		$data['kirimmailsudahbayar'] = isset($data['kirimmailsudahbayar']) ? $data['kirimmailsudahbayar'] : '';

		$data['tgl']         = date('Y-m-d H:i:s');

		$data['ipdata'] = $this->Fungsi->get_client_ip();

		//$status_pending = $this->Fungsi->fcaridata('_setting','setting_value','setting_key','config_orderstatus');

		$where = "setting_key IN ('config_orderstatus','config_ordercancel')";

		$status  = $this->Fungsi->fcaridata3('_setting', 'setting_key,setting_value', $where);

		$datastatus = [];

		if ($status) {

			foreach ($status as $sts) {

				$datastatus["{$sts['setting_key']}"] = $sts['setting_value'];

			}

		}

		$status_pending = $datastatus['config_orderstatus'];

		$status_cancel	= $datastatus['config_ordercancel'];



		$data['simpanstatusorder'] = false;

		if ($data['orderstatus'] != $data['stsnow']) {



			if ($data['orderstatus'] != '' && $data['orderstatus'] != '0' && $data['nopesanan'] != '') {

				$data['simpanstatusorder'] = true;

			} else {

				$error = true;

				$data['simpanstatusorder'] = false;

			}

		} else {



			if ($data['orderstatus'] == $status_pending) {

				$data['updatestatusorder'] = true;

			} else {

				$data['updatestatusorder'] = false;

			}

		}

		$data['konfirmasiorder'] = '';

		if ($data['orderstatus'] == $data['stskonfirm']) {

			if ($data['modekonfirm'] == 'inputkonfirm') {





				$data['konfirmasiorder'] = 'add';

			} else {

				$data['konfirmasiorder'] = 'edit';

			}

		}



		$checkpoin = $this->model->checkPoinHistory($data, 'IN');

		$data['simpangetpoin'] = '';

		//$data['insertgetpoin'] = false;



		$statusgetpoin = explode("::", $data['stsgetpoin']);

		if (in_array($data['orderstatus'], $statusgetpoin)) {

			$data['getpoins'] = true;

			/*

			if($checkpoin > 0){

			

				$data['simpangetpoin'] = 'update';

				$data['totpoindapat'] = $checkpoin - $data['totpoindapat'];

			} else {

				if($data['totpoindapat'] > 0 ) {

					$data['simpangetpoin'] = 'insert';

				}

			}

			*/

		} else {

			$data['getpoins'] = true;

		}



		$nextsave = true;



		if ($data['orderstatus'] == $status_cancel) {

			$dataorder = [];

			$dataorder[] = $data['nopesanan'];



			$cancelorder = $this->cancelOrder($dataorder, '1');

			if ($cancelorder['status'] == 'error') {

				$nextsave = false;

			}

		}

		$simpan['status'] = '';

		if ($nextsave == true) {

			$data['tglbayar'] = isset($data['tglbayar']) && $data['tglbayar'] != '' ? $data['tglbayar']  : date('Y-m-d');

			$simpan = $this->model->simpaneditstatus($data);

		}

		$data['kirimmailship'] = isset($data['kirimmailship']) ? $data['kirimmailship'] : '';

		if ($simpan['status'] == 'success') {

			$status = 'success';

			$pesan = 'Berhasil mengubah status order';

			if ($data['stsshipping'] == $data['orderstatus']) {

				if ($data['kirimmailship'] == '1') {

					if (!$this->kirimInvoice($data)) {

						$status = 'error';

						$pesan = 'Kirim email tidak berhasil';

					}

				}

			}



			if ($data['stssudahbayar'] == $data['orderstatus']) {

				if ($data['kirimmailsudahbayar'] == '1') {

					if (!$this->kirimNotifBayar($data)) {

						$status = 'error';

						$pesan = 'Kirim email tidak berhasil';

					}

				}

			}

		} else {

			$status = 'error';

			$pesan = 'Gagal Menyimpan Status Order';

		}



		echo json_encode(array("status" => $status, "result" => $pesan));

	}



	public function kirimNotifBayar($data)

	{

		$this->kirimemail = new PHPMailer();



		$pelangganid = $data['pelangganid'];

		$nopesanan = $data['nopesanan'];

		$wherecust = "cust_id='" . $pelangganid . "'";

		$datapelanggan = $this->Fungsi->fcaridata2('_customer', 'cust_nama,cust_email', $wherecust);

		$datakonfirmasi = $this->model->getOrderKonfirmasi($nopesanan);



		$cekbanktujuan = $this->Fungsi->fcaridata2("_bank_rekening INNER JOIN _bank ON _bank_rekening.bank_id = _bank.bank_id", "rekening_atasnama,rekening_no,rekening_cabang,bank_nama ", "_bank_rekening.bank_id = '" . $datakonfirmasi['bank_rek_tujuan'] . "'");



		$message   = $this->Fungsi->fcaridata('_setting', 'setting_value', 'setting_key', 'config_infosudahbayar');



		$from      = $this->Fungsi->fcaridata('_setting', 'setting_value', 'setting_key', 'config_emailnotif');

		$from_name = $this->Fungsi->fcaridata('_setting', 'setting_value', 'setting_key', 'config_namatoko');

		$subject   = 'INFO STATUS PEMBAYARAN ' . sprintf('%06s', $nopesanan);

		$to = $datapelanggan['cust_email'];

		$namapelanggan = $datapelanggan['cust_nama'];



		$message   = str_replace("[PELANGGAN]", $namapelanggan, $message);

		$message   = str_replace("[No Order]", $nopesanan, $message);

		$message   = str_replace("[NAMA BANK DARI]", $datakonfirmasi['bank_dari'], $message);

		$message   = str_replace("[NO REK DARI]", $datakonfirmasi['bank_rek_dari'], $message);

		$message   = str_replace("[ATASNAMA DARI]", $datakonfirmasi['bank_atasnama_dari'], $message);

		$message   = str_replace("[NAMA BANK TUJUAN]", $cekbanktujuan['bank_nama'], $message);

		$message   = str_replace("[NO REK TUJUAN]", $cekbanktujuan['rekening_no'], $message);

		$message   = str_replace("[ATASNAMA TUJUAN]", $cekbanktujuan['rekening_atasnama'], $message);



		$this->kirimemail->IsHTML(true);

		$this->kirimemail->SetFrom($from, $from_name);

		$this->kirimemail->Subject = $subject;

		$this->kirimemail->WordWrap = 50;

		$this->kirimemail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

		$this->kirimemail->MsgHTML($message);

		$this->kirimemail->CharSet = "UTF-8";

		$this->kirimemail->AddAddress($to, $namapelanggan);



		if ($this->kirimemail->Send()) {

			return true;

		} else {

			return false;

		}

	}



	public function kirimInvoice($data)

	{

		$this->kirimemail = new PHPMailer();

		$nopesan = $data['nopesanan'];

		$noawb = $data['noawb'];

		$tglkirim = $data['tglkirim'];

		$pelangganid = $data['pelangganid'];

		//$namashipping = $data['namashipping'];

		$namashipping = $data['shipping'];

		$from      = $this->Fungsi->fcaridata('_setting', 'setting_value', 'setting_key', 'config_emailnotif');





		$from_name = $this->Fungsi->fcaridata('_setting', 'setting_value', 'setting_key', 'config_namatoko');



		$subject   = 'INFO PENGIRIMAN ' . sprintf('%06s', $nopesan);

		$message   = $this->Fungsi->fcaridata('_setting', 'setting_value', 'setting_key', 'config_infoshipping');



		$wherecust = "cust_id='" . $pelangganid . "'";

		$datapelanggan = $this->Fungsi->fcaridata2('_customer inner join _customer_grup on _customer.cust_grup_id = _customer_grup.cg_id', 'cust_nama,cust_email,cg_nm', $wherecust);



		$to = $datapelanggan['cust_email'];

		$namapelanggan = $datapelanggan['cust_nama'];



		$message   = str_replace("[PELANGGAN]", $namapelanggan, $message);

		$message   = str_replace("[No Order]", sprintf('%06s', $nopesan), $message);

		$message   = str_replace("[Kurir]", $namashipping, $message);

		$message   = str_replace("[Tgl Kirim]", $tglkirim, $message);

		$message   = str_replace("[No Awb]", $noawb, $message);



		$this->kirimemail->IsHTML(true);

		$this->kirimemail->SetFrom($from, $from_name);

		$this->kirimemail->Subject = $subject;

		$this->kirimemail->WordWrap = 50;

		$this->kirimemail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

		$this->kirimemail->MsgHTML($message);

		$this->kirimemail->CharSet = "UTF-8";

		$this->kirimemail->AddAddress($to, $namapelanggan);



		$data['nama_pelanggan'] = $namapelanggan;

		$data['grup_pelanggan'] = $datapelanggan['cg_nm'];



		$attachinvoice = $this->attachInvoice($data);

		$namareport = "invoice" . $nopesan . ".pdf";

		$this->kirimemail->AddAttachment($attachinvoice, $namareport);

		if ($this->kirimemail->Send()) {

			if (file_exists(DIR_INCLUDE . $namareport)) {

				unlink(DIR_INCLUDE . $namareport);

			}

			return true;

		} else {



			return false;

		}

	}





	public function attachInvoice($data)

	{

		//$this->data['nopesan'] = $data['nopesanan'];

		$order = $this->model->getOrderByID($data['nopesanan']);

		//untuk report



		$data['alamatreport'] = $data['alamatreport'];



		$datadetail 	= $this->model->getOrderDetail($data['nopesanan']);





		$data['namaservis'] = $data['servis'];

		$data['detailproduk'] = $datadetail;

		$data['order'] = $order;



		return $this->cetakInvoicePDF($data, 'attach');

	}







	public function cetakInvoicePDFBackup($data, $jenis)

	{



		$tabel = "_setting";

		$fieldambil = "setting_key,setting_value";

		$where = "setting_key='config_namatoko' OR setting_key='config_alamattoko' OR setting_key='config_alamatsite'";

		$toko = $this->Fungsi->fcaridata3($tabel, $fieldambil, $where);



		foreach ($toko as $tk) {

			if ($tk['setting_key'] == 'config_namatoko') {

				$data['nama_toko'] = $tk['setting_value'];

			}

			if ($tk['setting_key'] == 'config_alamattoko') {

				$data['alamat_toko'] = $tk['setting_value'];

			}

			if ($tk['setting_key'] == 'config_alamatsite') {

				$data['alamat_site'] = $tk['setting_value'];

			}

		}



		$nama_report = 'invoice' . $data['nopesanan'] . '.pdf';

		$pdf = new PDFTable();

		$pdf->SetMargins(10, 10, 10, 10);

		$pdf->AddPage("L", "A5");

		/*

		$pdf->SetLeftMargin(10);

		$pdf->SetRightMargin(10);

		*/

		//$pdf->SetMargins(10,10,10,10);

		$pdf->defaultFontFamily = 'Arial';

		$pdf->defaultFontStyle  = '';

		$pdf->defaultFontSize   = 7;

		$pdf->SetFont($pdf->defaultFontFamily, $pdf->defaultFontStyle, $pdf->defaultFontSize);

		$x = $pdf->GetX();

		$y = $pdf->GetY();



		$pdf->SetLineWidth(0.2);

		$width = $pdf->w - $pdf->lMargin - $pdf->rMargin;



		$tabelheader = '<table width="100%"><tr><td align="center" valign="middle"><font style="bold" size="15">' . strtoupper($data['nama_toko']) . ' ' . $pdf->w . '</font></td></tr>';

		$tabelheader .= "<tr><td align=\"center\" valign=\"middle\"><font size=\"8\">" . $data['alamat_toko'] . "</font></td></tr>";

		$tabelheader .= "<tr><td align=\"center\" valign=\"middle\"><font size=\"8\">" . $data['alamat_site'] . "</font></td></tr><tr></table>";

		$pdf->Line($x, 42, $x + $width, 42);

		$pdf->htmltable($tabelheader);

		$tabelheader2 = "<table width=\"100%\">";

		$tabelheader2 .= "<tr><td valign=\"middle\" width=\"20%\"><font style=\"bold\">No Order</font></td>";

		$tabelheader2 .= "<td valign=\"middle\">: #" . sprintf('%08s', $data['nopesanan']) . "</td>";

		$tabelheader2 .= "</tr><tr><td valign=\"middle\" ><font style=\"bold\">Tgl Order</font></td>";

		$tabelheader2 .= "<td valign=\"middle\">: " . $this->Fungsi->ftanggalFull1($data['tglorder']) . "</td>";

		$tabelheader2 .= "</tr>

					   <tr>

						 <td valign=\"middle\"><font style=\"bold\">Pelanggan</font></td>

						 <td valign=\"middle\">: " . $data['nmreseller'] . " ( " . $data['nmgrpreseller'] . " )</td>

					   </tr></table>";

		$pdf->htmltable($tabelheader2);

		$pdf->Line($x, 62, $x + $width, 62);

		$pdf->setFont('Arial', 'B', 9);

		$pdf->Text($x, 70, "Alamat Pengirim");

		$pdf->Text(111, 70, "Alamat Penerima");

		$pdf->setFont('Arial', '', 7);





		$pdf->setXY($x, 72);

		$pdf->MultiCell(90, 5, stripslashes(ucwords($data['order']['nama_pengirim'])), 0, 'L');

		$pdf->setXY($x + 90, 72);

		$pdf->MultiCell(80, 5, stripslashes(ucwords($data['order']['nama_penerima'])), 0, 'L');

		//$pdf->Ln();

		$pdf->setXY($x, 77);

		$pdf->MultiCell(90, 5, stripslashes(ucwords($data['order']['alamat_pengirim'])) . '. ' . stripslashes(ucwords($data['order']['propinsinm_pengirim'])) . ', ' . stripslashes($data['order']['kotanm_pengirim']), 0, 'L');

		$pdf->setXY($x + 90, 77);

		$pdf->MultiCell(90, 5, stripslashes(ucwords($data['order']['alamat_penerima'])) . '. ' . stripslashes(ucwords($data['order']['propinsinm_penerima'])) . ', ' . stripslashes($data['order']['kotanm_penerima']), 0, 'L');



		$ketKecKelTagihan = 'Kec. ' . stripslashes(ucwords($data['order']['kecamatannm_pengirim']));

		$ketKecKelTerima = 'Kec. ' . stripslashes(ucwords($data['order']['kecamatannm_penerima']));

		if (trim($data['order']['kelurahan_pengirim']) != '' && $data['order']['kelurahan_pengirim'] != null) {

			$ketKecKelTagihan .= ' , Kel. ' . stripslashes(ucwords($data['order']['kelurahan_pengirim']));

		}

		$pdf->Cell(90, 5, $ketKecKelTagihan, 0, 'L');

		if (trim($data['order']['kelurahan_penerima']) != '' && $data['order']['kelurahan_penerima'] != null) {



			$ketKecKelTerima .= ' , Kel. ' . stripslashes(ucwords($data['order']['kelurahan_penerima']));

		}

		$pdf->Cell(80, 5, $ketKecKelTerima, 0, 'L');

		$pdf->Ln();

		$pdf->Cell(90, 5, stripslashes(ucwords($data['order']['negaranm_pengirim'])) . ' ' . $data['order']['kodepos_pengirim'], 0, 'L');

		$pdf->Cell(80, 5, stripslashes(ucwords($data['order']['negaranm_penerima'])) . ' ' . $data['order']['kodepos_penerima'], 0, 'L');

		$pdf->Ln();

		if ($data['order']['hp_pengirim'] != '') {

			$pdf->Cell(90, 5, 'Telp. ' . stripslashes(ucwords($data['order']['hp_pengirim'])), 0, 'L');

		}

		if ($data['order']['hp_penerima'] != '') {

			$pdf->Cell(80, 5, 'Telp. ' . stripslashes(ucwords($data['order']['hp_penerima'])), 0, 'L');

		}

		$pdf->Ln();



		/* detail produk */

		$x = $pdf->GetX();

		$pdf->setXY($x, 105);

		$pdf->setFont('Arial', '', 8);



		$tabelproduk  = "<table width=100% border=1>";

		$tabelproduk .= "<tr><td width=5%>No</td><td>Produk</td><td width=15% align=center>Jumlah</td><td width=20% align=center>Berat</td><td align=right>Harga</td><td align=right>Total</td>";

		$totberat = 0;

		$totpoin = 0;

		$no = 0;

		foreach ($data['detailproduk'] as $dt) {

			$no = $no + 1;

			$totberat = $totberat + $dt['berat'];

			$dt['poin'] = isset($dt['poin']) && $dt['poin'] != null && $dt['poin'] != '' ? $dt['poin'] : '0';

			$totpoin = $totpoin + (int) $dt['poin'];



			$datapoin = $this->Fungsi->fcaridata('_produk', 'poin', 'idproduk', $dt['product_id']);

			$datapoin = $datapoin == '' && $datapoin == null ? '0' : $datapoin;

			$tabelproduk .= "<tr><td>" . $no . "</td><td><font style=bold>" . $dt['nama_produk'] . "</font>";

			if ($dt['ukuran'] != '') {

				$tabelproduk .= "<font size=11> Ukuran : " . $dt['ukuran'] . "</font>";

			}

			if ($dt['warna'] != '') {

				$tabelproduk .= "<font size=11> Warna : " . $dt['warna'] . "</font>";

			}

			$tabelproduk .= "<td align=right>" . $dt['jml'] . "</td>";

			$tabelproduk .= "<td align=right>" . $dt['berat'] . " Gram </td>";

			$tabelproduk .= "<td align=right>" . $this->Fungsi->fFormatuang($dt['harga']) . "</td>";

			$tabelproduk .= "<td align=right>" . $this->Fungsi->fFormatuang(((int) $dt['jml']) * (int) $dt['harga']) . "</td>";

		}



		$tabelproduk .= "<tr><td colspan=6 align=center> Total Berat " . $totberat . ' Gram / ' . ($totberat / 1000) . ' Kg' . "</td></tr>";



		$tabelproduk .= "<tr><td colspan=5 align=right><font style=\"bold\">Sub Total</font></td><td align=right><font style=\"bold\">" . $this->Fungsi->fFormatuang($data['order']['pesanan_subtotal']) . "</font></td></tr>";

		$tabelproduk .= "<tr><td colspan=5 align=right><font style=\"bold\">" . $data['namaservis'] . "</font></td><td align=right><font style=\"bold\">" . $this->Fungsi->fFormatuang($data['order']['pesanan_kurir']) . "</font></td>";

		$tabelproduk .= "<tr><td colspan=5 align=right><font style=\"bold\">Potongan Poin</font></td><td align=right><font style=\"bold\">(" . $this->Fungsi->fFormatuang($data['order']['dari_poin']) . ")</font></td>";

		if ($data['order']['dari_deposito'] > 0) {

			$tabelproduk .= "<tr><td colspan=5 align=right><font style=\"bold\">Potongan Deposito</font></td><td align=right><font style=\"bold\">(" . $this->Fungsi->fFormatuang($data['order']['dari_deposito']) . ")</font></td>";

		}

		$grandtotal = ((int) $data['order']['pesanan_subtotal'] + (int) $data['order']['pesanan_kurir']) - (int) $data['order']['dari_poin'] - (int) $data['order']['dari_deposito'];

		$tabelproduk .= "<tr><td colspan=5 align=right><font style=\"bold\">TOTAL YANG HARUS DIBAYAR</font></td><td align=right><font style=\"bold\">" . $this->Fungsi->fFormatuang($grandtotal) . "</font></td>";

		$tabelproduk .= "</table>";

		$tabelproduk .= "<table width=100% border=1>";

		$tabelproduk .= "<tr><td align=center bgcolor=#cccccc><font style=bold size=7>" . ucwords($this->Fungsi->kekata($grandtotal)) . " Rupiah</font></td></tr>";

		$tabelproduk .= "<tr><td border=0 align=right>Hormat Kami</td></tr>";

		$tabelproduk .= "<tr><td border=0 align=right></td></tr>";

		$tabelproduk .= "<tr><td border=0 align=right></td></tr>";

		$tabelproduk .= "<tr><td border=0 align=right></td></tr>";

		$tabelproduk .= "<tr><td border=0 align=right>(" . $data['nama_toko'] . ")</td></tr>";

		$tabelproduk .= "</table>";

		$pdf->htmltable($tabelproduk);

		/* end detail produk */



		if ($jenis == 'report') {

			$pdf->output($nama_report, "I");

		} else {

			$pdf->output(DIR_INCLUDE . $nama_report, "F");

			return DIR_INCLUDE . $nama_report;

		}

		//echo file_exists ('../../pdf/pdftable/lib/pdftable.inc.php');



	}



	public function cetakInvoicePDF($data, $jenis)

	{

		require_once '../../fpdf/mypdf.php';

		$tabel = "_setting";

		$fieldambil = "setting_key,setting_value";

		$where = "setting_key='config_namatoko' OR setting_key='config_alamattoko' OR setting_key='config_alamatsite'";

		$toko = $this->Fungsi->fcaridata3($tabel, $fieldambil, $where);



		foreach ($toko as $tk) {

			if ($tk['setting_key'] == 'config_namatoko') {

				$data['nama_toko'] = $tk['setting_value'];

			}

			if ($tk['setting_key'] == 'config_alamattoko') {

				$data['alamat_toko'] = $tk['setting_value'];

			}

			if ($tk['setting_key'] == 'config_alamatsite') {

				$data['alamat_site'] = $tk['setting_value'];

			}

		}

		$totberat = 0;

		$totpoin = 0;

		$x = 0;

		$nama_report = 'invoice' . $data['nopesanan'] . '.pdf';

		ob_end_clean();

		$pdf = new MyPDF("P", "mm", "A4");

		$pdf->SetMargins(5, 5, 5, 5);

		$pdf->AliasNbPages();

		$pdf->AddPage();



		/* Kop Nota */

		$pdf->SetFont('Arial', 'B', 14);

		$pdf->Cell(200, 7, 'NOTA PEMBAYARAN ', 0, 1, 'C');

		$pdf->SetFont('Arial', 'B', 14);

		$pdf->Cell(200, 7, 'ID ORDER : ' .  $data['nopesanan'], 0, 1, 'C');

		$pdf->SetFont('Arial', 'B', 8);



		$pdf->Ln(5);



		$pdf->Line(5, 20, 5 + 200, 20);

		if ($data['order']['dropship'] == '1') {

			//if($data['order']['nama_penerima'] != $data['order']['nama_pengirim'] && $data['order']['alamat_penerima'] != $data['order']['alamat_pengirim']){

			//$width = 200;

			$width = 200;

			//$wilayah_penerima 	 = stripslashes(ucwords($data['order']['nama_penerima']))."\n";

			$wilayah_penerima 	= $data['order']['alamat_penerima'] . "\n";



			if ($data['order']['kelurahan_penerima'] != '') {

				$wilayah_penerima .= $data['order']['kelurahan_penerima'] . ', ';

			}

			$wilayah_penerima 	.= $data['order']['kecamatannm_penerima'] . ", ";

			$wilayah_penerima 	.= $data['order']['kotanm_penerima'] . ", ";

			$wilayah_penerima	.= $data['order']['propinsinm_penerima'];





			if ($data['order']['kodepos_penerima'] != '') {

				$wilayah_penerima .= " " . $data['order']['kodepos_penerima'];

			}



			$wilayah_penerima .= "\n" . stripslashes(ucwords('Hp. ' . $data['order']['hp_penerima']));



			$pdf->setFont('Arial', 'B', 8);

			$pdf->Cell($width, 5, 'Kepada Yth', 0, 1, 'L');

			$pdf->Cell($width, 5, stripslashes(ucwords($data['order']['nama_penerima'])), 0, 1, 'L');

			$pdf->setFont('Arial', '', 8);



			$x = $pdf->GetX();

			$y = $pdf->GetY();



			$pdf->MultiCell($width, 4, $wilayah_penerima, 0, 'L');

			$pdf->Ln(3);

			$pdf->setFont('Arial', 'B', 8);

			$pdf->SetFillColor(245, 245, 245);



			$pdf->Cell(128, 7, 'Produk', 1, 0, 'C', 1);

			$pdf->Cell(30, 7, 'Berat (Gr)', 1, 0, 'C', 1);

			$pdf->Cell(27, 7, 'Jumlah', 1, 0, 'C', 1);

			$pdf->Cell(15, 7, 'Cek List', 1, 1, 'C', 1);



			$pdf->setFont('Arial', '', 7);

			$pdf->SetWidths(array(128, 30, 27, 15));

			$pdf->SetAligns(array('L', 'C', 'C', 'C'));

			$pdf->SetBolds(array('', '', 'B', '', '', '', '', ''));

			$pdf->SetUkuranFonts(array('7', '7', '7', '7', '7', '7', '7', '7'));

			$totjml = 0;

			foreach ($data['detailproduk'] as $dt) {

				$totberat = $totberat + $dt['berat'];

				$dt['poin'] = isset($dt['poin']) && $dt['poin'] != null && $dt['poin'] != '' ? $dt['poin'] : '0';

				$totpoin = $totpoin + (int) $dt['poin'];

				$totjml = $totjml + $dt['jml'];

				$datapoin = $this->Fungsi->fcaridata('_produk', 'poin', 'idproduk', $dt['produk_id']);

				$datapoin = $datapoin == '' && $datapoin == null ? '0' : $datapoin;

				$nama_produk = $dt['nama_produk'];

				if ($dt['ukuran'] != '' || $dt['warna'] != '') {

					$nama_produk .= ' - ';

					if ($dt['warna'] != '') $nama_produk .= ucwords($dt['warna']);

					if ($dt['ukuran'] != '' && $dt['warna'] != '') $nama_produk .= ' - ';

					if ($dt['ukuran'] != '') $nama_produk .= ucwords($dt['ukuran']);

				}



				$pdf->Row(array($nama_produk, $dt['berat'], $dt['jml'], ''));

			}



			$pdf->SetFillColor(245, 245, 245);

			$pdf->setFont('Arial', 'B', 7);

			$pdf->Cell(158, 6, '', 1, 0, 'C', 1);

			$pdf->Cell(27, 6, $totjml . ' pcs', 1, 0, 'C', 1);

			$pdf->Cell(15, 6, '', 1, 1, 'C', 1);

			$pdf->Cell(200, 6, 'Total Berat ' . $totberat . ' Gr (' . $totberat / 1000 . ' Kg)', 1, 1, 'C', 1);

			$pdf->setFont('Arial', '', 7);

			$pdf->Cell(200, 6, 'Hormat Kami, ' . date('d M Y'), 0, 1, 'R');

			$pdf->Ln(7);

			$pdf->Cell(200, 6, '(' . strtoupper($data['order']['nama_pengirim']) . ')', 0, 1, 'R');

			//}

		} else {

			$width = 200;

			$height = 10;



			$wilayah_penerima 	= $data['order']['alamat_penerima'] . "\n";



			if ($data['order']['kelurahan_penerima'] != '') {

				$wilayah_penerima .= $data['order']['kelurahan_penerima'] . ", ";

			}

			$wilayah_penerima 	.= $data['order']['kecamatannm_penerima'] . ", ";

			$wilayah_penerima 	.= $data['order']['kotanm_penerima'] . ", ";



			$wilayah_penerima	.= $data['order']['propinsinm_penerima'];





			if ($data['order']['kodepos_penerima'] != '') {

				$wilayah_penerima .= " " . $data['order']['kodepos_penerima'];

			}



			$wilayah_penerima .= "\n" . stripslashes(ucwords('Hp. ' . $data['order']['hp_penerima']));



			$pdf->setFont('Arial', 'B', 8);

			$pdf->Cell($width, 5, 'Kepada Yth', 0, 1, 'L');

			$pdf->Cell($width, 5, stripslashes(ucwords($data['order']['nama_penerima'])), 0, 1, 'L');

			$pdf->setFont('Arial', '', 8);



			$x = $pdf->GetX();

			$y = $pdf->GetY();



			$pdf->MultiCell($width, 4, $wilayah_penerima, 0, 'L');

			$pdf->Ln(3);

			$pdf->setFont('Arial', 'B', 8);

			$pdf->SetFillColor(245, 245, 245);

			$pdf->Cell(50, 7, 'Produk', 1, 0, 'C', 1);

			$pdf->Cell(15, 7, 'Jumlah', 1, 0, 'C', 1);

			$pdf->Cell(15, 7, 'Cek List', 1, 0, 'C', 1);

			$pdf->Cell(20, 7, 'Berat (Gr)', 1, 0, 'C', 1);

			$pdf->Cell(30, 7, 'Harga Normal', 1, 0, 'R', 1);

			$pdf->Cell(15, 7, 'Diskon', 1, 0, 'C', 1);

			$pdf->Cell(25, 7, 'Harga', 1, 0, 'R', 1);

			$pdf->Cell(30, 7, 'Total', 1, 1, 'R', 1);



			$pdf->setFont('Arial', '', 7);



			$pdf->SetWidths(array(50, 15, 15, 20, 30, 15, 25, 30));

			$pdf->SetAligns(array('L', 'C', 'C', 'C', 'R', 'C', 'R', 'R'));

			$pdf->SetBolds(array('', 'B', '', '', '', '', '', ''));

			$pdf->SetUkuranFonts(array('7', '7', '7', '7', '7', '7', '7', '7'));

			$totjml = 0;

			foreach ($data['detailproduk'] as $dt) {

				$totberat = $totberat + $dt['berat'];

				$dt['poin'] = isset($dt['poin']) && $dt['poin'] != null && $dt['poin'] != '' ? $dt['poin'] : '0';

				$totpoin = $totpoin + (int) $dt['poin'];

				//$diskon = $dt['satuan'] - $dt['harga'];

				$totjml = $totjml + $dt['jml'];

				$harga_tambahan = $dt['harga_tambahan'];

				$diskon = ($dt['satuan'] + $harga_tambahan) - $dt['harga'];

				//$persendiskon = ($diskon/$dt['satuan']) * 100;

				$persendiskon = ($diskon / ($dt['satuan'] + $harga_tambahan)) * 100;

				$datapoin = $this->Fungsi->fcaridata('_produk', 'poin', 'idproduk', $dt['produk_id']);

				$datapoin = $datapoin == '' && $datapoin == null ? '0' : $datapoin;

				$nama_produk = $dt['nama_produk'];

				if ($dt['ukuran'] != '' || $dt['warna'] != '') {

					/*

					$nama_produk .= '(';

					if ($dt['ukuran'] != '') $nama_produk .= ucwords($dt['ukuran']);

					if ($dt['ukuran'] != '' && $dt['warna'] != '') $nama_produk .= ', ';

					if ($dt['warna'] != '') $nama_produk .= ucwords($dt['warna']);

					$nama_produk .= ')';

					*/

					$nama_produk .= ' - ';

					if ($dt['warna'] != '') $nama_produk .= ucwords($dt['warna']);

					if ($dt['ukuran'] != '' && $dt['warna'] != '') $nama_produk .= ' - ';

					if ($dt['ukuran'] != '') $nama_produk .= ucwords($dt['ukuran']);

				}

				$harga_normal = $this->Fungsi->fFormatuang($dt['satuan'] + $harga_tambahan);

				//if($harga_tambahan) {

				//	$harga_normal .= '\n<small> + '.$this->Fungsi->fFormatuang($harga_tambahan). ' (tambahan harga) </small>' ;

				//}

				/*

				$pdf->Cell(50,6,$nama_produk,1,0);

				

				$pdf->Cell(20,6,$dt['jml'],1,0,'C');

				$pdf->Cell(20,6,$dt['berat'],1,0,'C');

				$pdf->Cell(30,6,$harga_normal,1,0,'R');

				$pdf->Cell(20,6,$persendiskon.' %',1,0,'C');

				$pdf->Cell(30,6,$this->Fungsi->fFormatuang($dt['harga']),1,0,'R');

			

				$pdf->Cell(30,6,$this->Fungsi->fFormatuang($dt['harga'] * $dt['jml']),1,1,'R');

				*/

				$pdf->Row(array($nama_produk, $dt['jml'], '', $dt['berat'], $harga_normal, $persendiskon . ' %', $this->Fungsi->fFormatuang($dt['harga']), $this->Fungsi->fFormatuang($dt['harga'] * $dt['jml'])));

			}

			$pdf->SetFillColor(245, 245, 245);

			$pdf->setFont('Arial', 'B', 7);



			$pdf->Cell(50, 6, '', 1, 0, 'C', 1);

			$pdf->Cell(15, 6, $totjml . ' pcs', 1, 0, 'C', 1);

			$pdf->Cell(15, 6, '', 1, 0, 'C', 1);

			$pdf->Cell(20, 6, '', 1, 0, 'C', 1);

			$pdf->Cell(30, 6, '', 1, 0, 'R', 1);

			$pdf->Cell(15, 6, '', 1, 0, 'C', 1);

			$pdf->Cell(25, 6, '', 1, 0, 'R', 1);

			$pdf->Cell(30, 6, '', 1, 1, 'R', 1);

			$pdf->Cell(200, 6, 'Total Berat ' . $totberat . ' Gr (' . $totberat / 1000 . ' Kg)', 1, 1, 'C', 1);



			$pdf->Cell(159, 6, 'Sub Total', 1, 0, 'R');

			$pdf->Cell(41, 6, $this->Fungsi->fFormatuang($data['order']['pesanan_subtotal']), 1, 1, 'R');

			$pdf->Cell(159, 6, $data['namaservis'], 1, 0, 'R');

			$pdf->Cell(41, 6, $this->Fungsi->fFormatuang($data['order']['pesanan_kurir']), 1, 1, 'R');

			if ($data['order']['dari_poin'] > 0) {

				$pdf->Cell(159, 6, 'Potongan Poin', 1, 0, 'R');

				$pdf->Cell(41, 6, $this->Fungsi->fFormatuang($data['order']['dari_poin']), 1, 1, 'R');

			}

			if ($data['order']['dari_deposito'] > 0) {

				$pdf->Cell(159, 6, 'Potongan Saldo', 1, 0, 'R');

				$pdf->Cell(41, 6, $this->Fungsi->fFormatuang($data['order']['dari_deposito']), 1, 1, 'R');

			}

			$grandtotal = ((int) $data['order']['pesanan_subtotal'] + (int) $data['order']['pesanan_kurir']) - (int) $data['order']['dari_poin'] - (int) $data['order']['dari_deposito'];

			$pdf->setFont('Arial', 'B', 8);

			$pdf->Cell(159, 6, 'Total Yang Harus Dibayar (#' . $data['nopesanan'] . ')', 1, 0, 'R');

			$pdf->Cell(41, 6, $this->Fungsi->fFormatuang($grandtotal), 1, 1, 'R');

			$pdf->setFont('Arial', '', 7);

			$pdf->Cell(200, 6, 'Hormat Kami, ' . date('d M Y'), 0, 1, 'R');

			$pdf->Ln(7);

			$pdf->Cell(200, 6, '(' . strtoupper($data['nama_toko']) . ')', 0, 1, 'R');

		}







		if ($jenis == 'report') {

			$pdf->output($nama_report, "I");

		} else {

			$pdf->output(DIR_INCLUDE . $nama_report, "F");

			return DIR_INCLUDE . $nama_report;

		}

	}



	public function cetakInvoicePDF2($data, $jenis)

	{



		$tabel = "_setting";

		$fieldambil = "setting_key,setting_value";

		$where = "setting_key='config_namatoko' OR setting_key='config_alamattoko' OR setting_key='config_alamatsite'";

		$toko = $this->Fungsi->fcaridata3($tabel, $fieldambil, $where);



		foreach ($toko as $tk) {

			if ($tk['setting_key'] == 'config_namatoko') {

				$data['nama_toko'] = $tk['setting_value'];

			}

			if ($tk['setting_key'] == 'config_alamattoko') {

				$data['alamat_toko'] = $tk['setting_value'];

			}

			if ($tk['setting_key'] == 'config_alamatsite') {

				$data['alamat_site'] = $tk['setting_value'];

			}

		}



		$nama_report = 'invoice' . $data['nopesanan'] . '.pdf';

		$pdf = new FPDF("P", "mm", "A4");

		$pdf->SetMargins(5, 5, 5, 5);

		$pdf->AliasNbPages();

		$pdf->AddPage();



		/* Kop Nota */

		$pdf->SetFont('Arial', 'B', 10);

		$pdf->Cell(200, 7, strtoupper($data['nama_toko']), 0, 1, 'C');

		$pdf->SetFont('Arial', 'B', 7);

		$pdf->Cell(200, 7, strtoupper($data['alamat_toko']), 0, 1, 'C');

		$pdf->SetFont('Arial', 'B', 8);



		$pdf->Ln(5);

		$pdf->Line(5, 20, 5 + 200, 20);

		$pdf->Cell(20, 5, 'No. Order', 0, 0, 'L');

		$pdf->Cell(80, 5, ': ' . sprintf('%08s', $data['nopesanan']), 0, 0, 'L');

		$pdf->Cell(30, 5, 'Pelanggan', 0, 0, 'L');

		$pdf->Cell(70, 5, ': ' . ucwords($data['order']['cust_nama']), 0, 1, 'L');

		$pdf->Cell(20, 5, 'Tgl. Order', 0, 0, 'L');

		$pdf->Cell(80, 5, ': ' . $this->Fungsi->ftanggalFull1($data['tglorder']), 0, 0, 'L');

		$pdf->Cell(30, 5, 'Grup Pelanggan', 0, 0, 'L');

		$pdf->Cell(70, 5, ': ' . $data['order']['grup_cust'], 0, 1, 'L');

		$pdf->Ln(4);

		$pdf->Line(5, 37, 5 + 200, 37);

		if ($data['order']['dropship'] == '1') {

			if ($data['order']['nama_penerima'] != $data['order']['nama_pengirim'] && $data['order']['alamat_penerima'] != $data['order']['alamat_pengirim']) {

				$width = 100;

				$pdf->setFont('Arial', 'B', 8);

				$pdf->Cell($width, 5, 'Alamat Pengirim', 0, 0, 'L');

				$pdf->Cell($width, 5, 'Alamat Tujuan', 0, 1, 'R');



				$pdf->Cell($width, 5, stripslashes(ucwords($data['order']['nama_pengirim'])), 0, 0, 'L');

				$pdf->Cell($width, 5, stripslashes(ucwords($data['order']['nama_penerima'])), 0, 1, 'R');



				$wilayah_pengirim = $data['order']['alamat_pengirim'] . ', ' . $data['order']['kecamatannm_pengirim'];

				if ($data['order']['kelurahan_pengirim'] != '') {

					$wilayah_pengirim .= ', ' . $data['order']['kelurahan_pengirim'];

				}

				$wilayah_pengirim2 = $data['order']['kotanm_pengirim'] . ', ' . $data['order']['propinsinm_pengirim'];

				if ($data['order']['kodepos_pengirim'] != '') {

					$wilayah_pengirim2 .= ' ' . $data['order']['kodepos_pengirim'];

				}



				$pdf->setFont('Arial', '', 8);

				$pdf->Cell($width, 5, stripslashes(ucwords($wilayah_pengirim)), 0, 0, 'L');



				$wilayah_penerima = $data['order']['alamat_penerima'] . ', ' . $data['order']['kecamatannm_penerima'];

				if ($data['order']['kelurahan_penerima'] != '') {

					$wilayah_penerima .= ', ' . $data['order']['kelurahan_penerima'];

				}

				$wilayah_penerima2 = $data['order']['kotanm_penerima'] . ', ' . $data['order']['propinsinm_penerima'];

				if ($data['order']['kodepos_penerima'] != '') {

					$wilayah_penerima2 .= ' ' . $data['order']['kodepos_penerima'];

				}

				$pdf->Cell($width, 5, stripslashes(ucwords($wilayah_penerima)), 0, 1, 'R');



				$pdf->Cell($width, 5, stripslashes(ucwords($wilayah_pengirim2)), 0, 0, 'L');

				$pdf->Cell($width, 5, stripslashes(ucwords($wilayah_penerima2)), 0, 1, 'R');



				$pdf->Cell($width, 5, stripslashes(ucwords('Hp. ' . $data['order']['hp_pengirim'])), 0, 0, 'L');

				$pdf->Cell($width, 5, stripslashes(ucwords('Hp. ' . $data['order']['hp_penerima'])), 0, 1, 'R');

			}

		} else {

			$width = 200;



			$wilayah_penerima = $data['order']['alamat_penerima'] . ', ' . $data['order']['kecamatannm_penerima'];

			if ($data['order']['kelurahan_penerima'] != '') {

				$wilayah_penerima .= ', ' . $data['order']['kelurahan_penerima'];

			}

			$wilayah_penerima2 = $data['order']['kotanm_penerima'] . ', ' . $data['order']['propinsinm_penerima'];

			if ($data['order']['kodepos_penerima'] != '') {

				$wilayah_penerima2 .= ' ' . $data['order']['kodepos_penerima'];

			}

			$pdf->setFont('Arial', 'B', 8);

			$pdf->Cell($width, 5, 'Alamat Tujuan', 0, 1, 'R');

			$pdf->setFont('Arial', '', 8);



			$pdf->Cell($width, 5, stripslashes(ucwords($wilayah_penerima)), 0, 1, 'R');

			$pdf->Cell($width, 5, stripslashes(ucwords($wilayah_penerima2)), 0, 1, 'R');

			$pdf->Cell($width, 5, stripslashes(ucwords('Hp. ' . $data['order']['hp_penerima'])), 0, 1, 'R');

		}

		$pdf->Ln(3);

		$pdf->setFont('Arial', 'B', 8);

		$pdf->SetFillColor(245, 245, 245);

		$pdf->Cell(61, 7, 'Produk', 1, 0, 'C', 1);

		$pdf->Cell(30, 7, 'Jumlah', 1, 0, 'C', 1);

		$pdf->Cell(27, 7, 'Berat (Gr)', 1, 0, 'C', 1);

		$pdf->Cell(41, 7, 'Harga', 1, 0, 'R', 1);

		$pdf->Cell(41, 7, 'Total', 1, 1, 'R', 1);



		$totberat = 0;

		$totpoin = 0;

		$pdf->setFont('Arial', '', 7);

		$x = 0;

		foreach ($data['detailproduk'] as $dt) {

			$totberat = $totberat + $dt['berat'];

			$dt['poin'] = isset($dt['poin']) && $dt['poin'] != null && $dt['poin'] != '' ? $dt['poin'] : '0';

			$totpoin = $totpoin + (int) $dt['poin'];



			$datapoin = $this->Fungsi->fcaridata('_produk', 'poin', 'idproduk', $dt['product_id']);

			$datapoin = $datapoin == '' && $datapoin == null ? '0' : $datapoin;

			$nama_produk = $dt['nama_produk'];

			if ($dt['ukuran'] != '' || $dt['warna'] != '') {

				$nama_produk .= '(';

				if ($dt['ukuran'] != '') $nama_produk .= ' Ukuran : ' . $dt['ukuran'];

				if ($dt['ukuran'] != '' && $dt['warna'] != '') $nama_produk .= ', ';

				if ($dt['warna'] != '') $nama_produk .= ' Warna : ' . $dt['warna'];

				$nama_produk .= ')';

			}

			$pdf->Cell(61, 6, $nama_produk, 1, 0);



			$pdf->Cell(30, 6, $dt['jml'], 1, 0, 'C');

			$pdf->Cell(27, 6, $dt['berat'], 1, 0, 'C');

			$pdf->Cell(41, 6, $this->Fungsi->fFormatuang($dt['harga']), 1, 0, 'R');



			$pdf->Cell(41, 6, $this->Fungsi->fFormatuang($dt['harga'] * $dt['jml']), 1, 1, 'R');

		}

		$pdf->SetFillColor(245, 245, 245);

		$pdf->setFont('Arial', 'B', 7);

		$pdf->Cell(200, 6, 'Total Berat ' . $totberat . ' Gr (' . $totberat / 1000 . ' Kg)', 1, 1, 'C', 1);



		$pdf->Cell(159, 6, 'Sub Total', 1, 0, 'R');

		$pdf->Cell(41, 6, $this->Fungsi->fFormatuang($data['order']['pesanan_subtotal']), 1, 1, 'R');

		$pdf->Cell(159, 6, $data['namaservis'], 1, 0, 'R');

		$pdf->Cell(41, 6, $this->Fungsi->fFormatuang($data['order']['pesanan_kurir']), 1, 1, 'R');



		if ($data['order']['dari_poin'] > 0) {

			$pdf->Cell(159, 6, 'Potongan Poin', 1, 0, 'R');

			$pdf->Cell(41, 6, $this->Fungsi->fFormatuang($data['order']['dari_poin']), 1, 1, 'R');

		}

		if ($data['order']['dari_deposito'] > 0) {

			$pdf->Cell(159, 6, 'Potongan Deposito', 1, 0, 'R');

			$pdf->Cell(41, 6, $this->Fungsi->fFormatuang($data['order']['dari_deposito']), 1, 1, 'R');

		}

		$grandtotal = ((int) $data['order']['pesanan_subtotal'] + (int) $data['order']['pesanan_kurir']) - (int) $data['order']['dari_poin'] - (int) $data['order']['dari_deposito'];

		$pdf->setFont('Arial', 'B', 8);

		$pdf->Cell(159, 6, 'Total Yang Harus Dibayar (#' . sprintf('%08s', $data['nopesanan']) . ')', 1, 0, 'R');

		$pdf->Cell(41, 6, $this->Fungsi->fFormatuang($grandtotal), 1, 1, 'R');

		$pdf->setFont('Arial', '', 7);

		$pdf->Cell(200, 6, 'Hormat Kami, ' . date('d M Y'), 0, 1, 'R');

		$pdf->Ln(7);

		$pdf->Cell(200, 6, '(' . strtoupper($data['nama_toko']) . ')', 0, 1, 'R');

		if ($jenis == 'report') {

			$pdf->output($nama_report, "I");

		} else {

			$pdf->output(DIR_INCLUDE . $nama_report, "F");

			return DIR_INCLUDE . $nama_report;

		}

	}



	public function cetakInvoice()

	{

		$data['nopesanan'] = isset($_GET['pid']) ? $_GET['pid'] : '';

		$order = $this->model->getOrderByID($data['nopesanan']);

		if ($order) {

			$data['tglorder'] = $order['pesanan_tgl'];

			$data['nmstatus'] = $order['status_nama'];

			$datadetail 	= $this->model->getOrderDetail($data['nopesanan']);



			$data['namaservis'] = $order['kurir'] . ' - ' . $order['servis_code'];

			$data['detailproduk'] = $datadetail;

			$data['order'] = $order;

			$field 			= 'cust_nama,cg_nm';

			$tabel 			= '_customer INNER JOIN _customer_grup ON _customer.cust_grup_id = _customer_grup.cg_id';

			$where 			= "cust_id = '" . $order['pelanggan_id'] . "'";

			$reseller 		= $this->Fungsi->fcaridata2($tabel, $field, $where);



			$data['nmreseller'] =  $reseller['cust_nama'];

			$data['nmgrpreseller'] = $reseller['cg_nm'];

			$this->cetakInvoicePDF($data, 'report');

		}

	}



	public function cetakLabel()

	{

		require_once '../../fpdf/pdftable.inc.php';

		$data['nopesanan'] = isset($_GET['pid']) ? $_GET['pid'] : '';

		$order = $this->model->getOrderByID($data['nopesanan']);

		$tabel = "_setting";

		$fieldambil = "setting_key,setting_value";

		$where = "setting_key='config_namatoko' OR setting_key='config_alamattoko' OR setting_key='config_alamatsite' OR setting_key='config_telp'";

		$toko = $this->Fungsi->fcaridata3($tabel, $fieldambil, $where);



		foreach ($toko as $tk) {

			if ($tk['setting_key'] == 'config_namatoko') {

				$data['nama_toko'] = ucwords($tk['setting_value']);

			}

			if ($tk['setting_key'] == 'config_alamattoko') {

				$data['alamat_toko'] = $tk['setting_value'];

			}

			if ($tk['setting_key'] == 'config_alamatsite') {

				$data['alamat_site'] = $tk['setting_value'];

			}

			if ($tk['setting_key'] == 'config_telp') {

				$data['hp_toko'] = $tk['setting_value'];

			}

		}


		ob_end_clean();

		$pdf = new PDFTable();

		$pdf->SetMargins(5, 5, 5, 5);

		$pdf->AliasNbPages();

		//$pdf->AddPage();

		$pdf->AddPage("P", "A5");

		$pdf->defaultFontFamily = 'Arial';

		$pdf->defaultFontStyle  = '';

		$pdf->defaultFontSize   = 12;

		$pdf->SetFont($pdf->defaultFontFamily, $pdf->defaultFontStyle, $pdf->defaultFontSize);



		if ($order['dropship'] == '1') {

			$width = 69;



			$wilayah_pengirim 	= "Alamat Pengirim : <br>";

			//$pdf->setFont('Arial','B',10);

			$wilayah_pengirim .= "<font style=bold>" . $order['nama_pengirim'] . "</font><br>";

			/*

			$wilayah_pengirim .= $order['alamat_pengirim'].", \n";

			if($order['kelurahan_pengirim'] != '') {

				$wilayah_pengirim .= ' '.$order['kelurahan_pengirim'].', ';

			}

			$wilayah_pengirim .= $order['kecamatannm_pengirim']."\n".$order['kotanm_pengirim'].', '.$order['propinsinm_pengirim'];

			if($order['kodepos_pengirim'] != '') {

				$wilayah_pengirim .= ' '.$order['kodepos_pengirim'];

			}

			*/

			$wilayah_pengirim .= "Hp. " . $order['hp_pengirim'];

		} else {

			$width = 69;

			//$pdf->setFont('Arial','',10);

			$wilayah_pengirim 	= "Alamat Pengirim : <br>";

			//$pdf->setFont('Arial','B',10);

			$wilayah_pengirim .= "<font style=bold>" . $data['nama_toko'] . "</font><br>";

			//$wilayah_pengirim .= $data['alamat_toko'].", ";

			$wilayah_pengirim .= "Hp. " . $data['hp_toko'];

		}

		//$wilayah_pengirim .= "\n\n\n\n\n";

		$wilayah_penerima = "Alamat Penerima : <br>";

		$wilayah_penerima .= "<font style=bold>" . $order['nama_penerima'] . "</font><br>";

		$wilayah_penerima .= $order['alamat_penerima'] . " <br>";

		if ($order['kelurahan_penerima'] != '') {

			$wilayah_penerima .= $order['kelurahan_penerima'] . ', ';

		}

		$wilayah_penerima .= $order['kecamatannm_penerima'] . "<br>" . $order['kotanm_penerima'] . ', ' . $order['propinsinm_penerima'] . "<br>";

		if ($order['kodepos_penerima'] != '') {

			$wilayah_penerima .= $order['kodepos_penerima'] . "<br>";

		}

		$wilayah_penerima .= "Hp. " . $order['hp_penerima'];

		if ($order['tampil_label_keterangan'] == '1') {

			$wilayah_penerima .= '<br><br>' . $order['keterangan'];

		}



		$x = $pdf->GetX();

		$y = $pdf->GetY();

		$pdf->setFont('Arial', '', 12);

		/*

		$tablekurir = '<table border=0>';

		$tablekurir .= '<tr><td align="right">'.$order['kurir'].' - '.$order['servis_code'].'<td></tr>';

		$tablekurir .= '</table>';

		$pdf->htmltable($tablekurir);

		*/

		$pdf->SetX(5);

		$y = $pdf->GetY();

		$pdf->MultiCell(69, 4, (int) $order['pesanan_no'], 0, 'L');

		$pdf->SetX(74);

		$pdf->SetY($y);

		$pdf->setFont('Arial', '', 7);

		$pdf->MultiCell(138, 4, $order['kurir'] . ' - ' . $order['servis_code'], 0, 'R');



		/*

		$pdf->MultiCell($width,5,$wilayah_pengirim,1,'L');

		$pdf->SetXY($x + 69, $y+6);

		$pdf->MultiCell($width,5,$wilayah_penerima,1,'L');

		*/

		//$tabel = '<table width="100%" border="1" cellpadding="10">';

		//$tabel .= '<tr><td width="50%"><font size=7>'.sprintf('%08s', (int)$order['pesanan_no']).

		//				   '</font></td><td width="50%" align="right"><font size=7>'.$order['kurir'].' - '.$order['servis_code'].'</font></td></tr></table>';

		$tabel  = '<table width="100%" border="1" cellpadding="10">';

		$tabel .= "<tr><td width=\"40%\">" . $wilayah_pengirim . "</td><td width=\"60%\">" . $wilayah_penerima . "</td>";

		$tabel .= "</tr></table>";

		/*

		$tabel = '<table width="100%" border="1">';

		$tabel .= '<tr><td>ssss</td><td><font style=bold>ssssgsagaga</font><br> a</td>';

		$tabel .= "</tr></table>";

		*/

		$pdf->htmltable($tabel);

		$pdf->output();

	}



	public function updateketeranganorder()

	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {



			$data = [];



			foreach ($_POST as $key => $value) {

				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';

			}

			$data['tampilketerangan'] = isset($data['tampilketerangan']) ? $data['tampilketerangan'] : '0';



			$simpan = $this->model->updateketeranganorder($data);

			if ($simpan == 'success') {

				$status = 'success';

				$result = 'Berhasil mengubah Keterangan Tambahan';

			} else {

				$status = 'error';

				$result = 'Proses mengubah Keterangan Tambahan Gagal';

			}

		} else {

			$status = 'error';

			$result = 'Order tidak valid';

		}

		echo json_encode(array("status" => $status, "result" => $result));

		exit;

	}

}

