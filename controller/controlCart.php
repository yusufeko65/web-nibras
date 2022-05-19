<?php

class controller_Cart
{

	private $dataModel;
	private $dataShipping;
	private $Fungsi;
	private $dataProduk;
	private $dataRegister;
	private $tipemember;
	private $kirim_email;
	private $bank;
	private $data = array();


	public function __construct()
	{
		//require_once 'panelnibras/fpdf/fpdf.php';
		//print_r(URL_PROGRAM_ADMIN);
		//print_r(htmlspecialchars(htmlentities("<script>alert('tes')</script>")));
		//require_once 'areacontrol/fpdf/fpdf.php';
		require_once 'panelnibras/fpdf/fpdf.php';
		$this->dataModel = new model_Cart();
		$this->dataShipping = new model_Shipping();
		$this->dataCustomer = new model_Reseller();
		$this->Fungsi = new FungsiUmum();
		$this->dataProduk = new controller_Produk();
		$this->kirim_email = new PHPMailer();
		$this->bank = new model_Bank();

		if (!isset($_SESSION['hscart'])) {
			$_SESSION['hscart'] = array();
		}



		if (!isset($_SESSION['qtycart']))
			$_SESSION['qtycart'] = array();


		if (!isset($_SESSION['wrncart']))
			$_SESSION['wrncart'] = array();


		if (!isset($_SESSION['ukrcart']))
			$_SESSION['ukrcart'] = array();
	}

