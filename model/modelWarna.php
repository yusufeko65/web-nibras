<?php
class model_Warna {
	private $db;
	private $tabelnya;
	
	public function __construct(){
		$this->tabelnya = '_warna';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	public function getWarna(){
		$arWarna = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya.' ORDER BY warna asc');
		foreach ($strsql->rows as $rsa) {
		   $arWarna[] = array(
				'idwarna'    => $rsa['idwarna'],
				'nmwarna'    => $rsa['warna'],
				'aliaswarna' => $rsa['alias']
			);
		}
		
		return $arWarna;
	}
	
	function getWarnaByIDAlias($iddata,$alias){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where idwarna='".$iddata."' AND alias='".$alias."'");
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