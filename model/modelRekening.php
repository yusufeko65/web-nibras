<?php
class model_Rekening {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_bank_rekening';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataRekening($rekening_no){
		$check = mysql_query("select rekening_no from ".$this->tabelnya." where rekening_no='$rekening_no'");
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataRekeningByID($rekening_id){
		$check = mysql_query("select rekening_no from ".$this->tabelnya." where rekening_id='$rekening_id'");
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	
	function simpanRekening($data=array()){
	   $sql=mysql_query("insert into ".$this->tabelnya." values ('','".$data['bank']."','".$data['norek']."',
	                    '".$data['atasnama']."','".$data['cabang']."','".$data['status']."')");
	   if($sql) return true;
	   else return false;
	}
	
	function editRekening($data=array()){
	   $sql=mysql_query("update ".$this->tabelnya." set bank_id='".$data['bank']."',
	                     rekening_no='".$data['norek']."',rekening_atasnama='".$data['atasnama']."',
						 rekening_cabang='".$data['cabang']."', rekening_status='".$data['status']."' 
						 where rekening_id='".$data['id']."'");
	   if($sql) return true;
	   else return false;
	}
	
	function getRekening(){
		$arrekening = array();
		$strsql=mysql_query("select rekening_id,_bank.bank_nama,rekening_no,rekening_atasnama from ".$this->tabelnya." INNER JOIN 
		                     _bank ON _bank_rekening.bank_id=_bank.bank_id order by rekening_no,bank_nama asc ");
		while ($rsa=mysql_fetch_array($strsql)){
			$arrekening[] = array(
				'id' => $rsa['rekening_id'],
				'norek' => $rsa['rekening_no'],
				'bank' => $rsa['bank_nama'],
				'atasnama' => $rsa['rekening_atasnama']
			);
		}
		return $arrekening;
	}
	function getRekeningLimit($batas,$baris,$where){
	    $rows    = "rekening_id,_bank.bank_nama,rekening_no,rekening_atasnama,rekening_cabang";
		$orderby = "rekening_id,bank_nama desc limit $batas,$baris";
		$tabel = $this->tabelnya." INNER JOIN _bank ON _bank_rekening.bank_id=_bank.bank_id";
		$this->db->select($tabel, $rows, $where, $orderby);
		$hasil = $this->db->getResult();
		return $hasil;
	}
	function getRekeningByID($iddata){
		$strsql=mysql_query("select * from ".$this->tabelnya." where rekening_id='".$iddata."'");
		if($strsql){
		   $rsa=mysql_fetch_array($strsql);
		   return $rsa;
		} else {
		   return false;
		}
	}
	function totalRekening($where){
	    if($where!='') $where = " where ".$where;
		$strsql=mysql_query("select count(*) from ".$this->tabelnya." INNER JOIN _bank ON _bank_rekening.bank_id=_bank.bank_id".$where);
		$row = mysql_fetch_row($strsql);
		return $row[0];
	}
	function checkRelasi($data){
		$check = mysql_query("select rekening_id from _provinsi where rekening_id='$data'");
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	function hapusRekening($data){
		$check = mysql_query("delete from ".$this->tabelnya." where rekening_id='$data'");
		if($check) return true;
		else return false;
	}
	function __destruct() {
		$this->db->disconnect();
	}
}
?>