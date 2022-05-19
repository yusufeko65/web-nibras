<?php
class modelLapProdukLaris {
	private $db;
	private $tabelnya;
	
	public function __construct(){
		$this->db 		= new Database();
		$this->db->connect();
	}
    
	public function getResult($data,$jmllimit){
	    $where = '';
		
		if($data['bulan']!='') $filter[] = " MONTH(tanggal)= '".trim(strip_tags(urlencode($data['bulan'])))."'";
		if($data['tahun']!='') $filter[] = " YEAR(tanggal)= '".trim(strip_tags(urlencode($data['tahun'])))."'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where != '') $where = " AND $where";
		
		$sql = $this->db->query("SELECT SUM(jml) AS jumlah,kode_produk,nama_produk,_order.status_id,_order.pesanan_no,tanggal 
							FROM _order_detail 
							LEFT JOIN _order ON _order_detail.pesanan_no = _order.pesanan_no
							LEFT JOIN _order_status  ON _order_status.nopesanan = _order.pesanan_no  AND _order_status.status_id='".$data['status']."'
							LEFT JOIN _produk ON _produk.idproduk = _order_detail.produk_id
							LEFT JOIN _produk_deskripsi ON _produk_deskripsi.idproduk=_order_detail.produk_id
							WHERE (_order.status_id='".$data['status']."' AND _produk.status_produk='1') $where GROUP BY kode_produk,nama_produk ORDER BY jumlah DESC LIMIT $jmllimit");
		
		if($sql){
			$arkab = array();
			foreach($sql->rows as $rsa){
				$arkab[] = array(
					'nama' => $rsa['nama_produk'],
					'kode' => $rsa['kode_produk'],
					'jml' => $rsa['jumlah']
				);
			}
			return $arkab;
		}
		return false;
	}
}
?>