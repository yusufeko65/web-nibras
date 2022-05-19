<?php
class modelLapCustomer
{
	private $db;
	private $tabelnya;

	public function __construct()
	{
		$this->db 		= new Database();
		$this->db->connect();
	}

	public function getCustomer($data)
	{
		$where = '';

		if ($data['grup'] != '' && $data['grup'] != '0') $filter[] = "cust_grup_id= '" . trim(strip_tags(urlencode($data['grup']))) . "'";
		if ($data['bulan'] != '') $filter[] = " MONTH(cust_tgl_add)= '" . trim(strip_tags(urlencode($data['bulan']))) . "'";
		if ($data['tahun'] != '') $filter[] = " YEAR(cust_tgl_add)= '" . trim(strip_tags(urlencode($data['tahun']))) . "'";

		if (!empty($filter))	$where = implode(" and ", $filter);



		if ($where != '') $where = " WHERE $where";

		$sql = "SELECT cust_id,cust_grup_id,cg_nm,cust_nama,cust_alamat,kabupaten_nama,
		                    cust_email,cust_telp,cust_tgl_add 
							FROM _customer LEFT JOIN _customer_grup 
							ON _customer.cust_grup_id = _customer_grup.cg_id
							LEFT JOIN _kabupaten 
							ON _customer.cust_kota = _kabupaten.kabupaten_id $where";
		$strsql = $this->db->query($sql);
		if ($strsql) {
			$result = [];
			foreach ($strsql->rows as $row) {
				$result[] = $row;
			}
		} else {
			$result = false;
		}
		return $result;
	}

	function getCustomerDaily($data)
	{
		if ($data['grup'] != '' && $data['grup'] != '0') $filter[] = " cust_grup_id= '" . trim(strip_tags(urlencode($data['grup']))) . "'";
		if ($data['tanggal1'] != '' && $data['tanggal2'] != '') {
			$filter[] = " DATE_FORMAT(cust_tgl_add, '%Y-%m-%d') BETWEEN  '" . trim(strip_tags(urlencode($data['tanggal1']))) . "' AND '" . trim(strip_tags(urlencode($data['tanggal2']))) . "'";
		}
		if (!empty($filter))	$where = implode(" and ", $filter);

		if ($where != '') $where = " WHERE $where";
		$sql = "SELECT cust_id,cust_grup_id,cg_nm,cust_nama,cust_alamat,kabupaten_nama,
		                    cust_email,cust_telp,cust_tgl_add 
							FROM _customer LEFT JOIN _customer_grup 
							ON _customer.cust_grup_id = _customer_grup.cg_id
							LEFT JOIN _kabupaten 
							ON _customer.cust_kota = _kabupaten.kabupaten_id $where";

		$sql = $this->db->query($sql);

		if ($sql) {
			$arkab = array();
			foreach ($sql->rows as $rsa) {
				$arkab[] = $rsa;
			}
			return $arkab;
		}
		return false;
	}
}
