<?php

class modelOrder

{

	private $db;

	private $tabelnya;

	private $user;



	function __construct()

	{

		$this->db 		= new Database();

		$this->db->connect();

		$this->user = isset($_SESSION["idlogin"]) ? $_SESSION["idlogin"] : '';
	}



	function cekOrder($noorder)

	{

		$str = $this->db->query("select count(*) as total from _order WHERE pesanan_no = '" . $this->db->escape($noorder) . "'");

		$jml = isset($str->row['total']) ? $str->row['total'] : 0;



		if ($jml > 0) return true;

		else return false;
	}





	function checkPoinHistory($data, $jenis)

	{

		$poin = -1;

		$str = $this->db->query("SELECT cph_poin FROM _customer_point_history WHERE cph_tipe='" . $jenis . "' AND cph_cust_id='" . $data['pelangganid'] . "' AND cph_order='" . $data['nopesanan'] . "'");



		return isset($str->row['cph_poin']) ? $str->row['cph_poin'] : 0;
	}



	function insertGetPoin($data, $jenis)

	{

		$sql = "INSERT INTO _customer_point_history SET cph_cust_id='" . $data['pelangganid'] . "',cph_poin = '" . $data['totpoin'] . "',cph_tipe='$jenis',cph_tgl='" . $data['tgl'] . "',cph_order='" . $data['nopesanan'] . "'";

		return $sql;
	}

	function updateGetPoin($data, $jenis)

	{

		$sql = "UPDATE _customer_point_history SET cph_poin='" . $data['totpoin'] . "' WHERE cph_cust_id='" . $data['pelangganid'] . "' AND cph_tipe='$jenis' AND cph_order='" . $data['nopesanan'] . "'";

		return $sql;
	}

	function deleteGetPoin($data, $jenis)

	{

		$sql = "DELETE from _customer_point_history WHERE cph_cust_id='" . $data['pelangganid'] . "' AND cph_tipe='$jenis' AND cph_order='" . $data['nopesanan'] . "'";

		return $sql;
	}

	function updatePoinPelanggan($data)

	{

		$cekpoin = $this->db->query("SELECT count(cp_cust_id) FROM _customer_point WHERE cp_cust_id='" . $data['pelangganid'] . "'");

		$rs = mysql_fetch_row($cekpoin);

		if ($rs[0] > 0) {

			$sql = "UPDATE _customer_point SET cp_poin=cp_poin+" . $data['totpoin'] . ",cp_tglupdate='" . $data['tgl'] . "' WHERE cp_cust_id='" . $data['pelangganid'] . "'";
		} else {

			$sql = "INSERT into _customer_point SET cp_cust_id='" . $data['pelangganid'] . "',cp_poin=" . $data['totpoin'] . ",cp_tglupdate='" . $data['tgl'] . "'";
		}

		return $sql;
	}

	function simpanStatusOrder($data)

	{



		$sql = "INSERT INTO _order_status set nopesanan='" . $data['nopesanan'] . "',tanggal='" . $data['tgl'] . "',status_id='" . $data['orderstatus'] . "',

	                     keterangan='" . $data['keterangan'] . "'";

		return $sql;
	}

	function updateOrderStatus($data)

	{

		$sql = "UPDATE _order set status_id='" . $data['orderstatus'] . "',tgl_kirim  = '" . $data['tglkirim'] . "',no_awb='" . $data['noawb'] . "' WHERE pesanan_no='" . $data['nopesanan'] . "'";

		return $sql;
	}

	function editHrgOrderDetail($hrg, $iddetail, $jenis)

	{

		if ($jenis == 'jual') {

			$sql = $this->db->query("update _order_detail set harga='" . $hrg . "' WHERE iddetail='" . $iddetail . "'");
		} else {

			$sql = $this->db->query("update _order_detail set hrg_beli='" . $hrg . "' WHERE iddetail='" . $iddetail . "'");

			//echo "update _order_detail set hrg_beli='".$hrg."' WHERE iddetail='".$iddetail."'";

		}

		/*

		if($sql) return true;

		else return false;

		*/

		return $sql;
	}

	function editSubtotalOrder($hrg, $nopesan)

	{

		$sql = $this->db->query("update _order set pesanan_subtotal='" . $hrg . "' WHERE pesanan_no='" . $nopesan . "'");

		/*

		if($sql) return true;

		else return false;

		*/

		return $sql;
	}

	function editPenambahanKekuranganOrder($jmltambah, $jmlkurang, $nopesan, $ststransaksi)

	{

		$sql = $this->db->query("update _order set pesanan_penambahan='" . $jmltambah . "',

		                    pesanan_kekurangan='" . $jmlkurang . "',transaksi_close='" . $ststransaksi . "' WHERE pesanan_no='" . $nopesan . "'");

		/*

		if($sql) return true;

		else return false; */

		return $sql;
	}

	function getOrder($batas, $baris, $data)

	{



		$where = '';

		$filter = array();

		$sortir = 'pesanan_tgl-desc';

		if ($data['caridata'] != '') {

			$filter[] = " (_order.pesanan_no ='" . trim($this->db->escape($data['caridata'])) . "' 

							OR cust_nama like'" . trim($this->db->escape($data['caridata'])) . "%' 

							OR cust_telp like '" . trim($this->db->escape($data['caridata'])) . "%' 

							OR nama_penerima like '" . trim($this->db->escape($data['caridata'])) . "%')";
		}

		if ($data['status'] != '' && $data['status'] != '0') $filter[] = " _order.status_id = '" . trim(strip_tags(urlencode($data['status']))) . "'";

		if (!empty($filter))	$where = implode(" AND ", $filter);


		if(!empty($data['sortir'])) $sortir = $data['sortir'];
		$sortir = str_replace('-', ' ', $sortir);


		if ($where != '') $where = " where " . $where;


		// update - aar - select ip address - 10-06-2020
		$sql = "select idpesanan,_order.pesanan_no,

				cust_nama,nama_penerima,pesanan_jml as jml,

				pesanan_subtotal as subtotal,

				pesanan_tgl as tgl,

				status_nama as status,_order.status_id,

				pesanan_kurir,dari_poin,login_username, _order.iporder as iporder

				from _order 

				inner join _status_order on _order.status_id = _status_order.status_id 

				inner join _order_penerima on _order.pesanan_no = _order_penerima.pesanan_no

				inner join _customer on _order.pelanggan_id = _customer.cust_id

				left join _login on _order.input_by = _login.login_id " . $where . " order by " . $sortir . " limit $batas,$baris";



		$strsql = $this->db->query($sql);

		if ($strsql) {

			$hasil = [];

			foreach ($strsql->rows as $rs) {

				$hasil[] = $rs;
			}

			return $hasil;
		}

		return false;
	}



	public function totalOrder($data)

