<?php
class modelBank {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_bank';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataBank($bank_nama){
		$check = $this->db->query("select bank_nama from ".$this->tabelnya." where bank_nama='$bank_nama'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataBankByID($bank_id){
		$check = $this->db->query("select bank_nama from ".$this->tabelnya." where bank_id='$bank_id'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanBank($data){
	   return $this->db->query("insert into ".$this->tabelnya." values (null,'".$data['bank_nama']."','".$data['logo_name']."','".$data['bank_status']."')");
	   
	}
	
	function editBank($data){
	   return $this->db->query("update ".$this->tabelnya." set bank_nama='".$data['bank_nama']."',bank_logo='".$data['bank_logo']."',bank_status='".$data['bank_status']."' where bank_id='".$data['bank_id']."'");
	}
	
	function getBank(){
		$arbank = array();
		$strsql=$this->db->query("select bank_id,bank_nama from ".$this->tabelnya." order by bank_nama asc ");
		foreach ($strsql->rows as $rsa) {
			$arbank[] = array(
				'ids' => $rsa['bank_id'],
				'nms' => $rsa['bank_nama']
			);
		}
		return $arbank;
	}
	function getBankLimit($batas,$baris,$data){
	  
		$hasil = array();
		$where = '';
		$filter = array();
		
		if($data['caridata']!='') $filter[] = " bank_nama like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where) $where = " WHERE ".$where;
		$sql = "SELECT bank_id, bank_nama, bank_logo, bank_status 
				FROM _bank ".$where." 
				ORDER BY bank_id desc limit $batas,$baris";
		
		$strsql = $this->db->query($sql);
		foreach ($strsql->rows as $rsa) {
			$hasil[] = $rsa;
		}
		return $hasil;
	}
	
	function totalBank($data){
		
		$where = '';
		
	    if($data['caridata']!='') $filter[] = " bank_nama like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where) $where = " WHERE ".$where;
		
		
		$strsql=$this->db->query("select count(*) as total from _bank ".$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	
	function getBankByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where bank_id='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : array();
	}
	
	function checkRelasi($data){
		$check = $this->db->query("select bank_id from _bank_rekening where bank_id='$data'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	function hapusBank($data){
		return $this->db->query("delete from ".$this->tabelnya." where bank_id='$data'");
		
	}
	
}
?>