	public function delCart()
	{
		$data = isset($_POST['data']) ? $_POST['data'] : '';
		if ($data != '') {
			$data = explode("::", $data);
			$pid = $data[0];
			$options = unserialize(base64_decode($data[1]));
			$option = implode(",", $options);
			$jml = $data[2];
			$imageproduk = $data[3];
			if (!$options) {
				$key = $pid . '::' . $imageproduk;
			} else {
				$key = $pid . ':' . $option . '::' . $imageproduk;
			}

			unset($_SESSION['hscart'][$key]);

			$_SESSION['qtycart'][$pid] = $_SESSION['qtycart'][$pid] - $jml;

			if ($_SESSION['qtycart'][$pid] < 1) {
				unset($_SESSION['qtycart'][$pid]);
			}

			if (count($_SESSION['qtycart']) < 1) {
				unset($_SESSION['qtycart']);
			}
			$status = 'success';
			$pesan = "Berhasil dihapus";
		} else {
			$status = 'error';
			$pesan = 'Data tidak ditemukan';
		}
		echo json_encode(array("status" => $status, "msg" => $pesan));
	}
	public function updateCart()
	{
		$totbeli = array();
		$pesan = array();
		$status = '';
		$qtycat = array();
		$hasilcart = array();

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			foreach ($_POST['qty'] as $key => $value) {
				$data = explode("::", $key);

				$pid = $data[0];
				$options = unserialize(base64_decode($data[1]));
				$idukuran = $options[0];
				$idwarna = $options[1];
				$option = implode(",", $options);
				$jmllama = $data[2];
				//$imageproduk = $data[3];
				$dataproduk = $this->dataProduk->getOption($pid, $idwarna, $idukuran);
				$imageproduk = $dataproduk['gbr'];
				if (!$options) {
					$keys = $pid . '::' . $imageproduk;
					//$keys = $pid;
				} else {
					$keys = $pid . ':' . $option . '::' . $imageproduk;
					//$keys = $pid.':'.$option;
				}

				$qty = (int) $value;

				if (!isset($totbeli[$pid]))  $totbeli[$pid] = $qty;
				else $totbeli[$pid] += $qty;

				$produk  = $this->dataProduk->dataProdukByID($pid);
				$stokall = $produk['jml_stok'];

				if ((int) $qty && ((int) $qty > 0)) {
					//$cekstok = $this->dataProduk->getOption($pid,$options[1],$options[0]);
					//$cekstok['stok'] = isset($cekstok['stok']) ? $cekstok['stok']:0;
					$cekstok['stok'] = isset($dataproduk['stok']) ? $dataproduk['stok'] : 0;
					if ($cekstok['stok'] > 0) {
						$stok = $cekstok['stok'];
					} else {
						$stok = $stokall;
					}


					//if($stok < $totbeli[$pid]) {
					if ($stok < $_SESSION['hscart'][$keys]) {
						$qty = $jmllama;
						$pesan[] = 'stok <b>' . $produk['nama_produk'] . '</b> tinggal tersedia ' . $stok . ' item';
					}
				} else {
					if ($options[1] != '')
						$zwarna = '->' . $this->Fungsi->fcaridata('_warna', 'warna', 'idwarna', $options[1]) . ' ';
					else
						$zwarna = '';

					if ($options[0] != '')
						$zukuran = '->' . $this->Fungsi->fcaridata('_ukuran', 'ukuran', 'idukuran', $options[1]) . ' ';
					else
						$zukuran = '';

					$pesan[] = 'Produk <b>' . $produk['nama_produk'] . $zwarna . $zukuran . '</b>, silahkan masukkan jumlah';
					$qty = $jmllama;
				}

				$_SESSION['hscart'][$keys] = $qty;
				$_SESSION['qtycart'][$pid] = $totbeli[$pid];
			}
			if (count($pesan) == 0) {
				$status = 'success';
				$msg = "Berhasil Mengubah Data Belanja";
			} else {
				$status = 'error';
				$msg = implode("<br>", $pesan);
			}
		} else {
			$status = 'error';
			$msg = 'Tidak Ada Data';
		}
		echo json_encode(array("status" => $status, "msg" => $msg));
	}
	public function addCart($data)
	{
		$pesan = '';
		$jml = 0;
		$total = 0;
		$item = $data['product_id'];
		$qty  = $data['jumlah'];
		$qty_lama = isset($data['qty_lama']) ? $data['qty_lama'] : 0;

		if (!$data['option']) {
			$key = (int) $item . '::' . $data['image_product'];
		} else {
			$option = implode(",", $data['option']);
			$key = (int) $item . ':' . $option . '::' . $data['image_product'];
		}

		if ((int) $qty && ((int) $qty > 0)) {
			if (!isset($_SESSION['qtycart'][$item])) {
				$_SESSION['qtycart'][$item] = (int) $qty;
			} else {
				$_SESSION['qtycart'][$item] += (int) $qty;
			}

			if (!isset($_SESSION['hscart'][$key])) {
				$_SESSION['hscart'][$key] = (int) $qty;
			} else {
				$_SESSION['hscart'][$key] += (int) $qty;
			}

			$_SESSION['qtylamacart'][$key] = (int) $qty_lama;
		}

		$cekstok = $this->dataProduk->getOption($item, $data['option'][1], $data['option'][0]);
		$cekstok['stok'] = isset($cekstok['stok']) ? $cekstok['stok'] : 0;
		if ($cekstok['stok'] > 0) {
			$stok = $cekstok['stok'];
		} else {
			$stok = $data['stok'];
		}

		$totprodukini = isset($_SESSION['hscart'][$key]) ? (int) $_SESSION['hscart'][$key] - 1 : 0;
		if (isset($_SESSION['hscart'][$key])) {
			if ($stok + $qty_lama < $_SESSION['hscart'][$key]) {
				$pesan = 'Maaf, stok tinggal tersedia ' . $stok . ' item. Anda telah memesan produk ini sebanyak ' . $totprodukini . ' item ';
				$status = 'error';

				$_SESSION['qtycart'][$item] -= (int) $qty;
				$_SESSION['hscart'][$key] -= (int) $qty;
				if ($_SESSION['qtycart'][$item] == 0) {
					unset($_SESSION['qtycart'][$item]);
				}
				if ($_SESSION['hscart'][$key] == 0) {
					unset($_SESSION['hscart'][$key]);
				}
			} else {
				$miniCart = $this->showminiCart($_SESSION['hscart'], $data);
				$totalitem = $miniCart['items'];
				$cart = $miniCart['carts'];

				foreach ($cart as $c) {
					$jml += $c['qty'];
					$total += $c['total'];
				}
				$pesan = 'Anda memasukkan ' . $data['produk'] . ' kedalam keranjang belanja Anda';
				$status = 'success';
			}
		} else {
			$pesan = 'Proses Pesan Gagal';
			$status = 'error';
		}

		return array("status" => $status, "msg" => $pesan, "qty" => $jml, "total" => $total);
	}

	public function addCartFromProdukOrder($data)
	{
		$pesan = '';
		$jml = 0;
		$total = 0;
		$item = $data['product_id'];
		$qty  = $data['jumlah'];


		if (!$data['option']) {
			$key = (int) $item . '::' . $data['image_product'];
		} else {
			$option = implode(",", $data['option']);
			$key = (int) $item . ':' . $option . '::' . $data['image_product'];
		}

		if ((int) $qty && ((int) $qty > 0)) {
			if (!isset($_SESSION['qtycart'][$item])) {
				$_SESSION['qtycart'][$item] = (int) $qty;
			} else {
				$_SESSION['qtycart'][$item] += (int) $qty;
			}

			if (!isset($_SESSION['hscart'][$key])) {
				$_SESSION['hscart'][$key] = (int) $qty;
			} else {
				$_SESSION['hscart'][$key] += (int) $qty;
			}
			$_SESSION['qtylamacart'][$key] = $qty;
		}
	}

	public function pesanCart($dt = array())
	{
		$pesan       = array();
		$hasilpesan  = array();
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			foreach ($_POST as $key => $value) {
				$data["$key"]	= isset($_POST["$key"]) ? $value : '';
			}
			if ($data['jumlah'] < 0) {
				$pesan[] = 'Masukkan Jumlah Pesanan Anda';
			}
			if ($data['product_id'] < 1) {
				$pesan[] = 'Tidak Ada Produk';
			}
			if (isset($data['warna']) && $data['warna'] == '0') {
				$pesan[] = 'Pilih Warna';
			}

			if (isset($data['ukuran']) && $data['ukuran'] == '0') {
				$pesan[] = 'Pilih Jenis Ukuran';
			}
			if (count($pesan) > 0) {
				$hasil = implode("<br>", $pesan);
				$status = 'error';
				$qty = '';
				$total = 0;
			} else {
				$datacart        = array();

				$produk       	= $this->dataProduk->dataProdukByID($data['product_id']);
				$data['tipe'] 	= $dt['tipemember'];
				$data['stok']	= $produk['jml_stok'];
				$data['produk']	= $produk['nama_produk'];
				$data['ukuran']	= isset($data['ukuran']) ? $data['ukuran'] : '0';
				$data['warna']	= isset($data['warna']) ? $data['warna'] : '0';
				$data['option']  = array($data['ukuran'], $data['warna']);
				$data['total_awal'] = $dt['grup_totalawal'];
				$data['min_beli'] = $dt['grup_min_beli'];
				$data['min_beli_syarat'] = $dt['grup_min_beli_syarat']; /* Jika 1, per jenis produk. Jika 2, Bebas campur produk */
				$data['min_beli_wajib'] = $dt['grup_min_beli_wajib']; /* jika wajib, misal qty 3. maka ia harus beli minimal 3 */
				$data['diskon_grup'] = $dt['grup_diskon'];
				$datacart = $this->addCart($data);
				$status = $datacart['status'];
				$hasil = $datacart['msg'];
				$qty = isset($datacart['qty']) ? $datacart['qty'] : '';
				$total = $datacart['total'];
			}
		} else {
			$status = 'error';
			$qty = '';
			$total = 0;
			$hasil = 'Data tidak valid';
		}

		echo json_encode(array("status" => $status, "msg" => $hasil, "qty" => $qty, "total" => $this->Fungsi->fuang($total)));
	}
	public function showminiCart($cartitem, $data)
	{
		$options = array();
		$carts = array();
		$i = 0;
		$stoks = 0;
		$pesan = array();
		$jmlerror = 0;
		$stoknya = 0;

		$minbeli = $data['min_beli'];
		$syarat = $data['min_beli_syarat'];
		$diskoncust = $data['diskon_grup'];

		$jmlall = array_sum($cartitem);

		foreach ($cartitem as $key => $quantity) {
			$datacart = explode('::', $key);
			$image_produk = $datacart[1];
			$product = explode(':', $datacart[0]);
			$id = $product[0];

			$ukuran = '';
			$warna = '';

			$options = explode(",", $product[1]);
			/*
			if(!isset($_SESSION['ukrcart'][$id]))
				$_SESSION['ukrcart'][$id] = array();
		
			if(!isset($_SESSION['wrncart'][$id])) 
				 $_SESSION['wrncart'][$id] = array();
		
			$_SESSION['ukrcart'][$id][$i] = $options[0];
			$_SESSION['wrncart'][$id][$i] = $options[1];
			*/

			$qtylama = isset($_SESSION['qtylamacart'][$key]) ? $_SESSION['qtylamacart'][$key] : $quantity;
			$jmlitem = (int) $_SESSION['qtycart'][$id];
			$jmlcart = $quantity;

			$prod = $this->dataProduk->dataProdukByID($id);
			$getpoin = $prod['poin'];
			$hrgdiskon = $prod['hrg_diskon'];
			$hrgsatuan = $prod['hrg_jual'];
			/*
			$hrgjual = $hrgsatuan - (($hrgsatuan * $diskoncust)/100);
			$hrgjualdiskon = $hrgdiskon - (($hrgdiskon * $diskoncust)/100);
			*/

			$persen = $diskoncust + $prod['persen_diskon'];

			$stok       = $this->dataProduk->getOption($id, $options[1], $options[0]);

			$allstok    = $prod['jml_stok'];
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
					$harga = $hrgsatuan - (($hrgsatuan * $persen)/100);
				} else {
					$harga = $hrgjual;
				}
				*/
				$harga = $harganormal - (($harganormal * $persen) / 100);
			} else {
				/*
				if($hrgdiskon > 0){
					$harga = $hrgdiskon;
					
				} else {
					$harga = $hrgsatuan;
				}
				*/
				if ($hrgdiskon > 0) {
					$harga = $harganormal - (($harganormal * $persen) / 100);
				} else {
					$harga = $harganormal;
				}
			}


			//$totalharga = $harga + $tambahanhrg;

			$item_total = ((int) $harga) * (int) $jmlcart;
			$berat = ((int) $prod['berat_produk']) * (int) $jmlcart;

			if ($stok_option < 1) {
				$stoknya = $allstok;
			} else {
				$stoknya = $stok_option;
			}

			//if($stoknya < $jmlcart ) {
			if ($stoknya + $qtylama < $jmlcart) {
				$pesan[] = 'Stok ' . $prod['nama_produk'] . ' tersedia ' . $stoks . ' Pcs';
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
				"aliasurl"				=> $prod['alias_url'],
				"stok"					=> $stoks,
				"poin"          		=> $getpoin,
				"warna"					=> $options[1],
				"warna_nama"			=> $this->Fungsi->fcaridata('_warna', 'warna', 'idwarna', $options[1]),
				"ukuran"	    		=> $options[0],
				"ukuran_nama"			=> $this->Fungsi->fcaridata('_ukuran', 'ukuran', 'idukuran', $options[0]),
				"image_produk"			=> $image_produk
			);

			$i++;
		}
		$carts = array(
			'carts'	=> $carts,
			'items'	=> count($_SESSION['hscart']),
			'jmlerror' => $jmlerror,
			'pesan' => $pesan
		);

		return $carts;
	}

	public function simpanorder()
	{
		$status = '';
		$pesan = '';
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['idmember'])) {
			$data = [];
			$modelreseller = new model_Reseller();
			foreach ($_POST as $key => $value) {
				$data["{$key}"] = trim($value);
			}

			$data['propinsi_pengirim'] = isset($data['propinsi_pengirim']) && $data['propinsi_pengirim'] != '' && $data['propinsi_pengirim'] ? $data['propinsi_pengirim'] : '0';
			$data['kabupaten_pengirim'] = isset($data['kabupaten_pengirim']) && $data['kabupaten_pengirim'] != '' ? $data['kabupaten_pengirim'] : '0';
			$data['kecamatan_pengirim'] = isset($data['kecamatan_pengirim']) && $data['kecamatan_pengirim'] != '' ? $data['kecamatan_pengirim'] : '0';
			$data['alamat_pengirim'] = isset($data['alamat_pengirim']) ? $data['alamat_pengirim'] : '';
			$data['kelurahan_pengirim'] = isset($data['kelurahan_pengirim']) ? $data['kelurahan_pengirim'] : '';
			$data['kodepos_pengirim'] = isset($data['kodepos_pengirim']) ? $data['kodepos_pengirim'] : '';

			$customer = $modelreseller->getResellerCompleteById($_SESSION['idmember']);
			$data['cust_id'] = $customer['cust_id'];
			$data['cust_grup'] = $customer['cust_grup_id'];
			$data['ipaddress'] = $this->Fungsi->get_client_ip();
			$data['potongan_kupon'] = 0;
			$data['kode_kupon'] = '-';
			$data['poin'] = isset($data['poin']) && $data['poin'] != '' ? $data['poin'] : 0;
			$data['potdeposito'] = isset($data['potdeposito']) && $data['potdeposito'] != '' ? $data['potdeposito'] : 0;

			$serviskurir 	= isset($data['serviskurir']) ? explode("::", $data['serviskurir']) : array();
			$servis_id	 	= isset($serviskurir[0]) ? $serviskurir[0] : 0;
			$tarif		 	= isset($serviskurir[1]) ? $serviskurir[1] : 'Konfirmasi Admin';
			$shipping_kode	= isset($serviskurir[2]) ? $serviskurir[2] : 0;
			$servis_kode	= isset($serviskurir[3]) ? $serviskurir[3] : '';

			$data['servis_id']			= $servis_id;
			$data['shipping']			= $shipping_kode;
			$data['hrgkurir_perkilo']	= 0;
			$data['servis_code']		= $servis_kode;
			$totberat = (int) $data['totberat'] / 1000;
			if ($totberat < 1) $totberat = 1;


			if ($tarif == 'Konfirmasi Admin') {
				$data['kurir_konfirm'] = '1';
				$captiontarif = 'Konfirmasi Admin';
				$data['tarifkurir'] = 0;
			} else {
				$data['kurir_konfirm'] = '0';
				$captiontarif = 'Rp. ' . $this->Fungsi->fuang($tarif);
				$data['tarifkurir'] = $tarif;
			}

			$data['tgltransaksi'] = date('Y-m-d H:i:s');
			$data['tglkirim']	= '0000-00-00 00:00';
			$data['nomor_awb']  = '-';
			//print_r($data);
			$kodeakhir 	= $this->Fungsi->fIdAkhir('_order', 'CONVERT(pesanan_no,SIGNED)');
			//$data['nopesanan'] = sprintf('%08s', $kodeakhir + 1);
			$data['nopesanan'] = $kodeakhir + 1;

			$subtotalbelanja = ($data['subtotal'] + $data['tarifkurir']) - $data['poin'];

			$checkpoin = $modelreseller->getTotalPoin($data['cust_id']);
			if ($data['poin'] > 0) {
				if ($checkpoin['totalpoin'] == 0) {
					$status = "error";
					$pesan = "Anda tidak memiliki poin";
				} else {
					$sisapoin = (int) $checkpoin['totalpoin'] - (int) $data['poin'];
					if ($sisapoin < 0) {
						$status = 'error';
						$pesan  = "Anda hanya memiliki poin sebesar " . $checkpoin['totalpoin'];
					}
				}
				if ($status == 'error') {
					echo json_encode(array("status" => $status, "result" => $pesan));
					return;
				}

				if ($data['poin'] > $subtotalbelanja) {
					$data['poin'] = $subtotalbelanja;
				}
				$data['keteranganpoin'] = "Menggunakan Poin Pada Order : " . $data['nopesanan'];
			}

			$cart['min_beli'] = $customer['cg_min_beli'];
			$cart['min_beli_syarat'] = $customer['cg_min_beli_syarat'];
			$cart['diskon_grup'] = $customer['cg_diskon'];

			$keranjang 		= $this->showminiCart($_SESSION['hscart'], $cart);
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


			//foreach($datacart[0] as $key => $value){
			foreach ($datacart as $dc) {
				//$data["{$key}"] = $value;
				//print_r($key.'<br>');

				$data['orderdetail'][] = array(
					"product_id" => $dc["product_id"],
					"qty" => $dc["qty"],
					"harga" => $dc["harga"],
					"hrgsatuan" => $dc["hargasatuan"],
					"hrgtambahan" => $dc["hargatambahan"],
					"berat" => $dc['berat'],
					"persen_diskon_satuan" => $dc['persen_diskon_prod'],
					"sale" => $dc['sale'],
					"warna" => $dc['warna'],
					"ukuran" => $dc['ukuran'],
					"get_poin" => $dc['poin']
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
			$gunakandeposito = 0;
			if ($customer['cg_deposito'] == '1') {
				$checkdeposit = $modelreseller->gettotalDeposito($data['cust_id']);

				if ($checkdeposit['totaldeposito'] > 0 && $data['potdeposito'] > 0) {
					if ($subtotalbelanja > $checkdeposit['totaldeposito'] || $subtotalbelanja == $checkdeposit['totaldeposito']) {
						$totatagihan = $subtotalbelanja - $checkdeposit['totaldeposito'];
						$data['potdeposito'] = $checkdeposit['totaldeposito'];
					} else {
						$totaltagihan = 0;
						$data['potdeposito'] = $subtotalbelanja;
					}
					$data['sisadeposito'] = $checkdeposit['totaldeposito'] - $data['potdeposito'];
					$data['keterangandeposito'] = "Menggunakan Saldo di Order : " . $data['nopesanan'];
					$data['updatedeposito'] = true;
					$data['InsertDepositoDetail'] = true;
					$gunakandeposito == 1;
				}
			}
			$data['dropship'] = '0';
			if ($customer['cg_dropship'] == '1') {
				if (trim($data['nama_pengirim']) != trim($data['nama_penerima']) && trim($data['alamat_pengirim']) != trim($data['alamat_penerima']) && trim($data['telp_pengirim']) != trim($data['telp_penerima'])) {
					$data['dropship'] = '1';
				}
			}
			$sisaltotalbelanja = $subtotalbelanja - $data['potdeposito'];

			//$data['status_order'] = $this->Fungsi->fcaridata('_setting','setting_value','setting_key','config_orderstatus');
			$where = "setting_key IN ('config_orderstatus','config_sudahbayarstatus')";
			$status  = $this->Fungsi->fcaridata3('_setting', 'setting_key,setting_value', $where);
			$datastatus = [];
			if ($status) {
				foreach ($status as $sts) {
					$datastatus["{$sts['setting_key']}"] = $sts['setting_value'];
				}
			}

			if ($sisaltotalbelanja <= 0) {
				// Status Booking
				$_data = array(
					'nopesanan'		=> $data['nopesanan'],
					'tgltrans'		=> $data['tgltransaksi'],
					'orderstatus'	=> $datastatus['config_orderstatus'],
					'adminid'		=> isset($_SESSION['idmember']) && !empty($_SESSION['idmember'])?$_SESSION['idmember']:'-99'
				);
				
				$proses[] = $this->dataModel->SimpanOrderStatus($_data); 
				$this->dataModel->prosesTransaksi($proses);
				
				// Status Bayar
				$data['status_order'] = $datastatus['config_sudahbayarstatus'];
			} else {
				$data['status_order'] = $datastatus['config_orderstatus'];
			}
			$simpan = $this->dataModel->simpanorder($data);

			if ($simpan['status']=='success') {
				$status = 'success';
				$pesan  = 'Order Anda akan segera diproses';
				/*
				$_SESSION['totalbelanja'] = $totalnya;
				$_SESSION['nopesanan'] = $data['nopesanan'];
				$_SESSION['sukseskonfirm'] = 'ya';
				$_SESSION['potdeposito'] = $data['potdeposito'];
				*/
				$modelsetting = new model_SettingToko();
				$datasetting  = $modelsetting->getSettingToko();
				if ($datasetting) {
					foreach ($datasetting as $st) {
						$key 	= $st['setting_key'];
						$value 	= $st['setting_value'];
						$$key	= $value;
					}
				}

				/* 
				pengecekan apakah yang di alamat pengrim sudah atau belum
				jika belum, maka diinsert ke table alamat

				*/
				$cekPengirim = $this->dataCustomer->getCustByNameHp($data);
				if($cekPengirim < 1){
					$this->dataModel->insertCustomerAddressFromOrderPengirim($data);
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
				$tabel .= "<td colspan=\"6\" style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>" . $data['servis_code'] . ' - ' . $data['shipping'] . "</b> </td>";
				$tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>" . $captiontarif . "</b></td>";
				$tabel .= '</tr>';

				/* poin jika ada */
				if ($data['poin'] > 0) {
					$tabel .= '<tr style=\"margin:0;padding:0\">';
					$tabel .= '<td colspan="5"></td>';
					$tabel .= '<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>Potongan dari Poin : </b> </td>';
					$tabel .= '<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\">' . $this->Fungsi->fFormatuang((int) $data['poin']) . '</td>';
					$tabel .= '</tr>';
				}

				/* deposito jika ada */
				if ($data['potdeposito'] > 0) {
					$tabel .= '<tr style="margin:0;padding:0">';
					$tabel .= '<td colspan="6" style="text-align:right;margin:0;padding:10px" align="right" bgcolor="#ffffff"><b>Potongan dari Saldo</b> </td>';
					$tabel .= '<td style="text-align:right;margin:0;padding:10px" align="right" bgcolor="#ffffff">(' . $this->Fungsi->fFormatuang((int) $data['potdeposito']) . ')</td>';
					$tabel .= '</tr>';
				}

				/* total */
				$tabel	.= '<tr>';
				$tabel .= "<td colspan=\"6\" style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>Total Yang Harus di Transfer</b></td>";
				if ($captiontarif == 'Konfirmasi Admin') {
					$grandtotal = $captiontarif;
				} else {
					$grandtotal = 'Rp. ' . $this->Fungsi->fuang(($subtotal + $data['tarifkurir']) - $data['poin'] - $data['potdeposito']);
				}
				$tabel 	.= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>" . $grandtotal . "</b></td>";
				$tabel	.= '</tr>';
				$tabel 	.= '</tbody>';
				$tabel 	.= '</table>';

				/* pengirim */
				
				
				if($data['kecamatan_pengirim'] != '0' && $data['kecamatan_pengirim'] != '') {
					$tablewilayah = "_kecamatan kc left join _kabupaten kb on kc.kabupaten_id = kb.kabupaten_id 
									left join _provinsi p on kb.provinsi_id = p.provinsi_id";

					$wilayah_pengirim = $this->Fungsi->fcaridata2($tablewilayah, "kecamatan_nama,kabupaten_nama,provinsi_nama", "kc.kecamatan_id='" . $data['kecamatan_pengirim'] . "'");
				} else {
					$wilayah_pengirim = array();
				}

				$alamatpengirim   = '<b>Alamat Pengirim</b> <br>';
				$alamatpengirim  .= htmlentities($data['nama_pengirim']) . ' <br>';
				
				
				
				if(isset($data['alamat_pengirim']) && $data['alamat_pengirim'] != '') {
					$alamatpengirim  .= $data['alamat_pengirim'] . ' <br>' ;
				}
				if(isset($wilayah_pengirim['provinsi_nama'])) { 
					$alamatpengirim  .= $wilayah_pengirim['provinsi_nama'] . ', ';
					$alamatpengirim  .= $wilayah_pengirim['kabupaten_nama'] . ',';
					$alamatpengirim  .= 'Kec. ' . htmlentities($wilayah_pengirim['kecamatan_nama']) . ', ';
				}

				if ($data['kelurahan_pengirim'] != '') {

					$alamatpengirim .= 'Kelurahan. ' . htmlentities($data['kelurahan_pengirim']);
				}
				$alamatpengirim .= '<br>';
				if ($data['kodepos_pengirim'] != '') {
					$alamatpengirim .= 'Kode Pos ' . $data['kodepos_pengirim'];
				}
				$alamatpengirim .= '<br>';
				$alamatpengirim .= 'Hp. ' . $data['telp_pengirim'];

				/* penerima */
				$tablewilayah = "_kecamatan kc left join _kabupaten kb on kc.kabupaten_id = kb.kabupaten_id 
								left join _provinsi p on kb.provinsi_id = p.provinsi_id";

				$wilayah_penerima = $this->Fungsi->fcaridata2($tablewilayah, "kecamatan_nama,kabupaten_nama,provinsi_nama", "kc.kecamatan_id='" . $data['kecamatan_penerima'] . "'");

				$alamatpenerima   = '<b>Alamat Penerima </b> <br>';
				$alamatpenerima  .=  htmlentities($data['nama_penerima']) . ' <br>';
				$alamatpenerima  .=  htmlentities($data['alamat_penerima']) . ' <br>';
				$alamatpenerima  .= $wilayah_penerima['provinsi_nama'] . ', ';
				$alamatpenerima  .= $wilayah_penerima['kabupaten_nama'] . ', ';
				$alamatpenerima  .= 'Kec. ' .  htmlentities($wilayah_penerima['kecamatan_nama']) . ', ';

				if ($data['kelurahan_penerima'] != '') {

					$alamatpenerima .= 'Kelurahan. ' .  htmlentities($data['kelurahan_penerima']);
				}
				$alamatpenerima .= '<br>';
				if ($data['kodepos_penerima'] != '') {
					$alamatpenerima .= 'Kode Pos ' . $data['kodepos_penerima'];
				}
				$alamatpenerima .= '<br>';
				$alamatpenerima .= 'Hp. ' . $data['telp_penerima'];
				$databank = $this->bank->getBank();
				$databanks = '';
				foreach ($databank as $b) {
					$rekening = $this->bank->getRekening($b['ids']);
					foreach ($rekening as $rek) {
						$databanks .= $rek['bank'] . '<br>No. Rek. ' . $rek['norek'] . '<br>A/n ' . $rek['atasnama'] . '<br>Cabang. ' . $rek['cabang'] . '<br><br>';
					}
				}

				$message   = str_replace("[PELANGGAN]", htmlentities($customer['cust_nama']), $notabelanja);
				$message   = str_replace("[No Order]", $data['nopesanan'], $message);
				$message   = str_replace("[DATA ORDER]", $tabel, $message);
				if ($data['dropship'] == '1') {
					$message   = str_replace("[ALAMAT PENGIRIM]", $alamatpengirim, $message);
				} else {
					$message   = str_replace("[ALAMAT PENGIRIM]", '', $message);
				}

				$message   = str_replace("[ALAMAT PENERIMA]", $alamatpenerima, $message);
				$message   = str_replace("[DATA BANK]", $databanks, $message);
				$message   = str_replace("[NAMAWEBSITE]", $from_name, $message);

				$bodys = "<div bgcolor=\"#FFFFFF\" style=\"font-family:'Helvetica Neue','Helvetica',Helvetica,Arial,sans-serif;width:100%!important;min-height:100%;font-size:14px;color:#404040;margin:0;padding:0\">";
				$bodys	.= $headernota . $message;
				$bodys .= '</div>';
				$this->kirim_email->IsHTML(true);
				$this->kirim_email->SetFrom($from, $from_name);
				$this->kirim_email->Subject = $subject;
				$this->kirim_email->WordWrap = 500;
				$this->kirim_email->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
				$this->kirim_email->Body = $bodys;
				$this->kirim_email->CharSet = "UTF-8";
				$this->kirim_email->AddAddress($to, $customer['cust_nama']);
				$this->kirim_email->Send();
				$_SESSION['sukses'] = 'ya';
				$_SESSION['message_order'] = $message;
				$session = array('hscart', 'qtycart', 'wrncart', 'ukrcart');
				$this->Fungsi->hapussession($session);
			} else if(isset($simpan['stock'])){
				$status = 'error';
				$pesan = 'Stok Habis';
			} else {
				$status = 'error';
				$pesan  = 'Proses Order Anda tidak berhasil';
			}
		} else {
			$status = 'error';
			$pesan = 'Data tidak valid';
		}

		echo json_encode(array("status" => $status, "result" => $pesan));
	}



	private function cetakInvoicePDF($data)
	{
		$nama_report = 'invoice' . $data['nopesanan'] . '.pdf';
		$pdf = new PDFTable();
		$pdf->AddPage("P", "A4");
	}

	public function editPenerimaOrder()
	{
		$this->data['dkurir'] = isset($_POST['epdkurir']) ? $_POST['epdkurir'] : '';
		$this->data['hrgkurir'] = isset($_POST['ephrgkurir']) ? $_POST['ephrgkurir'] : 0;
		$this->data['nopesan'] = isset($_POST['noorder']) ? $_POST['noorder'] : 0;
		$this->data['nama'] = isset($_POST['epnama']) ? $_POST['epnama'] : '';
		$this->data['alamat'] = isset($_POST['epalamat']) ? $_POST['epalamat'] : '';
		$this->data['negara'] = isset($_POST['epnegara']) ? $_POST['epnegara'] : '';
		$this->data['propinsi'] = isset($_POST['eppropinsi']) ? $_POST['eppropinsi'] : '';
		$this->data['kota'] = isset($_POST['epkabupaten']) ? $_POST['epkabupaten'] : '';
		$this->data['kecamatan'] = isset($_POST['epkecamatan']) ? $_POST['epkecamatan'] : '';
		$this->data['kelurahan'] = isset($_POST['epkelurahan']) ? $_POST['epkelurahan'] : '';
		$this->data['kodepos'] = isset($_POST['epkdpos']) ? $_POST['epkdpos'] : '';
		$this->data['nohp'] = isset($_POST['ephandphone']) ? $_POST['ephandphone'] : '';
		$this->data['notelp'] = isset($_POST['eptelp']) ? $_POST['eptelp'] : '';
		$this->data['totberat'] = isset($_POST['ntotberat']) ? $_POST['ntotberat'] : 0;
		$hasil = '';
		$proses = array();
		$pesan = array();
		//print_r($this->data['dkurir']);
		$zdata		= explode(":", $this->data['dkurir']);
		$idservis		= $zdata[0];
		$idkurir		= $zdata[1];
		$kurir		= $this->dataShipping->getShippingbyName($idkurir);
		$namashipping = $kurir['nama_shipping'];
		$tabelservis	= $kurir['tabel_servis'];
		$tabeltarif	= $kurir['tabel_tarif'];
		$tabeldiskon	= $kurir['tabel_diskon'];

		$detekkdpos   = $kurir['detek_kdpos'];
		$servisdata   = $this->dataShipping->getServisbyId($tabelservis, $idservis);
		$namaservis	= $servisdata[1];
		$servisid		= $servisdata[0];
		$minkilo		= $servisdata[3];

		$tarifkurir = $this->dataShipping->getTarif($servisid, $this->data['negara'], $this->data['propinsi'], $this->data['kota'], $this->data['kecamatan'], $this->data['totberat'], $minkilo, $tabeltarif, $detekkdpos, $namashipping);
		if ($tabeldiskon != Null || $tabeldiskon != '') {
			$zzdizkon = explode("::", $tabeldiskon);
			$tabels = $zzdizkon[0];
			$fieldambil = $zzdizkon[1];
			$where = " $zzdizkon[2]='" . $servisid . "' AND $zzdizkon[3]=1";

			$dtdiskon = $this->Fungsi->fcaridata2($tabels, $fieldambil, $where);
			$zdiskon = $dtdiskon[0] / 100;
		} else {
			$zdiskon = 0;
		}

		$tarifkurir[1] = $tarifkurir[1] - ($tarifkurir[1] * $zdiskon);
		$tarifkurir[4] = $tarifkurir[4] - ($tarifkurir[4] * $zdiskon);

		if ($tarifkurir[1] > 0) {
			$this->data['tarifkurir'] = $tarifkurir[1];
			$this->data['satuantarifkurir'] = $tarifkurir[4];
		} else {
			$this->data['tarifkurir'] = $this->data['hrgkurir'];
			$this->data['satuantarifkurir'] = 0;
		}


		$this->data['kurir'] = $namashipping;
		$this->data['kurirservis'] = $servisid;
		$this->data['nopesanan'] = $this->data['nopesan'];

		if (!$this->dataModel->simpanEditPenerimaOrder($this->data)) {
			$proses[] = $this->dataModel->simpanEditPenerimaOrder($this->data);
			$pesan[] = 'Gagal Mengubah Alamat Penerima';
		}
		if (!$this->dataModel->simpanEditKurir($this->data)) {
			$proses[] = $this->dataModel->simpanEditKurir($this->data);
			$pesan[] = 'Gagal Mengubah Tarif Kurir';
		}
		if (count($pesan) == 0) {
			$this->dataModel->prosesTransaksi($proses);
			$hasil = 'sukses|Berhasil Ubah Alamat Penerima';
		} else {
			$hasil = 'gagal|' . implode(", ", $pesan);
		}

		return $hasil;
	}
	public function editprodukorder()
	{
		$data = array();
		$error = false;
		$pesan = '';
		$pesans = array();
		$nopesan  = isset($_POST['enopesan']) ? $_POST['enopesan'] : '0';
		$idproduk = isset($_POST['eidproduk']) ? $_POST['eidproduk'] : '0';
		$iddetail = isset($_POST['eiddetail']) ? $_POST['eiddetail'] : '';
		$idgrup   = isset($_POST['eidgrup']) ? $_POST['eidgrup'] : '0';
		$idmember = isset($_POST['eidmember']) ? $_POST['eidmember'] : '0';
		$qtylama  = isset($_POST['eqtylama']) ? $_POST['eqtylama'] : 0;
		$qty      = isset($_POST['eqty']) ? $_POST['eqty'] : 0;
		$wnlama   = isset($_POST['ewnlama']) ? $_POST['ewnlama'] : 0;
		$uklama   = isset($_POST['ewnlama']) ? $_POST['euklama'] : 0;
		$zwarna    = isset($_POST['ewarna']) ? $_POST['ewarna'] : 0;
		$zukuran   = isset($_POST['eukuran']) ? $_POST['eukuran'] : 0;
		$zhrgkurir   = isset($_POST['zhrgkurir']) ? $_POST['zhrgkurir'] : 0;
		$proses = array();

		$produk  = $this->dataProduk->dataProdukByID($idproduk);
		$stok	  = $produk['jml_stok'];
		$data['option']   = array($zukuran, $zwarna);

		$cekstok = $this->dataProduk->getOption($idproduk, $data['option'][1], $data['option'][0]);
		if ($cekstok['stok'] > 0) {
			$stok = $cekstok['stok'];
		}

		if ($stok < $qty) {
			$pesan = 'Maaf, stok tinggal tersedia ' . $stok . ' item';
			$error = true;
		}

		if ($nopesan == '0') {
			$pesan = 'No. Pesan tidak ditemukan';
			$error = true;
		} elseif ((int) $qty < 1) {
			$pesan = 'Jumlah harus lebih dari 0';
			$error = true;
		}
		if ($error) {
			$pesan = 'gagal|' . $pesan;
		} else {
			//Kurir
			$wdtorder  = "pesanan_no = '" . $nopesan . "'";
			$dataord   = $this->Fungsi->fcaridata2('_order', 'kurir,servis_kurir', $wdtorder);

			$dataordpenerima  = $this->Fungsi->fcaridata2('_order_penerima', 'negara_penerima,propinsi_penerima,kota_penerima,kecamatan_penerima', $wdtorder);
			$negara    = $dataordpenerima[0];
			$propinsi  = $dataordpenerima[1];
			$kota      = $dataordpenerima[2];
			$kecamatan = $dataordpenerima[3];
			$idkurir   = $dataord[0];
			$idservis  = $dataord[1];

			$kurir		= $this->dataShipping->getShippingbyName($idkurir);
			$namashipping = $kurir['nama_shipping'];
			$tabelservis	= $kurir['tabel_servis'];
			$tabeltarif	= $kurir['tabel_tarif'];
			$tabeldiskon	= $kurir['tabel_diskon'];
			$detekkdpos   = $kurir['detek_kdpos'];

			$servisdata   = $this->dataShipping->getServisbyId($tabelservis, $idservis);
			$namaservis	   = $servisdata[1];
			$servisid		= $servisdata[0];
			$minkilo		= $servisdata[3];


			if ((int) $wnlama > 0 || (int) $uklama > 0) {
				// stok produk optionnya nya dibalikin dulu
				$proses[] = $this->dataModel->updateStokOptionBertambah($nopesan, $qtylama, $wnlama, $uklama, $idproduk);
			}
			// stok nya dibalikin dulu
			$data['jml'] = $qtylama;
			$data['pid'] = $idproduk;
			$proses[] = $this->dataModel->updateStokBertambah($qtylama, $idproduk);

			$produk       	  = $this->dataProduk->dataProdukByID($idproduk);
			$data['tipe'] 	  = $idgrup;
			$data['stok']	  = $produk['jml_stok'];
			$data['produk']	  = $produk['nama_produk'];
			$data['pid']      = $idproduk;
			$data['jml']	  = $qty;
			$data['option']   = array($zukuran, $zwarna);
			$data['idmember'] = $idmember;
			/*
		 $cekstok = $this->dataProduk->getOption($idproduk,$data['option'][1],$data['option'][0]);
	     if($cekstok['stok'] > 0) {
	       $stok = $cekstok['stok'];
	     } else {
	       $stok = $data['stok'];
	     }
	  
	     if($stok < $qty) $pesan = 'gagal|Maaf, stok tinggal tersedia '.$stok.' item';
		*/
			$this->addCart($data);
			$bongkar = $this->dataModel->getProdukOrderOption('', '', '', $nopesan);
			//echo $iddetail;
			foreach ($bongkar as $order) {

				if ((int) $order['iddetail'] != (int) $iddetail) {

					$produk = $this->dataProduk->dataProdukByID($order['idproduk']);
					$data['stok'] = $produk['jml_stok'];
					$data['produk'] = $produk['nama_produk'];
					$data['pid']  = $order['idproduk'];
					$data['jml']	  = $order['jml'];
					$data['option']   = array($order['ukuran'], $order['warna']);
					if ((int) $wnlama > 0 || (int) $uklama > 0) {
						// stok produk optionnya nya dibalikin dulu
						$proses[] = $this->dataModel->updateStokOptionBertambah($nopesan, $order['jml'], $order['warna'], $order['ukuran'], $order['idproduk']);
					}

					// stok nya dibalikin dulu
					$proses[] = $this->dataModel->updateStokBertambah($order['jml'], $order['idproduk']);

					$this->addCart($data);
				}
			} // end foreach bongkar

			$keranjang = $this->showminiCart($_SESSION['hscart']);
			$totalitem   = $keranjang['items'];
			$cart 		= $keranjang['carts'];
			$subtotal 	= 0;
			$i      	= 0;
			$totberat 	= 0;
			$totjumlah	= 0;

			//looping cartnya
			foreach ($cart as $c) {
				$this->data['pid']   	= $c['product_id'];
				$this->data['jml'] 	 	= $c['qty'];
				$this->data['berat'] 	= $c['berat'];
				$this->data['harga'] 	= $c['harga'];
				$this->data['total'] 	= $c['total'];

				$this->data['idwarna']  = $_SESSION['wrncart'][$this->data['pid']][$i];
				$this->data['idukuran'] = $_SESSION['ukrcart'][$this->data['pid']][$i];

				if ($this->data['idwarna'] != '') $warna	= $this->Fungsi->fcaridata('_warna', 'warna', 'idwarna', $this->data['idwarna']);
				else $warna = '';

				if ($this->data['idukuran'] != '') $ukuran = $this->Fungsi->fcaridata('_ukuran', 'ukuran', 'idukuran', $this->data['idukuran']);
				else $ukuran = '';

				$nama_produk 			= $c['product'];
				$where 					= "idproduk='" . $this->data['pid'] . "'";
				$prods					= $this->Fungsi->fcaridata2('_produk', 'hrg_jual,hrg_beli', $where);
				$this->data['satuan']	= $prods[0];
				$this->data['hrgbeli']	= $prods[1];

				if ($zukuran == $this->data['idukuran'] && $zwarna == $this->data['idwarna'] && $idproduk == $this->data['pid']) {
					$this->data['ukuranlama'] = $uklama;
					$this->data['warnalama'] = $wnlama;
				} else {
					$this->data['ukuranlama'] = $this->data['idukuran'];
					$this->data['warnalama'] = $this->data['idwarna'];
				}

				$this->data['nopesan'] = $nopesan;
				if (!$this->dataModel->updateOrderProduk($this->data)) {
					$proses[] = $this->dataModel->updateOrderProduk($this->data);
					$pesans[] = 'error simpan detail';
				}
				if ($this->data['idwarna'] != '' || $this->data['idwarna'] != '0' || $this->data['idukuran'] != '' || $this->data['idukuran'] != '0') {
					if (!$this->dataModel->UpdateStokOption($this->data)) {
						$proses[] = $this->dataModel->UpdateStokOption($this->data);
						$pesans[] = 'error update stok option';
					}
				}

				if (!$this->dataModel->UpdateStok($this->data)) {
					$proses[] = $this->dataModel->UpdateStok($this->data);
					$pesan[] = 'error update stok';
				}

				$subtotal	+= $this->data['total'];
				$totberat   += $this->data['berat'];
				$totjumlah  += (int) $c['qty'];


				$i++;
			} // end foreach looping

			$this->data['subtotal'] = $subtotal;
			$this->data['totjumlah'] = $totjumlah;

			$tarifkurir = $this->dataShipping->getTarif($servisid, $negara, $propinsi, $kota, $kecamatan, $totberat, $minkilo, $tabeltarif, $detekkdpos, $namashipping);

			if ($tabeldiskon != Null || $tabeldiskon != '') {
				$zzdizkon = explode("::", $tabeldiskon);
				$tabels = $zzdizkon[0];
				$fieldambil = $zzdizkon[1];
				$where = " $zzdizkon[2]='" . $servisid . "' AND $zzdizkon[3]=1";

				$dtdiskon = $this->Fungsi->fcaridata2($tabels, $fieldambil, $where);
				$zdiskon = $dtdiskon[0] / 100;
			} else {
				$zdiskon = 0;
			}

			$tarifkurir[1] = $tarifkurir[1] - ($tarifkurir[1] * $zdiskon);
			$tarifkurir[4] = $tarifkurir[4] - ($tarifkurir[4] * $zdiskon);

			if ($tarifkurir[1] > 0) {
				$this->data['tarifkurir'] = $tarifkurir[1];
				$this->data['satuantarifkurir'] = $tarifkurir[4];
			} else {
				$this->data['tarifkurir'] = $zhrgkurir;
				$this->data['satuantarifkurir'] = 0;
			}

			$this->data['kurir'] = $namashipping;
			$this->data['kurirservis'] = $servisid;
			$this->data['nopesanan'] = $nopesan;
			if (!$this->dataModel->UpdateOrder($this->data)) {
				$proses[] = $this->dataModel->UpdateOrder($this->data);
				$pesans[] = 'error simpan order';
			}

			if (count($pesans) == 0) {
				$pesan = 'sukses|Berhasil';
				$this->dataModel->prosesTransaksi($proses);
			} else {
				$pesan = 'gagal|Proses Simpan Gagal';
			}
		}

		return $pesan;
	}

	public function hapusprodukorder()
	{
		$data     = array();
		$error    = false;
		$pesan = '';
		$pesans = array();
		$proses = array();
		$data['idproduk'] = isset($_POST['didproduk']) ? $_POST['didproduk'] : '';
		$data['idmember'] = isset($_POST['didmember']) ? $_POST['didmember'] : '';
		$data['nopesan']  = isset($_POST['dnopesan'])  ? $_POST['dnopesan'] : '';
		$data['qtylama']  = isset($_POST['dqtylama'])  ? $_POST['dqtylama'] : 0;
		$data['uklama']   = isset($_POST['duklama'])   ? $_POST['duklama'] : 0;
		$data['wnlama']   = isset($_POST['dwnlama'])   ? $_POST['dwnlama'] : 0;
		$data['iddetail'] = isset($_POST['diddetail']) ? $_POST['diddetail'] : 0;
		$data['idgrup']   = isset($_POST['didgrup']) ? $_POST['didgrup'] : '';
		$zhrgkurir = isset($_POST['zhrgkurir']) ? $_POST['zhrgkurir'] : 0;
		if ($data['iddetail'] == '' && $data['iddetail'] == '0') {
			$pesan = 'Data tidak ada';
			$error = true;
		}

		if ($error) {
			$pesan = 'gagal|' . $pesan;
		} else {
			//Kurir
			$wdtorder  = "pesanan_no = '" . $data['nopesan'] . "'";
			$dataord   = $this->Fungsi->fcaridata2('_order', 'kurir,servis_kurir', $wdtorder);

			$dataordpenerima  = $this->Fungsi->fcaridata2('_order_penerima', 'negara_penerima,propinsi_penerima,kota_penerima,kecamatan_penerima', $wdtorder);
			$negara    = $dataordpenerima[0];
			$propinsi  = $dataordpenerima[1];
			$kota      = $dataordpenerima[2];
			$kecamatan = $dataordpenerima[3];
			$idkurir   = $dataord[0];
			$idservis  = $dataord[1];

			$kurir		= $this->dataShipping->getShippingbyName($idkurir);
			$namashipping = $kurir['nama_shipping'];
			$tabelservis	= $kurir['tabel_servis'];
			$tabeltarif	= $kurir['tabel_tarif'];
			$tabeldiskon	= $kurir['tabel_diskon'];
			$logoshipping	= $kurir['logo'];
			$detekkdpos   = $kurir['detek_kdpos'];

			$servisdata     = $this->dataShipping->getServisbyId($tabelservis, $idservis);
			$namaservis  	= $servisdata[1];
			$servisid		= $servisdata[0];
			$minkilo		= $servisdata[3];

			if ((int) $data['wnlama'] > 0 || (int) $data['uklama'] > 0) {
				// stok produk optionnya nya dibalikin dulu
				$proses[] = $this->dataModel->updateStokOptionBertambah($data['nopesan'], $data['qtylama'], $data['wnlama'], $data['uklama'], $data['idproduk']);
			}
			// stok nya dibalikin dulu
			$proses[] = $this->dataModel->updateStokBertambah($data['qtylama'], $data['idproduk']);

			// Hapus order_detail, order_Detail_option
			$proses[] = $this->dataModel->hapusProdukOrderOption($data);

			$bongkar = $this->dataModel->getProdukOrderOption('', '', '', $data['nopesan']);
			$data['tipe'] 	  = $data['idgrup'];
			foreach ($bongkar as $order) {

				$produk         = $this->dataProduk->dataProdukByID($order['idproduk']);
				$data['stok']   = $produk['jml_stok'];
				$data['produk'] = $produk['nama_produk'];
				$data['pid']    = $order['idproduk'];
				$data['jml']	 = $order['jml'];
				$data['option'] = array($order['ukuran'], $order['warna']);

				if ((int) $data['wnlama'] > 0 || (int) $data['uklama'] > 0) {

					// stok produk optionnya nya dibalikin dulu
					$proses[] = $this->dataModel->updateStokOptionBertambah($data['nopesan'], $order['jml'], $order['warna'], $order['ukuran'], $order['idproduk']);
				}

				// stok nya dibalikin dulu
				$proses[] = $this->dataModel->updateStokBertambah($order['jml'], $order['idproduk']);

				$this->addCart($data);
			} // end foreach bongkar

			$idmember   = $data['idmember'];
			$idgrup     = $data['idgrup'];
			$keranjang  = $this->showminiCart($_SESSION['hscart']);
			$totalitem  = $keranjang['items'];
			$cart 		= $keranjang['carts'];
			$subtotal 	= 0;
			$i      	= 0;
			$totberat 	= 0;
			$totjumlah	= 0;

			//looping cartnya
			foreach ($cart as $c) {
				$this->data['pid']   	= $c['product_id'];
				$this->data['jml'] 	 	= $c['qty'];
				$this->data['berat'] 	= $c['berat'];
				$this->data['harga'] 	= $c['harga'];
				$this->data['total'] 	= $c['total'];

				$this->data['idwarna']  = $_SESSION['wrncart'][$this->data['pid']][$i];
				$this->data['idukuran'] = $_SESSION['ukrcart'][$this->data['pid']][$i];

				if ($this->data['idwarna'] != '') $warna	= $this->Fungsi->fcaridata('_warna', 'warna', 'idwarna', $this->data['idwarna']);
				else $warna = '';

				if ($this->data['idukuran'] != '') $ukuran = $this->Fungsi->fcaridata('_ukuran', 'ukuran', 'idukuran', $this->data['idukuran']);
				else $ukuran = '';


				$nama_produk 			= $c['product'];
				$where 					= "idproduk='" . $this->data['pid'] . "'";
				$prods					= $this->Fungsi->fcaridata2('_produk', 'hrg_jual,hrg_beli', $where);
				$this->data['satuan']	= $prods[0];
				$this->data['hrgbeli']	= $prods[1];

				$this->data['ukuranlama'] = $this->data['idukuran'];
				$this->data['warnalama'] = $this->data['idwarna'];

				$this->data['nopesan'] = $data['nopesan'];
				if (!$this->dataModel->updateOrderProduk($this->data)) {
					$proses[] = $this->dataModel->updateOrderProduk($this->data);
					$pesans[] = 'error simpan detail';
				}
				if ($this->data['idwarna'] != '' || $this->data['idwarna'] != '0' || $this->data['idukuran'] != '' || $this->data['idukuran'] != '0') {

					if (!$this->dataModel->UpdateStokOption($this->data)) {
						$proses[] = $this->dataModel->UpdateStokOption($this->data);
						$pesans[] = 'error update stok option';
					}
				}

				if (!$this->dataModel->UpdateStok($this->data)) {
					$proses[] = $this->dataModel->UpdateStok($this->data);
					$pesans[] = 'error update stok';
				}

				$subtotal	+= $this->data['total'];
				$totberat   += $this->data['berat'];
				$totjumlah  += (int) $c['qty'];


				$i++;
			} // end foreach looping

			$this->data['subtotal'] = $subtotal;
			$this->data['totjumlah'] = $totjumlah;

			$tarifkurir = $this->dataShipping->getTarif($servisid, $negara, $propinsi, $kota, $kecamatan, $totberat, $minkilo, $tabeltarif, $detekkdpos, $namashipping);

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
			$tarifkurir[1] = $tarifkurir[1] - ($tarifkurir[1] * $zdiskon);
			$tarifkurir[4] = $tarifkurir[4] - ($tarifkurir[4] * $zdiskon);

			if ($tarifkurir[1] > 0) {
				$this->data['tarifkurir'] = $tarifkurir[1];
				$this->data['satuantarifkurir'] = $tarifkurir[4];
			} else {
				$this->data['tarifkurir'] = $zhrgkurir;
				$this->data['satuantarifkurir'] = 0;
			}

			$this->data['kurir'] = $namashipping;
			$this->data['kurirservis'] = $servisid;
			$this->data['nopesanan'] = $data['nopesan'];
			if (!$this->dataModel->UpdateOrder($this->data)) {
				$proses[] = $this->dataModel->UpdateOrder($this->data);
				$pesans[] = 'error simpan order';
			}

			if ($totjumlah == 0) {
				if (!$this->dataModel->HapusOrder($this->data)) {
					$proses[] = $this->dataModel->UpdateOrder($this->data);
					$pesans[] = 'error simpan order';
				}
			}

			if (count($pesans) == 0) {
				$pesan = 'sukses|Berhasil';
				$this->dataModel->prosesTransaksi($proses);
			} else {
				$pesan = 'gagal|Proses Hapus Gagal';
			}
		}
		return $pesan;
	}


	public function addprodukorder()
	{
		$data = array();
		$error = false;
		$pesan = '';
		$pesans = array();
		$nopesan  = isset($_POST['anopesan']) ? $_POST['anopesan'] : '0';
		$idproduk = isset($_POST['aidproduk']) ? $_POST['aidproduk'] : '0';
		$idgrup   = isset($_POST['aidgrup']) ? $_POST['aidgrup'] : '0';
		$idmember = isset($_POST['aidmember']) ? $_POST['aidmember'] : '0';
		$qty      = isset($_POST['aqty']) ? $_POST['aqty'] : 0;
		$zwarna    = isset($_POST['awarna']) ? $_POST['awarna'] : 0;
		$zukuran   = isset($_POST['aukuran']) ? $_POST['aukuran'] : 0;
		$zhrgkurir   = isset($_POST['zhrgkurir']) ? $_POST['zhrgkurir'] : 0;
		$proses = array();
		$produkskrg  = $this->dataProduk->dataProdukByID($idproduk);
		$stok	  = $produkskrg['jml_stok'];
		$data['option']   = array($zukuran, $zwarna);

		$cekstok = $this->dataProduk->getOption($idproduk, $data['option'][1], $data['option'][0]);
		if ($cekstok['stok'] > 0) {
			$stok = $cekstok['stok'];
		}

		if ($stok < $qty) {
			$pesan = 'Maaf, stok tinggal tersedia ' . $stok . ' item';
			$error = true;
		}
		if ($nopesan == '0') {
			$pesan = 'No. Pesan tidak ditemukan';
			$error = true;
		} elseif ((int) $qty < 1) {
			$pesan = 'Jumlah harus lebih dari 0 ' . $qty;
			$error = true;
		}
		$tabelcek = '_order_detail INNER JOIN _order_detail_option ON _order_detail.iddetail = _order_detail_option.iddetail';
		$wherecek = "WHERE pesanan_no = '" . $nopesan . "' AND product_id='" . $idproduk . "' AND warnaid='" . $zwarna . "' AND ukuranid='" . $zukuran . "'";
		$ceklist  = $this->Fungsi->fjumlahdata($tabelcek, $wherecek);

		if ($ceklist > 0) {
			$pesan = 'Produk sudah ada di List Order';
			$error = true;
		}

		if ($error) {
			$pesan = 'gagal|' . $pesan;
		} else {
			//Kurir
			$wdtorder  = "pesanan_no = '" . $nopesan . "'";
			$dataord   = $this->Fungsi->fcaridata2('_order', 'kurir,servis_kurir', $wdtorder);

			$dataordpenerima  = $this->Fungsi->fcaridata2('_order_penerima', 'negara_penerima,propinsi_penerima,kota_penerima,kecamatan_penerima', $wdtorder);
			$negara    = $dataordpenerima[0];
			$propinsi  = $dataordpenerima[1];
			$kota      = $dataordpenerima[2];
			$kecamatan = $dataordpenerima[3];
			$idkurir   = $dataord[0];
			$idservis  = $dataord[1];

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

			$produk       	  = $this->dataProduk->dataProdukByID($idproduk);
			$data['tipe'] 	  = $idgrup;
			$data['stok']	  = $produk['jml_stok'];
			$data['produk']	  = $produk['nama_produk'];
			$data['pid']      = $idproduk;
			$data['jml']	  = $qty;
			$data['option']   = array($zukuran, $zwarna);
			$data['idmember'] = $idmember;
			$this->addCart($data);
			$bongkar = $this->dataModel->getProdukOrderOption('', '', '', $nopesan);

			foreach ($bongkar as $order) {
				$produk = $this->dataProduk->dataProdukByID($order['idproduk']);
				$data['stok']   = $produk['jml_stok'];
				$data['produk'] = $produk['nama_produk'];
				$data['pid']    = $order['idproduk'];
				$data['jml']	   = $order['jml'];
				$data['option'] = array($order['ukuran'], $order['warna']);

				// stok produk optionnya nya dibalikin dulu
				$proses[] = $this->dataModel->updateStokOptionBertambah($nopesan, $order['jml'], $order['warna'], $order['ukuran'], $order['idproduk']);


				// stok nya dibalikin dulu
				$proses[] = $this->dataModel->updateStokBertambah($order['jml'], $order['idproduk']);

				$this->addCart($data);
			} // end foreach bongkar

			$keranjang = $this->showminiCart($_SESSION['hscart']);
			$totalitem   = $keranjang['items'];
			$cart 		= $keranjang['carts'];
			$subtotal 	= 0;
			$i      	= 0;
			$totberat 	= 0;
			$totjumlah	= 0;

			$this->data['iddetail'] 	= (int) $this->Fungsi->fIdAkhir('_order_detail', 'iddetail') + 1;

			//looping cartnya
			foreach ($cart as $c) {
				$this->data['pid']   	= $c['product_id'];
				$this->data['jml'] 	 	= $c['qty'];
				$this->data['berat'] 	= $c['berat'];
				$this->data['harga'] 	= $c['harga'];
				$this->data['total'] 	= $c['total'];

				$this->data['idwarna']  = $_SESSION['wrncart'][$this->data['pid']][$i];
				$this->data['idukuran'] = $_SESSION['ukrcart'][$this->data['pid']][$i];

				if ($this->data['idwarna'] != '') $warna	= $this->Fungsi->fcaridata('_warna', 'warna', 'idwarna', $this->data['idwarna']);
				else $warna = '';

				if ($this->data['idukuran'] != '') $ukuran = $this->Fungsi->fcaridata('_ukuran', 'ukuran', 'idukuran', $this->data['idukuran']);
				else $ukuran = '';


				$nama_produk 			= $c['product'];
				$where 					= "idproduk='" . $this->data['pid'] . "'";
				$prods					= $this->Fungsi->fcaridata2('_produk', 'hrg_jual,hrg_beli', $where);
				$this->data['satuan']	= $prods[0];
				$this->data['hrgbeli']	= $prods[1];


				$this->data['ukuranlama'] = $this->data['idukuran'];
				$this->data['warnalama'] = $this->data['idwarna'];

				$this->data['nopesan'] = $nopesan;
				$this->data['nopesanan'] = $nopesan;
				if ($zukuran == $this->data['idukuran'] && $zwarna == $this->data['idwarna'] && $idproduk == $this->data['pid']) {
					if (!$this->dataModel->SimpanOrderDetail($this->data)) {
						$proses[] = $this->dataModel->SimpanOrderDetail($this->data);
						$pesans[] = 'error simpan order detail';
					}
					if (!$this->dataModel->SimpanOrderDetailOption($this->data)) {
						$proses[] = $this->dataModel->SimpanOrderDetailOption($this->data);
						$pesan[] = 'error simpan detail option';
					}
				} else {
					if (!$this->dataModel->updateOrderProduk($this->data)) {
						$proses[] = $this->dataModel->updateOrderProduk($this->data);
						$pesans[] = 'error simpan detail';
					}
				}

				if ($this->data['idwarna'] != '' || $this->data['idwarna'] != '0' || $this->data['idukuran'] != '' || $this->data['idukuran'] != '0') {

					if (!$this->dataModel->UpdateStokOption($this->data)) {
						$proses[] = $this->dataModel->UpdateStokOption($this->data);
						$pesans[] = 'error update stok option';
					}
				}

				if (!$this->dataModel->UpdateStok($this->data)) {
					$proses[] = $this->dataModel->UpdateStok($this->data);
					$pesan[] = 'error update stok';
				}

				$subtotal	+= $this->data['total'];
				$totberat   += $this->data['berat'];
				$totjumlah  += (int) $c['qty'];

				$this->data['iddetail']++;
				$i++;
			} // end foreach looping

			$this->data['subtotal'] = $subtotal;
			$this->data['totjumlah'] = $totjumlah;

			$tarifkurir = $this->dataShipping->getTarif($servisid, $negara, $propinsi, $kota, $kecamatan, $totberat, $minkilo, $tabeltarif, $detekkdpos, $namashipping);

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
			$tarifkurir[1] = $tarifkurir[1] - ($tarifkurir[1] * $zdiskon);
			$tarifkurir[4] = $tarifkurir[4] - ($tarifkurir[4] * $zdiskon);

			if ($tarifkurir[1] > 0) {
				$this->data['tarifkurir'] = $tarifkurir[1];
				$this->data['satuantarifkurir'] = $tarifkurir[4];
			} else {
				$this->data['tarifkurir'] = $zhrgkurir;
				$this->data['satuantarifkurir'] = 0;
			}
			//$this->data['tarifkurir'] = $tarifkurir[1];
			//$this->data['satuantarifkurir'] = $tarifkurir[4];
			$this->data['kurir'] = $namashipping;
			$this->data['kurirservis'] = $servisid;
			$this->data['nopesanan'] = $nopesan;
			if (!$this->dataModel->UpdateOrder($this->data)) {
				$proses[] = $this->dataModel->UpdateOrder($this->data);
				$pesans[] = 'error simpan order';
			}

			if (count($pesans) == 0) {
				$pesan = 'sukses|Berhasil';
				$this->dataModel->prosesTransaksi($proses);
			} else {
				$pesan = 'gagal|Gagal Proses Tambah Produk';
			}
		}
		return $pesan;
	}
	public function editKurir()
	{
		$dkurir = isset($_POST['dkurir']) ? $_POST['dkurir'] : '';
		$hrgkurir = isset($_POST['hrgkurir']) ? $_POST['hrgkurir'] : 0;
		$nopesan = isset($_POST['nopesan']) ? $_POST['nopesan'] : 0;
		$negara = isset($_POST['nnegaraid']) ? $_POST['nnegaraid'] : '';
		$propinsi = isset($_POST['npropid']) ? $_POST['npropid'] : '';
		$kota = isset($_POST['nkotaid']) ? $_POST['nkotaid'] : '';
		$kecamatan = isset($_POST['nkecid']) ? $_POST['nkecid'] : '';
		$totberat = isset($_POST['ntotberat']) ? $_POST['ntotberat'] : 0;
		$hasil = '';
		$proses = array();
		$zdata		= explode(":", $dkurir);
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
		// echo '$tabeltarif ='.$tabeltarif.' ';
		$tarifkurir = $this->dataShipping->getTarif($servisid, $negara, $propinsi, $kota, $kecamatan, $totberat, $minkilo, $tabeltarif, $detekkdpos, $namashipping);
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
		//echo '$tarifkurir[1] = '.$tarifkurir[1].' ';
		$tarifkurir[1] = $tarifkurir[1] - ($tarifkurir[1] * $zdiskon);
		$tarifkurir[4] = $tarifkurir[4] - ($tarifkurir[4] * $zdiskon);
		//echo 'tarif kurir 1 ='.$tarifkurir[1].' & tarif kurir 2 ='.$tarifkurir[2];
		if ($tarifkurir[1] > 0) {
			$this->data['tarifkurir'] = $tarifkurir[1];
			$this->data['satuantarifkurir'] = $tarifkurir[4];
		} else {
			$this->data['tarifkurir'] = $hrgkurir;
			$this->data['satuantarifkurir'] = 0;
		}

		$this->data['kurir'] = $namashipping;
		$this->data['kurirservis'] = $servisid;
		$this->data['nopesanan'] = $nopesan;

		if (!$this->dataModel->simpanEditKurir($this->data)) {
			$hasil = 'gagal|Gagal Mengubah Tarif Kurir';
		} else {
			$this->dataModel->prosesTransaksi($proses);
			$hasil = 'sukses|Berhasil Mengubah Tarif Kurir';
		}

		return $hasil;
	}

	public function keranjangInfo($hcart)
	{

		if ($hcart) {

			$html = '	<div class="col-md-12">
							<a href="' . URL_PROGRAM . 'cart" class="btn btn-outline-danger btn-block"><i class="fa fa-shopping-basket" aria-hidden="true"></i> Beli</a>
						</div>';
			foreach ($hcart as $cart) {
				$html .=   '<div class="col-md-12 list-cart-header">
								<div class="row">
									<div class="col-4">
										<img class="img-fluid mx-auto" src="' . URL_IMAGE . '_thumb/thumbs_gproduk' . $cart['image_produk'] . '">
									</div>
									<div class="col-8 text-right">
										<h3 class="produk-cart-header">' . $cart['product'] . '</h3>
										<small class="atribut-cart-head"> Warna : ' . $cart['warna_nama'] . ', Ukuran : ' . $cart['ukuran_nama'] . '</small><br>
										<small class="atribut-cart-head"><b>' . $cart['qty'] . ' x Rp. ' . $this->Fungsi->fuang($cart['harga']) . '</b></small>
									</div>
								</div>
							</div>';
			}

			$html .= '<br>';
		} else {
			$html .= '<p class="text-center">Tidak Ada Pesanan</p>';
		}

		echo $html;
	}

	public function keranjangKasir()
	{
		if (isset($_SESSION['usermember'])) {
			$nama 		= isset($_POST['rnama']) ? $_POST['rnama'] : '';
			$telp			= isset($_POST['rtelp']) ? $_POST['rtelp'] : '';
			$alamat 		= isset($_POST['ralamat']) ? $_POST['ralamat'] : '';
			$negara		= isset($_POST['rnegara']) ? $_POST['rnegara'] : '0';
			$propinsi		= isset($_POST['rpropinsi']) ? $_POST['rpropinsi'] : '0';
			$kabupaten	= isset($_POST['rkabupaten']) ? $_POST['rkabupaten'] : '0';
			$kecamatan	= isset($_POST['rkecamatan']) ? $_POST['rkecamatan'] : '0';
			$kelurahan	= isset($_POST['rkelurahan']) ? $_POST['rkelurahan'] : '';
			$kodepos		= isset($_POST['rkdpos']) ? $_POST['rkdpos'] : '';

			if ($nama == '') $pesan[] = 'Masukkan Nama';
			if ($alamat == '') $pesan[] = 'Masukkan Alamat';
			if ($negara == '' || $negara == '0') $pesan[] = 'Pilih Negara';
			if ($propinsi == '' || $propinsi == '0') $pesan[] = 'Pilih Propinsi';
			if ($kabupaten == '' || $kabupaten == '0') $pesan[] = 'Pilih Kabupaten';
			if ($kecamatan == '' || $kecamatan == '0') $pesan[] = 'Pilih Kecamatan';

			if (count($pesan) > 0) {
				if (count($pesan) == 11) {
					$hasil = 'gagal|Lengkapi data alamat tujuan dibawah ini';
				} else {
					$hasil = implode("<br>", $pesan);
					$hasil = 'gagal|' . $hasil;
				}
			} else {
				$_SESSION['checkout']		= 'ya';
				$_SESSION['frmnama'] 		= $nama;
				$_SESSION['frmtelp'] 		= $telp;
				$_SESSION['frmalamat'] 	= $alamat;
				$_SESSION['frmnegara']   	= $negara;
				$_SESSION['frmpropinsi']	= $propinsi;
				$_SESSION['frmkabupaten']	= $kabupaten;
				$_SESSION['frmkecamatan']	= $kecamatan;
				$_SESSION['frmkelurahan']	= $kelurahan;
				$_SESSION['frmkodepos']		= $kodepos;
				$hasil = 'sukses|' . URL_PROGRAM . 'cart/metode';
			}
		} else {
			$hasil = 'gagal|Anda belum login';
		}
		return $hasil;
	}

	public function metodePengiriman()
	{
		$hasil = '';
		if ($_SESSION['checkout'] == 'ya' && isset($_SESSION['usermember'])) {
			$servis = isset($_POST['serviskurir']) ? $_POST['serviskurir'] : '';
			$poin = isset($_POST['poinku']) ? $_POST['poinku'] : '';
			$idmember = $_SESSION['idmember'];

			if ($servis == '') {
				$hasil = 'gagal|Pilih Servis Kurir';
			} elseif ($poin != '' && $poin != '0') {
				$checkpoin = $this->dataModel->checkPoin($idmember, $poin);
				if ($checkpoin < 1) {
					$hasil = 'gagal|Anda tidak memiliki poin';
				}
			} else {
				$_SESSION['konfirm'] = 'ya';
				$_SESSION['frmservis'] = $servis;
				$_SESSION['poin'] = $poin;
				$hasil = 'sukses';
			}
		} else {
			$hasil = 'gagal|Anda belum login';
		}
		return $hasil;
	}
}
