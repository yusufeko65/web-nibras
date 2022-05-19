<?php
class model_SlideShow {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_slideshow';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	
		
	function getSlideShow(){
		$arSlideShow = array();
		
		$strsql=$this->db->query("select * from ".$this->tabelnya."  where sts_slide='1' order by urutan asc ");
		foreach ($strsql->rows as $rsa) {
			$arSlideShow[] = array(
				'ids' => $rsa['id_slide'],
				'nms' => $rsa['nama_slide'],
				'gbr' => $rsa['gbr_slide'],
				'url' => $rsa['link_slide']
			);
		}
		return $arSlideShow;
	}
	
	function getSlideShowBySlot($slot){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where slot_SlideShow='".$slot."' and tampil='1'");
		return $strsql->rows;
	}
	
	function __destruct() {
		$this->db->disconnect();
	}
}
?>