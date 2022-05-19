<?php
class modelNegara {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_negara';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataNegara($negara_nama){
		$check = $this->db->query("select negara_nama from ".$this->tabelnya." where negara_nama='$negara_nama'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataNegaraByID($negara_id){
		$check = $this->db->query("select negara_nama from ".$this->tabelnya." where negara_id='$negara_id'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanNegara($data=array()){
	   return $this->db->query("insert into ".$this->tabelnya." values (null,'".$data['negara_nama']."')");
	}
	
	function editNegara($data=array()){
	   return $this->db->query("update ".$this->tabelnya." set negara_nama='".$data['negara_nama']."' where negara_id='".$data['negara_id']."'");
	}
	
	function getNegara(){
		$arnegara = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya." order by negara_nama asc ");
		foreach ($strsql->rows as $rsa) {
			$arnegara[] = array(
				'idn' => $rsa['negara_id'],
				'nmn' => $rsa['negara_nama']
			);
		}
		return $arnegara;
	}
	function getNegaraLimit($batas,$baris,$data){
		$hasil = array();
		$where = '';
		$filter = array();
		
		if($data['caridata'] != '') $filter[] = " negara_nama like '%".trim(strip_tags($this->db->escape($data['caridata'])))."%'";
		
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where) $where = " WHERE ".$where;
		$sql = "SELECT negara_id, negara_nama FROM _negara ".$where." ORDER BY negara_id desc limit $batas,$baris";
		
		$strsql = $this->db->query($sql);
		foreach ($strsql->rows as $rsa) {
			$hasil[] = $rsa;
		}
		return $hasil;
	}
	
	function totalNegara($data){
		$hasil = array();
		$where = '';
		$filter = array();
		
		if($data['caridata'] != '') $filter[] = " negara_nama like '%".trim(strip_tags($this->db->escape($data['caridata'])))."%'";
		
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		//if($where) $where = " WHERE ".$where;
	    if($where!='') $where = " where ".$where;
		$strsql=$this->db->query("select count(*) as total from _negara ".$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	
	function getNegaraByID($iddata){
		$strsql=$this->db->query("select * from _negara where negara_id='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : array();
	}
	
	function checkRelasi($data){
		$check 	= $this->db->query("select negara_id from _provinsi where negara_id='".$data."'");
		$jml	= $check->num_rows;
		
		if($jml>0) return true;
		else return false;
	}
	function hapusNegara($data){
		return $this->db->query("delete from _negara where negara_id='".$data."'");
	}
	function __destruct() {
		$this->db->disconnect();
		
	}
}
?>