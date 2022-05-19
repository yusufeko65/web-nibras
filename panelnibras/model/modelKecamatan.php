<?php
class modelKecamatan {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_kecamatan';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataKecamatan($kecamatan_nama,$kabupaten){
		$check = $this->db->query("select kecamatan_nama from ".$this->tabelnya." where kecamatan_nama='$kecamatan_nama' AND kabupaten_id='$kabupaten'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataKecamatanByID($kecamatan_id){
		$check = $this->db->query("select kecamatan_nama from ".$this->tabelnya." where kecamatan_id='$kecamatan_id'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanKecamatan($data){
	   return $this->db->query("insert into ".$this->tabelnya." values (null,'".$data['kecamatan_kabupaten']."','".$this->db->escape($data['kecamatan_nama'])."')");
	}
	
	function editKecamatan($data){
	   return $this->db->query("update ".$this->tabelnya." set kecamatan_nama='".$data['kecamatan_nama']."',kabupaten_id='".$data['kecamatan_kabupaten']."' where kecamatan_id='".$data['kecamatan_id']."'");
	}
	
	function getKecamatan(){
		$arkecamatan = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya." order by kecamatan_nama asc ");
		foreach ($strsql->rows as $rsa) {
			$arkecamatan[] = array(
				'idn' => $rsa['kecamatan_id'],
				'nmn' => $rsa['kecamatan_nama']
			);
		}
		return $arkecamatan;
	}
	function getKecamatanLimit($batas,$baris,$data){
	    $hasil = array();
		$where = '';
		$filter = array();
		
		if($data['kabupaten']!='' && $data['kabupaten']!='0') $filter[] = " _kecamatan.kabupaten_id = '".trim(strip_tags($data['kabupaten']))."'";
		if($data['caridata']!='') $filter[] = " kecamatan_nama like '%".trim(strip_tags($this->db->escape($data['caridata'])))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where) $where = " WHERE ".$where;
		$sql = "SELECT kecamatan_id, kecamatan_nama, _kabupaten.kabupaten_nama, _provinsi.provinsi_nama 
				FROM _kecamatan 
				INNER JOIN _kabupaten ON _kecamatan.kabupaten_id=_kabupaten.kabupaten_id 
				INNER JOIN _provinsi ON _kabupaten.provinsi_id=_provinsi.provinsi_id ".$where." 
				ORDER BY _kecamatan.kecamatan_id desc limit $batas,$baris";
		
		$strsql = $this->db->query($sql);
		foreach ($strsql->rows as $rsa) {
			$hasil[] = $rsa;
		}
		return $hasil;
	}
	
	function totalKecamatan($data){
	   
		$where = '';
		if($data['kabupaten']!='' && $data['kabupaten']!='0') $filter[] = " _kecamatan.kabupaten_id = '".trim(strip_tags($data['kabupaten']))."'";
		if($data['caridata']!='') $filter[] = " kecamatan_nama like '%".trim(strip_tags($this->db->escape($data['caridata'])))."%'";
		
		if(!empty($filter))	$where = implode(" and ",$filter);
		
	    if($where!='') $where = " where ".$where;
		$strsql=$this->db->query("select count(*) as total from _kecamatan INNER JOIN _kabupaten ON _kecamatan.kabupaten_id=_kabupaten.kabupaten_id INNER JOIN _provinsi ON _kabupaten.provinsi_id=_provinsi.provinsi_id ".$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	
	function getKecamatanByID($iddata){
		$strsql=$this->db->query("select _kecamatan.kecamatan_id,_kecamatan.kecamatan_nama,
		                     _kabupaten.kabupaten_id,_provinsi.provinsi_id from ".$this->tabelnya." INNER JOIN _kabupaten
							 ON _kecamatan.kabupaten_id=_kabupaten.kabupaten_id INNER JOIN _provinsi 
							 ON _kabupaten.provinsi_id=_provinsi.provinsi_id where kecamatan_id='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : array();
	}
	function getKecamatanByKabupaten($kabupaten){
		$arkecamatan = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya." WHERE kabupaten_id='".$kabupaten."'order by kecamatan_nama asc ");
		
		foreach($strsql->rows as $rsa) {
		
			$arkecamatan[] = array(
				'idn' => $rsa['kecamatan_id'],
				'nmn' => $rsa['kecamatan_nama']
			);
		}
		return $arkecamatan;
	}
	
	function checkRelasi($data){
		
		$check = $this->db->query("select reseller_kecamatan from _reseller where reseller_kecamatan='$data'");
		$jml	= $check->num_rows;
		
		if($jml > 0) {
			$chtarif = $this->db->query("select kecamatan_id from _tarif_jne where kecamatan_id='$data'");
			$jmltarif	= $chtarif->num_rows;
			if($jmltarif > 0) return true;
			else return false;
		} else {
			return false;
		}
	}
	function hapusKecamatan($data){
		return $this->db->query("delete from ".$this->tabelnya." where kecamatan_id='$data'");
		
	}
	function __destruct() {
		$this->db->disconnect();
	}
}
?>