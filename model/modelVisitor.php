<?php
class DataModelVisitor {
	private $db;
	private $tabelnya;
	private $ip;
	private $tgl;
	
	public function __construct(){
		$this->tabelnya = '_visitor';
		$this->db 		= new Database();
		$this->db->connect();
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->tgl = date('Y-m-d');
	}
	
	
	public function addVisitor(){
		$str = mysql_query("INSERT INTO ".$this->tabelnya." values ('".mysql_real_escape_string($this->ip)."','".$this->tgl."',1) ON DUPLICATE KEY UPDATE count=count+1");
		if($str){
		  return true;
		} else {
		  return false;
		}
	}
	
	public function __destruct() {
		$this->db->disconnect();
	}
}
?>