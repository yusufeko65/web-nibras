<?php
class model_Propinsi {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_provinsi';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	
	function getPropinsi(){
		$arprovinsi = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya." order by provinsi_nama asc ");
	
		foreach ($strsql->rows as $rsa) {
			$arprovinsi[] = array(
				'idp' => $rsa['provinsi_id'],
				'nmp' => $rsa['provinsi_nama']
			);
		}
		return $arprovinsi;
	}
	
	function getPropinsiByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where provinsi_id='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : array();
	}
	
	
}
?>