<?php
class modelLapOrder
{
	private $db;
	private $tabelnya;

	public function __construct()
	{
		$this->db 		= new Database();
		$this->db->connect();
	}

	public function getOrder($data)
	{
		$where = '';
		$filter = [];
		if ($data['status'] != '' && $data['status'] != '0') $filter[] = " _order.status_id= '" . trim(strip_tags(urlencode($data['status']))) . "'";
		if ($data['bulan'] != '') $filter[] = " MONTH(pesanan_tgl)= '" . trim(strip_tags(urlencode($data['bulan']))) . "'";
		if ($data['tahun'] != '') $filter[] = " YEAR(pesanan_tgl)= '" . trim(strip_tags(urlencode($data['tahun']))) . "'";


		if (!empty($filter))	$where = implode(" and ", $filter);

		if ($where != '') $where = " WHERE $where";

		$sql = "SELECT idpesanan,_order.pesanan_no,cust_nama,pesanan_jml as jml,pesanan_subtotal as subtotal,pesanan_tgl as tgl,status_nama as status,pesanan_kurir,dari_poin,dari_deposito
				FROM _order INNER JOIN _status_order ON _order.status_id = _status_order.status_id
				INNER JOIN _customer ON _order.pelanggan_id = _customer.cust_id $where";

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

	public function getOrderDaily($data)
	{
		$where = '';
		$filter = [];
		if ($data['status'] != '' && $data['status'] != '0') {
			$filter[] = " _order.status_id= '" . trim(strip_tags(urlencode($data['status']))) . "'";
		}
		if ($data['tanggal1'] != '' && $data['tanggal2'] != '') {
			if ($data['status'] == $data['status_order_kirim']) {
				$filter[] = " DATE_FORMAT(tgl_kirim, '%Y-%m-%d') BETWEEN  '" . trim(strip_tags(urlencode($data['tanggal1']))) . "' AND '" . trim(strip_tags(urlencode($data['tanggal2']))) . "'";
			} else {
				$filter[] = " DATE_FORMAT(pesanan_tgl, '%Y-%m-%d') BETWEEN  '" . trim(strip_tags(urlencode($data['tanggal1']))) . "' AND '" . trim(strip_tags(urlencode($data['tanggal2']))) . "'";
			}
		}
		if ($data['customer_id'] != '') $filter[] = " pelanggan_id='" . $data['customer_id'] . "'";


		if (!empty($filter))	$where = implode(" and ", $filter);

		if ($where != '') $where = " WHERE $where";

		$sql = "SELECT idpesanan,_order.pesanan_no,cust_nama,pesanan_jml as jml,
				pesanan_subtotal as subtotal,pesanan_tgl as tgl,status_nama as status,pesanan_kurir,dari_poin,dari_deposito,tgl_kirim
				FROM _order INNER JOIN _status_order ON _order.status_id = _status_order.status_id
				INNER JOIN _customer ON _order.pelanggan_id = _customer.cust_id $where";

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
	
	public function getTotalOrderDaily($data){
		
		$where = '';
		$filter = [];
		$data['status'] = isset($data['status']) ? $data['status']: '';
		$data['customer_id'] = isset($data['customer_id']) ? $data['customer_id'] : '';
		if ($data['status'] != '' && $data['status'] != '0') {
			$filter[] = " _order.status_id= '" . trim(strip_tags(urlencode($data['status']))) . "'";
		}
		if ($data['tanggal1'] != '' && $data['tanggal2'] != '') {
			if ($data['status'] == $data['status_order_kirim']) {
				$filter[] = " DATE_FORMAT(tgl_kirim, '%Y-%m-%d') BETWEEN  '" . trim(strip_tags(urlencode($data['tanggal1']))) . "' AND '" . trim(strip_tags(urlencode($data['tanggal2']))) . "'";
			} else {
				$filter[] = " DATE_FORMAT(pesanan_tgl, '%Y-%m-%d') BETWEEN  '" . trim(strip_tags(urlencode($data['tanggal1']))) . "' AND '" . trim(strip_tags(urlencode($data['tanggal2']))) . "'";
			}
		}
		if ($data['customer_id'] != '') $filter[] = " pelanggan_id='" . $data['customer_id'] . "'";


		if (!empty($filter))	$where = implode(" and ", $filter);

		if ($where != '') $where = " WHERE $where";
		
		$sql = "SELECT sum(pesanan_subtotal + pesanan_kurir) as total
				FROM _order 
				INNER JOIN _status_order ON _order.status_id = _status_order.status_id
				INNER JOIN _customer ON _order.pelanggan_id = _customer.cust_id $where";
		
		$sql = $this->db->query($sql);
		return $sql->row;
		
	}
}
