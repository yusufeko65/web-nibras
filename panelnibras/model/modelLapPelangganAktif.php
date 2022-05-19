<?php
class modelLapPelangganAktif {
	private $db;
	private $tabelnya;
	
	public function __construct(){
		$this->db 		= new Database();
		$this->db->connect();
	}
    
	public function getPelangganByWaktuBelanja($waktu,$tgl,$grup,$status){
		
		if($grup != '0') {
			$w = " AND (cust_grup_id='".$grup."') ";
		} else {
			$w = "";
		}
	 
		if($status == '1') {
			$wj = "jml > 0";
		} else {
			$wj = "jml < 1";
		}
		$sql = "SELECT cust_id,cust_kode,cust_nama,cust_grup_id,cg_nm,jml FROM (SELECT cust_id,cust_kode,cust_nama,cust_grup_id,cg_nm,
               (SELECT COUNT(_order.pesanan_no) FROM _order WHERE _order.pelanggan_id=_customer.cust_id
               AND _order.pesanan_tgl BETWEEN ('$tgl' + INTERVAL -2 MONTH) AND '2015-05-01') AS jml
               FROM _customer 
	           LEFT JOIN _customer_grup ON _customer.cust_grup_id = _customer_grup.cg_id
			   LEFT JOIN _customer_history ON _customer.cust_id = _customer_history.ch_cust_id
               WHERE ch_cust_default='1' $w
			   AND (ch_cust_tgl + INTERVAL 2 MONTH) < '$tgl') as data WHERE $wj";
       
		$strsql = $this->db->query($sql);
		if($strsql){
			$ar = array();
			foreach($strsql->rows as $r){
				$ar[] = array(
					 'id' => $r['cust_id'],
					 'kode' => $r['cust_kode'],
					 'nama' => $r['cust_nama'],
					 'grup' => $r['cg_nm'],
					 'jml' => $r['jml']
				);
		   }
		   return $ar;
		} 
		return false;		
	}
	function jumlahBelanja($reseller_id,$grpResellertargetmasaaktif,$tgl) {
		$strsql = $this->db->query("SELECT COUNT(pesanan_no) as total FROM _order WHERE pelanggan_id='$reseller_id' AND pesanan_tgl BETWEEN ('$tgl' + INTERVAL -$grpResellertargetmasaaktif MONTH) AND '$tgl'");
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
}