	{

		$where = '';

		$filter = array();



		if ($data['caridata'] != '') $filter[] = " (_order.pesanan_no ='" . trim(strip_tags($this->db->escape($data['caridata']))) . "' OR cust_nama like'" . trim(strip_tags($this->db->escape($data['caridata']))) . "%' OR cust_telp like '" . trim(strip_tags($this->db->escape($data['caridata']))) . "%')";

		if ($data['status'] != '' && $data['status'] != '0') $filter[] = " _order.status_id = '" . trim(strip_tags(urlencode($data['status']))) . "'";

		if (!empty($filter))	$where = implode(" AND ", $filter);



		if ($where != '') $where = " where " . $where;

		$query = '';



		$strsql = $this->db->query("select count(*) as total 

								  from _order 

								  INNER JOIN _status_order ON _order.status_id = _status_order.status_id 

								  inner join _order_penerima on _order.pesanan_no = _order_penerima.pesanan_no

								  INNER JOIN _customer ON _order.pelanggan_id = _customer.cust_id " . $query . $where);



		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}



	function getOrderByID($noorder)

	{



		$sql = "select o.pesanan_no,o.pelanggan_id,c.cust_nama,cg.cg_nm as grup_cust,cg.cg_deposito,

				o.pesanan_jml,o.pesanan_subtotal,o.pesanan_kurir,o.kurir_perkilo,o.pesanan_tgl,

				o.status_id,so.status_nama,o.kurir,sp.shipping_nama,

				o.servis_kurir,s.servis_code,o.grup_member,

				o.dari_poin,o.potongan_kupon,o.kode_kupon,o.tgl_kirim,

				o.no_awb,o.dari_deposito,o.dropship,o.kurir_konfirm,o.keterangan,o.tampil_label_keterangan,

				n.nama_penerima,n.hp_penerima,

				n.alamat_penerima,ngn.negara_nama as negaranm_penerima,n.negara_penerima,

				prn.provinsi_nama as propinsinm_penerima,n.propinsi_penerima,kbn.kabupaten_nama as kotanm_penerima,n.kota_penerima,kcn.kecamatan_nama as kecamatannm_penerima,n.kecamatan_penerima,

				n.kelurahan_penerima, n.kodepos_penerima,

				p.nama_pengirim,p.hp_pengirim,

				p.alamat_pengirim,ngp.negara_nama as negaranm_pengirim,prp.provinsi_nama as propinsinm_pengirim,p.propinsi_pengirim,kbp.kabupaten_nama as kotanm_pengirim,p.kota_pengirim,kcp.kecamatan_nama as kecamatannm_pengirim,kecamatan_pengirim,

				p.kelurahan_pengirim,p.kodepos_pengirim,shipping_kode,shipping_kdrajaongkir,servis_code, o.no_awb as no_awb,

				shipping_rajaongkir,shipping_konfirmadmin

				from _order o

				left join _status_order so on o.status_id = so.status_id

				left join _customer c on o.pelanggan_id = c.cust_id

				LEFT join _customer_grup cg on o.grup_member = cg.cg_id

				left join _servis s on o.servis_kurir = s.servis_id

				left join _shipping sp on o.kurir = sp.shipping_kode

				left join _order_penerima n ON o.pesanan_no = n.pesanan_no

				left JOIN _negara ngn ON n.negara_penerima = ngn.negara_id

				left JOIN _provinsi prn ON n.propinsi_penerima = prn.provinsi_id

				left JOIN _kabupaten kbn ON n.kota_penerima = kbn.kabupaten_id

				left JOIN _kecamatan kcn ON n.kecamatan_penerima = kcn.kecamatan_id

				left JOIN _order_pengirim p ON o.pesanan_no = p.pesanan_no

				left JOIN _negara ngp ON p.negara_pengirim = ngp.negara_id

				left JOIN _provinsi prp ON p.propinsi_pengirim = prp.provinsi_id

				left JOIN _kabupaten kbp ON p.kota_pengirim = kbp.kabupaten_id

				left JOIN _kecamatan kcp ON p.kecamatan_pengirim = kcp.kecamatan_id

				where o.pesanan_no = '" . $this->db->escape($noorder) . "'";



		$strsql = $this->db->query($sql);

		return isset($strsql->row) ? $strsql->row : false;
	}



	function getOrderAlamat($noorder)

	{

		$strsql = $this->db->query("SELECT 

							 n.pesanan_no, n.nama_penerima,n.telp_penerima,n.hp_penerima,

							 n.alamat_penerima,ngn.negara_nama,prn.provinsi_nama,kbn.kabupaten_nama,kcn.kecamatan_nama,

							 n.kelurahan_penerima, n.kodepos_penerima,

							 p.nama_pengirim,p.telp_pengirim,p.hp_pengirim,

							 p.alamat_pengirim,ngp.negara_nama,prp.provinsi_nama,kbp.kabupaten_nama,kcp.kecamatan_nama,

							 p.kelurahan_pengirim,p.kodepos_pengirim,

							 n.negara_penerima,n.propinsi_penerima,

							 n.kota_penerima,n.kecamatan_penerima,n.kodepos_penerima

							 FROM _order_penerima n

							 INNER JOIN _negara ngn ON n.negara_penerima = ngn.negara_id

							 INNER JOIN _provinsi prn ON n.propinsi_penerima = prn.provinsi_id

							 INNER JOIN _kabupaten kbn ON n.kota_penerima = kbn.kabupaten_id

							 INNER JOIN _kecamatan kcn ON n.kecamatan_penerima = kcn.kecamatan_id

							 INNER JOIN _order_pengirim p ON n.pesanan_no = p.pesanan_no

							 INNER JOIN _negara ngp ON p.negara_pengirim = ngp.negara_id

							 INNER JOIN _provinsi prp ON p.propinsi_pengirim = prp.provinsi_id

							 INNER JOIN _kabupaten kbp ON p.kota_pengirim = kbp.kabupaten_id

							 INNER JOIN _kecamatan kcp ON p.kecamatan_pengirim = kcp.kecamatan_id

							 WHERE n.pesanan_no='" . $this->db->escape($noorder) . "'");

		return isset($strsql->row) ? $strsql->row : false;
	}



	function getOrderDetail($noorder)

	{

		$sql = "SELECT _order_detail.pesanan_no,

				_order_detail.produk_id,

				_produk_deskripsi.nama_produk,

				_order_detail.jml,

				_order_detail.harga,

				_order_detail.warnaid,w.warna,

				_order_detail.ukuranid,u.ukuran,

				_order_detail.berat,

				_order_detail.satuan,

				_order_detail.iddetail,poin,

				_order_detail.harga_tambahan,

				_order_detail.get_poin

				FROM _order_detail 

				left join _warna w on _order_detail.warnaid = w.idwarna

				left join _ukuran u on _order_detail.ukuranid = u.idukuran

				left join _produk_deskripsi ON _order_detail.produk_id = _produk_deskripsi.idproduk

				left join _produk ON _order_detail.produk_id = _produk.idproduk

				WHERE pesanan_no = '" . $this->db->escape($noorder) . "'";

		$strsql = $this->db->query($sql);



		if ($strsql) {

			$tabels = array();

			foreach ($strsql->rows as $rs) {

				$tabels[] = $rs;
			}

			return $tabels;
		}

		return false;
	}



	function getOrderStatus($noorder)

	{

		$sql = "SELECT tanggal,status_nama,keterangan,idostatus,admin_id,login_nama,cust_nama,_order_status.status_id

				FROM _order_status 

				INNER JOIN _status_order ON

				 _order_status.status_id = _status_order.status_id

				left join _login on

				_order_status.admin_id = _login.login_id

				left join _customer on

				_order_status.admin_id = _customer.cust_id

				WHERE nopesanan = '" . $this->db->escape($noorder) . "' 

				ORDER by tanggal desc";

		$strsql = $this->db->query($sql);



		if ($strsql) {

			$tabels = array();

			foreach ($strsql->rows as $rs) {

				$tabels[] = $rs;
			}

			return $tabels;
		}

		return false;
	}

	function getOrderPoin($noorder, $customer)

	{

		$data = 0;

		$sql = "SELECT cph_poin FROM _customer_point_history WHERE cph_order='" . $noorder . "' AND cph_cust_id='" . $customer . "' AND cph_tipe='IN'";

		$strsql = $this->db->query($sql);

		return isset($strsql->row['cph_poin']) ? $strsql->row['cph_poin'] : 0;
	}

	function getOrderKonfirmasi($noorder)

	{

		$sql = "SELECT id_konfirm,order_pesan,jml_bayar,

		        bank_rek_tujuan,bank_dari,bank_rek_dari,

				bank_atasnama_dari,tgl_transfer,status_bayar,buktitransfer

				FROM _order_konfirmasi_bayar WHERE order_pesan = '" . $this->db->escape($noorder) . "'";



		$strsql = $this->db->query($sql);

		return isset($strsql->row) ? $strsql->row : false;
	}





	function getQytOrder($noorder)

	{

		$sql = "SELECT _order_detail.pesanan_no,

				iddetail,jml,

				warnaid,ukuranid,produk_id,status_id,

				dari_deposito,dari_poin,pelanggan_id

				FROM _order_detail

				left join _order on _order_detail.pesanan_no = _order.pesanan_no

				WHERE _order.pesanan_no='" . $this->db->escape($noorder) . "'";

		$strsql = $this->db->query($sql);

		if ($strsql) {

			$tabels = array();

			foreach ($strsql->rows as $rs) {

				$tabels[] = $rs;
			}

			return $tabels;
		}

		return false;
	}



	function getServisbyId($tabel, $id)

	{

		$strsql = $this->db->query("select * from $tabel WHERE ids='" . $id . "'");



		return isset($strsql->row) ? $strsql->row : false;
	}

	function getTarif($servis, $frmnegara, $frmpropinsi, $frmkabupaten, $frmkecamatan, $totberat, $minkilo)

	{

		//$tarif = array();

		$totberat = (int) $totberat / 1000;

		if ($totberat < 1) $totberat = ceil($totberat);

		if ($minkilo > $totberat) $totberat = $minkilo;

		if ($totberat > 1) {

			$berat = floor($totberat);

			$jarakkoma = $totberat - $berat;

			if ($jarakkoma > 0.4) $totberat = ceil($totberat);

			else $totberat = floor($totberat);
		} else {

			$jarakkoma = 0;
		}

		$strsql = $this->db->query("select * from _tarif_jne WHERE servis_id='" . $servis . "' AND negara_id='" . $frmnegara . "' AND

	                          provinsi_id='" . $frmpropinsi . "' AND kabupaten_id='" . $frmkabupaten . "' AND kecamatan_id='" . $frmkecamatan . "'");





		$rs = mysql_fetch_row($strsql);

		$tarif = array($rs[0], ((int) $totberat) * (int) $rs[6], $rs[7], (int) $totberat, (int) $rs[6], $jarakkoma);





		return $tarif;
	}



	function generateInvoice($data)

	{

		$sql = $this->db->query("update _order set no_invoice='" . $data['invoice'] . "' WHERE pesanan_no='" . $data['noorder'] . "'");

		if ($sql) return true;

		else return false;
	}



	function hapusOrder($data)

	{



		$sql = "DELETE _order_penerima,

		                      _order_pengirim,

							  _order, 

							  _order_detail,_order_status FROM 

							  _order 

							  LEFT JOIN _order_penerima ON _order.pesanan_no = _order_penerima.pesanan_no

							  LEFT JOIN _order_pengirim ON _order.pesanan_no = _order_pengirim.pesanan_no 

							  LEFT JOIN _order_detail ON _order.pesanan_no = _order_detail.pesanan_no 

							  LEFT JOIN _order_status ON _order.pesanan_no = _order_status.nopesanan

							  WHERE _order.pesanan_no = '" . $data . "'";

		return $sql;
	}



	function hapusOrderDetailOption($data)

	{



		$sql = "DELETE FROM _order_detail_option WHERE iddetail = '" . $data . "'";

		return $sql;
	}

	function updateStokOption($nopesan, $jml, $warna, $ukuran, $produk)

	{

		$sql = "UPDATE _produk_options set stok = stok+$jml WHERE idproduk='" . $produk . "' AND ukuran='" . $ukuran . "' AND warna='" . $warna . "'";

		return $sql;
	}



	public function UpdateStokOptionberKurang($data)

	{

		$sql = "update _produk_options set stok=stok - " . $data['jml'] . " WHERE idproduk = '" . $data['pid'] . "' AND ukuran='" . $data['idukuran'] . "' AND warna = '" . $data['idwarna'] . "'";



		return $sql;
	}



	function updateStok($jml, $produk)

	{

		$sql = "UPDATE _produk set jml_stok = jml_stok+$jml WHERE idproduk='" . $produk . "'";

		return $sql;
	}



	public function UpdateStokberKurang($data)

	{

		$sql = "update _produk set jml_stok=jml_stok - " . $data['jml'] . " WHERE idproduk = '" . $data['pid'] . "'";

		/* if($sql) return true;

	    else return false; */

		return $sql;
	}





	function getOrderEksekusi($masa_belanja, $order_status, $tgl)

	{



		$sql = "SELECT idpesanan,pesanan_no,pesanan_tgl FROM _order WHERE status_id='" . $order_status . "' AND (pesanan_tgl + INTERVAL $masa_belanja DAY) < '$tgl'";

		$strsql = $this->db->query($sql);

		if ($strsql) {

			$data = array();

			foreach ($strsql->rows as $rsa) {

				$data[] = $rsa['pesanan_no'];
			}

			return $data;
		}

		return false;
	}



	function getProdukOrderOptionByIDdetail($iddetail, $nopesan)

	{

		$sql = "SELECT _order_detail.iddetail,pesanan_no,produk_id,jml,harga,satuan,berat,hrg_beli,warnaid,ukuranid

               FROM _order_detail LEFT JOIN _order_detail_option 

			   ON _order_detail.iddetail = _order_detail_option.iddetail

			   WHERE _order_detail.iddetail <> '" . $iddetail . "' AND pesanan_no='$nopesan' ORDER BY _order_detail.iddetail ASC";



		$strsql = $this->db->query($sql);

		foreach ($strsql->rows as $rsa) {

			$arkab[] = array(

				'iddetail' => $rsa['iddetail'],

				'nopesanan' => $rsa['pesanan_no'],

				'idproduk' => $rsa['produk_id'],

				'jml' => $rsa['jml'],

				'warna' => $rsa['warnaid'],

				'ukuran' => $rsa['ukuranid']

			);
		}

		return $arkab;
	}



	function getProdukOrderOption($idproduk, $idwarna, $idukuran, $nopesan)

	{

		$arkab = array();

		$w = '';

		if ($idukuran != '' && $idukuran != '0') {

			$w .= " AND ukuranid='" . $idukuran . "'";
		}

		if ($idwarna != '' && $idwarna != '0') {

			$w .= " AND warnaid='" . $idwarna . "'";
		}

		if ($idproduk != '') {

			$w .= " AND produk_id='" . $idproduk . "'";
		}

		$sql = "SELECT _order_detail.iddetail,pesanan_no,

			   produk_id,jml,harga,satuan,berat,

			   warnaid,ukuranid

               FROM _order_detail 

			   WHERE pesanan_no='" . $nopesan . "' $w 

			   ORDER BY _order_detail.iddetail ASC";



		$strsql = $this->db->query($sql);

		foreach ($strsql->rows as $rsa) {

			$arkab[] = array(

				'iddetail' => $rsa['iddetail'],

				'nopesanan' => $rsa['pesanan_no'],

				'idproduk' => $rsa['produk_id'],

				'jml' => $rsa['jml'],

				'warna' => $rsa['warnaid'],

				'ukuran' => $rsa['ukuranid']

			);
		}

		return $arkab;
	}





	public function simpanEditKurir($data)

	{

		$error = array();

		$status = '';

		$idorder = '';

		$this->db->autocommit(false);

		$lock  = "LOCK TABLES _order WRITE, _customer_deposito_history WRITE";

		$this->db->query($lock);

		$sql = "UPDATE _order set pesanan_kurir = '" . $data['tarifkurir'] . "',

				kurir='" . $data['kurir'] . "',servis_kurir='" . $data['serviskurir'] . "',

				dari_deposito='" . $data['jmldeposit'] . "',

				kurir_perkilo='" . $data['kurir_perkilo'] . "',

				kurir_konfirm='" . $data['konfirm_admin'] . "'

				WHERE pesanan_no='" . $data['nopesanan'] . "'";

		$stre = $this->db->query($sql);

		if (!$stre) {

			$error[] = 'Error di update _order';
		}

		if ($data['jmldeposit'] > 0) {



			$sql = "select cdh_cust_id from _customer_deposito_history 

					where cdh_cust_id='" . $data['pelanggan_id'] . "' and cdh_tipe='OUT' and cdh_order='" . $data['nopesanan'] . "'";

			$strsql = $this->db->query($sql);

			$jmldata = $strsql->num_rows;



			if ($jmldata > 0) {



				$sql = "update _customer_deposito_history 

						set cdh_deposito='" . $data['jmldeposit'] . "' 

						where cdh_cust_id='" . $data['pelanggan_id'] . "' and cdh_tipe='OUT' and cdh_order='" . $data['nopesanan'] . "'";



				$strsql = $this->db->query($sql);



				if (!$strsql) {

					$error[] = 'Error di update _customer_deposito_history';
				}
			} else {

				$sql = "insert into _customer_deposito_history 

						values (null,'" . $data['pelanggan_id'] . "',

						'" . $data['jmldeposit'] . "','OUT','" . date('Y-m-d H:i:s') . "','" . $data['nopesanan'] . "','" . $data['keterangan'] . "')";

				$strsql = $this->db->query($sql);



				if (!$strsql) {

					$error[] = 'Error di insert _customer_deposito_history';
				}
			}
		}

		/* end pembatalan Order */

		if (count($error) > 0) {

			$this->db->rollback();

			$status = "error";
		} else {

			$this->db->commit();

			$status = "success";
		}

		$this->db->query("UNLOCK TABLES");

		return array("status" => $status);
	}



	public function getOrderNolKurir()

	{

		$tabels = array();

		$sql = "select pesanan_no,cust_nama,pesanan_jml,pesanan_subtotal,pesanan_kurir,pesanan_infaq,pesanan_tgl from _order INNER JOIN _status_order ON _order.status_id = _status_order.status_id INNER JOIN _reseller ON _order.pelanggan_id = _reseller.cust_id where pesanan_kurir = 0 AND kurir <> '' AND servis_kurir <> '' AND servis_kurir > 0 ";

		$strsql = $this->db->query($sql);

		foreach ($strsql->rows as $rs) {

			$tabels[] = array(

				'noorder' 		=> $rs[0],

				'reseller'		=> $rs[1],

				'jml'   	        => $rs[2],

				'subtotal'		=> $rs[3],

				'kurir'			=> $rs[4],

				'infaq'   		=> $rs[5],

				'tgl'   		    => $rs[6]

			);
		}

		return $tabels;
	}



	public function getOrderPending($status)

	{

		$tabels = array();

		$sql = "select pesanan_no,cust_nama,pesanan_jml,pesanan_subtotal,pesanan_kurir,

	           pesanan_infaq,pesanan_tgl from _order INNER JOIN _status_order ON _order.status_id = _status_order.status_id 

			   NNER JOIN _customer ON _order.pelanggan_id = _customer.cust_id where status_id='" . $status . "'";

		$strsql = $this->db->query($sql);

		foreach ($strsql->rows as $rs) {

			$tabels[] = array(

				'noorder' 		=> $rs[0],

				'reseller'		=> $rs[1],

				'jml'   	        => $rs[2],

				'subtotal'		=> $rs[3],

				'kurir'			=> $rs[4],

				'infaq'   		=> $rs[5],

				'tgl'   		    => $rs[6]

			);
		}

		return $tabels;
	}

	public function getTotalOrderPending($status)

	{

		$sql = "SELECT count(pesanan_no) FROM _order WHERE status='" . $status . "'";

		$str = $this->db->query($sql);

		$rs  = mysql_fetch_row($sql);

		return $rs[0];
	}



	public function simpaneditstatus($data)

	{

		$error = array();

		$status = '';

		$this->db->autocommit(false);



		/* simpan ke table order status */

		if ($data['simpanstatusorder']) {

			$sql = "insert into _order_status values

					(null,'" . $data['nopesanan'] . "','" . $data['tgl'] . "',

					'" . $data['orderstatus'] . "',

					'" . $this->db->escape($data['keterangan']) . "',

					'" . $this->user . "')";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = 'Error di table _order_status';
		} elseif ($data['updatestatusorder']) {



			$sql = "update _order_status 

					set keterangan='" . $this->db->escape($data['keterangan']) . "'

					where idostatus='" . $data['idstatushistory'] . "'";



			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = 'Error di table _order_status';
		}



		/* simpan ke table _order_konfirmasi_bayar */

		if ($data['konfirmasiorder'] == 'add') {



			$sql = "insert into _order_konfirmasi_bayar values 

					(null,'" . $data['nopesanan'] . "',

					'" . $data['jmlbayar'] . "','" . $data['bankto'] . "',

					'" . $data['namabankdari'] . "','" . $data['rekbankdari'] . "',

					'" . $data['atasnamabankdari'] . "','" . $data['tglbayar'] . "',

					'" . $data['orderstatus'] . "','" . $data['tgl'] . "','" . $data['ipdata'] . "','')";



			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = 'Error di table _order_konfirmasi_bayar';
		} elseif ($data['konfirmasiorder'] == 'edit') {



			$sql = "update _order_konfirmasi_bayar set

					jml_bayar='" . $data['jmlbayar'] . "',

					bank_rek_tujuan='" . $data['bankto'] . "',

					bank_dari='" . $data['namabankdari'] . "',

					bank_rek_dari='" . $data['rekbankdari'] . "',

					bank_atasnama_dari='" . $data['atasnamabankdari'] . "',

					tgl_transfer='" . $data['tglbayar'] . "',

					status_bayar='" . $data['orderstatus'] . "'

					WHERE order_pesan='" . $data['nopesanan'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = 'Error di table _order_konfirmasi_bayar';
		}





		/* simpan ke tabel _order */

		$sql = "UPDATE _order set 

				status_id='" . $data['orderstatus'] . "',

				tgl_kirim  = '" . $data['tglkirim'] . "',

				no_awb='" . $data['noawb'] . "' 

				WHERE pesanan_no='" . $data['nopesanan'] . "'";



		$strsql = $this->db->query($sql);

		if (!$strsql) $error[] = 'Error di table _order';



		/* simpan ke table _customer_point_history */

		/*

		if($data['simpangetpoin'] == 'update'){

			

			$sql = "update _customer_point_history 

					SET cph_poin='".$data['totpoindapat']."' 

					WHERE cph_cust_id='".$data['pelangganid']."' 

					AND cph_tipe='IN' AND cph_order='".$data['nopesanan']."'";

					

		} elseif ($data['simpangetpoin'] == 'insert'){

			

			$sql = "INSERT INTO _customer_point_history 

					SET cph_cust_id='".$data['pelangganid']."',

					cph_poin = '".$data['totpoindapat']."',

					cph_tipe='IN',cph_tgl='".$data['tgl']."',

					cph_order='".$data['nopesanan']."'";

			

		}

		

		*/

		if ($data['totpoindapat'] > 0 && $data['getpoins'] === true) {

			$sql = "delete from _customer_point_history 

					where cph_tipe='IN' and cph_order='" . $data['nopesanan'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = 'Error di table _customer_point_history';



			$sql = "INSERT INTO _customer_point_history 

					SET cph_cust_id='" . $data['pelangganid'] . "',

					cph_poin = '" . $data['totpoindapat'] . "',

					cph_tipe='IN',cph_tgl='" . $data['tgl'] . "',

					cph_order='" . $data['nopesanan'] . "',

					cph_keterangan=''";



			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = 'Error di table _customer_point_history';
		}





		/* simpan _customer_point */

		/*

		if($data['customerpoin'] == 'update'){

			

			$sql = "UPDATE _customer_point 

					SET cp_poin=cp_poin+".$data['totpoindapat'].",

					cp_tglupdate='".$data['tgl']."' 

					WHERE cp_cust_id='".$data['pelangganid']."'";

					

		} elseif ($data['customerpoin'] == 'insert'){

			

			$sql = "INSERT into _customer_point values 

					(null,'".$data['pelangganid']."','".$data['totpoindapat']."',

					cp_tglupdate='".$data['tgl']."')";

			

		}

			

		$strsql = $this->db->query($sql);

		if(!$strsql) $error[] = 'Error di table _customer_point';

		*/



		if (count($error) > 0) {

			$this->db->rollback();

			$status = 'error';
		} else {

			$this->db->commit();

			$status = 'success';
		}

		return array("status" => $status);
	}



	public function checkCustomerPoin($id)

	{

		$cekpoin = $this->db->query("SELECT count(cp_cust_id) as total FROM _customer_point WHERE cp_cust_id='" . $id . "'");

		return isset($cekpoin->row['total']) ? $cekpoin->row['total'] : 0;
	}



	function simpanEditAlamat($data)

	{

		$error = array();

		$status = '';

		$this->db->autocommit(false);

		/* update order alamat pengirim */

		if ($data['jenis_alamat'] == 'alamatpengirim') {

			$sql = "update _order_pengirim set

					nama_pengirim='" . $this->db->escape($data['nama_pengirim']) . "',

					hp_pengirim='" . $this->db->escape($data['hp_pengirim']) . "',

					alamat_pengirim='" . $this->db->escape($data['alamat_pengirim']) . "',

					propinsi_pengirim='" . $data['propinsi_pengirim'] . "',

					kota_pengirim='" . $data['kabupaten_pengirim'] . "',

					kecamatan_pengirim='" . $data['kecamatan_pengirim'] . "',

					kelurahan_pengirim='" . $this->db->escape($data['kelurahan_pengirim']) . "',

					kodepos_pengirim='" . $data['kodepos_pengirim'] . "'

					where pesanan_no='" . $data['nopesanan'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _order_pengirim";
		} elseif ($data['jenis_alamat'] == 'alamatpenerima') {



			$sql = "update _order_penerima set

					nama_penerima='" . $this->db->escape($data['nama_penerima']) . "',

					hp_penerima='" . $this->db->escape($data['hp_penerima']) . "',

					alamat_penerima='" . $this->db->escape($data['alamat_penerima']) . "',

					propinsi_penerima='" . $data['propinsi_penerima'] . "',

					kota_penerima='" . $data['kabupaten_penerima'] . "',

					kecamatan_penerima='" . $data['kecamatan_penerima'] . "',

					kelurahan_penerima='" . $data['kelurahan_penerima'] . "',

					kodepos_penerima='" . $data['kodepos_penerima'] . "' 

					where pesanan_no='" . $data['nopesanan'] . "'";



			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _order_penerima";
		}



		$sql = "update _order set dropship = '" . $data['dropship'] . "',

				pesanan_kurir='" . $data['tarifkurir'] . "',

				kurir_perkilo='" . $data['hrgkurir_perkilo'] . "',

				dari_poin='" . $data['dari_poin'] . "'

				where pesanan_no='" . $data['nopesanan'] . "'";

		$strsql = $this->db->query($sql);

		if (!$strsql) $error[] = "Error di table _order";



		if ($data['savetoaddress'] == '1') {

			/* menyimpan data alamat ke table _customer_address */

			$sql = "insert into _customer_address values (null,

					'" . $data['idmember'] . "',

					'" . $this->db->escape($data['add_nama']) . "',

					'" . $this->db->escape($data['add_alamat']) . "','33',

					'" . $data['add_propinsi'] . "',

					'" . $data['add_kabupaten'] . "',

					'" . $data['add_kecamatan'] . "',

					'" . $this->db->escape($data['add_kelurahan']) . "',

					'" . $data['add_kodepos'] . "',

					'" . $data['add_telp'] . "','0')";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _customer_address";
		}

		if ($data['sisa_dari_poin'] > 0) {

			$sql = "update _customer_point_history

					set cph_poin=" . $data['dari_poin'] . "

					where cph_cust_id='" . $data['idmember'] . "'

					and cph_tipe='OUT' and cph_order='" . $data['nopesanan'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _customer_point_history";
		}

		$totpoin = $data['sisa_dari_poin'];

		if ($totpoin > 0) {

			$sql = "update _customer_point

					set cp_poin=cp_poin+" . $totpoin . ",

					where cp_cust_id='" . $data['idmember'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _customer_point";
		}



		if ($data['dari_deposito'] > 0) {



			$sql = "select cdh_deposito from _customer_deposito_history 

					where cdh_tipe='OUT' and cdh_cust_id='" . $data['idmember'] . "' and cdh_order='" . $data['nopesanan'] . "'";



			$strsql = $this->db->query($sql);

			$jmldata = $strsql->num_rows;



			if ($jmldata > 0) {



				$sql = "update _customer_deposito_history 

						set cdh_deposito='" . $data['dari_deposito'] . "' 

						where cdh_cust_id='" . $data['idmember'] . "' and cdh_tipe='OUT' and cdh_order='" . $data['nopesanan'] . "'";



				$strsql = $this->db->query($sql);



				if (!$strsql) {

					$error[] = 'Error di update _customer_deposito_history';
				}
			} else {

				$sql = "insert into _customer_deposito_history 

						values (null,'" . $data['idmember'] . "',

						'" . $data['dari_deposito'] . "','OUT','" . date('Y-m-d H:i:s') . "','" . $data['nopesanan'] . "','" . $data['keterangan'] . "')";

				$strsql = $this->db->query($sql);



				if (!$strsql) {

					$error[] = 'Error di insert _customer_deposito_history';
				}
			}
		}



		if (count($error) > 0) {

			$this->db->rollback();

			$status = "error";
		} else {

			$this->db->commit();

			$status = "success";
		}

		return array("status" => $status);
	}

	function simpanAddAlamat($data)

	{

		/* menyimpan data alamat ke table _customer_address */

		$sql = "insert into _customer_address values (null,

				'" . $data['idmember'] . "',

				'" . $this->db->escape($data['add_nama']) . "',

				'" . $this->db->escape($data['add_alamat']) . "','33',

				'" . $data['add_propinsi'] . "',

				'" . $data['add_kabupaten'] . "',

				'" . $data['add_kecamatan'] . "',

				'" . $this->db->escape($data['add_kelurahan']) . "',

				'" . $data['add_kodepos'] . "',

				'" . $data['add_telp'] . "','0')";

		$strsql = $this->db->query($sql);

		if (!$strsql) {

			$status = 'error';
		} else {

			$status = 'success';
		}

		return array("status" => $status);
	}

	function simpanaddprodukorder($data)

	{

		$error = array();

		$status = '';

		$tglupdate = date('Y-m-d H:i:s');

		$this->db->autocommit(false);



		$lock  = "LOCK TABLES _order WRITE, _produk_options WRITE, _produk WRITE,_order_detail WRITE,_customer_point WRITE, _customer_point_history WRITE,_customer_deposito_history WRITE";

		$this->db->query($lock);



		/* update order */

		$sql = "update _order 

				set pesanan_jml='" . $data['totjumlah'] . "',

				pesanan_subtotal='" . $data['subtotal'] . "',

				pesanan_kurir='" . $data['tarifkurir'] . "',

				kurir_perkilo='" . $data['hrgkurir_perkilo'] . "',

				dari_poin='" . $data['dari_poin'] . "',

				dari_deposito='" . $data['potdeposito'] . "',

				kurir_konfirm='" . $data['kurir_konfirm'] . "'

				where pesanan_no='" . $data['nopesanan'] . "'";

		$strsql = $this->db->query($sql);

		if (!$strsql) $error[] = "Error di table _order";



		if (isset($data['updatestokoptionberkurang'])) {

			$sql = "update _produk_options 

					set stok = stok - " . $data['updatestokoptionberkurang']['qty'] . "

					where idproduk='" . $data['updatestokoptionberkurang']['idproduk'] . "' and

					ukuran='" . $data['updatestokoptionberkurang']['idukuran'] . "' and

					warna='" . $data['updatestokoptionberkurang']['idwarna'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _produk_options, update kurang";
		}



		if (isset($data['updatestokberkurang'])) {

			$sql = "update _produk	set 

					jml_stok = jml_stok-" . $data['updatestokberkurang']['qty'] . "

					where idproduk='" . $data['updatestokberkurang']['idproduk'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _produk, update kurang";
		}



		if (isset($data['updateorderprodukoption'])) {

			$insertorder = '';

			$i = 0;



			foreach ($data['updateorderprodukoption'] as $option) {

				if ($option['persen_diskon_satuan'] > 0) {

					$sale = '1';
				} else {

					$sale = '0';
				}



				if ($i == 0) {

					$insertorder = "insert into _order_detail values (null,

								   '" . $option['nopesanan'] . "',

								   '" . $option['idproduk'] . "','" . $option['qty'] . "',

								   '" . $option['harga'] . "','" . $option['satuan'] . "','" . $option['berat'] . "',

								   '" . $sale . "','" . $option['persen_diskon_satuan'] . "',

								   '" . $option['idwarna'] . "','" . $option['idukuran'] . "',

								   '" . $option['get_poin'] . "','" . $option['harga_tambahan'] . "')";

					$strsql = $this->db->query($insertorder);

					if (!$strsql) $error[] = "Error di table _order_detail";
				} else {





					$sql = "update _order_detail set

							jml='" . $option['qty'] . "',

							harga='" . $option['harga'] . "',

							satuan='" . $option['satuan'] . "',

							berat='" . $option['berat'] . "',

							persen_diskon_satuan='" . $option['persen_diskon_satuan'] . "',

							produk_sale='" . $sale . "',

							get_poin='" . $option['get_poin'] . "',

							harga_tambahan='" . $option['harga_tambahan'] . "'

							where 

							warnaid='" . $option['idwarna'] . "'

							and ukuranid='" . $option['idukuran'] . "'

							and pesanan_no='" . $option['nopesanan'] . "'

							and produk_id='" . $option['idproduk'] . "'";



					$strsql = $this->db->query($sql);

					if (!$strsql) {

						$error[] = "Error di table _order_detail";
					}
				}

				$i++;
			}





			//if(!$strsql) $error[] = "Error di table _order_detail";





		}



		if (isset($data['updateorderproduk'])) {

			$sqlinsert = '';

			foreach ($data['updateorderproduk'] as $options) {

				if (

					$option['idproduk'] != $data['addprodukorder']['idproduk'] &&

					$option['idwarna'] != $data['addprodukorder']['idwarna'] &&

					$option['idukuran'] != $data['addprodukorder']['idukuran']

				) {



					$sql = "update _order_detail 

								set jumlah='" . $options['qty'] . "',

								harga='" . $options['harga'] . "',

								satuan='" . $options['satuan'] . "',

								berat='" . $options['berat'] . "',

								persen_diskon_satuan='" . $options['persen_diskon_satuan'] . "',

								produk_sale='" . $sale . "',

								get_poin='" . $option['get_poin'] . "'

								where pesanan_no='" . $options['nopesanan'] . "' and produk_id='" . $options['idproduk'] . "'";

					$strsql = $this->db->query($sql);

					if (!$strsql) $error[] = "Error di table _order_detail";
				} else {



					$sqlinsert = "insert into _order_detail values (null,

							   '" . $option['nopesanan'] . "',

							   '" . $option['idproduk'] . "','" . $option['qty'] . "',

							   '" . $option['harga'] . "','" . $option['satuan'] . "','" . $option['berat'] . "',

							   '" . $sale . "','" . $option['persen_diskon_satuan'] . "',

							   '" . $option['idwarna'] . "','" . $option['idukuran'] . "',

							   '" . $option['get_poin'] . "')";
				}
			}

			$strsql = $this->db->query($sqlinsert);

			if (!$strsql) $error[] = "Error di table _order_detail";
		}

