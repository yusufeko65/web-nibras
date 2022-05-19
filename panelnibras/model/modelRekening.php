<?php
class modelRekening {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_bank_rekening';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataRekening($rekening_no){
		$check = $this->db->query("select rekening_no from ".$this->tabelnya." where rekening_no='$rekening_no'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataRekeningByID($rekening_id){
		$check = $this->db->query("select rekening_no from ".$this->tabelnya." where rekening_id='$rekening_id'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanRekening($data=array()){
		return $this->db->query("insert into ".$this->tabelnya." values (null,'".$data['bank']."','".$data['norek']."',
	                    '".$data['atasnama']."','".$data['cabang']."','".$data['status']."')");
	   
	}
	
	function editRekening($data=array()){
		return $this->db->query("update ".$this->tabelnya." set bank_id='".$data['bank']."',
	                     rekening_no='".$data['norek']."',rekening_atasnama='".$data['atasnama']."',
						 rekening_cabang='".$data['cabang']."', rekening_status='".$data['status']."' 
						 where rekening_id='".$data['id']."'");
	   
	}
	
	function getRekening(){
		$arrekening = array();
		$strsql=$this->db->query("select rekening_id,_bank.bank_nama,
								  rekening_no,rekening_atasnama,rekening_cabang from ".$this->tabelnya." INNER JOIN 
		                     _bank ON _bank_rekening.bank_id=_bank.bank_id group by rekening_id order by rekening_no,bank_nama asc ");
		foreach ($strsql->rows as $rsa) {
			$arrekening[] = array(
				'id' => $rsa['rekening_id'],
				'norek' => $rsa['rekening_no'],
				'bank' => $rsa['bank_nama'],
				'atasnama' => $rsa['rekening_atasnama'],
				'cabang' => $rsa['rekening_cabang']
			);
		}
		return $arrekening;
	}
	function getRekeningLimit($batas,$baris,$data){
	    
		$hasil = array();
		$where = '';
		$filter = array();
		
		if($data['caridata']!='') $filter[] = " rekening_atasnama like '%".trim($this->db->escape($data['caridata']))."%' OR rekening_no like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where) $where = " WHERE ".$where;
		$sql = "SELECT rekening_id,_bank.bank_nama,rekening_no,rekening_atasnama,rekening_cabang 
				FROM _bank_rekening INNER JOIN _bank ON _bank_rekening.bank_id = _bank.bank_id ".$where." 
				ORDER BY rekening_id desc limit $batas,$baris";
		
		$strsql = $this->db->query($sql);
		foreach ($strsql->rows as $rsa) {
			$hasil[] = $rsa;
		}
		return $hasil;
		
	}
	
	function totalRekening($data){
		$where = '';
		$filter = array();
		if($data['caridata']!='') $filter[] = " rekening_atasnama like '%".trim($this->db->escape($caridata))."%' OR rekening_no like '%".trim(strip_tags($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where) $where = " WHERE ".$where;
		
		$strsql=$this->db->query("select count(*) from ".$this->tabelnya." INNER JOIN _bank ON _bank_rekening.bank_id=_bank.bank_id".$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	
	function getRekeningByID($iddata){
		$strsql = $this->db->query("select * from ".$this->tabelnya." where rekening_id='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : array();
	}
	
	function checkRelasi($data){
		$check = $this->db->query("select rekening_id from _provinsi where rekening_id='$data'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	function hapusRekening($data){
		return $this->db->query("delete from ".$this->tabelnya." where rekening_id='$data'");
		
	}
	
}
?>