<?php

class model_Order

{

	private $db;

	private $idlogin;



	function __construct()

	{

		$this->db 	= new Database();

		$this->db->connect();

		$this->idlogin = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : '';

	}



	function cekOrder($noorder, $login)

	{

		$str = $this->db->query("select count(*) as total from _order WHERE pesanan_no = '" . $noorder . "' AND pelanggan_id='" . $login . "'");



		if ($str->row['total'] > 0) return true;

		else return false;

	}



	function getOrder($batas, $baris, $where)

	{

		$sql = "SELECT * FROM _order 

				INNER JOIN _status_order ON _order.status_id = _status_order.status_id 

				left join _order_pengirim on _order.pesanan_no = _order_pengirim.pesanan_no

				left join _order_penerima on _order.pesanan_no = _order_penerima.pesanan_no

				WHERE pelanggan_id='" . $this->idlogin . "' ORDER BY pesanan_tgl desc limit $batas,$baris";



		$str = $this->db->query($sql);

		if ($str) {

			$data = [];

			foreach ($str->rows as $row) {

				$data[] = $row;

			}

			return $data;

		} else {

			return false;

		}

	}



	function getOrderByIDAutocomplete($noorder, $idmember, $status)

	{

		$sql = "select pesanan_no,pesanan_subtotal,pesanan_kurir,

				dari_poin,dari_deposito

				from _order 

				where pesanan_no like '%" . (int) $noorder . "%' AND pelanggan_id='" . $idmember . "'

				AND status_id='" . $status . "'";



		$strsql = $this->db->query($sql);

		if ($strsql) {

			$data = [];

			foreach ($strsql->rows as $row) {

				$data[] = $row;

			}

			return $data;

		}

		return false;

	}



	function getOrderByID($noorder)

	{



		$sql = "select o.pesanan_no,o.pelanggan_id,o.pesanan_jml,

				o.pesanan_subtotal,o.pesanan_kurir,o.pesanan_tgl,

				o.status_id,so.status_nama,o.kurir,sp.shipping_nama,

				o.servis_kurir,s.servis_code,o.kurir_perkilo,o.grup_member,

				o.dari_poin,o.potongan_kupon,o.kode_kupon,o.tgl_kirim,

				o.no_awb,o.dari_deposito,o.kurir_konfirm,o.keterangan, o.biaya_packing,

				n.nama_penerima,n.hp_penerima,

				n.alamat_penerima,ngn.negara_nama as negaranm_penerima,n.negara_penerima,

				prn.provinsi_nama as propinsinm_penerima,n.propinsi_penerima,kbn.kabupaten_nama as kotanm_penerima,

				n.kota_penerima,n.kecamatan_penerima,

				kcn.kecamatan_nama as kecamatannm_penerima,

				n.kelurahan_penerima, n.kodepos_penerima,

				p.nama_pengirim,p.hp_pengirim,

				p.alamat_pengirim,ngp.negara_nama as negaranm_pengirim,

				prp.provinsi_nama as propinsinm_pengirim,

				prp.provinsi_id as propinsi_pengirim,

				kbp.kabupaten_nama as kotanm_pengirim,

				kbp.kabupaten_id as kota_pengirim,

				kcp.kecamatan_nama as kecamatannm_pengirim,

				kcp.kecamatan_id as kecamatan_pengirim,

				p.kelurahan_pengirim,p.kodepos_pengirim,

				cg.cg_dropship,id_konfirm,jml_bayar,bank_rek_tujuan,

				rekening_no,rekening_atasnama,

				rekening_cabang,bank_nama,

				bank_dari,bank_rek_dari,

				bank_atasnama_dari,tgl_transfer,

				buktitransfer,shipping_kode,shipping_kdrajaongkir,servis_code,

				shipping_rajaongkir,shipping_konfirmadmin

				from _order o

				inner join _customer_grup cg on o.grup_member = cg.cg_id

				left join _status_order so on o.status_id = so.status_id

				left join _servis s on o.servis_kurir = s.servis_id

				left join _shipping sp on o.kurir = sp.shipping_kode

				inner join _order_penerima n on o.pesanan_no = n.pesanan_no

				INNER JOIN _negara ngn ON n.negara_penerima = ngn.negara_id

				INNER JOIN _provinsi prn ON n.propinsi_penerima = prn.provinsi_id

				INNER JOIN _kabupaten kbn ON n.kota_penerima = kbn.kabupaten_id

				INNER JOIN _kecamatan kcn ON n.kecamatan_penerima = kcn.kecamatan_id

				left JOIN _order_pengirim p ON o.pesanan_no = p.pesanan_no

				left JOIN _negara ngp ON p.negara_pengirim = ngp.negara_id

				left JOIN _provinsi prp ON p.propinsi_pengirim = prp.provinsi_id

				left JOIN _kabupaten kbp ON p.kota_pengirim = kbp.kabupaten_id

				left JOIN _kecamatan kcp ON p.kecamatan_pengirim = kcp.kecamatan_id

				left join _order_konfirmasi_bayar okb on o.pesanan_no = okb.order_pesan

				left join _bank_rekening br on okb.bank_rek_tujuan = br.rekening_id

				left join _bank b on br.bank_id = b.bank_id

				WHERE o.pelanggan_id='" . $this->idlogin . "' AND o.pesanan_no='" . $this->db->escape($noorder) . "'";



		$strsql = $this->db->query($sql);



		return isset($strsql->row) ? $strsql->row : false;

	}



