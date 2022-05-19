<?php
class model_Account
{
	private $db;
	private $tabelnya;

	public function __construct()
	{
		$this->tabelnya = '_customer';
		$this->db 		= new Database();
		$this->db->connect();
	}

	public function checkDataAccount($email)
	{
		$check = $this->db->query("select count(*) as total from " . $this->tabelnya . " where cust_email='$email'");
		if ($check->row['total'] > 0) return true;
		else return false;
	}

	public function Simpan($data)
	{
		$sql = $this->db->query("UPDATE " . $this->tabelnya . " set cust_nama='" . htmlspecialchars(htmlentities($this->db->escape($data['rnama']))) . "',
                            cust_telp = '" . $data['rtelp'] . "',
							cust_email = '" . $data['remail'] . "',
							cust_tgl_upd ='" . $data['tglupdate'] . "' WHERE cust_id='" . $data['id'] . "'");

		if ($sql) return true;
		else return false;
	}

	public function simpanHistory($data)
	{
		$sql = $this->db->query("INSERT INTO _cust_history values (null,'" . $data['kode'] . "',NOW(),'" . $data['tipe_reseller'] . "','1')");
		if ($sql) return true;
		else false;
	}

	public function simpanInvoice($data)
	{
		$sql = $this->db->query("INSERT INTO _cust_invoice values (null,'" . $data['id'] . "','" . $data['tipe_reseller'] . "',NOW(),'" . $data['biaya_register'] . "','0')");
		if ($sql) return true;
		else false;
	}
	public function simpanAlamat($data)
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
					cust_alamat='" . htmlspecialchars(htmlentities($this->db->escape($data['add_alamat']))) . "',
					cust_kelurahan='" . htmlspecialchars(htmlentities($this->db->escape($data['add_kelurahan']))) . "',
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
				'" . htmlspecialchars(htmlentities($this->db->escape($data['add_nama']))) . "',
				'" . htmlspecialchars(htmlentities($this->db->escape($data['add_alamat']))) . "',
				'33','" . $data['add_propinsi'] . "','" . $data['add_kabupaten'] . "',
				'" . $data['add_kecamatan'] . "','" . htmlspecialchars(htmlentities($this->db->escape($data['add_kelurahan']))) . "',
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

	public function updateAlamat($data)
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
					cust_alamat='" . htmlspecialchars(htmlentities($this->db->escape($data['add_alamat']))) . "',
					cust_kelurahan='" . htmlspecialchars(htmlentities($this->db->escape($data['add_kelurahan']))) . "',
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
				ca_nama='" . htmlspecialchars(htmlentities($this->db->escape($data['add_nama']))) . "',
				ca_alamat='" . htmlspecialchars(htmlentities($this->db->escape($data['add_alamat']))) . "',
				ca_negara='33',
				ca_propinsi='" . $data['add_propinsi'] . "',
				ca_kabupaten='" . $data['add_kabupaten'] . "',
				ca_kecamatan='" . $data['add_kecamatan'] . "',
				ca_kelurahan='" . htmlspecialchars(htmlentities($this->db->escape($data['add_kelurahan']))) . "',
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
	public function listAlamat($idmember)
	{
		$sql = "select ca_id,ca_cust_id,ca_nama,ca_alamat,
				ca_propinsi,provinsi_nama,ca_kabupaten,kabupaten_nama,
				ca_kecamatan,kecamatan_nama,ca_kelurahan,
				ca_kodepos,ca_hp,ca_default
				from _customer_address
				left join _provinsi on ca_propinsi = provinsi_id
				left join _kabupaten on ca_kabupaten = kabupaten_id
				left join _kecamatan on ca_kecamatan = kecamatan_id
				where ca_cust_id='" . $idmember . "'";
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

	public function cariNama($idmember, $name)
	{
		$sql = "select ca_id,ca_cust_id,ca_nama,ca_alamat,
				ca_propinsi,provinsi_nama,ca_kabupaten,kabupaten_nama,
				ca_kecamatan,kecamatan_nama,ca_kelurahan,
				ca_kodepos,ca_hp,ca_default
				from _customer_address
				left join _provinsi on ca_propinsi = provinsi_id
				left join _kabupaten on ca_kabupaten = kabupaten_id
				left join _kecamatan on ca_kecamatan = kecamatan_id
				where ca_cust_id='" . $idmember . "' and ca_nama like '%". $name ."%'";
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

	public function ubahpassword($data)
	{
		$sql = "update _customer set cust_pass='" . $data['newpassword'] . "' where cust_id='" . $data['idmember'] . "'";
		return $this->db->query($sql);
	}
}
