<?php
class modelOrderStatus {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_status_order';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataOrderStatus($status_order_nama){
		$check = $this->db->query("select status_nama from ".$this->tabelnya." where status_nama='$status_order_nama'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataOrderStatusByID($status_order_id){
		$check = $this->db->query("select status_nama from ".$this->tabelnya." where status_id='$status_order_id'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanOrderStatus($data){
		return $this->db->query("insert into ".$this->tabelnya." values (null,'".$data['status_nama']."','".$data['status_keterangan']."')");
		
	}
	
	function editOrderStatus($data){
	   return $this->db->query("update ".$this->tabelnya." set status_nama='".$data['status_nama']."',status_keterangan='".$data['status_keterangan']."' where status_id='".$data['status_id']."'");
	  
	}
	
	function getOrderStatus(){
		$arstatus_order = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya." order by status_nama asc ");
		foreach ($strsql->rows as $rsa) {
			$arstatus_order[] = array(
				'ids' => $rsa['status_id'],
				'nms' => $rsa['status_nama']
			);
		}
		return $arstatus_order;
	}
	function getOrderStatusLimit($batas,$baris,$data){
		$where = '';
		$hasil = array();
		if($data['caridata']!='') $filter[] = " status_nama like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where) $where = " WHERE ".$where;
		$sql = "SELECT status_id, status_nama 
				FROM _status_order ".$where." 
				ORDER BY status_id desc limit $batas,$baris";
		
		$strsql = $this->db->query($sql);
		foreach ($strsql->rows as $rsa) {
			$hasil[] = $rsa;
		}
		return $hasil;
	}
	
	function totalOrderStatus($data){
		$where = '';
		
		if($data['caridata']!='') $filter[] = " status_nama like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where) $where = " WHERE ".$where;
		
	    
		$strsql=$this->db->query("select count(*) from ".$this->tabelnya.$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	function getOrderStatusByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where status_id='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : array();
	}
	
	function checkRelasi($data){
		$check = $this->db->query("select status_order_id from _provinsi where status_order_id='$data'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	function hapusOrderStatus($data){
		return $this->db->query("delete from ".$this->tabelnya." where status_id='$data'");
		
	}
	function __destruct() {
		$this->db->disconnect();
	}
}
?>