	function getOrderAlamat($noorder)

	{

		$strsql = $this->db->query("SELECT 

							 n.pesanan_no, n.nama_penerima,n.hp_penerima,

							 n.alamat_penerima,ngn.negara_nama as negaranm_penerima,prn.provinsi_nama as propinsinm_penerima,kbn.kabupaten_nama as kotanm_penerima,kcn.kecamatan_nama as kecamatannm_penerima,

							 n.kelurahan_penerima, n.kodepos_penerima,

							 p.nama_pengirim,p.hp_pengirim,

							 p.alamat_pengirim,ngp.negara_nama as negaranm_pengirim,prp.provinsi_nama as propinsinm_pengirim,kbp.kabupaten_nama as kotanm_pengirim,kcp.kecamatan_nama as kecamatannm_pengirim,

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

							 WHERE n.pesanan_no='" . $noorder . "'");

		return isset($strsql->row) ? $strsql->row : false;

	}



	function getOrderDetail($noorder)

	{



		$sql = "SELECT _order_detail.pesanan_no,

					   _order_detail.produk_id,

					   _produk_deskripsi.nama_produk,

					   _order_detail.jml,

					   _order_detail.satuan,

					   _order_detail.harga,

					   _order_detail.harga_tambahan,

					   _order_detail.persen_diskon_satuan,

					   _order_detail.get_poin,

					   _order_detail.warnaid,w.warna,

					   _order_detail.ukuranid,u.ukuran,

					   _order_detail.iddetail,_order_detail.berat

			    FROM _order_detail 

				LEFT JOIN _produk_deskripsi ON _order_detail.produk_id = _produk_deskripsi.idproduk

				left join _warna w on _order_detail.warnaid = w.idwarna

				left join _ukuran u on _order_detail.ukuranid = u.idukuran

				WHERE pesanan_no = '" . $noorder . "'";



		$strsql = $this->db->query($sql);

		if ($strsql) {

			$data = array();

			foreach ($strsql->rows as $rs) {

				$data[] = array(

					'noorder' 		=> $rs['pesanan_no'],

					'produkid'		=> $rs['produk_id'],

					'nama_produk'	=> $rs['nama_produk'],

					'jml'			=> $rs['jml'],

					'harga_satuan'	=> $rs['satuan'],

					'harga'			=> $rs['harga'],

					'harga_tambahan' => $rs['harga_tambahan'],

					'diskon_satuan'	=> $rs['persen_diskon_satuan'],

					'get_poin'		=> $rs['get_poin'],

					'warnaid'		=> $rs['warnaid'],

					'ukuranid'		=> $rs['ukuranid'],

					'warna'			=> $rs['warna'],

					'ukuran'			=> $rs['ukuran'],

					'iddetail'		=> $rs['iddetail'],

					'berat'          => $rs['berat']

				);

			}

			return $data;

		} else {

			return false;

		}

	}



	function getOrderStatus($noorder)

	{

		$tabels = array();

		$strsql = $this->db->query("SELECT tanggal,status_nama,keterangan FROM _order_status INNER JOIN _status_order ON

		                     _order_status.status_id = _status_order.status_id

							WHERE nopesanan = '" . $noorder . "' ORDER BY tanggal desc ");



		foreach ($strsql->rows as $rs) {

			$tabels[] = array(

				'tgl' 		=> $rs['tanggal'],

				'status'		=> $rs['status_nama'],

				'keterangan'	=> $rs['keterangan'],

			);

		}

		return $tabels;

	}



	public function totalOrder($where)

	{

		if ($where != '') $where = " where " . $where;

		$query = '';

		$sql = "select count(pelanggan_id) as total from _order INNER JOIN _status_order ON _order.status_id = _status_order.status_id WHERE pelanggan_id='" . $this->idlogin . "'" . $query . $where;



		$strsql = $this->db->query($sql);



		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;

	}



	function getOrderbyName($nama)

	{

		$strsql = $this->db->query("select * from _shipping WHERE tampil=1 AND nama_shipping='$nama'");

		return $strsql->row;

	}



	function getLastOrder($idmember, $status_order, $limit)

	{

		$w = '';

		if ($status_order != '') {

			$w .=  "and status_id='" . $status_order . "'";

		}

		$sql = "select pesanan_no,pesanan_subtotal,

				pesanan_kurir,pesanan_tgl,dari_poin,potongan_kupon,dari_deposito

				from _order 

				where pelanggan_id='" . $idmember . "' " . $w . "

				order by pesanan_tgl desc limit $limit";



		$strsql = $this->db->query($sql);

		if ($strsql) {

			$data = [];

			foreach ($strsql->rows as $row) {

				$data[] = $row;

			}

			return $data;

		} else {

			return false;

		}

	}

	public function simpanEditKurir($data)

	{

		$error = array();

		$status = '';

		$idorder = '';

		$this->db->autocommit(false);



		$sql = "UPDATE _order set pesanan_kurir = '" . $data['tarifkurir'] . "',kurir='" . $data['kurir'] . "',servis_kurir='" . $data['serviskurir'] . "',

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

						'" . $data['jmldeposit'] . "','OUT','" . date('Y-m-d H:i:s') . "','" . $data['nopesanan'] . "','" . htmlspecialchars(htmlentities($this->db->escape($data['keterangan']))) . "')";

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

						'" . $data['nopesanan'] . "','" . htmlspecialchars(htmlentities($this->db->escape($data['keterangan']))) . "')";

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

			   warnaid,ukuranid,gbr

               FROM _order_detail 

			   left join _produk_img on _order_detail.produk_id = _produk_img.idproduk and _order_detail.warnaid = _produk_img.idwarna

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

				'ukuran' => $rsa['ukuranid'],

				'gbr'	=> $rsa['gbr']

			);

		}

		return $arkab;

	}

	function getProdukOrderOptionSingle($idproduk, $idwarna, $idukuran, $nopesan)

	{

		$arkab = array();

		$w = '';

		//if($idukuran != '' && $idukuran != '0') {

		$w .= " AND ukuranid='" . $idukuran . "'";

		//} 

		//if($idwarna != '' && $idwarna != '0') {

		$w .= " AND warnaid='" . $idwarna . "'";

		//}

		//if($idproduk != ''){

		$w .= " AND produk_id='" . $idproduk . "'";

		//}

		$sql = "SELECT _order_detail.iddetail,pesanan_no,

			   produk_id,jml,harga,satuan,berat,

			   warnaid,ukuranid,gbr

               FROM _order_detail 

			   left join _produk_img on _order_detail.produk_id = _produk_img.idproduk and _order_detail.warnaid = _produk_img.idwarna

			   WHERE pesanan_no='" . $nopesan . "' $w 

			   ORDER BY _order_detail.iddetail ASC";



		$strsql = $this->db->query($sql);

		return isset($strsql->row) ? $strsql->row : false;

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



		if (isset($data['stokbertambah'])) {

			$sql = "update _produk set 

					jml_stok=jml_stok+" . $data['stokbertambah']['qty'] . "

					where idproduk='" . $data['stokbertambah']['idproduk'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _produk, update bertambah";

		}



		if (isset($data['hapusprodukorder'])) {

			$sql = "delete from _order_detail where iddetail='" . $data['hapusprodukorder'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _orde_detail, hapus";

		}

		if ($data['jmlproduk'] > 1) {

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

							'" . $data['nopesanan'] . "','" . htmlspecialchars(htmlentities($this->db->escape($data['keterangan']))) . "')";

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

			$sql = "delete _order_penerima,

						  _order_pengirim,

						  _order, 

						  _order_detail,_order_status FROM 

						  _order  

				LEFT JOIN _order_penerima ON _order.pesanan_no = _order_penerima.pesanan_no

				LEFT JOIN _order_pengirim ON _order.pesanan_no = _order_pengirim.pesanan_no 

				LEFT JOIN _order_detail ON _order.pesanan_no = _order_detail.pesanan_no 

				LEFT JOIN _order_status ON _order.pesanan_no = _order_status.nopesanan

				WHERE _order.pesanan_no = '" . $data['nopesanan'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table hapus _order";



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

	function simpanEditAlamat($data)

	{

		$error = array();

		$status = '';

		$this->db->autocommit(false);

		/* update order alamat pengirim */

		if ($data['jenis_alamat'] == 'alamatpengirim') {

			$sql = "update _order_pengirim set

					nama_pengirim='" . htmlspecialchars(htmlentities($this->db->escape($data['nama_pengirim']))) . "',

					hp_pengirim='" . htmlspecialchars(htmlentities($this->db->escape($data['hp_pengirim']))) . "',

					alamat_pengirim='" . htmlspecialchars(htmlentities($this->db->escape($data['alamat_pengirim']))) . "',

					propinsi_pengirim='" . $data['propinsi_pengirim'] . "',

					kota_pengirim='" . $data['kabupaten_pengirim'] . "',

					kecamatan_pengirim='" . $data['kecamatan_pengirim'] . "',

					kelurahan_pengirim='" . htmlspecialchars(htmlentities($this->db->escape($data['kelurahan_pengirim']))) . "',

					kodepos_pengirim='" . htmlspecialchars(htmlentities($this->db->escape($data['kodepos_pengirim']))) . "'

					where pesanan_no='" . $data['nopesanan'] . "'";

			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di table _order_pengirim";

		} elseif ($data['jenis_alamat'] == 'alamatpenerima') {



			$sql = "update _order_penerima set

					nama_penerima='" . htmlspecialchars(htmlentities($this->db->escape($data['nama_penerima']))) . "',

					hp_penerima='" . htmlspecialchars(htmlentities($this->db->escape($data['hp_penerima']))) . "',

					alamat_penerima='" . htmlspecialchars(htmlentities($this->db->escape($data['alamat_penerima']))) . "',

					propinsi_penerima='" . $data['propinsi_penerima'] . "',

					kota_penerima='" . $data['kabupaten_penerima'] . "',

					kecamatan_penerima='" . htmlspecialchars(htmlentities($this->db->escape($data['kecamatan_penerima']))) . "',

					kelurahan_penerima='" . htmlspecialchars(htmlentities($this->db->escape($data['kelurahan_penerima']))) . "',

					kodepos_penerima='" . htmlspecialchars(htmlentities($this->db->escape($data['kodepos_penerima']))) . "' 

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



		/* menyimpan data alamat ke table _customer_address */

		if ($data['chkdefault'] == '1') {

			$default_address = '1';

		} else {

			$default_address = '0';

		}



		if ($data['savetoaddress'] == '1') {

			$sql = "insert into _customer_address values (null,

					'" . $data['idmember'] . "',

					'" . htmlspecialchars(htmlentities($this->db->escape($data['add_nama']))) . "',

					'" . htmlspecialchars(htmlentities($this->db->escape($data['add_alamat']))) . "','33',

					'" . $data['add_propinsi'] . "',

					'" . $data['add_kabupaten'] . "',

					'" . $data['add_kecamatan'] . "',

					'" . htmlspecialchars(htmlentities($this->db->escape($data['add_kelurahan']))) . "',

					'" . htmlspecialchars(htmlentities($this->db->escape($data['add_kodepos']))) . "',

					'" . htmlspecialchars(htmlentities($this->db->escape($data['add_telp']))) . "','" . $default_address . "')";

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



		if (count($error) > 0) {

			$this->db->rollback();

			$status = "error";

		} else {

			$this->db->commit();

			$status = "success";

		}

		return array("status" => $status);

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

						'" . $data['nopesanan'] . "','" . htmlspecialchars(htmlentities($this->db->escape($data['keterangan']))) . "')";

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

		$this->db->query("UNLOCK TABLES");

		return array("status" => $status);

	}



	public function updateketerangan($data)

	{

		$sql = "update _order set keterangan='" . htmlspecialchars(htmlentities($this->db->escape($data['keterangan']))) . "'

				where pesanan_no='" . $data['pesanan_no'] . "'";

		$strsql = $this->db->query($sql);

		if ($strsql) {

			return 'success';

		} else {

			return 'error';

		}

	}

}

