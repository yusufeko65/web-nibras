<?php
class model_Bank {
	private $db;
	private $tabelnya;
	
	public function __construct(){
		$this->tabelnya = '_bank';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	
	public function checkDataBankByID($bank_id){
		$check = $this->db->query("select bank_nama from ".$this->tabelnya." where bank_id='$bank_id'");
		if (!$check->num_rows) {
		   return false;
		} else {
		   return true;
		} 
	}
	
	public function getBank(){
		$arbank = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya." WHERE bank_status='1' order by bank_nama asc ");
		
		foreach ($strsql->rows as $rsa) {
		   $arbank[] = array(
				'ids' => $rsa['bank_id'],
				'nms' => $rsa['bank_nama'],
				'lgs' => $rsa['bank_logo']
			);
		}
		
		
		return $arbank;
	}
	
	public function getBankInRekening(){
		
		$strsql=$this->db->query("select _bank.*,_bank_rekening.rekening_id,_bank_rekening.rekening_no, rekening_atasnama,rekening_cabang from ".$this->tabelnya." INNER JOIN
		                     _bank_rekening ON _bank.bank_id = _bank_rekening.bank_id order by bank_nama asc ");
		if($strsql){
			$arbank = array();
			foreach ($strsql->rows as $rsa) {
				$arbank[] = array(
					'ids' => $rsa['bank_id'],
					'idr' => $rsa['rekening_id'],
					'nms' => $rsa['bank_nama'],
					'lgs' => $rsa['bank_logo'],
					'rek' => $rsa['rekening_no'],
					'an'  => $rsa['rekening_atasnama'],
					'cabang'  => $rsa['rekening_cabang']
				);
			}
			return $arbank;
		} 
		return false;
		
	}
	
	public function getRekening($bank){
		$arrekening = array();
		$strsql=$this->db->query("select rekening_id,_bank.bank_nama,rekening_no,rekening_atasnama,rekening_cabang from _bank_rekening INNER JOIN 
		                     _bank ON _bank_rekening.bank_id=_bank.bank_id WHERE _bank.bank_id='".$bank."' order by rekening_no,bank_nama asc ");
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
	
	public function getBankByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where bank_id='".$iddata."'");
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