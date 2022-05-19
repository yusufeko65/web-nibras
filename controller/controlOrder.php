<?php

class controller_Order
{
	private $page;
	private $rows;
	private $offset;
	private $dataModel;
	private $Fungsi;
	private $data = array();
	private $idlogin;
	private $error;

	public function __construct()
	{
		$this->dataModel = new model_Order();
		$this->Fungsi = new FungsiUmum();
		$this->idlogin = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : '';
	}

	public function cekOrder($noorder)
	{
		return $this->dataModel->cekOrder($noorder, $this->idlogin);
	}

	public function getLastOrder($idmember, $status_order, $limit)
	{
		return $this->dataModel->getLastOrder($idmember, $status_order, $limit);
	}

	public function tampildata()
	{
		$this->page 	    = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows			=  10;

		$result 			= array();
		$filter				= array();
		$where 				= '';
		$caridata			= isset($_GET['q']) ? $_GET['q'] : '';
		$sortir				= isset($_GET['sort']) ? $_GET['sort'] : '';

		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page - 1) * $this->rows;

		$result["total"]   = $this->dataModel->totalOrder($where);
		$result["rows"]    = $this->dataModel->getOrder($this->offset, $this->rows, $where);
		$result["page"]    = $this->page;
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"]) / intval($result["baris"]));

