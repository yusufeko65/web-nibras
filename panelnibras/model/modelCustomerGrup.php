<?php
class modelCustomerGrup {
	private $db;
	private $tabelnya;
	private $userlogin;
	function __construct(){
		$this->tabelnya = '_customer_grup';
		$this->db 		= new Database();
		$this->db->connect();
		$this->userlogin = isset($_SESSION["userlogin"]) ? $_SESSION["userlogin"]:'';
	}
	
	function checkDataResellerGrup($nama_grup){
		$check = $this->db->query("select * from ".$this->tabelnya." where cg_nm='$nama_grup'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataResellerGrupByID($rs_grupid){
		$check = $this->db->query("select * from ".$this->tabelnya." where cg_id='$rs_grupid'");
		
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanResellerGrup($data){
		$strsql = "insert into ".$this->tabelnya." values (null,'".$data['grup_nama']."',
	                    '".$data['keterangan']."','".$data['total_awal']."','".$data['min_beli']."',
						'".$data['minbeli_syarat']."',
						'".$data['minbeli_wjb']."',
						'".$data['urutan']."',
						'".$data['deposito']."','".$data['diskon']."','".$data['dropship']."')";
		
		return $this->db->query($strsql);
	
		
	}
	
	function editResellerGrup($data=array()){
		$strsql = "update ".$this->tabelnya." set cg_nm='".$data['grup_nama']."',
	                     cg_ket='".$data['keterangan']."',
	                     cg_total_awal = '".$data['total_awal']."',
						 cg_min_beli = '".$data['min_beli']."',
						 cg_min_beli_syarat = '".$data['minbeli_syarat']."',
						 cg_min_beli_wajib = '".$data['minbeli_wjb']."',
						 cg_urutan = '".$data['urutan']."',
						 cg_deposito='".$data['deposito']."',
						 cg_diskon='".$data['diskon']."',
						 cg_dropship='".$data['dropship']."'						 
						 WHERE cg_id='".$data['iddata']."'";
		return $this->db->query($strsql);
	   
	}
	
	function editUrutanLain($data) {
		return $this->db->query("UPDATE _customer_grup SET cg_urutan='".$data['urutan_lama']."' WHERE cg_urutan='".$data['urutan']."'");
	   
	}
	
	function getResellerGrupLimit($batas,$baris,$data){
	    
		$hasil = array();
		$where = '';
		$filter = array();
		
		
		if($data['caridata']!='') $filter[] = " cg_nm like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where) $where = " WHERE ".$where;
		$sql = "SELECT * 
				FROM _customer_grup ".$where." 
				ORDER BY cg_id desc limit $batas,$baris";
		
		$strsql = $this->db->query($sql);
		foreach ($strsql->rows as $rsa) {
			$hasil[] = $rsa;
		}
		return $hasil;
	}
	
	function totalResellerGrup($data){
		$where = '';
		
		if($data['caridata']!='') $filter[] = " cg_nm like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
	    if($where!='') $where = " where ".$where;
		$strsql=$this->db->query("select count(*) as total from ".$this->tabelnya.$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	
	function getResellerGrupByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where cg_id='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : array();
	}
	function getResellerGrup(){
	    $argrup = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya." order by cg_urutan asc ");
		foreach ($strsql->rows as $rsa) {
			$argrup[] = array(
				'id' => $rsa['cg_id'],
				'nm' => $rsa['cg_nm'],
				'dp' => $rsa['cg_deposito']
			);
		}
		return $argrup;
	}
	
	function checkRelasi($data){
		$check = $this->db->query("select * from _customer where customer_grup='$data'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	function hapusResellerGrup($data){
		return $this->db->query("delete from ".$this->tabelnya." where cg_id='$data'");
	}
	function __destruct() {
		$this->db->disconnect();
	}
}
?>