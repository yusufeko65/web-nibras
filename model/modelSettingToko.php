<?php
class model_SettingToko {
	private $db;
	private $tabelnya;
	private $userlogin;
	
	public function __construct(){
		$this->tabelnya = '_setting';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	public function getSettingToko(){
	    $data = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya);
		foreach ($strsql->rows as $rsa) {
		  $data[] = $rsa;
		}
		
		return $data;
	}
	
	public function getSettingTokoByKey($key){
		$sql = "select setting_key,setting_value 
				from _setting where setting_key='".$key."'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row['setting_value']) ? $strsql->row['setting_value'] : false;
	}
	function getSettingByKeys($keys){
	    $data = array();
	    $key = '';
	    
		$jmlkey = count($keys);
		for($i=0;$i<$jmlkey;$i++) {
		   $key .= "'".$keys[$i]."'";
           if($i < $jmlkey-1) {
		      $key .= ",";
		   }
		}
		$where = ' WHERE setting_key IN ('.$key.')';
		$sql = "SELECT setting_grup,setting_key,setting_value from ".$this->tabelnya." $where ";
		
		$sql = $this->db->query($sql);
		
		foreach($sql->rows as $rs) {
		   $data[] = $rs;
		}
		return $data;
		
	}
	public function __destruct() {
		$this->db->disconnect();
	}
}
?>