<?php
class modelJenisSupport {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_jenis_support';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataJenisSupport($jenis_nama){
		$check = $this->db->query("select jenis_nama from ".$this->tabelnya." where jenis_nama='$jenis_nama'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataJenisSupportByID($idjsupport){
		$check = $this->db->query("select jenis_nama from ".$this->tabelnya." where idjsupport='$idjsupport'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanJenisSupport($data){
	   $sql=$this->db->query("insert into ".$this->tabelnya." values ('','".$data['jenis_nama']."','".$data['link_sumber']."')");
	   if($sql) return true;
	   else return false;
	}
	
	function editJenisSupport($data){
	   $sql=$this->db->query("update ".$this->tabelnya." set jenis_nama='".$data['propinsi_nama']."',negara_id='".$data['propinsi_negara']."' where idjsupport='".$data['propinsi_id']."'");
	   if($sql) return true;
	   else return false;
	}
	
	function getJenisSupport(){
		$arprovinsi = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya." order by jenis_support asc ");
		foreach($strsql->rows as $rsa){
			$arprovinsi[] = array(
				'idp' => $rsa['idjsupport'],
				'nmp' => $rsa['jenis_support']
			);
		}
		return $arprovinsi;
	}
	function getJenisSupportLimit($batas,$baris,$where){
	    $rows    = "idjsupport, jenis_nama, _negara.negara_nama";
		$orderby = "_provinsi.idjsupport desc limit $batas,$baris";
		$tabel = $this->tabelnya.' INNER JOIN _negara ON _provinsi.negara_id=_negara.negara_id';
		$this->db->select($tabel, $rows, $where, $orderby);
		$hasil = $this->db->getResult();
		return $hasil;
	}
	function getJenisSupportByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where idjsupport='".$iddata."'");
		if($strsql){
		   $rsa=mysql_fetch_array($strsql);
		   return $rsa;
		} else {
		   return false;
		}
	}
	function totalJenisSupport($where){
	    if($where!='') $where = " where ".$where;
		$strsql=$this->db->query("select count(*) from _provinsi INNER JOIN _negara ON _provinsi.negara_id=_negara.negara_id ".$where);
		$row = mysql_fetch_row($strsql);
		return $row[0];
	}
	function checkRelasi($data){
		$check = $this->db->query("select idjsupport from _kabupaten where idjsupport='$data'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	function hapusJenisSupport($data){
		$check = $this->db->query("delete from ".$this->tabelnya." where idjsupport='$data'");
		if($check) return true;
		else return false;
	}
	function __destruct() {
		$this->db->disconnect();
	}
}
?>