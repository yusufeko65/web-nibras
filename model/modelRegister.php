<?php
class model_Register
{
	private $db;
	private $tabelnya;

	public function __construct()
	{
		$this->tabelnya = '_customer';
		$this->db 		= new Database();
		$this->db->connect();
	}

	public function checkDataRegister($email)
	{
		$sql = "select count(*) as total from " . $this->tabelnya . " where cust_email='$email'";

		$check = $this->db->query($sql);
		if ($check->row['total'] > 0) return true;
		else return false;
	}

	public function getCustomerByEmail($email)
	{
		$sql = "select * from _customer where cust_email='" . $email . "'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}

	public function checkDataRegHP($nohp)
	{
		$check = $this->db->query("select count(*) as total from " . $this->tabelnya . " where cust_telp='$nohp'");

		if ($check->row['total'] > 0) return true;
		else return false;
	}

	public function checkBiayaRegister($grup)
	{
		$check = $this->db->query("select rs_hrgregister from _reseller_grup where rs_grupid='" . $grup . "'");

		if ($rs) return $rs[0];
		else return '0';
	}
	public function SimpanBatasAktif($data)
	{
		$sql = $this->db->query("INSERT INTO _reseller_batas_aktif values ('" . $data['id'] . "','" . $data['tglexpired'] . "','0')");
	}
	public function Simpan($data)
	{
		$status = '';
		$idcust = 0;
		$error = array();
		$this->db->autocommit(false);

		/* simpan ke table _customer */

		$sql = "INSERT INTO " . $this->tabelnya . " values (null,
							'" . $data['rtipereseller'] . "',
							'" . htmlentities(htmlentities($this->db->escape($data['rnama']))) . "',
							'" . htmlentities(htmlentities($this->db->escape($data['ralamat']))) . "',
							'" . htmlentities(htmlentities($this->db->escape($data['rkelurahan']))) . "',
							'" . $data['rkecamatan'] . "',
							'" . $data['rkabupaten'] . "',
							'" . $data['rpropinsi'] . "',
							'33',
							'" . $data['rkdpos'] . "',
							'" . $data['rtelp'] . "',
							'" . $data['remail'] . "',
							'" . $data['rpass'] . "',
							'0',
							'" . $data['approve'] . "',
							'" . $data['status'] . "',
							'" . $data['tglregis'] . "',
							'" . $data['tglregis'] . "')";

		$strsql = $this->db->query($sql);

		if (!$strsql) {
			$error[] = "Error di table customer";
		} else {
			$idcust = $this->db->lastid();
		}

		/* update table customer history */
		$sql = "update _customer_history set ch_cust_default=0 where ch_cust_id='" . $idcust . "'";
		$strsql = $this->db->query($sql);

		if (!$strsql) $error[] = "Error di update table customer history";

		/* insert table cutomer history */
		$sql = "insert into _customer_history values 
				(null,'" . $idcust . "','" . $data['rtipereseller'] . "',
				'" . $data['tglregis'] . "','1','" . $data['ipaddress'] . "')";
		$strsql = $this->db->query($sql);

		if (!$strsql) $error[] = "Error di insert table customer history";

		/* simpan ke table customer aktivitas*/
		$sql = "insert into _customer_aktivitas values 
				(null,'" . $idcust . "','" . $data['tglregis'] . "','" . $data['aktivitas'] . "',
				'" . htmlspecialchars(htmlentities($this->db->escape($data['keterangan']))) . "')";
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
		return array("status" => $status, "idcust" => $idcust);
	}

	public function simpanHistory($data)
	{
		$sqlu = $this->db->query("UPDATE _customer_history set ch_cust_default='0' WHERE ch_cust_id='" . $data['id'] . "'");

		$sql = $this->db->query("INSERT INTO _customer_history values (null,'" . $data['id'] . "','" . $data['tipe_reseller'] . "',
	                      '" . $data['tglregis'] . "','1','" . $data['ipaddress'] . "')");
	}

	public function simpanAktivitas($data)
	{
		$keterangan = htmlspecialchars(htmlentities($this->db->escape($data['keterangan'])));
		$str  = "INSERT INTO _customer_aktivitas set 
				 ca_cust_id='" . $data['id'] . "',
				ca_tgl='" . $data['tglregis'] . "',
				ca_aktivitas='" . $data['aktivitas'] . "',
				ca_keterangan='" . $keterangan . "'";
		$this->db->query($str);
	}

	public function simpanInvoice($data)
	{
		$sql = $this->db->query("INSERT INTO _reseller_invoice values (null,'" . $data['id'] . "','" . $data['tipe_reseller'] . "','" . $data['tglregis'] . "','" . $data['biaya_register'] . "','" . $data['stsbayar'] . "','" . $data['keterangan'] . "')");
	}

	public function resetPassword($data)
	{
		$sql = "UPDATE _customer set cust_pass = '" . $data['resetpass'] . "' WHERE cust_email='" . $data['email'] . "'";
		$sql = $this->db->query($sql);
	}
}