		if ($data['sisa_dari_poin'] > 0) {

			$sql = "update _customer_point_history

					set cph_poin=" . $data['dari_poin'] . "

					where cph_cust_id='" . $data['idmember'] . "'

					and cph_tipe='OUT' and cph_order='" . $data['nopesanan'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _customer_point_history";
		}

		$totpoin = $data['totgetpoin'] + $data['sisa_dari_poin'];

		if ($totpoin > 0) {

			$sql = "update _customer_point

					set cp_poin=cp_poin+" . $totpoin . ",

					cp_tglupdate='" . $tglupdate . "'

					where cp_cust_id='" . $data['idmember'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _customer_point";
		}



		if ($data['potdeposito'] > 0) {



			$sql = "select cdh_cust_id from _customer_deposito_history 

					where cdh_cust_id='" . $data['idmember'] . "' and cdh_tipe='OUT' and cdh_order='" . $data['nopesanan'] . "'";



			$strsql = $this->db->query($sql);

			$jmldata = $strsql->num_rows;



			if ($jmldata > 0) {



				$sql = "update _customer_deposito_history

						set cdh_deposito='" . $data['potdeposito'] . "'

						where cdh_tipe='OUT' 

						and cdh_order='" . $data['nopesanan'] . "' 

						and cdh_cust_id='" . $data['idmember'] . "'";

				$strsql = $this->db->query($sql);

				if (!$strsql) $error[] = "Error di table _customer_deposito_history";
			} else {



				$sql = "insert into _customer_deposito_history 

						values (null,'" . $data['idmember'] . "',

						'" . $data['potdeposito'] . "','OUT','" . date('Y-m-d H:i:s') . "',

						'" . $data['nopesanan'] . "','" . $data['keterangan'] . "')";

				$strsql = $this->db->query($sql);



				if (!$strsql) {

					$error[] = 'Error di insert _customer_deposito_history';
				}
			}

			/*

			$sql = "update _customer_deposito

					set cd_deposito = cd_deposito+".$data['sisa_dari_potdeposito'].",

					cd_tglupdate='".$tglupdate."'

					where cd_cust_id='".$data['idmember']."'";

			$strsql = $this->db->query($sql);

			if(!$strsql) $error[] = "Error di table _customer_deposito";

			*/
		}



