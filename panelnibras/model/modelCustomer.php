<?php
class modelCustomer
{
	private $db;
	private $tabelnya;
	private $userlogin;
	function __construct()
	{
		$this->tabelnya = '_customer';
		$this->db 		= new Database();
		$this->db->connect();
		$this->userlogin = isset($_SESSION["userlogin"]) ? $_SESSION["userlogin"] : '';
	}

	function checkDataReseller($email)
	{
		$sql = "select cust_email from " . $this->tabelnya . " where cust_email='" . $email . "'";

		$check = $this->db->query($sql);
		$jml   = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function checkNoTelp($notelp)
	{

		$check = $this->db->query("select cust_telp from " . $this->tabelnya . " where cust_telp='$notelp'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function checkDataResellerByID($rsid)
	{
		$check = $this->db->query("select cust_id from " . $this->tabelnya . " where cust_id='$rsid'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function simpanCustomer($data)
	{
		$error = array();
		$status = '';
		$idcust = '';
		$this->db->autocommit(false);

		/* simpan ke table _customer */
		$sql = "insert into _customer values 
				(null,'" . $data['rtipecust'] . "',
				'" . $this->db->escape($data['rnama']) . "',
				'" . $this->db->escape($data['ralamat']) . "',
				'" . $this->db->escape($data['rkelurahan']) . "',
				'" . $data['rkecamatan'] . "',
				'" . $data['rkabupaten'] . "','" . $data['rpropinsi'] . "',
				'33','" . $data['rkodepos'] . "',
				'" . $data['rtelp'] . "','" . $data['remail'] . "',
				'" . $data['pass'] . "','0',
				'" . $data['rapprove'] . "','" . $data['rstatus'] . "',
				'" . $data['tglupdate'] . "','" . $data['tglupdate'] . "')";
		$strsql = $this->db->query($sql);
		if (!$strsql) {
			$error[] = "Error di table customer";
		} else {
			$idcust = $this->db->lastid();
		}

		/* simpan ke table customer address */
		/*
		$sql = "insert into _customer_address values
				(null,'".$idcust."','".$this->db->escape($data['rnama'])."',
				'".$this->db->escape($data['ralamat'])."',
				'33','".$data['rpropinsi']."','".$data['rkabupaten']."',
				'".$data['rkecamatan']."','".$data['rkelurahan']."',
				'".$data['rkodepos']."','".$data['rtelp']."')";
		$strsql = $this->db->query($sql);
		
		if(!$strsql) $error[] = "Error di table customer address";
		*/

		/* simpan ke table customer aktivitas*/
		$sql = "insert into _customer_aktivitas values 
				(null,'" . $idcust . "','" . $data['tglupdate'] . "','" . $data['aktivitas'] . "',
				'" . $this->db->escape($data['keterangan']) . "')";
		$strsql = $this->db->query($sql);

		if (!$strsql) $error[] = "Error di table customer aktivitas";

		/* update table customer history */
		$sql = "update _customer_history set ch_cust_default=0 where ch_cust_id='" . $idcust . "'";
		$strsql = $this->db->query($sql);

		if (!$strsql) $error[] = "Error di update table customer history";

		/* insert table cutomer history */
		$sql = "insert into _customer_history values 
				(null,'" . $idcust . "','" . $data['rtipecust'] . "',
				'" . $data['tglupdate'] . "','1','" . $data['ipaddress'] . "')";
		$strsql = $this->db->query($sql);

		if (!$strsql) $error[] = "Error di insert table customer history";

		/* mengecek apakah ada inputan deposito */
		if ($data['rdeposit'] > 0) {
			/* simpan ke table _customer deposit0 */
			/*
			$sql = "insert into _customer_deposito values 
					(null,'".$idcust."','".$data['rdeposit']."','".$data['tglupdate']."')";
			$strsql = $this->db->query($sql);
			
			if(!$strsql) $error[] = "Error di insert table _customer_deposito";
			*/
			/* simpan ke table _customer deposito history */
			$sql = "insert into _customer_deposito_history values 
					(null,'" . $idcust . "','" . $data['rdeposit'] . "','IN',
					'" . $data['tglupdate'] . "',0,'" . $data['depositohistory'] . "')";
			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di insert table _customer_deposito_history";
		}

		/* proses penyimpanan */
		if (count($error) > 0) {
			$this->db->rollback();
			$status = "error";
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status" => $status);
	}

	function editCustomer($data)
	{
		$error = array();
		$status = '';
		$this->db->autocommit(false);

		/* update table _customer */
		$sql = "update _customer set 
				cust_grup_id='" . $data['rtipecust'] . "',
				cust_nama='" . $this->db->escape($data['rnama']) . "',
				cust_alamat='" . $this->db->escape($data['ralamat']) . "',
				cust_kelurahan='" . $this->db->escape($data['rkelurahan']) . "',
				cust_kecamatan='" . $this->db->escape($data['rkecamatan']) . "',
				cust_kota='" . $this->db->escape($data['rkabupaten']) . "',
				cust_propinsi='" . $this->db->escape($data['rpropinsi']) . "',
				cust_negara='33',
				cust_kdpos='" . $data['rkodepos'] . "',
				cust_telp='" . $data['rtelp'] . "',
				cust_email='" . $data['remail'] . "',
				cust_pass='" . $data['pass'] . "',
				cust_newsletter='0',
				cust_approve='" . $data['rapprove'] . "',
				cust_status='" . $data['rstatus'] . "',
				cust_tgl_upd='" . $data['tglupdate'] . "'
				where cust_id='" . $data['iddata'] . "'";

		$strsql = $this->db->query($sql);
		if (!$strsql) $error[] = "Error di update table _customer";

		/* update table _customer_address */
		/*
		$sql = "update _customer_address set
				ca_alamat='".$this->db->escape($data['ralamat'])."',
				ca_propinsi='".$this->db->escape($data['rpropinsi'])."',
				ca_kabupaten='".$this->db->escape($data['rkabupaten'])."',
				ca_kecamatan='".$this->db->escape($data['rkecamatan'])."',
				ca_kelurahan='".$this->db->escape($data['rkelurahan'])."',
				ca_kodepos='".$data['rkodepos']."'
				where ca_cust_id='".$data['iddata']."'";
		
		$strsql = $this->db->query($sql);
		if(!$this->db->affected_rows()) { // jika data tidak ada maka diinsert
		*/
		/* simpan ke table customer address */
		/*	$sql = "insert into _customer_address values
					(null,'".$data['iddata']."',
					'".$this->db->escape($data['rnama'])."',
					'".$this->db->escape($data['ralamat'])."',
					'33','".$data['rpropinsi']."','".$data['rkabupaten']."',
					'".$data['rkecamatan']."','".$data['rkelurahan']."',
					'".$data['rkodepos']."','".$data['rtelp']."')";
			$strsql = $this->db->query($sql);
			
			
		}
		
		if(!$strsql) $error[] = "Error di table customer address";
		*/
		/* pengecekan update customer history, jika ada perubahan tipe customer */
		if ($data['rtipecust'] != $data['rtipecustlama']) {

			/* update customer history */
			$sql = "update _customer_history set ch_cust_default='0' where ch_cust_id='" . $data['iddata'] . "'";
			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di update table customer history";

			/* insert table cutomer history */
			$sql = "insert into _customer_history values 
					(null,'" . $data['iddata'] . "',
					'" . $data['rtipecust'] . "',
					'" . $data['tglupdate'] . "','1','" . $data['ipaddress'] . "')";
			$strsql = $this->db->query($sql);

			if (!$strsql) $error[] = "Error di insert table customer history";
		}

		/* simpan ke table customer aktivitas*/
		$sql = "insert into _customer_aktivitas values 
				(null,'" . $data['iddata'] . "','" . $data['tglupdate'] . "','" . $data['aktivitas'] . "',
				'" . $this->db->escape($data['keterangan']) . "')";
		$strsql = $this->db->query($sql);

		if (!$strsql) $error[] = "Error di table customer aktivitas";

		/* proses penyimpanan */
		if (count($error) > 0) {
			$this->db->rollback();
			$status = "error";
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status" => $status);
	}

	public function cekDeposito($data)
	{
		$check = $this->db->query("select count(*) as total from _customer_deposito_history where cdh_cust_id='" . $data . "'");
		$jml = $check->num_rows;
		if ($rs[0] > 0) return true;
		else return false;
	}
	function checkDepositoHistory($data, $jenis)
	{
		$deposito = -1;
		$str = $this->db->query("SELECT cdh_deposito FROM _customer_deposito_history WHERE cdh_tipe='$jenis' AND cdh_cust_id='" . $data['pelangganid'] . "' AND cdh_order='" . $data['nopesan'] . "'");
		return isset($str->row['cdh_deposito']) ? $str->row['cdh_deposito'] : -1;
	}

	function updateDepositoHistory($data, $jenis)
	{
		$sql = "UPDATE _customer_despotiso_history SET cdh_deposito='" . $data['totdeposito'] . "' 
			  WHERE cdh_cust_id='" . $data['pelangganid'] . "' AND cdh_tipe='$jenis' AND cdh_order='" . $data['nopesan'] . "'";
		return $sql;
	}

	public function updateDeposito($data)
	{

		$sql = "UPDATE _customer_deposito set cd_deposito=cd_deposito+'" . $data['deposito'] . "',
						  cd_tglupdate='" . $data['tglupdate'] . "' WHERE cd_cust_id='" . $data['iddata'] . "'";
		return $sql;
	}
	public function DeleteDepositDetail($id, $idorder, $jenis)
	{
		$sql = "DELETE from _customer_deposito_history where cdh_order='" . $idorder . "' AND cdh_cust_id='" . $id . "' AND cdh_tipe='$jenis'";
		return $sql;
	}
	public function simpanInvoice($data)
	{

		return $this->db->query("INSERT INTO _reseller_invoice values (null,'" . $data['id'] . "','" . $data['tipe_reseller'] . "',
	                      '" . $data['tglupdate'] . "','" . $data['biaya_register'] . "','" . $data['stsbayar'] . "','" . $data['keterangan'] . "')");
	}

	function approveReseller($data)
	{
		return $this->db->query("update " . $this->tabelnya . " set stsapprove = '1',reseller_kode = '" . $data['kode'] . "' WHERE reseller_id='" . $data['id'] . "' AND stsapprove = '0'");
	}

	function approveResellerInvoice($data)
	{
		return $this->db->query("update _reseller_invoice set stsbayar = '1' WHERE idmember='" . $data['id'] . "' AND stsbayar = '0'");
	}
	function editResellerMasaAktif($data = array(), $renew)
	{
		$sql = $this->db->query("select count(*) as total from _reseller_batas_aktif where idreseller='" . $data['iddata'] . "'");
		$total  = isset($sql->row['total']) ? $sql->row['total'] : 0;
		if ($total > 0) {
			return $this->db->query("UPDATE _reseller_batas_aktif set
	                       tgl_batas='" . $data['tglexpired'] . "',renew='$renew' where idreseller='" . $data['iddata'] . "'");
		} else {
			return $this->db->query("INSERT INTO _reseller_batas_aktif values ('" . $data['iddata'] . "','" . $data['tglexpired'] . "','$renew')");
		}
	}
	function simpanResellerMasaAktif($data = array(), $renew)
	{
		return $this->db->query("INSERT INTO _reseller_batas_aktif values ('" . $data['id'] . "','" . $data['tglexpired'] . "','" . $renew . "')");
	}

	function editReseller($data = array())
	{

		$sql = "UPDATE " . $this->tabelnya . " set cust_grup_id = '" . $data['tipe_reseller'] . "',cust_nama='" . $data['nama'] . "',cust_alamat = '" . $data['alamat'] . "',
               cust_kelurahan = '" . $data['kelurahan'] . "',cust_kecamatan = '" . $data['kecamatan'] . "',cust_kota = '" . $data['kabupaten'] . "',
							cust_propinsi = '" . $data['propinsi'] . "',cust_negara = '" . $data['negara'] . "',cust_kdpos = '" . $data['kodepos'] . "',
							cust_telp = '" . $data['telp'] . "',cust_email = '" . $data['email'] . "',cust_pass = '" . $data['pass'] . "',
							cust_status = '" . $data['status'] . "',
							cust_tgl_upd ='" . $data['tglupdate'] . "' WHERE cust_id='" . $data['iddata'] . "'";
		return $sql;
	}

	function editResellerGrup($data = array())
	{
		return $this->db->query("UPDATE " . $this->tabelnya . " set 
							reseller_tglupdated ='" . $data['tglupdate'] . "',

							reseller_grup = '" . $data['tipe_reseller'] . "'
						    WHERE reseller_id='" . $data['id'] . "'");
	}
	function getCustomerByName($name)
	{
		$sql = "select cust_nama,cust_id from _customer where cust_nama like'%" . $this->db->escape($name) . "%'";
		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = [];
			foreach ($strsql->rows as $rs) {
				$data[] = $rs;
			}
			return $data;
		} else {
			return false;
		}
	}
	function getResellerLimit($batas, $baris, $data)
	{

		$filter				= array();
		$where = '';

		if ($data['caridata'] != '') {
			$filter[] = " cust_telp like '%" . trim(strip_tags($this->db->escape($data['caridata']))) . "%' 
							OR cust_nama like '%" . trim(strip_tags($this->db->escape($data['caridata']))) . "%'
							OR cust_email like '%" . trim(strip_tags($this->db->escape($data['caridata']))) . "%'";
		}
		if ($data['grup'] != '' && $data['grup'] != '0') $filter[] = " cust_grup_id = '" . trim(strip_tags(urlencode($data['grup']))) . "'";

		if (!empty($filter))	$where = implode(" and ", $filter);
		if ($where != '') $where = " WHERE " . $where;


		$sql = "SELECT * 
				FROM _customer LEFT JOIN  _customer_grup ON _customer.cust_grup_id = _customer_grup.cg_id " . $where . " 
				ORDER BY cust_id desc limit $batas,$baris";

		$strsql = $this->db->query($sql);
		if ($strsql) {
			$hasil = array();
			foreach ($strsql->rows as $rsa) {
				$hasil[] = $rsa;
			}
			return $hasil;
		}
		return false;
	}

	function totalReseller($data)
	{
		$where = '';


		if ($data['caridata'] != '') $filter[] = " cust_telp like '%" . trim(strip_tags($this->db->escape($data['caridata']))) . "%' OR cust_nama like '%" . trim(strip_tags($this->db->escape($data['caridata']))) . "%'";
		if ($data['grup'] != '' && $data['grup'] != '0') $filter[] = " cust_grup_id = '" . trim(strip_tags(urlencode($data['grup']))) . "'";
		if (!empty($filter))	$where = implode(" and ", $filter);
		if ($where != '') $where = " WHERE " . $where;
		$strsql = $this->db->query("select count(cust_id) as total from " . $this->tabelnya . " INNER JOIN _customer_grup ON _customer.cust_grup_id = _customer_grup.cg_id " . $where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}


	function getDataDepositoLimit($batas, $baris, $iddata)
	{

		/*
		$sql = "SELECT c.cust_id,c.cust_nama, cg.cg_nm,
					   c.cust_grup_id,cdh_tipe,cdh_tgl,cdh_keterangan,
					   SUM(CASE WHEN cdh_tipe = 'OUT' THEN -cdh_deposito
					    WHEN cdh_tipe = 'IN' THEN cdh_deposito
					    END) AS totaldeposito
				FROM _customer_deposito_history cdh
				left join _customer c on cdh.cdh_cust_id = c.cust_id
				LEFT JOIN _customer_grup cg ON c.cust_grup_id = cg.cg_id
				where cdh_cust_id='".$iddata."' order by cdh_tgl desc limit $batas,$baris";
		*/
		$sql = "SELECT c.cust_id,c.cust_nama, cg.cg_nm,
					   c.cust_grup_id,cdh_tipe,cdh_tgl,cdh_keterangan,cdh_deposito,cdh_bukti
				FROM _customer_deposito_history cdh
				left join _customer c on cdh.cdh_cust_id = c.cust_id
				LEFT JOIN _customer_grup cg ON c.cust_grup_id = cg.cg_id
				where cdh_cust_id='" . $iddata . "' order by cdh_tgl desc limit $batas,$baris";
		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = array();
			foreach ($strsql->rows as $rsa) {
				$data[] = $rsa;
			}
		} else {
			$data = false;
		}
		return $data;
	}

	function getResellerByID($iddata)
	{
		$sql = "select * from " . $this->tabelnya . " 
				LEFT JOIN _customer_deposito on _customer.cust_id = _customer_deposito.cd_cust_id 
				left join _customer_grup on _customer.cust_grup_id = _customer_grup.cg_id
				left join _provinsi on _customer.cust_propinsi = _provinsi.provinsi_id 
				left join _kabupaten on _customer.cust_kota = _kabupaten.kabupaten_id
				left join _kecamatan on _customer.cust_kecamatan = _kecamatan.kecamatan_id 
				where cust_id='" . $iddata . "'";

		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	function getResellerByKode($iddata)
	{
		$ar = array();
		$strsql = $this->db->query("select * from " . $this->tabelnya . " INNER JOIN _reseller_grup ON reseller_grup = rs_grupid where stsapprove='1' AND (CONCAT(rs_grupcode,reseller_kode) like '" . $iddata . "%' OR reseller_nama like '" . $iddata . "%' OR reseller_hp like '" . $iddata . "%')");
		foreach ($strsql->rows as $rsa) {
			$ar[] = $rsa;
		}
		return $ar;
	}

	function totalDataDeposito($iddata)
	{
		$strsql = $this->db->query("select count(cdh_id) as total from _customer_deposito_history WHERE cdh_cust_id='" . $iddata . "'");
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}

	function getCustomerDepositoLimit($batas, $baris, $data)
	{
		$where = '';
		$filter = [];
		$data['caridata'] = isset($data['caridata']) ? $data['caridata'] : '';
		$data['grup'] = isset($data['grup']) ? $data['grup'] : array();

		if ($data['caridata'] != '') $filter[] = " ( cust_telp like '%" . trim($this->db->escape($data['caridata'])) . "%' OR cust_nama like '%" . trim($this->db->escape($data['caridata'])) . "%' )";

		if (count($data['grup']) > 0) {
			foreach ($data['grup'] as $dg) {
				$grup[] = $dg['cg_id'];
			}
			$filter[] = " cust_grup_id in (" . implode(" , ", $grup) . " )";
		}

		if (!empty($filter))	$where = ' WHERE ' . implode(" and ", $filter);

		$orderby = "  GROUP BY cust_id order by cust_id asc limit $batas,$baris";

		$sql = "select cust_id,cust_grup_id,
					   cust_nama,cg_nm,
						SUM(CASE WHEN cdh_tipe = 'OUT' THEN -cdh_deposito
					    WHEN cdh_tipe = 'IN' THEN cdh_deposito
					    END) AS totaldeposito
				from _customer_deposito_history cdh
				inner join _customer c on cdh.cdh_cust_id = c.cust_id
				inner join _customer_grup cg on c.cust_grup_id = cg.cg_id " . $where . $orderby;
		
		$strsql = $this->db->query($sql);
		if ($strsql) {
			$hasil = [];
			foreach ($strsql->rows as $row) {
				$hasil[] = $row;
			}
		} else {
			$hasil = false;
		}
		return $hasil;
	}

	function totalCustomerDeposito($data)
	{
		$where = '';
		$filter = [];
		$grup = [];
		$data['caridata'] = isset($data['caridata']) ? $data['caridata'] : '';
		$data['grup'] = isset($data['grup']) ? $data['grup'] : array();

		if ($data['caridata'] != '') $filter[] = " ( cust_telp like '%" . trim($this->db->escape($data['caridata'])) . "%' OR cust_nama like '%" . trim($this->db->escape($data['caridata'])) . "%' )";

		if (count($data['grup']) > 0) {
			foreach ($data['grup'] as $dg) {
				$grup[] = $dg['cg_id'];
			}
			$filter[] = " cust_grup_id in(" . implode(" , ", $grup) . " )";
		}

		if (!empty($filter))	$where = ' WHERE ' . implode(" and ", $filter);

		
		/*
		$sql = "select count(cust_id) as total 
				from _customer_deposito_history cdh 
				LEFT JOIN _customer c ON cdh.cdh_cust_id = c.cust_id 
				LEFT JOIN _customer_grup cg ON c.cust_grup_id = cg.cg_id " . $where . '  GROUP BY cust_id';
		*/
		$sql = "select cust_id
				from _customer_deposito_history cdh 
				LEFT JOIN _customer c ON cdh.cdh_cust_id = c.cust_id 
				LEFT JOIN _customer_grup cg ON c.cust_grup_id = cg.cg_id " . $where . '  GROUP BY cust_id';
		
		$strsql = $this->db->query($sql);

		//return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
		return $strsql->num_rows;
	}

	function checkRelasi($data)
	{
		$check = $this->db->query("select pelanggan_id from _order where pelanggan_id='$data'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}
	function hapusReseller($data)
	{
		$check = $this->db->query("delete from " . $this->tabelnya . " where cust_id='$data'");
		$check2 = $this->db->query("delete from _customer_history where ch_cust_id='$data'");

		if ($check) return true;
		else return false;
	}
	public function checkBiayaReseller($grup)
	{

		$check = $this->db->query("select rs_hrgregister from _reseller_grup where rs_grupid='" . $grup . "'");
		return isset($check->row['rs_hrgregister']) ? $check->row['rs_hrgregister'] : 0;
	}
	public function checkBiayaPerpanjang($grup)
	{
		$check = $this->db->query("select rs_hrgperpanjangreg from _reseller_grup where rs_grupid='" . $grup . "'");
		return isset($check->row['rs_hrgperpanjangreg']) ? $check->row['rs_hrgperpanjangreg'] : 0;
	}
	function getResellerEksekusi($masa_approve, $data)
	{
		$arkab = array();
		$strsql = $this->db->query("SELECT reseller_id,reseller_kode,reseller_nama, reseller_grup,rs_hrgregister,
	                        reseller_tglupdated,NOW() AS tglskarang FROM _reseller INNER JOIN 
							_reseller_grup ON _reseller.reseller_grup = _reseller.reseller_grup 
							WHERE stsapprove='0' AND rs_hrgregister > 0 AND 
							(reseller_tglupdated + INTERVAL $masa_approve DAY) < '$data'");
		foreach ($strsql->rows as $rsa) {
			$arkab[] = array(
				'reseller_id' => $rsa['reseller_id'],
				'reseller_kode' => $rsa['reseller_kode']
			);
		}
		return $arkab;
	}
	function getResellerEksekusiMasaAktif($grpResellermasaaktifreseller, $reseller_premium, $tgldata, $tenggang_day)
	{
		$arkab = array();
		$strsql = $this->db->query("SELECT reseller_id,reseller_kode,reseller_nama, reseller_grup,rs_hrgregister,
	                        reseller_tglupdated,NOW() AS tglskarang FROM _reseller INNER JOIN 
							_reseller_grup ON _reseller.reseller_grup = _reseller.reseller_grup 
							left join _reseller_batas_aktif on _reseller.reseller_id = _reseller_batas_aktif.idreseller
							WHERE stsapprove='1' AND rs_hrgregister > 0 AND reseller_grup ='$reseller_premium' and
                            (tgl_batas + INTERVAL $notif_day MONTH) < '$tgldata'");



		foreach ($strsql->rows as $rsa) {
			$arkab[] = array(
				'reseller_id' => $rsa['reseller_id'],
				'reseller_kode' => $rsa['reseller_kode']
			);
		}
		return $arkab;
	}
	function getResellerExport($where)
	{
		$arkab = array();
		$strsql = $this->db->query("SELECT reseller_id,reseller_kode,reseller_nama, rs_grupnama,rs_grupcode,reseller_tglupdated,
							reseller_email
							FROM _reseller INNER JOIN _reseller_grup 
							ON _reseller.reseller_grup = _reseller_grup.rs_grupid
							$where ORDER by reseller_kode asc");

		foreach ($strsql->rows as $rsa) {
			$arkab[] = array(
				'reseller_kode' => $rsa['rs_grupcode'] . $rsa['reseller_kode'],
				'reseller_nama' => $rsa['reseller_nama'],
				'reseller_email' => $rsa['reseller_email'],
				'reseller_grup' => $rsa['rs_grupnama']
				//'tgl_update' => $rsa['reseller_tglupdated'],

			);
		}

		return $arkab;
	}
	function getTotalCustomer()
	{
		$sql = "SELECT count(cust_kode)  as total from _customer WHERE cust_status='1'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}

	function getAlamatCustomer($id)
	{
		$sql = "select ca_id,ca_cust_id,ca_nama,
		        ca_alamat,ca_propinsi,_provinsi.provinsi_nama,
				ca_kabupaten,_kabupaten.kabupaten_nama,
				ca_kecamatan,_kecamatan.kecamatan_nama,
				ca_kelurahan,ca_kodepos,ca_hp,ca_default
				from _customer_address 
				left join _provinsi on _customer_address.ca_propinsi = _provinsi.provinsi_id 
				left join _kabupaten on _customer_address.ca_kabupaten = _kabupaten.kabupaten_id
				left join _kecamatan on _customer_address.ca_kecamatan = _kecamatan.kecamatan_id 
				where ca_cust_id='" . $id . "' order by ca_default asc, ca_id desc";

		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = array();
			foreach ($strsql->rows as $rs) {
				$data[] = $rs;
			}
			return $data;
		}
		return false;
	}

	function getAlamatCustomerByID($id)
	{
		$sql = "select ca_id,ca_cust_id,ca_nama,
		        ca_alamat,ca_propinsi,provinsi_nama,
				ca_kabupaten,kabupaten_nama,ca_kecamatan,kecamatan_nama,
				ca_kelurahan,ca_kodepos,ca_hp,ca_default
				from _customer_address 
				left join _provinsi on _customer_address.ca_propinsi = _provinsi.provinsi_id
				left join _kabupaten on _customer_address.ca_kabupaten = _kabupaten.kabupaten_id
				left join _kecamatan on _customer_address.ca_kecamatan = _kecamatan.kecamatan_id
				
				where ca_id='" . $id . "'";

		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}

	function simpanAlamat($data)
	{

		$error = array();
		$status = '';
		$idcust = '';
		$this->db->autocommit(false);



		$default = isset($data['chkdefault']) ? $data['chkdefault'] : '0';

		/* mengecek apakah alamat yang baru diinput akan dijadikan alamat utama */
		if ($default == '1') {

			//jika dijadikan alamat utama, maka update ke table _customer alamat yang baru diinput
			$sql = "update _customer set 
					cust_alamat='" . $this->db->escape($data['add_alamat']) . "',
					cust_kelurahan='" . $this->db->escape($data['add_kelurahan']) . "',
					cust_kecamatan='" . $this->db->escape($data['add_kecamatan']) . "',
					cust_kota='" . $this->db->escape($data['add_kabupaten']) . "',
					cust_propinsi='" . $this->db->escape($data['add_propinsi']) . "',
					cust_negara='33',
					cust_kdpos='" . $data['add_kodepos'] . "',
					cust_telp='" . $data['add_telp'] . "',
					cust_tgl_upd='" . $data['tglupdate'] . "'
					where cust_id='" . $data['idcust'] . "'";

			$strsql = $this->db->query($sql);
			if (!$strsql) $error[] = "Error di update table _customer";

			/* update _customer_address menjadikan semua alamat di customer tersebut menjadi bukan defaultnya */
			$sql = "update _customer_address set ca_default='0' where ca_cust_id='" . $data['idcust'] . "'";
			$strsql = $this->db->query($sql);
			if (!$strsql) $error[] = "Error di update table _customer_address";
		}
		/* simpan ke table customer address */
		$sql = "insert into _customer_address values
				(null,'" . $data['idcust'] . "',
				'" . $this->db->escape($data['add_nama']) . "',
				'" . $this->db->escape($data['add_alamat']) . "',
				'33','" . $data['add_propinsi'] . "','" . $data['add_kabupaten'] . "',
				'" . $data['add_kecamatan'] . "','" . $data['add_kelurahan'] . "',
				'" . $data['add_kodepos'] . "','" . $data['add_telp'] . "','" . $default . "')";
		$strsql = $this->db->query($sql);

		if (!$strsql) $error[] = "Error di table customer address";

		/* proses penyimpanan */
		if (count($error) > 0) {
			$this->db->rollback();
			$status = "error";
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status" => $status);
	}

	function editAlamat($data)
	{

		$error = array();
		$status = '';
		$idcust = '';
		$this->db->autocommit(false);

		$default = isset($data['chkdefault']) ? $data['chkdefault'] : '0';

		/* mengecek apakah alamat yang baru diinput akan dijadikan alamat utama */
		if ($default == '1') {

			//jika dijadikan alamat utama, maka update ke table _customer alamat yang baru diinput
			$sql = "update _customer set 
					cust_alamat='" . $this->db->escape($data['add_alamat']) . "',
					cust_kelurahan='" . $this->db->escape($data['add_kelurahan']) . "',
					cust_kecamatan='" . $this->db->escape($data['add_kecamatan']) . "',
					cust_kota='" . $this->db->escape($data['add_kabupaten']) . "',
					cust_propinsi='" . $this->db->escape($data['add_propinsi']) . "',
					cust_negara='33',
					cust_kdpos='" . $data['add_kodepos'] . "',
					cust_telp='" . $data['add_telp'] . "',
					cust_tgl_upd='" . $data['tglupdate'] . "'
					where cust_id='" . $data['idcust'] . "'";

			$strsql = $this->db->query($sql);
			if (!$strsql) $error[] = "Error di update table _customer";

			/* update _customer_address menjadikan semua alamat di customer tersebut menjadi bukan defaultnya */
			$sql = "update _customer_address set ca_default='0' where ca_cust_id='" . $data['idcust'] . "'";
			$strsql = $this->db->query($sql);
			if (!$strsql) $error[] = "Error di update table _customer_address";
		}
		/* simpan ke table customer address */
		$sql = "update _customer_address set 
				ca_nama='" . $this->db->escape($data['add_nama']) . "',
				ca_alamat='" . $this->db->escape($data['add_alamat']) . "',
				ca_negara='33',
				ca_propinsi='" . $data['add_propinsi'] . "',
				ca_kabupaten='" . $data['add_kabupaten'] . "',
				ca_kecamatan='" . $data['add_kecamatan'] . "',
				ca_kelurahan='" . $data['add_kelurahan'] . "',
				ca_kodepos='" . $data['add_kodepos'] . "',
				ca_hp='" . $data['add_telp'] . "',
				ca_default='" . $default . "' where ca_id='" . $data['id'] . "'";
		$strsql = $this->db->query($sql);

		if (!$strsql) $error[] = "Error di table customer address";

		/* proses penyimpanan */
		if (count($error) > 0) {
			$this->db->rollback();
			$status = "error";
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status" => $status);
	}

	public function hapusAlamat($id)
	{
		$sql = "delete from _customer_address where ca_id='" . $id . "'";
		return $this->db->query($sql);
	}
	public function getCustomersBy($data)
	{
		$stsdeposito = isset($data['stsdeposito']) ? $data['stsdeposito'] : '';
		$wheredeposito = '';
		if ($stsdeposito) {
			$wheredeposito = ' and cg_deposito=1';
		}
		$sql = "select cust_id,cust_nama,cust_grup_id,
				cg_nm,cust_alamat,cust_kelurahan,
				cust_kecamatan,kecamatan_nama,
				cust_kota,kabupaten_nama,
				cust_propinsi,provinsi_nama,
				cust_negara,cust_kdpos,
				cust_telp,cust_email,cg_min_beli,cg_min_beli_syarat,
				cg_min_beli_wajib,cg_deposito,cg_diskon,cg_dropship
				from _customer inner join _customer_grup
				on _customer.cust_grup_id = _customer_grup.cg_id
				left join _provinsi on _customer.cust_propinsi = _provinsi.provinsi_id
				left join _kabupaten on _customer.cust_kota = _kabupaten.kabupaten_id
				left join _kecamatan on _customer.cust_kecamatan = _kecamatan.kecamatan_id
				where cust_nama like '%" . $this->db->escape($data['cari']) . "%'
				OR cust_telp like '%" . $this->db->escape($data['cari']) . "%'
				and cust_status = '1'" . $wheredeposito;

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
	function getCustomerPoinList($batas, $baris, $data)
	{
		$filter				= array();
		$where = '';

		if ($data['caridata'] != '') $filter[] = " cust_telp like '%" . trim(strip_tags($this->db->escape($data['caridata']))) . "%' OR cust_nama like '%" . trim(strip_tags($this->db->escape($data['caridata']))) . "%'";


		if (!empty($filter))	$where = implode(" and ", $filter);
		if ($where != '') $where = " WHERE " . $where;
		$sql = "SELECT c.cust_id,c.cust_nama, cg.cg_nm,
					   c.cust_grup_id,
					   SUM(CASE WHEN cph_tipe = 'OUT' THEN -cph_poin
					    WHEN cph_tipe = 'IN' THEN cph_poin
					    END) AS totalpoin
				FROM _customer_point_history cph
				left join _customer c on cph.cph_cust_id = c.cust_id
				LEFT JOIN _customer_grup cg ON c.cust_grup_id = cg.cg_id " . $where . " 
				group by cust_id
				ORDER BY cust_nama asc limit $batas,$baris";

		$strsql = $this->db->query($sql);
		if ($strsql) {
			$hasil = array();
			foreach ($strsql->rows as $rsa) {
				$hasil[] = $rsa;
			}
			return $hasil;
		}
		return false;
	}

	function totalCustomerPoin($data = array())
	{
		$filter				= array();
		$where = '';
		$data['caridata'] = isset($data['caridata']) ? $data['caridata'] : '';
		if ($data['caridata'] != '') $filter[] = " cust_telp like '%" . trim(strip_tags($this->db->escape($data['caridata']))) . "%' OR cust_nama like '%" . trim(strip_tags($this->db->escape($data['caridata']))) . "%'";


		if (!empty($filter))	$where = implode(" and ", $filter);
		if ($where != '') $where = " WHERE " . $where;
		$sql = "SELECT count(*) as total
				FROM _customer_point_history cph
				left join _customer c on cph.cph_cust_id = c.cust_id
				LEFT JOIN _customer_grup cg ON c.cust_grup_id = cg.cg_id " . $where . ' group by cust_id';


		$strsql = $this->db->query($sql);
		return isset($strsql->num_rows) ? $strsql->num_rows : 0;
	}
	function getPoinListById($batas, $baris, $id)
	{
		$sql = "select cph_id,cph_cust_id,cph_poin,cph_tipe,
				cph_tgl,cph_order 
				from _customer_point_history
				where cph_cust_id='" . $id . "' 
				order by cph_tgl desc limit $batas,$baris";
		$strsql = $this->db->query($sql);
		if ($strsql) {
			$hasil = [];
			foreach ($strsql->rows as $row) {
				$hasil[] = $row;
			}
			return $hasil;
		}
		return false;
	}
	function totalPagePoinById($id)
	{
		$sql = "select count(*) as total
				from _customer_point_history
				where cph_cust_id='" . $id . "'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	function totalPoinById($id)
	{
		$sql = "SELECT SUM(CASE WHEN cph_tipe = 'OUT' THEN -cph_poin
					    WHEN cph_tipe = 'IN' THEN cph_poin
					    END) AS totalpoin
				FROM _customer_point_history
				where cph_cust_id='" . $id . "'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row['totalpoin']) ? $strsql->row['totalpoin'] : 0;
	}

	function totalDepositoById($id)
	{

		$sql = "SELECT SUM(CASE WHEN cdh_tipe = 'OUT' THEN -cdh_deposito
					    WHEN cdh_tipe = 'IN' THEN cdh_deposito
					    END) AS totaldeposito
				FROM _customer_deposito_history
				where cdh_cust_id='" . $id . "'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row['totaldeposito']) ? $strsql->row['totaldeposito'] : 0;
	}

	function simpanDeposito($data)
	{
		$error = array();
		$status = '';
		$idcust = '';
		$this->db->autocommit(false);

		$sql = "insert into _customer_deposito_history values (null,
					'" . $data['iddata'] . "','" . $data['deposito'] . "',
					'IN','" . $data['tglupdate'] . "',0,'" . $data['keterangan'] . "','" . $data['bukti_transfer'] . "')";
		$strsql = $this->db->query($sql);
		if (!$strsql) $error[] = "Error di insert table _customer_deposito_history";

		$sql = "INSERT INTO _customer_aktivitas set ca_cust_id='" . $data['iddata'] . "',ca_tgl='" . $data['tglupdate'] . "', ca_aktivitas='" . $data['aktivitas'] . "',ca_keterangan='" . $data['keterangan'] . "'";
		$strsql = $this->db->query($sql);
		if (!$strsql) $error[] = "Error di insert table _customer_aktivitas";



		/* proses penyimpanan */
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