		return $result;
	}

	public function getOrder()
	{
		return $this->dataModel->getOrder();
	}

	public function getOrderOption($id, $tipe)
	{
		return $this->dataModel->getOrderOption($id, $tipe);
	}

	public function getOrderWarna($idproduk)
	{
		return $this->dataModel->getOrderWarna($idproduk);
	}

	public function getOrderImages($idproduk)
	{
		return $this->dataModel->getOrderImages($idproduk);
	}

	public function getOrderImagesbyWarna($idproduk, $warna)
	{
		return $this->dataModel->getOrderImagesbyWarna($idproduk, $warna);
	}

	public function getOrderDiskon($id, $grup)
	{
		return $this->dataModel->getOrderDiskon($id, $grup);
	}

	public function checkDataOrderByID($pid)
	{
		return $this->dataModel->checkDataOrderByID($pid);
	}

	public function checkDataKategori($pid, $j)
	{
		return $this->dataModel->checkDataKategori($pid, $j);
	}

	public function dataOrderByID($noorder)
	{
		return $this->dataModel->getOrderByID($noorder);
	}

	public function dataOrderDetail($noorder)
	{
		return $this->dataModel->getOrderDetail($noorder);
	}

	public function dataOrderStatus($noorder)
	{
		return $this->dataModel->getOrderStatus($noorder);
	}

	public function dataOrderAlamat($noorder)
	{
		return $this->dataModel->getOrderAlamat($noorder);
	}

	public function getResellerGrup($tipe)
	{
		return $this->dataModel->getResellerGrup($tipe);
	}
	public function getGambarOrder($id)
	{
		return $this->dataModel->getGambarOrder($id);
	}
	public function getKategoriOrder($id)
	{
		return $this->dataModel->getKategoriOrder($id);
	}
	public function getOptionOrder($id)
	{
		return $this->dataModel->getOptionOrder($id);
	}
	public function getOption($id, $warna, $ukuran)
	{
		return $this->dataModel->getOption($id, $warna, $ukuran);
	}
	public function getHarga($pid, $tipe)
	{
		return $this->dataModel->getHarga($pid, $tipe);
	}
	public function formEditKurir()
	{
		$data = array();
		$dataorder = array();
		$servis = array();
		$totberat = 0;
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			foreach ($_POST as $key => $value) {
				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';
			}

			$dataorder = $this->dataModel->getOrderByID($data['pesanan_no']);
			if ($dataorder) {
				$propinsi_penerima = $dataorder['propinsi_penerima'];
				$kabupaten_penerima = $dataorder['kota_penerima'];
				$kecamatan_penerima = $dataorder['kecamatan_penerima'];
				$modelShipping = new model_Shipping();
				//$servis = $modelShipping->getAllServicesAndTarifByWilayah($propinsi_penerima,$kabupaten_penerima,$kecamatan_penerima);
				$modelsetting = new model_SettingToko();
				$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin', 'config_apiurlongkir', 'config_apikeyongkir'));
				foreach ($setting as $st) {
					$key 	= $st['setting_key'];
					$value 	= $st['setting_value'];
					$$key = $value;
				}

				//$servis_rajaongkir = $this->getAllServicesAndTarifByWilayahRajaOngkir($config_lokasiorigin,$kecamatan,$totberat,$config_apiurlongkir,$config_apikeyongkir);
				$shipping = $modelShipping->getShippingRajaOngkir();
				$kurir = array();
				$cekKurir = array();

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
				$servis_rajaongkir = array();
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
								"shipping_rajaongkir" => 1
							);
						}
					}
				}
				$servis_ondb = $modelShipping->getAllServisKonfirmAdmin();

				//$services = $this->dataModel->getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan);
				$services = array();
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

	public function formEditProdukOrder()
	{
		$data = array();
		$dataorder = array();
		$servis = array();
		$totberat = 0;
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			foreach ($_POST as $key => $value) {
				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';
			}

			$dataorder = $this->dataModel->getOrderByID($data['pesanan_no']);

			if ($dataorder) {
				$zdata['pelanggan_id'] 	= $dataorder['pelanggan_id'];
				$zdata['grup_member']	= $dataorder['grup_member'];
				$datapost				= explode("::", $data['pid']);

				$zdata['produk_id']		= $datapost[0];
				$options 				= unserialize(base64_decode($datapost[1]));
				$zdata['idukuran']		= $options[0];
				$zdata['idwarna']		= $options[1];
				$zdata['ukuran']		= $options[2];
				$zdata['warna']			= $options[3];
				$zdata['qty']			= $datapost[2];
				$zdata['pesanan_no']	= $data['pesanan_no'];
				$modelproduk	= new model_Produk();
				$dataproduk		= $modelproduk->getProdukByID($zdata['produk_id']);
				$zdata['nama_produk'] = $dataproduk['nama_produk'];
				if ($zdata['idwarna'] != '0' && $zdata['idukuran'] != '0') {
					$zdata['stok']	= $modelproduk->getStokWarnaUkuran($zdata['produk_id'], $zdata['idukuran'], $zdata['idwarna']);
				} else {
					$zdata['stok'] 	= $dataproduk['jml_stok'];
				}

				$status = 'success';
				$msg = 'Data diterima';
				$zdata['totberat'] = $data['totberat'];
			} else {
				$status = 'error';
				$msg = 'Order tidak valid';
			}
		} else {
			$status = 'error';
			$msg = 'Data tidak ada';
		}

		return array("status" => $status, "msg" => $msg, "produk" => $zdata);
	}

	public function editprodukorder()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = array();
			$datacart = array();
			$cart = array();
			foreach ($_POST as $key => $value) {
				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';
			}
			$data['idmember'] = $_SESSION['idmember'];
			$modelproduk = new model_Produk();
			$controlCart	= new controller_Cart();
			$modelreseller = new model_Reseller();
			$orderdetail = $this->dataModel->getProdukOrderOptionSingle($data['product_id'], $data['idwarna'], $data['idukuran'], $data['nopesanan']);
			$customer = $modelreseller->getResellerCompleteById($data['idmember']);
			$dataproduk = $modelproduk->getProdukByID($data['product_id']);
			if ($data['idwarna'] == '0' && $data['idwarna'] == '' && $data['idukuran'] == '' && $data['idukuran'] == '0') {
				$stok = $dataproduk['jml_stok'];
			} else {
				$stok = $modelproduk->getStokWarnaUkuran($data['product_id'], $data['idukuran'], $data['idwarna']);
			}
			$data['iddetail'] = $orderdetail['iddetail'];

			if ($stok + $data['qty'] < 1) {
				$status = 'error';
				$pesan  = 'Stok produk tersebut sedang kosong';
			} else {

				$jml = abs($data['qty'] - $data['qtylama']);

				//if($stok + $data['qty'] < $jml) {
				//if($stok + $jml < $data['qty']) {
				if ($stok + $data['qtylama'] < $data['qty']) {
					$status = 'error';
					$pesan  = 'Stok produk tersebut hanya tersedia ' . $stok . ' pcs';
				} else {

					$cart_edit['tipe'] 		= $customer['cust_grup_id'];
					$cart_edit['stok']		= $dataproduk['jml_stok'];
					$cart_edit['produk']	= $dataproduk['nama_produk'];
					$cart_edit['product_id'] = $data['product_id'];
					$cart_edit['jumlah']	= $data['qty'];
					$cart_edit['qty_lama']	= $data['qtylama'];
					$cart_edit['option']	= array($data['idukuran'], $data['idwarna']);
					$cart_edit['idmember']	= $customer['cust_id'];
					$cart_edit['persen_diskon'] = $dataproduk['persen_diskon'];
					$cart_edit['image_product'] = $orderdetail['gbr'];
					$cart_edit['min_beli'] = $customer['cg_min_beli'];
					$cart_edit['min_beli_syarat'] = $customer['cg_min_beli_syarat'];
					$cart_edit['diskon_grup'] = $customer['cg_diskon'];

					$addCart = $controlCart->addCart($cart_edit);

					if ($addCart['status'] == 'success') {
						if ($data['qty'] > $data['qtylama']) {
							if ((int)$data['idwarna'] > 0 || (int)$data['idukuran'] > 0) {
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
							if ((int)$data['idwarna'] > 0 || (int)$data['idukuran'] > 0) {
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
						$produk_in_order = $this->dataModel->getProdukOrderOption('', '', '', $data['nopesanan']);

						foreach ($produk_in_order as $order) {

							if ((int)$order['iddetail'] != (int)$data['iddetail']) {

								$produk = $modelproduk->getProdukByID($order['idproduk']);
								$produk_to_cart['stok'] = $produk['jml_stok'];
								$produk_to_cart['produk'] = $produk['nama_produk'];
								$produk_to_cart['product_id']  = $order['idproduk'];
								$produk_to_cart['jumlah']	= $order['jml'];
								$produk_to_cart['idmember'] = $customer['cust_id'];
								$produk_to_cart['tipe'] 	= $customer['cust_grup_id'];
								$produk_to_cart['persen_diskon'] = $produk['persen_diskon'];
								$produk_to_cart['image_product']  = $order['gbr'];
								if ($order['ukuran'] == '' && $order['ukuran'] == null) {
									$order['ukuran'] = '0';
								}
								if ($order['warna'] == '' && $order['warna'] == null) {
									$order['warna'] = '0';
								}
								$produk_to_cart['option']   = array($order['ukuran'], $order['warna']);
								$produk_to_cart['min_beli'] = $customer['cg_min_beli'];
								$produk_to_cart['min_beli_syarat'] = $customer['cg_min_beli_syarat'];
								$produk_to_cart['diskon_grup'] = $customer['cg_diskon'];
								$addcartproduk = $controlCart->addCartFromProdukOrder($produk_to_cart);
							}
						}

						$dcart['min_beli'] = $customer['cg_min_beli'];
						$dcart['min_beli_syarat'] = $customer['cg_min_beli_syarat'];
						$dcart['diskon_grup'] = $customer['cg_diskon'];

						$carts = $controlCart->showminiCart($_SESSION['hscart'], $dcart);
						//print_r($carts);
						$totalitem	= $carts['items'];
						$datacart 	= $carts['carts'];

						$subtotal 	= 0;
						$i      	= 0;
						$totberat 	= 0;
						$totjumlah	= 0;
						$totgetpoin = 0;
						$zprod = array();
						$dtproduk = array();

						foreach ($datacart as $c) {

							if (!in_array($c['product_id'], $zprod)) {
								$zprod[] = $c['product_id'];
								$dtproduk[$c['product_id']] = $modelproduk->getProdukByID($c['product_id']);
							}
							if (($c['warna'] != '' && $c['warna'] != '0')  || ($c['ukuran'] != '' && $c['ukuran'] != '0')) {
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
							} else {
								$data['updateorderproduk'][] = array(
									"qty" => $c['qty'],

									"harga" => $c['harga'],
									"satuan" => $dtproduk[$c['product_id']]['hrg_jual'],
									"persen_diskon_satuan" => $dtproduk[$c['product_id']]['persen_diskon'],
									"berat" => $c['berat'],
									"nopesanan" => $data['nopesanan'],
									"idproduk" => $c['product_id'],
									"get_poin" => $dtproduk[$c['product_id']]['poin']
								);
							}
							$poinku  = (int)$c['poin'] * (int)$c['qty'];
							$subtotal	+= $c['total'];
							$totberat   += $c['berat'];
							$totjumlah  += (int)$c['qty'];
							$totgetpoin += (int)$poinku;

							$i++;
						}

						$data['subtotal'] = $subtotal;
						$data['totjumlah'] = $totjumlah;
						$data['totgetpoin'] = $totgetpoin;
						$data['totberat'] 	= $totberat;

						$dataorder = $this->dataModel->getOrderByID($data['nopesanan']);
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
						$modelShipping = new model_Shipping();

						/*
						$dataservis 			= $modelShipping->getServisByIdserv($data);
						$datashipping 			= $modelShipping->getShippingByIdServ($data);
						$data['servis_id'] 		= isset($dataservis['servis_id']) && !empty($dataservis['servis_id'])? $dataservis['servis_id'] : $data['serviskurir'];
						$data['servis_code'] 	= isset($dataservis['servis_code']) ? $dataservis['servis_code'] : $datashipping['servis_code'];
						$data['shipping']		= $datashipping['shipping_kode'];
						$data['hrgkurir_perkilo'] = isset($dataservis['hrg_perkilo']) ? $dataservis['hrg_perkilo'] : 0;
						$data['kurir_konfirm'] = $datashipping['shipping_konfirmadmin'];
						*/
						$totalberat = (int)$totberat / 1000;
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
							$modelsetting = new model_SettingToko();
							$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin', 'config_apiurlongkir', 'config_apikeyongkir'));
							foreach ($setting as $st) {
								$key 	= $st['setting_key'];
								$value 	= $st['setting_value'];
								$$key = $value;
							}
							$shipping = $modelShipping->getShippingRajaOngkir();
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
						$simpan = $this->dataModel->simpaneditprodukorder($data);
						if ($simpan['status'] == 'success') {
							$status = 'success';
							$pesan  = 'Berhasil menyimpan data';
						} else {
							$status = 'error';
							$pesan  = 'Proses menyimpan data tidak berhasil';
						}
					} else {
						$status = 'error';

						$pesan = $addCart['msg'];
					}
				}
			}
		} else {
			$status = 'error';
			$pesan = 'Data tidak valid';
		}

		$session = array('hscart', 'qtycart', 'qtylamacart');
		$this->Fungsi->hapusSession($session);
		echo json_encode(array("status" => $status, "result" => $pesan));
	}

	public function formAddProduk()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = isset($_POST['data']) ? $_POST['data'] : '';
			$zdata    		= explode(":", $data);
			$dataproduk 	= array();
			$dataproduk['produkid']		= $zdata[0];
			$dataproduk['produkkode']	= $zdata[1];
			$dataproduk['produknm']		= $zdata[2];
			$dataproduk['ukuran'] 		= $zdata[3];
			$dataproduk['berat']  		= $zdata[4];
			$dataproduk['hrgsatuan']	= $zdata[5];

			$dataproduk['diskonsatuan']	= $zdata[6];
			$dataproduk['stok']		 	= $zdata[7];
			$dataproduk['minbeli']     	= $zdata[8];
			$dataproduk['diskon_member'] = $zdata[9];
			$dataproduk['pesanan_no']	= $zdata[10];

			$dataproduk['grup_nama']	= $zdata[12];
			$dataproduk['minbelisyarat'] = $zdata[13];
			$dataproduk['persen_diskon'] = $zdata[14];

			$dataproduk['totalpersendiskon'] = (int)$dataproduk['persen_diskon'] + (int)$dataproduk['diskon_member'];
			$hargamember = $zdata[5] - (($zdata[5] * $dataproduk['totalpersendiskon']) / 100);
			$dataproduk['harga_member'] = $hargamember;
			if ($zdata[13] == '2') {
				$dataproduk['syarat'] = 'Bebas Campur';
			} else {
				$dataproduk['syarat'] = 'Per Jenis Produk';
			}

			$status = 'success';
			$msg = 'Berhasil load data';
		} else {
			$status = 'error';
			$msg = 'Data tidak ada';
		}
		return array("status" => $status, "msg" => $msg, "produk" => $dataproduk);
	}

	public function formHapusProdukOrder()
	{
		$data = array();
		$dataorder = array();
		$servis = array();
		$totberat = 0;
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			foreach ($_POST as $key => $value) {
				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';
			}

			if ($data['jmlproduk'] == 1) {
				$status = 'error';
				$pesan = 'Batas minimal menghapus produk adalah 1 item produk di pesanan belanja';
			} else { }
			$dataorder = $this->dataModel->getOrderByID($data['pesanan_no']);

			if ($dataorder) {


				$zdata['pelanggan_id'] 	= $dataorder['pelanggan_id'];
				$zdata['grup_member']	= $dataorder['grup_member'];
				$datapost				= explode("::", $data['pid']);

				$zdata['produk_id']		= $datapost[0];
				$options 				= unserialize(base64_decode($datapost[1]));
				$zdata['idukuran']		= $options[0];
				$zdata['idwarna']		= $options[1];
				$zdata['ukuran']		= $options[2];
				$zdata['warna']			= $options[3];
				$zdata['qty']			= $datapost[2];
				$zdata['pesanan_no']	= $data['pesanan_no'];
				$modelproduk			= new model_Produk();
				$dataproduk				= $modelproduk->getProdukByID($zdata['produk_id']);
				$zdata['nama_produk'] 	= $dataproduk['nama_produk'];

				$zdata['jmlproduk']		= $data['jmlproduk'];
				if ($data['jmlproduk'] == 1) {
					$status = 'error';
					$msg = 'Batas minimal menghapus produk adalah 1 item produk tersisa di pesanan belanja';
				} else {
					$status = 'success';
					$msg = 'Data diterima';
				}
				$zdata['totberat'] = $data['totberat'];
			} else {
				$status = 'error';
				$msg = 'Order tidak valid';
			}
		} else {
			$status = 'error';
			$msg = 'Data tidak ada';
		}

		return array("status" => $status, "msg" => $msg, "produk" => $zdata);
	}

	public function hapusprodukorder()
	{
		$delall = '0';
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';
			}

			$dataorder = $this->dataModel->getOrderByID($data['pesanan_no']);
			if ($dataorder) {
				$modelreseller 	= new model_Reseller();
				$modeShippping	= new model_Shipping();
				$modelproduk	= new model_Produk();
				$controlCart	= new controller_Cart();
				$customer = $modelreseller->getResellerCompleteById($_SESSION['idmember']);
				$orderdetail = $this->dataModel->getProdukOrderOptionSingle($data['product_id'], $data['idwarna'], $data['idukuran'], $data['pesanan_no']);
				$data['iddetail'] = $orderdetail['iddetail'];
				$data['idmember'] = $_SESSION['idmember'];
				$data['nopesanan'] = $data['pesanan_no'];
				if ((int)$data['idwarna'] > 0 || (int)$data['idukuran'] > 0) {
					$data['stokoptionbertambah'] = array(
						"nopesanan" => $data['pesanan_no'],
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

				if ($data['jmlproduk'] > 1) {

					$produk_in_order = $this->dataModel->getProdukOrderOption('', '', '', $data['pesanan_no']);
					foreach ($produk_in_order as $order) {
						if ((int)$order['iddetail'] != (int)$data['iddetail']) {
							$produk = $modelproduk->getProdukByID($order['idproduk']);
							$produk_to_cart['stok'] = $produk['jml_stok'];
							$produk_to_cart['produk'] = $produk['nama_produk'];
							$produk_to_cart['product_id']  = $order['idproduk'];
							$produk_to_cart['jumlah']	= $order['jml'];
							$produk_to_cart['idmember'] = $customer['cust_id'];
							$produk_to_cart['tipe'] 	= $customer['cust_grup_id'];
							$produk_to_cart['persen_diskon'] = $produk['persen_diskon'];
							$produk_to_cart['image_product'] = $order['idproduk'] . $order['ukuran'] . $order['warna'];
							if ($order['ukuran'] == '' && $order['ukuran'] == null) {
								$order['ukuran'] = '0';
							}
							if ($order['warna'] == '' && $order['warna'] == null) {
								$order['warna'] = '0';
							}
							$produk_to_cart['option']   = array($order['ukuran'], $order['warna']);

							$produk_to_cart['min_beli'] = $customer['cg_min_beli'];
							$produk_to_cart['min_beli_syarat'] = $customer['cg_min_beli_syarat'];
							$produk_to_cart['diskon_grup'] = $customer['cg_diskon'];
							$controlCart->addCartFromProdukOrder($produk_to_cart);
						}
					}
					$dcart['min_beli'] = $customer['cg_min_beli'];
					$dcart['min_beli_syarat'] = $customer['cg_min_beli_syarat'];
					$dcart['diskon_grup'] = $customer['cg_diskon'];
					$carts = $controlCart->showminiCart($_SESSION['hscart'], $dcart);
					$totalitem	= $carts['items'];
					$datacart 	= $carts['carts'];
					$subtotal 	= 0;
					$i      	= 0;
					$totberat 	= 0;
					$totjumlah	= 0;
					$totgetpoin = 0;
					$zprod = array();
					$dtproduk = array();
					foreach ($datacart as $c) {
						if (!in_array($c['product_id'], $zprod)) {
							$zprod[] = $c['product_id'];
							$dtproduk[$c['product_id']] = $modelproduk->getProdukByID($c['product_id']);
						}
						if (($c['warna'] != '' && $c['warna'] != '0')  || ($c['ukuran'] != '' && $c['ukuran'] != '0')) {
							$data['orderprodukoption'][] = array(
								"idwarna" => $c['warna'],
								"idukuran" => $c['ukuran'],
								"qty" => $c['qty'],
								"harga" => $c['harga'],
								"satuan" => $dtproduk[$c['product_id']]['hrg_jual'],
								"persen_diskon_satuan" => $dtproduk[$c['product_id']]['persen_diskon'],
								"berat" => $c['berat'],
								"nopesanan" => $data['pesanan_no'],
								"idproduk" => $c['product_id']
							);
						} else {
							$data['orderproduk'][] = array(
								"qty" => $c['qty'],

								"harga" => $c['harga'],
								"satuan" => $dtproduk[$c['product_id']]['hrg_jual'],
								"persen_diskon_satuan" => $dtproduk[$c['product_id']]['persen_diskon'],
								"berat" => $c['berat'],
								"nopesanan" => $data['pesanan_no'],
								"idproduk" => $c['product_id']
							);
						}
						$poinku  = (int)$c['poin'] * (int)$c['qty'];
						$subtotal	+= $c['total'];
						$totberat   += $c['berat'];
						$totjumlah  += (int)$c['qty'];
						$totgetpoin += (int)$poinku;

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
					/*
					$dataservis 			= $modeShippping->getServisByIdserv($data);
					$datashipping 			= $modeShippping->getShippingByIdServ($data);
					$data['servis_id'] 		= isset($dataservis['servis_id']) && !empty($dataservis['servis_id'])? $dataservis['servis_id'] : $data['serviskurir'];
					$data['servis_code'] 	= isset($dataservis['servis_code']) ? $dataservis['servis_code'] : $datashipping['servis_code'];
					$data['shipping']		= $datashipping['shipping_kode'];
					$data['hrgkurir_perkilo'] = isset($dataservis['hrg_perkilo']) ? $dataservis['hrg_perkilo'] : 0;
					$data['kurir_konfirm'] = $datashipping['shipping_konfirmadmin'];
					*/
					$totalberat = (int)$totberat / 1000;
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
						$modelsetting = new model_SettingToko();
						$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin', 'config_apiurlongkir', 'config_apikeyongkir'));
						foreach ($setting as $st) {
							$key 	= $st['setting_key'];
							$value 	= $st['setting_value'];
							$$key = $value;
						}
						$shipping = $modeShippping->getShippingRajaOngkir();
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

					$simpan = $this->dataModel->deleteProdukOrder($data);
					if ($simpan['status'] == 'success') {
						$status = 'success';
						$result  = 'Berhasil menyimpan data';
						$delall = $simpan['delall'];
						$session = array('hscart', 'qtycart', 'qtylamacart');
						$this->Fungsi->hapusSession($session);
					} else {
						$status = 'error';
						$result  = 'Proses menyimpan data tidak berhasil';
					}
				} else {
					$status = 'error';
					$result = 'Produk minimal 1 item tersedia di Orderan';
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

	public function editKurir()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data["$key"]	= isset($_POST["$key"]) ? $value : '';
			}

			$data['pelanggan_id'] = $this->idlogin;
			$order = $this->dataModel->getOrderByID($data['nopesanan']);
			$data['order'] = $order;
			if ($this->validasisimpankurir($data)) {
				/*
				$modelShipping = new model_Shipping();
				$dataservis 	= $modelShipping->getServisByIdserv($data);
				$datashipping 	= $modelShipping->getShippingByIdServ($data);
				
				$data['kurir'] 	= $datashipping['shipping_kode'];
				$data['kurir_perkilo'] = isset($dataservis['hrg_perkilo']) ? $dataservis['hrg_perkilo'] : 0;
				$data['tarifkurir'] = isset($data['tarifkurir']) ? $data['tarifkurir'] : '';
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
				*/

				$serviskurir 	= isset($data['serviskurir']) ? explode("::", $data['serviskurir']) : array();
				$servis_id	 	= isset($serviskurir[0]) ? $serviskurir[0] : 0;
				$tarif		 	= isset($serviskurir[1]) ? $serviskurir[1] : 'Konfirmasi Admin';
				$shipping_kode	= isset($serviskurir[2]) ? $serviskurir[2] : 0;
				$servis_kode	= isset($serviskurir[3]) ? $serviskurir[3] : '';

				$data['serviskurir']		= $servis_id;
				$data['kurir']				= $shipping_kode;
				$data['kurir_perkilo']		= 0;
				$data['servis_code']		= $servis_kode;



				$totberat = (int)$data['totberat'] / 1000;
				if ($totberat < 1) $totberat = 1;

				if ($tarif == 'Konfirmasi Admin') {
					$data['konfirm_admin'] = '1';
					$captiontarif = 'Konfirmasi Admin';
					$data['tarifkurir'] = 0;
				} else {
					$data['konfirm_admin'] = '0';
					$captiontarif = 'Rp. ' . $this->Fungsi->fuang($tarif);
					$data['tarifkurir'] = $tarif;
				}
				$totalbelanja 	= $order['pesanan_subtotal'] + $data['tarifkurir'];
				if ($totalbelanja < $order['dari_deposito']) {
					$data['jmldeposit'] = $totalbelanja;
				} else {
					$data['jmldeposit'] = $order['dari_deposito'];
				}
				$simpan = $this->dataModel->simpanEditKurir($data);
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
		echo json_encode(array("status" => $status, "result" => $pesan));
	}
	function formEditOrderAlamat()
	{
		$dataorder = array();
		$servis = array();
		$alamat_pelanggan = array();
		$datapropinsi = array();
		$totberat = 0;
		$msg = '';

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			/*
			foreach ($_GET as $key => $value) {
				$data["{$key}"]	= isset($_GET["{$key}"]) ? $value : '';
			}
			
			*/
			foreach ($_POST as $key => $value) {
				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';
			}

			if ($data['jenisalamat'] == 'alamatpengirim') {
				$data['caption_alamat'] = 'Alamat Pengirim';
			} elseif ($data['jenisalamat'] == 'alamatpenerima') {
				$data['caption_alamat'] = 'Alamat Penerima';
			}


			$data['idmember'] = $_SESSION['idmember'];
			$data['grup_member'] = $_SESSION['tipemember'];
			$data['dropship']	= $_SESSION['dropship'];

			if ($data['dropship'] != '1' && $data['jenisalamat'] == 'alamatpengirim') {
				$status = 'error';
				$pesan = 'Grup Member Anda tidak memiliki fasilitas Dropship';
			} else {
				$dataorder = $this->dataModel->getOrderByID($data['pesanan_no']);

				if ($dataorder) {
					$propinsi_penerima = $dataorder['propinsi_penerima'];
					$kabupaten_penerima = $dataorder['kota_penerima'];
					$kecamatan_penerima = $dataorder['kecamatan_penerima'];
					$modelShipping = new model_Shipping();
					$servis = $modelShipping->getAllServicesAndTarifByWilayah($propinsi_penerima, $kabupaten_penerima, $kecamatan_penerima);

					$totberat = $data['totberat'];
					$modelcust = new model_Reseller();
					$alamat_pelanggan = $modelcust->getAlamatCustomer($data['idmember']);

					$dtPropinsi = new model_Propinsi();
					$datapropinsi = $dtPropinsi->getPropinsi();

					$status = 'success';
					$msg = 'Data diterima';
				} else {
					$status = 'error';
					$msg = 'Order tidak valid';
				}
			}
		} else {
			$status = 'error';
			$msg = 'Data tidak ada';
		}

		return array(
			"status" => $status,
			"msg" => $msg,
			"order" => $dataorder,
			"servis" => $servis,
			"totberat" => $totberat,
			"caption_form" => $data['caption_alamat'],
			"jenisalamat" => $data['jenisalamat'],
			"alamatcustomer" => $alamat_pelanggan,
			"dataprop" => $datapropinsi
		);
	}

	private function validasisimpankurir($data)
	{
		//if($data['nopesanan'] == '' || !$this->dataModel->cekOrder($data['nopesanan'],$this->idlogin)) {
		if ($data['nopesanan'] == '' || (isset($data['order']['pelanggan_id']) && $data['order']['pelanggan_id'] != $this->idlogin)) {
			$this->error[] = 'No. Pesanan tidak valid';
		}
		if ($data['serviskurir'] == '' || $data['serviskurir'] == '0') {
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
	public function saveOrderAlamat()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = array();
			$modelReseller	= new model_Reseller();
			$modeshipping = new model_Shipping();
			foreach ($_POST as $key => $value) {
				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';
			}
			$data['nopesanan'] = $data['pesanan_no'];
			//$data['savetoaddress'] = isset($data['add_check_saveaddress']) ? $data['add_check_saveaddress'] : '0';
			$data['savetoaddress'] = '1';
			$data['chkdefault'] = isset($data['chkdefault']) ? $data['chkdefault'] : '0';
			$dataorder = $this->dataModel->getOrderByID($data['pesanan_no']);

			if ($dataorder) {
				$grup_member = $dataorder['grup_member'];
				$data['idmember']  = $dataorder['pelanggan_id'];
				$datagrup = $modelReseller->getGrupCustByID($grup_member);

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

					$data['serviskurir']			= $dataorder['servis_kurir'];
					$data['shipping_kode']			= $dataorder['shipping_kode'];
					$data['shipping'] 				= $dataorder['shipping_kdrajaongkir'];
					$data['kurir_konfirm'] 			= $dataorder['shipping_konfirmadmin'];
					$data['hrgkurir_perkilo'] 		= 0;

					$totberat = (int)$data['totberat'] / 1000;
					if ($totberat < 1) $totberat = 1;

					if ($data['kurir_konfirm'] != '1') {
						$modelsetting = new model_SettingToko();
						$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin', 'config_apiurlongkir', 'config_apikeyongkir'));
						foreach ($setting as $st) {
							$key 	= $st['setting_key'];
							$value 	= $st['setting_value'];
							$$key = $value;
						}
						$shipping = $modeshipping->getShippingRajaOngkir();
						foreach ($shipping as $ship) {

							//if($data['shipping']  == $ship['shipping_kode']) {
							$kurir["{$ship['shipping_kode']}"]["{$ship['servis_code']}"] = array("servis" => $ship['servis_id'], "shipping_code" => $ship['shipping_kdrajaongkir']);
							//break;
							//}



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
					$total = ($dataorder['pesanan_subtotal'] + $data['tarifkurir']) - $dataorder['dari_deposito'];
					if ($total < $data['dari_poin']) {
						$data['dari_poin'] = $total;
						$data['sisa_dari_poin'] = $data['dari_poin'] - $total;
					}
				}
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

				$simpan = $this->dataModel->simpanEditAlamat($data);
				if ($simpan['status'] == 'success') {
					$status = 'success';
					$result = 'Berhasil mengubah Alamat Pengirim';
				} else {
					$status = 'error';
					$result = 'Proses mengubah Alamat Pengirim Gagal';
				}
			}
		} else {
			$status = 'error';
			$result = 'Data tidak valid';
		}
		echo json_encode(array("status" => $status, "result" => $result));
	}

	public function useOrderAlamat()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$data = array();

			$modelReseller	= new model_Reseller();
			$modeshipping = new model_Shipping();

			foreach ($_POST as $key => $value) {
				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';
			}
			$data['nopesanan'] = $data['pesanan_no'];
			$data['savetoaddress'] = '0';
			$data['chkdefault'] = isset($data['chkdefault']) ? $data['chkdefault'] : '0';
			$dataalamat = $modelReseller->getAlamatCustomerByID($data['idalamat']);

			$dataorder = $this->dataModel->getOrderByID($data['pesanan_no']);

			if ($dataorder) {
				$grup_member = $dataorder['grup_member'];
				$data['idmember'] = $dataorder['pelanggan_id'];

				$datagrup = $modelReseller->getGrupCustByID($grup_member);

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
					$dataservis 				= $modeshipping->getServisByIdserv($data);
					$datashipping 				= $modeshipping->getShippingByIdServ($data);
					$data['servis_id'] 			= isset($dataservis['servis_id']) && !empty($dataservis['servis_id'])? $dataservis['servis_id'] : $data['serviskurir'];
					$data['servis_code'] 		= isset($dataservis['servis_code']) ? $dataservis['servis_code'] : $datashipping['servis_code'];
					$data['shipping']			= $datashipping['shipping_kode'];
					$data['hrgkurir_perkilo'] 	= isset($dataservis['hrg_perkilo']) ? $dataservis['hrg_perkilo'] : 0;
					$data['kurir_konfirm'] 		= $datashipping['shipping_konfirmadmin'];
					
					$totalberat = (int)$data['totberat'] / 1000;
					if($totalberat < 1) $totalberat = 1;
					
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
					$data['serviskurir']			= $dataorder['servis_kurir'];
					$data['shipping_kode']			= $dataorder['shipping_kode'];
					$data['shipping'] 				= $dataorder['shipping_kdrajaongkir'];
					$data['kurir_konfirm'] 			= $dataorder['shipping_konfirmadmin'];
					$data['hrgkurir_perkilo'] 		= 0;

					$totberat = (int)$data['totberat'] / 1000;
					if ($totberat < 1) $totberat = 1;

					if ($data['kurir_konfirm'] != '1') {
						$modelsetting = new model_SettingToko();
						$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin', 'config_apiurlongkir', 'config_apikeyongkir'));
						foreach ($setting as $st) {
							$key 	= $st['setting_key'];
							$value 	= $st['setting_value'];
							$$key = $value;
						}
						$shipping = $modeshipping->getShippingRajaOngkir();
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
				}
				if ($getdropship == '1') { //mengecek apakah grup si member tersebut mendapatkan fasilitas dropship

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
					// jika grup tidak mendapatkan fasilitas dropship maka otomatis dropship dianggap 0 atau tidak ada.
					$data['dropship'] = '0';
				}

				$simpan = $this->dataModel->simpanEditAlamat($data);
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

	public function addprodukorder()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';
			}
			$data['idmember'] = $_SESSION['idmember'];
			if ($data['nopesanan'] == '' || $data['nopesanan'] == '0') {
				$status = 'error';
				$result = 'No. Order tidak ditemukan';
			} else {
				$controlCart	= new controller_Cart();
				$modelproduk 	= new model_Produk();
				$modelshipping 	= new model_Shipping();
				$modelreseller 	= new model_Reseller();
				$customer 		= $modelreseller->getResellerCompleteById($_SESSION['idmember']);

				$dataproduk = $modelproduk->getProdukByID($data['product_id']);
				if ($data['idwarna'] == '0' && $data['idwarna'] == '' && $data['idukuran'] == '' && $data['idukuran'] == '0') {
					$stok = $dataproduk['jml_stok'];
				} else {
					$stok = $modelproduk->getStokWarnaUkuran($data['product_id'], $data['idukuran'], $data['idwarna']);
				}
				$cekorder = $this->dataModel->JumlahOrder($data['nopesanan'], $data['product_id'], $data['idwarna'], $data['idukuran']);
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
					$cart_add['tipe'] 			= $customer['cust_grup_id'];
					$cart_add['stok']			= $dataproduk['jml_stok'];
					$cart_add['produk']			= $dataproduk['nama_produk'];
					$cart_add['product_id']		= $data['product_id'];
					$cart_add['jumlah']			= $data['qty'];
					$cart_add['option']			= array($data['idukuran'], $data['idwarna']);
					$cart_add['idmember']		=  $customer['cust_id'];
					$cart_add['persen_diskon'] 	= $dataproduk['persen_diskon'];
					$cart_add['image_product'] = $data['product_id'] . $data['idukuran'] . $data['idwarna'];
					$cart_add['min_beli'] = $customer['cg_min_beli'];
					$cart_add['min_beli_syarat'] = $customer['cg_min_beli_syarat'];
					$cart_add['diskon_grup'] = $customer['cg_diskon'];
					$addCart = $controlCart->addCart($cart_add);
					//print_r($addCart);
					if ($addCart['status'] == 'success') {
						if ((int)$data['idwarna'] > 0 || (int)$data['idukuran'] > 0) {
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

						$produk_in_order = $this->dataModel->getProdukOrderOption('', '', '', $data['nopesanan']);

						$produk_cart = array();

						foreach ($produk_in_order as $order) {

							if (!in_array($order['idproduk'], $produk_cart)) {
								$produk_cart[] = $order['idproduk'];
								$produk[$order['idproduk']] = $modelproduk->getProdukByID($order['idproduk']);
							}
							$produk_to_cart['stok'] = $produk[$order['idproduk']]['jml_stok'];
							$produk_to_cart['produk'] = $produk[$order['idproduk']]['nama_produk'];
							$produk_to_cart['product_id']  = $order['idproduk'];
							$produk_to_cart['jumlah']	= $order['jml'];
							$produk_to_cart['idmember'] = $customer['cust_id'];
							$produk_to_cart['tipe'] 	= $customer['cust_grup_id'];
							$produk_to_cart['persen_diskon'] = $produk[$order['idproduk']]['persen_diskon'];
							$produk_to_cart['image_product']  = $order['gbr'];
							if ($order['ukuran'] == '' && $order['ukuran'] == null) {
								$order['ukuran'] = '0';
							}
							if ($order['warna'] == '' && $order['warna'] == null) {
								$order['warna'] = '0';
							}
							$produk_to_cart['option']   = array($order['ukuran'], $order['warna']);
							$produk_to_cart['min_beli'] = $customer['cg_min_beli'];
							$produk_to_cart['min_beli_syarat'] = $customer['cg_min_beli_syarat'];
							$produk_to_cart['diskon_grup'] = $customer['cg_diskon'];
							$controlCart->addCartFromProdukOrder($produk_to_cart);
						}

						$dcart['min_beli'] = $customer['cg_min_beli'];
						$dcart['min_beli_syarat'] = $customer['cg_min_beli_syarat'];
						$dcart['diskon_grup'] = $customer['cg_diskon'];

						$carts = $controlCart->showminiCart($_SESSION['hscart'], $dcart);
						$totalitem	= $carts['items'];
						$datacart 	= $carts['carts'];
						$subtotal 	= 0;
						$i      	= 0;
						$totberat 	= 0;
						$totjumlah	= 0;
						$totgetpoin = 0;
						$zprod = array();
						$dtproduk = array();

						foreach ($datacart as $c) {

							if (!in_array($c['product_id'], $zprod)) {
								$zprod[] = $c['product_id'];
								$dtproduk[$c['product_id']] = $modelproduk->getProdukByID($c['product_id']);
							}

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

							$poinku  = (int)$c['poin'] * (int)$c['qty'];
							$subtotal	+= $c['total'];
							$totberat   += $c['berat'];
							$totjumlah  += (int)$c['qty'];
							$totgetpoin += (int)$poinku;

							$i++;
						}
						$data['subtotal'] = $subtotal;
						$data['totjumlah'] = $totjumlah;
						$data['totgetpoin'] = $totgetpoin;
						$data['totberat'] 	= $totberat;
						$dataorder = $this->dataModel->getOrderByID($data['nopesanan']);
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

						/*
						$dataservis 			= $modelshipping->getServisByIdserv($data);
						$datashipping 			= $modelshipping->getShippingByIdServ($data);
						$data['servis_id'] 		= isset($dataservis['servis_id']) && !empty($dataservis['servis_id'])? $dataservis['servis_id'] : $data['serviskurir'];
						$data['servis_code'] 	= isset($dataservis['servis_code']) ? $dataservis['servis_code'] : $datashipping['servis_code'];
						$data['shipping']		= $datashipping['shipping_kode'];
						$data['hrgkurir_perkilo'] = isset($dataservis['hrg_perkilo']) ? $dataservis['hrg_perkilo'] : 0;
						$data['kurir_konfirm'] = $datashipping['shipping_konfirmadmin'];
						*/

						$totalberat = (int)$totberat / 1000;
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
							$modelsetting = new model_SettingToko();
							$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin', 'config_apiurlongkir', 'config_apikeyongkir'));
							foreach ($setting as $st) {
								$key 	= $st['setting_key'];
								$value 	= $st['setting_value'];
								$$key = $value;
							}
							$shipping = $modelshipping->getShippingRajaOngkir();
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
						$simpan = $this->dataModel->simpanaddprodukorder($data);
						if ($simpan['status'] == 'success') {
							$status = 'success';
							$result  = 'Berhasil menyimpan data';
							$session = array("hscart", "qtycart", "qtylamacart");
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
	function SearchOrderAutocomplete()
	{
		$json 	= array();
		$idmember = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : '0';

		if ($idmember == '' && $idmember == '0') {
			$status = 'error';
			$pesan = 'Tidak ada hak akses';
		} else {

			$dtsetting = new model_SettingToko();
			$status_pending = $dtsetting->getSettingTokoByKey('config_orderstatus');

			$noorder = isset($_GET['search']) ? $_GET['search'] : '';

			$dataorder = $this->dataModel->getOrderByIDAutocomplete($noorder, $idmember, $status_pending);

			if ($dataorder) {
				foreach ($dataorder as $order) {
					$totalbelanja = (($order['pesanan_subtotal'] + $order['pesanan_kurir']) - $order['dari_poin'] - $order['dari_deposito']);
					$json[] = array(
						'noorder' => $order['pesanan_no'],
						'totalbelanja' => $totalbelanja
					);
				}
				$status = 'success';
				$pesan = $json;
			} else {
				$status = 'error';
				$pesan = 'Data order tidak valid';
			}
		}
		//echo json_encode(array('status'=>$status,'result'=>$pesan));
		echo json_encode($json);
	}
	public function updateketerangan()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$data = array();

			foreach ($_POST as $key => $value) {
				$data["{$key}"]	= isset($_POST["{$key}"]) ? $value : '';
			}
			$simpan = $this->dataModel->updateketerangan($data);
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
	}

	public function potongSaldo()
	{
	
		$data = array();
		$noorder = isset($_GET['order']) ? $_GET['order']:'0';
		$data['pesanan_no'] = $noorder;

		$dataorder = $this->dataModel->getOrderByID($noorder);

		$datadetail = $this->dataModel->getOrderDetail($noorder);

		$tagihan = ($dataorder['pesanan_subtotal'] + $dataorder['pesanan_kurir']) - $dataorder['dari_deposito'];

		$modelreseller = new model_Reseller();
		$checkdeposit = $modelreseller->gettotalDeposito($dataorder['pelanggan_id']);
		
		if($checkdeposit['totaldeposito']<$tagihan){
			return false;
		}

		$data['keterangan'] = 'Menggunakan Saldo di Order ' . sprintf('%08s', (int) $data["pesanan_no"]);
		$data['jmldeposit'] = $tagihan;
		$data['column'] = 'dari_deposito';

		$data['pelanggan_id'] = $this->idlogin;

		$data['order_status'] = 10;
		$data['insert_status'] = '1';

		$this->dataModel->simpanPotonganDeposito($data);
	}
}
