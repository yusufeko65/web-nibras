<?php
class modelKabupaten {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_kabupaten';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataKabupaten($kabupaten_nama){
		$check = $this->db->query("select kabupaten_nama from ".$this->tabelnya." where kabupaten_nama='$kabupaten_nama'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataKabupatenByID($kabupaten_id){
		$check = $this->db->query("select kabupaten_nama from ".$this->tabelnya." where kabupaten_id='$kabupaten_id'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanKabupaten($data){
	   return $this->db->query("insert into ".$this->tabelnya." values (null,'".$data['kabupaten_propinsi']."','".$data['kabupaten_nama']."')");
	   if($sql) return true;
	   else return false;
	}
	
	function editKabupaten($data){
	   $sql=$this->db->query("update ".$this->tabelnya." set kabupaten_nama='".$data['kabupaten_nama']."',provinsi_id='".$data['kabupaten_propinsi']."' where kabupaten_id='".$data['kabupaten_id']."'");
	   if($sql) return true;
	   else return false;
	}
	
	function getKabupaten($idprop=0){
		$arkab = array();
		if ($idprop!=0) $where = " where provinsi_id='".$idprop."'";
		else $where = "";
		
		$strsql=$this->db->query("select * from ".$this->tabelnya.$where." order by kabupaten_nama asc ");
		
		foreach ($strsql->rows as $rsa) {
			$arkab[] = array(
				'idk' => $rsa['kabupaten_id'],
				'nmk' => $rsa['kabupaten_nama']
			);
		}
		return $arkab;
	}
	function getKabupatenLimit($batas,$baris,$data){
	   		
		$hasil = array();
		$where = '';
		$filter = array();
		
		if($data['caridata'] != '') $filter[] = " kabupaten_nama like '%".trim(strip_tags($this->db->escape($data['caridata'])))."%'";
		
		if($data['propinsi'] != '' && $data['propinsi'] != '0') $filter[] = " _kabupaten.provinsi_id = '".trim(strip_tags($data['propinsi']))."'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		
		if($where) $where = " WHERE ".$where;
		$sql = "SELECT kabupaten_id, kabupaten_nama, _provinsi.provinsi_nama FROM _kabupaten INNER JOIN _provinsi ON _kabupaten.provinsi_id=_provinsi.provinsi_id ".$where." ORDER BY _kabupaten.provinsi_id desc limit $batas,$baris";
		
		$strsql = $this->db->query($sql);
		foreach ($strsql->rows as $rsa) {
			$hasil[] = $rsa;
		}
		return $hasil;
	}
	
	function totalKabupaten($data){
		
		$where = '';
		if($data['caridata'] != '') $filter[] = " kabupaten_nama like '%".trim(strip_tags($this->db->escape($data['caridata'])))."%'";
		if($data['propinsi'] != '' && $data['propinsi'] != '0') $filter[] = " _kabupaten.provinsi_id = '".trim(strip_tags($data['propinsi']))."'";
		
		if(!empty($filter))	$where = implode(" and ",$filter);
		
	    if($where!='') $where = " where ".$where;
		$strsql=$this->db->query("select count(*) as total from _kabupaten INNER JOIN _provinsi ON _kabupaten.provinsi_id=_provinsi.provinsi_id ".$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
		
	}
	
	function getKabupatenByID($iddata){
		$strsql = $this->db->query("select * from ".$this->tabelnya." where kabupaten_id='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : array();
	}
	
	function checkRelasi($data){
		
		
		$check = $this->db->query("select kabupaten_id from _kecamatan where kabupaten_id='".$data."'");
		$jml	= $check->num_rows;
		
		if($jml > 0) {
			$chtarif = $this->db->query("select kabupaten_id from _tarif_jne where kabupaten_id='".$data."'");
			$jmltarif	= $chtarif->num_rows;
			if($jmltarif > 0) return true;
			else return false;
		} else {
			return false;
		}
	}
	function hapusKabupaten($data){
		return $this->db->query("delete from ".$this->tabelnya." where kabupaten_id='$data'");
	}
	function __destruct() {
		$this->db->disconnect();
	}
}
?>