		if (count($error) > 0) {

			$this->db->rollback();

			$status = "error";
		} else {

			$this->db->commit();

			$status = "success";
		}

		$this->db->query("UNLOCK TABLES");

		return array("status" => $status);
	}



	function simpaneditprodukorder($data)

	{

		$error = array();

		$status = '';

		$tglupdate = date('Y-m-d H:i:s');

		$this->db->autocommit(false);



		$lock  = "LOCK TABLES _order WRITE, _produk_options WRITE, _produk WRITE, _order_detail WRITE,  _order_penerima WRITE,";

		$lock .= "_order_pengirim WRITE, _order_status WRITE, _customer_point_history WRITE, _customer_deposito_history WRITE";

		$this->db->query($lock);



		/* update order */

		$sql = "update _order 

				set pesanan_jml='" . $data['totjumlah'] . "',

				pesanan_subtotal='" . $data['subtotal'] . "',

				pesanan_kurir='" . $data['tarifkurir'] . "',

				kurir_perkilo='" . $data['hrgkurir_perkilo'] . "',

				dari_poin='" . $data['dari_poin'] . "',

				dari_deposito='" . $data['potdeposito'] . "',

				kurir_konfirm='" . $data['kurir_konfirm'] . "'

				where pesanan_no='" . $data['nopesanan'] . "'";

		$strsql = $this->db->query($sql);

		if (!$strsql) $error[] = "Error di table _order";



		/* simpan perubahan order_detail */

		if (isset($data['updatestokoptionberkurang'])) {

			$sql = "update _produk_options 

					set stok = stok - " . $data['updatestokoptionberkurang']['qty'] . "

					where idproduk='" . $data['updatestokoptionberkurang']['idproduk'] . "' and

					ukuran='" . $data['updatestokoptionberkurang']['idukuran'] . "' and

					warna='" . $data['updatestokoptionberkurang']['idwarna'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _produk_options, update kurang";
		}



		if (isset($data['updatestokberkurang'])) {

			$sql = "update _produk	set 

					jml_stok = jml_stok-" . $data['updatestokberkurang']['qty'] . "

					where idproduk='" . $data['updatestokberkurang']['idproduk'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _produk, update kurang";
		}



		if (isset($data['updatestokoptionbertambah'])) {

			$sql = "update _produk_options set

					stok = stok+" . $data['updatestokoptionbertambah']['qty'] . "

					where idproduk='" . $data['updatestokoptionbertambah']['idproduk'] . "' and

					ukuran='" . $data['updatestokoptionbertambah']['idukuran'] . "' and

					warna='" . $data['updatestokoptionbertambah']['idwarna'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _produk_options, update bertambah";
		}



		if (isset($data['updatestokbertambah'])) {

			$sql = "update _produk set 

					jml_stok=jml_stok+" . $data['updatestokbertambah']['qty'] . "

					where idproduk='" . $data['updatestokbertambah']['idproduk'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _produk, update bertambah";
		}



		if (isset($data['updateorderprodukoption'])) {



			foreach ($data['updateorderprodukoption'] as $option) {

				if ($option['persen_diskon_satuan'] > 0) {

					$sale = '1';
				} else {

					$sale = '0';
				}

				$sql = "update _order_detail set

						jml='" . $option['qty'] . "',

						harga='" . $option['harga'] . "',

						satuan='" . $option['satuan'] . "',

						berat='" . $option['berat'] . "',

						persen_diskon_satuan='" . $option['persen_diskon_satuan'] . "',

						produk_sale='" . $sale . "',

						harga_tambahan='" . $option['harga_tambahan'] . "',

						get_poin='" . $option['get_poin'] . "'

						where 

						warnaid='" . $option['idwarna'] . "'

						and ukuranid='" . $option['idukuran'] . "'

						and pesanan_no='" . $option['nopesanan'] . "'

						and produk_id='" . $option['idproduk'] . "'";

				$strsql = $this->db->query($sql);

				if (!$strsql) $error[] = "Error di table _order_detail";
			}
		}

		if (isset($data['updateorderproduk'])) {

			foreach ($data['updateorderproduk'] as $options) {

				$sql = "update _order_detail 

						set jumlah='" . $options['qty'] . "',

						harga='" . $options['harga'] . "',

						satuan='" . $options['satuan'] . "',

						berat='" . $options['berat'] . "',

						persen_diskon_satuan='" . $options['persen_diskon_satuan'] . "',

						produk_sale='" . $sale . "',

						get_poin='" . $options['get_poin'] . "'

						where pesanan_no='" . $options['nopesanan'] . "' and produk_id='" . $options['idproduk'] . "'";



				$strsql = $this->db->query($sql);

				if (!$strsql) $error[] = "Error di table _order_detail";
			}
		}





		if ($data['sisa_dari_poin'] > 0) {

			$sql = "update _customer_point_history

					set cph_poin=" . $data['dari_poin'] . "

					where cph_cust_id='" . $data['idmember'] . "'

					and cph_tipe='OUT' and cph_order='" . $data['nopesanan'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _customer_point_history";
		}

		/*

		$totpoin = $data['totgetpoin'] + $data['sisa_dari_poin'];

		if($totpoin > 0){

			$sql = "update _customer_point

					set cp_poin=cp_poin+".$totpoin.",

					cp_tglupdate='".$tglupdate."'

					where cp_cust_id='".$data['idmember']."'";

			$strsql = $this->db->query($sql);

			if(!$strsql) $error[] = "Error di table _customer_point";

		}

		*/

		/*

		if($data['sisa_dari_potdeposito'] > 0){

			$sql = "update _customer_deposito_history

					set cdh_deposito='".$data['potdeposito']."',

					where cdh_tipe='OUT' 

					and cdh_order='".$data['nopesanan']."' 

					and cdh_cust_id='".$data['idmember']."'";

			$strsql = $this->db->query($sql);

			if(!$strsql) $error[] = "Error di table _customer_deposito_history";

			

			$sql = "update _customer_deposito

					set cd_deposito = cd_deposito+".$data['sisa_dari_potdeposito'].",

					cd_tglupdate='".$tglupdate."'

					where cd_cust_id='".$data['idmember']."'";

			$strsql = $this->db->query($sql);

			if(!$strsql) $error[] = "Error di table _customer_deposito";

		}

		*/

		if ($data['potdeposito'] > 0) {



			$sql = "select cdh_cust_id from _customer_deposito_history 

					where cdh_cust_id='" . $data['idmember'] . "' and cdh_tipe='OUT' and cdh_order='" . $data['nopesanan'] . "'";



			$strsql = $this->db->query($sql);

			$jmldata = $strsql->num_rows;



			if ($jmldata > 0) {



				$sql = "update _customer_deposito_history

						set cdh_deposito='" . $data['potdeposito'] . "'

						where cdh_tipe='OUT' 

						and cdh_order='" . $data['nopesanan'] . "' 

						and cdh_cust_id='" . $data['idmember'] . "'";

				$strsql = $this->db->query($sql);

				if (!$strsql) $error[] = "Error di table _customer_deposito_history";
			} else {



				$sql = "insert into _customer_deposito_history 

						values (null,'" . $data['idmember'] . "',

						'" . $data['potdeposito'] . "','OUT','" . date('Y-m-d H:i:s') . "',

						'" . $data['nopesanan'] . "','" . $data['keterangan'] . "')";

				$strsql = $this->db->query($sql);



				if (!$strsql) {

					$error[] = 'Error di insert _customer_deposito_history';
				}
			}

			/*

			$sql = "update _customer_deposito

					set cd_deposito = cd_deposito+".$data['sisa_dari_potdeposito'].",

					cd_tglupdate='".$tglupdate."'

					where cd_cust_id='".$data['idmember']."'";

			$strsql = $this->db->query($sql);

			if(!$strsql) $error[] = "Error di table _customer_deposito";

			*/
		}

		/*

		$sql = "update _order set

				pesanan_jml='".$data['totjumlah']."',

				pesanan_subtotal='".$data['subtotal']."',

				pesanan_kurir='".$data['tarifkurir']."',

				kurir_perkilo='".$data['hrgkurir_perkilo']."',

				dari_poin='".$data['dari_poin']."',

				dari_deposito='".$data['potdeposito']."'

				where pesanan_no='".$data['nopesanan']."'";

	

		$strsql = $this->db->query($sql);

		if(!$strsql) $error[] = "Error di table _order";		

		*/

		if (count($error) > 0) {

			$this->db->rollback();

			$status = "error";
		} else {

			$this->db->commit();

			$status = "success";
		}

		$this->db->query("UNLOCK TABLES");

		return array("status" => $status);
	}

	function deleteProdukOrder($data)

	{

		$error = array();

		$status = '';

		$tglupdate = date('Y-m-d H:i:s');

		$this->db->autocommit(false);

		$lock  = "LOCK TABLES _order WRITE, _order_detail WRITE, _produk_options WRITE, _produk WRITE, _order_penerima WRITE,";

		$lock .= "_order_pengirim WRITE, _order_status WRITE, _customer_point_history WRITE, _customer_deposito_history WRITE";



		if (isset($data['stokoptionbertambah'])) {

			$sql = "update _produk_options set

					stok = stok+" . $data['stokoptionbertambah']['qty'] . "

					where idproduk='" . $data['stokoptionbertambah']['idproduk'] . "' and

					ukuran='" . $data['stokoptionbertambah']['idukuran'] . "' and

					warna='" . $data['stokoptionbertambah']['idwarna'] . "'";



			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _produk_options, update bertambah";
		}



		if (isset($data['updatestokbertambah'])) {

			$sql = "update _produk set 

					jml_stok=jml_stok+" . $data['updatestokbertambah']['qty'] . "

					where idproduk='" . $data['updatestokbertambah']['idproduk'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _produk, update bertambah";
		}



		if (isset($data['hapusprodukorder'])) {

			$sql = "delete from _order_detail where iddetail='" . $data['hapusprodukorder'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _orde_detail, hapus";
		}

		/*** */ //

		if (isset($data['orderprodukoption'])) {

			foreach ($data['orderprodukoption'] as $option) {

				if ($option['persen_diskon_satuan'] > 0) {

					$sale = '1';
				} else {

					$sale = '0';
				}

				$sql = "update _order_detail set

						jml='" . $option['qty'] . "',

						harga='" . $option['harga'] . "',

						satuan='" . $option['satuan'] . "',

						berat='" . $option['berat'] . "',

						persen_diskon_satuan='" . $option['persen_diskon_satuan'] . "',

						produk_sale='" . $sale . "'

						where 

						warnaid='" . $option['idwarna'] . "'

						and ukuranid='" . $option['idukuran'] . "'

						and pesanan_no='" . $option['nopesanan'] . "'

						and produk_id='" . $option['idproduk'] . "'";

				$strsql = $this->db->query($sql);

				if (!$strsql) $error[] = "Error di table _order_detail";
			}
		}



		if (isset($data['orderproduk'])) {

			foreach ($data['orderproduk'] as $options) {

				$sql = "update _order_detail 

						set jml='" . $options['qty'] . "',

						harga='" . $options['harga'] . "',

						satuan='" . $options['satuan'] . "',

						berat='" . $options['berat'] . "',

						persen_diskon_satuan='" . $options['persen_diskon_satuan'] . "',

						produk_sale='" . $sale . "'

						where pesanan_no='" . $options['nopesanan'] . "' and produk_id='" . $options['idproduk'] . "'";



				$strsql = $this->db->query($sql);

				if (!$strsql) $error[] = "Error di table _order_detail";
			}
		}



		if ($data['sisa_dari_poin'] > 0) {

			$sql = "update _customer_point_history

					set cph_poin=" . $data['dari_poin'] . "

					where cph_cust_id='" . $data['idmember'] . "'

					and cph_tipe='OUT' and cph_order='" . $data['nopesanan'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _customer_point_history";
		}

		$totpoin = $data['totgetpoin'] + $data['sisa_dari_poin'];

		if ($totpoin > 0) {

			$sql = "update _customer_point

					set cp_poin=cp_poin+" . $totpoin . ",

					cp_tglupdate='" . $tglupdate . "'

					where cp_cust_id='" . $data['idmember'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _customer_point";
		}





		if ($data['jmlproduk'] > 1) {

			if ($data['potdeposito'] > 0) {



				$sql = "select cdh_cust_id from _customer_deposito_history 

					where cdh_cust_id='" . $data['idmember'] . "' and cdh_tipe='OUT' and cdh_order='" . $data['nopesanan'] . "'";



				$strsql = $this->db->query($sql);

				$jmldata = $strsql->num_rows;



				if ($jmldata > 0) {



					$sql = "update _customer_deposito_history

							set cdh_deposito='" . $data['potdeposito'] . "'

							where cdh_tipe='OUT' 

							and cdh_order='" . $data['nopesanan'] . "' 

							and cdh_cust_id='" . $data['idmember'] . "'";

					$strsql = $this->db->query($sql);

					if (!$strsql) $error[] = "Error di table _customer_deposito_history";
				} else {



					$sql = "insert into _customer_deposito_history 

							values (null,'" . $data['idmember'] . "',

							'" . $data['potdeposito'] . "','OUT','" . date('Y-m-d H:i:s') . "',

							'" . $data['nopesanan'] . "','" . $data['keterangan'] . "')";

					$strsql = $this->db->query($sql);



					if (!$strsql) {

						$error[] = 'Error di insert _customer_deposito_history';
					}
				}
			}

			$sql = "update _order set

					pesanan_jml='" . $data['totjumlah'] . "',

					pesanan_subtotal='" . $data['subtotal'] . "',

					pesanan_kurir='" . $data['tarifkurir'] . "',

					kurir_perkilo='" . $data['hrgkurir_perkilo'] . "',

					dari_poin='" . $data['dari_poin'] . "',

					dari_deposito='" . $data['potdeposito'] . "'

					where pesanan_no='" . $data['nopesanan'] . "'";



			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _order";



			$delall = '0';
		} else {

			/*

			$sql = "delete _order_penerima,

						  _order_pengirim,

						  _order, 

						  _order_detail,_order_status FROM 

						  _order  

				LEFT JOIN _order_penerima ON _order.pesanan_no = _order_penerima.pesanan_no

				LEFT JOIN _order_pengirim ON _order.pesanan_no = _order_pengirim.pesanan_no 

				LEFT JOIN _order_detail ON _order.pesanan_no = _order_detail.pesanan_no 

				LEFT JOIN _order_status ON _order.pesanan_no = _order_status.nopesanan

				WHERE _order.pesanan_no = '".$data['nopesanan']."'";

			*/

			$sql = "update _order set status_id='" . $data['status_cancel'] . "',

					pesanan_jml='" . $data['totjumlah'] . "',

					pesanan_subtotal='" . $data['subtotal'] . "',

					pesanan_kurir='" . $data['tarifkurir'] . "',

					kurir_perkilo='" . $data['hrgkurir_perkilo'] . "',

					dari_poin='" . $data['dari_poin'] . "',

					dari_deposito='" . $data['dari_deposito'] . "'

					where pesanan_no = '" . $data['nopesanan'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table hapus _order";



			if ($data['potdeposito'] > 0) {

				$sql = "insert into _customer_deposito_history 

						values (null,'" . $data['idmember'] . "',

						'" . $data['potdeposito'] . "','IN','" . date('Y-m-d H:i:s') . "',

						'" . $data['nopesanan'] . "','" . $data['keterangan_cancel'] . "')";

				$strsql = $this->db->query($sql);



				if (!$strsql) {

					$error[] = 'Error di insert _customer_deposito_history';
				}
			}



			$delall = '1';
		}



		if (count($error) > 0) {

			$this->db->rollback();

			$status = "error";
		} else {

			$this->db->commit();

			$status = "success";
		}

		$this->db->query("UNLOCK TABLES");

		return array("status" => $status, "delall" => $delall);
	}

	public function JumlahOrder($pesanan_no, $produk_id, $warna_id, $ukuran_id)

	{



		$sql = "SELECT count(pesanan_no) as total 

			   FROM _order_detail 

			   WHERE pesanan_no = '" . $pesanan_no . "' AND produk_id='" . $produk_id . "' AND warnaid='" . $warna_id . "' 

			   AND ukuranid='" . $ukuran_id . "'";

		$str = $this->db->query($sql);

		return isset($str->row['total']) ? $str->row['total'] : 0;
	}



	public function simpanneworder($data)

	{

		$error = array();

		$status = '';

		$idorder = '';

		$this->db->autocommit(false);



		$lock  = "LOCK TABLES _order WRITE, _order_detail WRITE, _produk_options WRITE, _produk WRITE, _order_penerima WRITE,";

		$lock .= "_order_pengirim WRITE, _order_status WRITE, _customer_point_history WRITE, _customer_deposito_history WRITE";

		$this->db->query($lock);

		$resi = isset($data['noresi'])?$data['noresi']:'';

		/* simpan ke table _order */

		$sql = "insert into _order 

				values 

				(null,'" . $data['nopesanan'] . "','" . $data['cust_id'] . "',

				 '" . $data['totjumlah'] . "','" . $data['subtotal'] . "',

				 '" . $data['tarifkurir'] . "','" . $data['tgltransaksi'] . "','0',

				 '" . $data['status_order'] . "','" . $data['shipping'] . "',

				 '" . $data['servis_id'] . "','-','" . $data['hrgkurir_perkilo'] . "',

				 '" . $data['ipaddress'] . "','" . $data['cust_grup'] . "','" . $data['poin'] . "',

				 '" . $data['potongan_kupon'] . "','" . $data['kode_kupon'] . "','" . $data['tglkirim'] . "',

				 '" . $data['nomor_awb'] . "','" . $data['potdeposito'] . "','" . $data['dropship'] . "',

				 '" . $data['kurir_konfirm'] . "','" . $this->user . "','".$resi."','0')";



		$strsql = $this->db->query($sql);

		if (!$strsql) {

			$error[] = "Error di table _order";
		} else {

			$idorder = $this->db->lastid();
		}



		/* 

			simpan ke table _order_detail 

			menyimpan detail order

		*/

		if (isset($data['orderdetail'])) {

			$valueorderdetail = [];

			foreach ($data['orderdetail'] as $orderdetail) {

				$valueorderdetail[] = "(null,'" . $data['nopesanan'] . "',

									  '" . $orderdetail['product_id'] . "',

									  '" . $orderdetail['qty'] . "',

									  '" . $orderdetail['harga'] . "',

									  '" . $orderdetail['hrgsatuan'] . "',

									  '" . $orderdetail['berat'] . "',

									  '" . $orderdetail['sale'] . "',

									  '" . $orderdetail['persen_diskon_satuan'] . "',

									  '" . $orderdetail['warna'] . "',

									  '" . $orderdetail['ukuran'] . "',

									  '" . $orderdetail['get_poin'] . "',

									  '" . $orderdetail['hrgtambahan'] . "')";
			}

			if (count($valueorderdetail) > 0) {

				$dataorder = implode(",", $valueorderdetail);
			}

			$sql = "insert into _order_detail values " . $dataorder;

			$strsql = $this->db->query($sql);

			if (!$strsql) {

				$error[] = "Error di table _order_detail";
			}
		}



		/* 

			simpan ke table _produk_options 

			mengurangi stok ukuran warna

		

		*/

		if (isset($data['updatestokoption'])) {

			$this->db->beginTransaction();

			foreach ($data['updatestokoption'] as $options) {

				// Check stock
				$sql = "select stok

						from _produk_options

						where idproduk='" . $options['product_id'] . "'

						and ukuran='" . $options['idukuran'] . "'

						and warna='" . $options['idwarna'] . "' FOR UPDATE";

				$strsql = $this->db->query($sql);

				if (!$strsql) {

					$error[] = "Error di table _produk_options";
				}else{
					$stock = $strsql->row['stok'];
					if($stock - $options['qty'] < 0){
						$this->db->rollback();
						$_err = "Stok produk tersebut hanya tersedia " . $stock . " pcs";
						return array('status' => 'error', 'stock' => $_err);
					}
				}

				$sql = "update _produk_options

						set stok = stok - " . $options['qty'] . "

						where idproduk='" . $options['product_id'] . "'

						and ukuran='" . $options['idukuran'] . "'

						and warna='" . $options['idwarna'] . "'";

				$strsql = $this->db->query($sql);

				if (!$strsql) {

					$error[] = "Error di table _produk_options";
				}
			}
		}



		/* simpan ke table _produk */

		if (isset($data['updatestok'])) {

			foreach ($data['updatestok'] as $stok) {

				// Check stock
				$sql = "select jml_stok

						from _produk

						where idproduk='" . $stok['product_id'] . "' FOR UPDATE";

				$strsql = $this->db->query($sql);

				if (!$strsql) {

					$error[] = "Error di table _produk_options";
				}else{
					$stock = $strsql->row['jml_stok'];
					if($stock - $stok['qty'] < 0){
						$this->db->rollback();
						$_err = "Stok produk tersebut hanya tersedia " . $stock . " pcs";
						return array('status' => 'error', 'stock' => $_err);
					}
				}

				$sql = "update _produk

						set jml_stok = jml_stok - " . $stok['qty'] . "

						where idproduk='" . $stok['product_id'] . "'";

				$strsql = $this->db->query($sql);

				if (!$strsql) {

					$error[] = "Error di table _produk";
				}
			}
		}



		/* simpan order penerima */

		$sql = "insert into _order_penerima values 

				('" . $data['nopesanan'] . "','" . $this->db->escape($data['nama_penerima']) . "',

				 '" . $data['telp_penerima'] . "',

				 '" . $this->db->escape($data['alamat_penerima']) . "','33',

				 '" . $data['propinsi_penerima'] . "','" . $data['kabupaten_penerima'] . "',

				 '" . $data['kecamatan_penerima'] . "','" . $this->db->escape($data['kelurahan_penerima']) . "',

				 '" . $data['kodepos_penerima'] . "')";



		$strsql = $this->db->query($sql);

		if (!$strsql) {

			$error[] = "Error di table _order_penerima";
		}



		/* simpan order pengirim */

		$sql = "insert into _order_pengirim values

				('" . $data['nopesanan'] . "','" . $this->db->escape($data['nama_pengirim']) . "',

				 '" . $data['telp_pengirim'] . "','" . $this->db->escape($data['alamat_pengirim']) . "','33',

				 '" . $data['propinsi_pengirim'] . "',

				 '" . $data['kabupaten_pengirim'] . "',

				 '" . $this->db->escape($data['kecamatan_pengirim']) . "',

				 '" . $this->db->escape($data['kelurahan_pengirim']) . "',

				 '" . $data['kodepos_pengirim'] . "')";

		$strsql = $this->db->query($sql);

		if (!$strsql) {

			$error[] = "Error di table _order_pengirim";
		}



		/* simpan order status */

		$sql = "insert into _order_status values 

				(null,'" . $data['nopesanan'] . "','" . $data['tgltransaksi'] . "',

				'" . $data['status_order'] . "','','" . $this->user . "')";

		$strsql = $this->db->query($sql);

		if (!$strsql) {

			$error[] = "Error di table _order_pengirim";
		}



		if (count($error) > 0) {

			$this->db->rollback();

			$status = "error";
		} else {

			$this->db->commit();

			$status = "success";
		}

		$this->db->query("UNLOCK TABLES");

		return array("status" => $status);
	}



	public function CancelOrder($data)

	{



		$error = array();

		$status = '';

		$idorder = '';

		$this->db->autocommit(false);



		if ($data['by_sistem'] == '0') {

			$idadmin = 0;
		} else {

			$idadmin = $this->user;
		}



		/* mengembalikan stok option (warna dan ukuran) */

		if (isset($data['stokoptionbertambah'])) {



			foreach ($data['stokoptionbertambah'] as $options) {

				$sql = "update _produk_options

						set stok = stok + " . $options['qty'] . "

						where idproduk='" . $options['product_id'] . "'

						and ukuran='" . $options['idukuran'] . "'

						and warna='" . $options['idwarna'] . "'";

				$strsql = $this->db->query($sql);

				if (!$strsql) {

					$error[] = "Error di table _produk_options";
				}
			}
		}

		/* end mengembalikan stok option (warna dan ukuran) */





		/* simpan ke table _produk */

		if (isset($data['stokbertambah'])) {

			foreach ($data['stokbertambah'] as $stok) {

				$sql = "update _produk

						set jml_stok = jml_stok + " . $stok['qty'] . "

						where idproduk='" . $stok['product_id'] . "'";

				$strsql = $this->db->query($sql);

				if (!$strsql) {

					$error[] = "Error di table _produk";
				}
			}
		}

		/* end simpan ke table _produk */



		/* mengembalikan poin yang dikeluarkan di order */

		if (isset($data['updatedaripoincust'])) {

			foreach ($data['updatedaripoincust'] as $datapoin) {

				$sql = "insert into _customer_point_history 

						values (null,'" . $datapoin['idmember'] . "',

						'" . $datapoin['dari_poin'] . "','IN','" . date('Y-m-d H:i:s') . "',

						'" . $datapoin['pesanan_no'] . "','" . $datapoin['keterangan'] . "')";

				$strsql = $this->db->query($sql);

				if (!$strsql) {

					$error[] = "Error di table _customer_point_history";
				}
			}
		}



		/* end mengembalikan poin yang dikeluarkan di order */



		/* mengembalikan deposito yang dikeluarkan di order */

		if (isset($data['updatedaridepositcust'])) {

			foreach ($data['updatedaridepositcust'] as $datadeposito) {

				$sql = "insert into _customer_deposito_history 

						values (null,'" . $datadeposito['idmember'] . "',

						'" . $datadeposito['dari_deposito'] . "','IN',

						'" . date('Y-m-d H:i:s') . "','" . $datadeposito['pesanan_no'] . "',

						'" . $datadeposito['keterangan'] . "','')";

				$strsql = $this->db->query($sql);

				if (!$strsql) {

					$error[] = "Error di table _customer_deposito_history";
				}



				$sql = "update _order set dari_deposito = 0 where pesanan_no='" . $datadeposito['pesanan_no'] . "'";

				$strsql = $this->db->query($sql);

				if (!$strsql) {

					$error[] = "Error update _order set dari_deposito = 0";
				}
			}
		}

		/* end mengembalikan deposito yang dikeluarkan di order */



		/* Pembatalan Order */

		if (isset($data['cancelorder'])) {

			foreach ($data['cancelorder'] as $order) {

				$sql = "update _order set status_id='" . $order['status_cancel'] . "' where pesanan_no='" . $order['pesanan_no'] . "'";

				$strsql = $this->db->query($sql);

				if (!$strsql) {

					$error[] = "Error di table _order";
				}

				if ($data['by_sistem'] == '0') {

					$sql = "insert into _order_status values (null,'" . $order['pesanan_no'] . "','" . date('Y-m-d H:i:s') . "','" . $order['status_cancel'] . "','Canceled By Sistem','" . $idadmin . "')";

					$strsql = $this->db->query($sql);

					if (!$strsql) {

						$error[] = "Error di table insert _order_status";
					}
				} else {

					if ($data['simpan_history'] == '1') {

						$sql = "insert into _order_status values

								(null,'" . $order['pesanan_no'] . "','" . date('Y-m-d H:i:s') . "',

								'" . $order['status_cancel'] . "',

								'',

								'" . $idadmin . "')";

						$strsql = $this->db->query($sql);

						if (!$strsql) $error[] = 'Error di table _order_status';
					}
				}
			}
		}

		/* end pembatalan Order */

		if (count($error) > 0) {

			$this->db->rollback();

			$status = "error";
		} else {

			$this->db->commit();

			$status = "success";
		}

		return array("status" => $status);
	}

	public function updateketeranganorder($data)

	{

		$sql = "update _order set keterangan='" . $this->db->escape($data['keterangan']) . "',

				tampil_label_keterangan='" . $data['tampilketerangan'] . "'

				where pesanan_no='" . $data['pesanan_no'] . "'";

		$strsql = $this->db->query($sql);

		if ($strsql) {

			return 'success';
		} else {

			return 'error';
		}
	}



	public function simpanEditPotonganDeposito($data)

	{

		$error = array();

		$status = '';

		$idorder = '';

		$this->db->autocommit(false);



		$sql = "update _order set dari_deposito='" . $data['jmldeposit'] . "' where pesanan_no='" . $data['pesanan_no'] . "'";

		$strsql = $this->db->query($sql);



		if (!$strsql) {

			$error[] = 'Error di update order';
		}



		$sql = "select cdh_cust_id from _customer_deposito_history 

				where cdh_cust_id='" . $data['pelanggan_id'] . "' and cdh_tipe='OUT' and cdh_order='" . $data['pesanan_no'] . "'";

		$strsql = $this->db->query($sql);

		$jmldata = $strsql->num_rows;



		if ($jmldata > 0) {



			$sql = "update _customer_deposito_history 

					set cdh_deposito='" . $data['jmldeposit'] . "' 

					where cdh_cust_id='" . $data['pelanggan_id'] . "' and cdh_tipe='OUT' and cdh_order='" . $data['pesanan_no'] . "'";



			$strsql = $this->db->query($sql);



			if (!$strsql) {

				$error[] = 'Error di update _customer_deposito_history';
			}
		} else {

			$sql = "insert into _customer_deposito_history 

					values (null,'" . $data['pelanggan_id'] . "',

					'" . $data['jmldeposit'] . "','OUT','" . date('Y-m-d H:i:s') . "','" . $data['pesanan_no'] . "','" . $data['keterangan'] . "','')";

			$strsql = $this->db->query($sql);



			if (!$strsql) {

				$error[] = 'Error di insert _customer_deposito_history';
			}
		}



		if ($data['insert_status'] == '1') {

			$sql = "INSERT INTO _order_status set nopesanan='" . $data['pesanan_no'] . "',tanggal='" . date('Y-m-d H:i:s') . "',status_id='" . $data['order_status'] . "',

	                keterangan='',admin_id='" . $this->user . "'";

			$strsql = $this->db->query($sql);



			if (!$strsql) {

				$error[] = 'Error di insert _order_status';
			}



			$sql = "UPDATE _order set status_id='" . $data['order_status'] . "' where pesanan_no='" . $data['pesanan_no'] . "'";

			$strsql = $this->db->query($sql);



			if (!$strsql) {

				$error[] = 'Error di UPDATE _order';
			}
		}



		/* end pembatalan Order */

		if (count($error) > 0) {

			$this->db->rollback();

			$status = "error";
		} else {

			$this->db->commit();

			$status = "success";
		}

		return array("status" => $status);
	}
}
