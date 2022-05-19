<?php
class DataModelBanner {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_banner';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	
		
	function getBanner(){
		$arBanner = array();
		$strsql=mysql_query("select * from ".$this->tabelnya." order by Banner_nama asc ");
		while ($rsa=mysql_fetch_array($strsql)){
			$arBanner[] = array(
				'ids' => $rsa['Banner_id'],
				'nms' => $rsa['Banner_nama']
			);
		}
		return $arBanner;
	}
	
	function getBannerBySlot($slot){
		$strsql=mysql_query("select * from ".$this->tabelnya." where slot_banner='".$slot."' and tampil='1'");
		if($strsql){
		   $rsa=mysql_fetch_array($strsql);
		   return $rsa;
		} else {
		   return false;
		}
	}
	
	function __destruct() {
		$this->db->disconnect();
	}
}
?>