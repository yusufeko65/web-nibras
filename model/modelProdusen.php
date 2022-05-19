<?php
class model_Produsen {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_produsen';
		$this->db 		= new Database();
		$this->db->connect();
	}
	function getProdusen(){
		$arprodusen = array();
		$strsql=mysql_query("select * from ".$this->tabelnya." order by produsen_nama asc ");
		while ($rsa=mysql_fetch_array($strsql)){
			$arprodusen[] = array(
				'ids' => $rsa['produsen_id'],
				'nms' => $rsa['produsen_nama'],
				'alias' => $rsa['produsen_alias']
			);
		}
		return $arprodusen;
	}
	function getProdusenByID($iddata){
		$strsql=mysql_query("select * from ".$this->tabelnya." where produsen_id='".$iddata."'");
		if($strsql){
		   $rsa=mysql_fetch_array($strsql);
		   return $rsa;
		} else {
		   return false;
		}
	}
	function getProdusenByIDAlias($iddata,$alias){
		$strsql=mysql_query("select * from ".$this->tabelnya." where produsen_id='".$iddata."' AND produsen_alias='".$alias."'");
		if($strsql){
		   $rsa=mysql_fetch_array($strsql);
		   return $rsa;
		} else {
		   return false;
		}
	}
	
	function simpanProdusen($data){
	   $sql=mysql_query("insert into ".$this->tabelnya." 
						 values ('".$data['produsen_id']."','".$data['produsen_nama']."',
						 '".$data['produsen_logo']."','".$data['produsen_telp']."',
						 '".$data['produsen_alamat']."','".$data['produsen_email']."',
						 '".$data['produsen_keterangan']."',
						 '".$data['produsen_web']."',
						 '".$data['produsen_fb']."',
						 '".$data['produsen_kapasitas']."',
						 '".$data['produsen_ketgrosir']."','0',
						 '".$data['aliasurl']."')");
	   if($sql) return $this->simpanAliasProdusen($data);
	   else return false;
	}
	
	function simpanAliasProdusen($data){
	   $inisial = 'produsen='.$data['produsen_id'];
	   
	   $del = mysql_query("delete from _url_alias WHERE inisial='".$inisial."'");
	   $sql = mysql_query("insert into _url_alias values ('".$inisial."','".$data['aliasurl']."','produk')");
	   if($sql) return true;
	   else return false;
	}
	
	function simpanGambarProduk($value){
	   $sql = mysql_query("insert into _produsen_gambar values $value");
	   if($sql) return true;
	   else return false;
	}
	
	function __destruct() {
		$this->db->disconnect();
	}
}
?>