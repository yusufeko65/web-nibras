<?php
class model_Ukuran {
	private $db;
	private $tabelnya;
	
	public function __construct(){
		$this->tabelnya = '_ukuran';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	public function getUkuran(){
		$arukuran = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya.' ORDER BY ukuran asc');
		foreach ($strsql->rows as $rsa) {
		   $arukuran[] = array(
				'idukuran' => $rsa['idukuran'],
				'nmukuran' => $rsa['ukuran'],
				'aliasukuran' => $rsa['alias']
			);
		}
		
		return $arukuran;
	}
	
	function getUkuranByIDAlias($iddata,$alias){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where idukuran='".$iddata."' AND alias='".$alias."'");
		if($strsql->num_rows){
		   return $strsql->row;
		} else {
		   return false;
		}
	}
	public function __destruct() {
		$this->db->disconnect();
	}
}
?>