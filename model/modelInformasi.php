<?php
class model_Informasi {
	private $db;
	private $tabelnya;
	private $userlogin;
	
	function __construct(){
		$this->tabelnya = '_informasi';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	public function checkDataInformasiByID($id,$alias){
		$check = $this->db->query("select id_info from ".$this->tabelnya." where id_info='$id' AND aliasurl='$alias'");
		if (!$check->num_rows) {
		   return false;
		} else {
		   return true;
		} 
		
	}
	function getInformasi($jmenu){
		$arinfo = array();
		if($jmenu != 'menuatas')
			$strsql=$this->db->query("select * from ".$this->tabelnya." INNER JOIN _informasi_deskripsi ON _informasi.id_info=_informasi_deskripsi.idinfo WHERE headline='0' order by info_judul asc ");
		else
		    $strsql=$this->db->query("select * from ".$this->tabelnya." INNER JOIN _informasi_deskripsi ON _informasi.id_info=_informasi_deskripsi.idinfo WHERE headline='0' AND menuatas='1' order by info_judul asc ");
	
	
	    foreach ($strsql->rows as $rsa) {
		   $arinfo[] = array(
				'id' => $rsa['id_info'],
				'nm' => $rsa['info_judul'],
				'al' => $rsa['aliasurl']
			);
		}
		
		return $arinfo;
	}
	function getMenuInformasi(){
		$arinfo = array();
		
		$strsql=$this->db->query("select * from ".$this->tabelnya." INNER JOIN _informasi_deskripsi ON _informasi.id_info=_informasi_deskripsi.idinfo WHERE sts_info='1' order by info_judul asc ");
	
	     foreach ($strsql->rows as $rsa) {
		    $arinfo[] = array(
				'id' => $rsa['id_info'],
				'nm' => $rsa['info_judul'],
				'al' => $rsa['aliasurl']
			);
		 }
	
		return $arinfo;
	}
	function getHeadline(){
		$arinfo = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya." INNER JOIN _informasi_deskripsi ON _informasi.id_info=_informasi_deskripsi.idinfo WHERE headline='1' order by info_judul asc ");
		
		foreach ($strsql->rows as $rsa) {
		   $arinfo[] = array(
				'id' => $rsa['id_info'],
				'nm' => trim(html_entity_decode($rsa['info_detail'])),
				'al' => $rsa['aliasurl']
			);
		}
		
		
		return $arinfo;
	}
	
	function getInformasiByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." INNER JOIN _informasi_deskripsi ON _informasi.id_info=_informasi_deskripsi.idinfo where id_info='".$iddata."'");
		if($strsql->num_rows){
		   return $strsql->row;
		} else {
		   return false;
		}
	}
		
}
?>