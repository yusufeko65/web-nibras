<?php
class model_Atribut {
	private $db;
	private $tabelnya;
	private $breadcrumbs = array();
	public function __construct(){
		
		$this->db 		= new Database();
		$this->db->connect();
		
	}
	
	public function getWarna(){
		$sql = "select * from _warna order by warna asc";
		$strsql = $this->db->query($sql);
		if($strsql){
			$data = [];
			foreach($strsql->rows as $row){
				$data[] = $row;
			}
			
		} else {
			$data = false;
		}
		return $data;
	}
	public function getUkuran(){
		$sql = "select * from _ukuran order by ukuran asc";
		$strsql = $this->db->query($sql);
		if($strsql){
			$data = [];
			foreach($strsql->rows as $row){
				$data[] = $row;
			}
			
		} else {
			$data = false;
		}
		return $data;
	}
	
	public function getWarnaByAlias($alias){
		$sql = "select idwarna,warna,alias from _warna where alias='".$this->db->escape($alias)."'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	
	public function getUkuranByAlias($alias){
		$sql = "select idukuran,ukuran,alias from _ukuran where alias='".$this->db->escape($alias)."'";
		
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	
}