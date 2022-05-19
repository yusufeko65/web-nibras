<?php
class model_Login {
	private $db;
	private $tabelnya;
	
	public function __construct(){
		$this->tabelnya = '_customer';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	
	public function checkDataLogin($data){
		$check = $this->db->query("select cust_email,cust_pass from ".$this->tabelnya." where cust_email='".$this->db->escape($data['emailuser'])."' AND cust_pass='".$this->db->escape($data['passuser'])."' AND cust_status='1'");
		$jml=$check->num_rows;
		if($jml) return true;
		else return false;
	}
	
	public function getLogin($data){
		$strsql=$this->db->query("select cust_id,cust_email,cust_nama,
								  cust_grup_id,cg_nm,cg_total_awal,
								  cg_min_beli,cg_min_beli_syarat,
								  cg_min_beli_wajib,cg_deposito,
								  cg_diskon,cg_dropship
								  from ".$this->tabelnya." 
								  left join _customer_grup on _customer.cust_grup_id = _customer_grup.cg_id
								  where cust_email='".$this->db->escape($data['emailuser'])."' AND cust_pass='".$this->db->escape($data['passuser'])."' AND cust_status='1'");
		return $strsql->row;
	}
	
	public function __destruct() {
		$this->db->disconnect();
	}
}
?>