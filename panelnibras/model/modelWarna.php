<?php
class modelWarna {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_warna';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataWarna($warna){
		//$check = $this->db->query("select warna from ".$this->tabelnya." where BINARY warna='$warna'");
		$check = $this->db->query("select warna from ".$this->tabelnya." where warna='$warna'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataWarnaByID($id){
		$check = $this->db->query("select warna from ".$this->tabelnya." where idwarna='$id'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanWarna($data){
	   $this->db->query("insert into ".$this->tabelnya." values (null,'".$data['warna_nama']."','".$data['warna_alias']."')");
	   
	   $inisial = 'warna='.$data['warna_id'];
	   
	   $del = $this->db->query("delete from _url_alias WHERE inisial='".$inisial."'");
	   $sql = $this->db->query("insert into _url_alias values ('".$inisial."','".$data['warna_alias']."','produk')");
	   
	   if($sql) return true;
	   else return false;
	   
	   //if($sql) return $this->simpanAliasWarna($data);
	   //else return false;
	}
	
	function editWarna($data){
		$inisial = 'warna='.$data['warna_id'];
		
		$sql=$this->db->query("update ".$this->tabelnya." set warna='".$data['warna_nama']."',alias='".$data['warna_alias']."' where idwarna='".$data['warna_id']."'");
		$del = $this->db->query("delete from _url_alias WHERE inisial='".$inisial."'");
		$sql = $this->db->query("insert into _url_alias values ('".$inisial."','".$data['warna_alias']."','produk')");
		if($sql) return true;
		else return false;
	}
	function simpanAliasWarna($data){
	   $inisial = 'warna='.$data['warna_id'];
	   
	   $del = $this->db->query("delete from _url_alias WHERE inisial='".$inisial."'");
	   $sql = $this->db->query("insert into _url_alias values ('".$inisial."','".$data['warna_alias']."','produk')");
	   if($sql) return true;
	   else return false;
	}
	function getWarna(){
		$data = [];
		$strsql=$this->db->query("select * from ".$this->tabelnya." order by warna asc ");
		if($strsql){
			foreach($strsql->rows as $rs){
				$data[] = $rs;
			}
		}
		return $data;
	}
	function getWarnaLimit($batas,$baris,$data){
		
		$hasil = array();
		$where = '';
		$filter = array();
		
		if($data['caridata'] != '') $filter[] = " warna like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		
		if($where) $where = " WHERE ".$where;
		$sql = "SELECT idwarna, warna 
				FROM _warna ".$where." 
				ORDER BY idwarna desc limit $batas,$baris";
		
		$strsql = $this->db->query($sql);
		if($strsql){
			foreach ($strsql->rows as $rsa) {
				$hasil[] = $rsa;
			}
		}
		return $hasil;
		
		
	}
	
	function totalWarna($data){
		$where = '';
		
		if($data['caridata'] != '') $filter[] = " warna like '%".trim(strip_tags($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
	    if($where!='') $where = " where ".$where;
				
		$strsql=$this->db->query("select count(*) as total from _warna ".$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	function getWarnaByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where idwarna='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : array();
	}
	
	function checkRelasi($data){
		$check = $this->db->query("select idwarna from _provinsi where provinsi_id='$data'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	function hapusWarna($data){
		return $this->db->query("delete from ".$this->tabelnya." where idwarna='$data'");
		
	}
	function __destruct() {
		//$this->db->disconnect();
	}
}
?>