<?php
class model_Jne {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_tarif_jne';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataJne($kecamatan,$kabupaten,$servis){
		$check = mysql_query("select hrg_perkilo from ".$this->tabelnya." where kecamatan_id='$kecamatan' AND kabupaten_id='$kabupaten' and servis_id='".$servis."'");
		
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataJneByID($idjne){
		$check = mysql_query("select idjne from ".$this->tabelnya." where idjne='$idjne'");
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	
	function simpanJne($data){
	   $sql=mysql_query("insert into ".$this->tabelnya." values ".$data);
	   if($sql) return true;
	   else return false;
	}
	
	function editJne($data){
	   $sql=mysql_query("update ".$this->tabelnya." set kecamatan_id='".$data['kecamatan']."',
	                     kabupaten_id='".$data['kabupaten']."',
						 provinsi_id='".$data['propinsi']."',
						 negara_id='".$data['negara']."',
						 servis_id='".$data['servis']."',
						 hrg_perkilo='".$data['tarif']."',
						 keterangan='".$data['keterangan']."' where idjne='".$data['id']."'");
	   if($sql) return true;
	   else return false;
	}
	
	function getTarifJne(){
		$strsql=mysql_query("select * from ".$this->tabelnya);
		$rsa=mysql_fetch_array($strsql);

		return $rsa;
	}
	function getJneLimit($batas,$baris,$where){
	    $rows    = "idjne, _kecamatan.kecamatan_nama, _kabupaten.kabupaten_nama, _provinsi.provinsi_nama,_negara.negara_nama,";
		$rows   .= "_servis_jne.servis_nama,hrg_perkilo,_tarif_jne.keterangan,_tarif_jne.kabupaten_id,_tarif_jne.servis_id";
		$orderby = "_kabupaten.kabupaten_nama,_provinsi.provinsi_nama,_kecamatan.kecamatan_nama asc limit $batas,$baris";
		$tabel = $this->tabelnya.' INNER JOIN _servis_jne ON _tarif_jne.servis_id=_servis_jne.ids ';
		$tabel.= 'INNER JOIN _kecamatan ON _tarif_jne.kecamatan_id=_kecamatan.kecamatan_id ';
		$tabel.= 'INNER JOIN _kabupaten ON _tarif_jne.kabupaten_id=_kabupaten.kabupaten_id ';
		$tabel.= 'INNER JOIN _provinsi ON _tarif_jne.provinsi_id=_provinsi.provinsi_id ';
		$tabel.= 'INNER JOIN _negara ON _tarif_jne.negara_id=_negara.negara_id ';
		$this->db->select($tabel, $rows, $where, $orderby);
		$hasil = $this->db->getResult();
		return $hasil;
	}
	function getJneByID($iddata){
		$strsql=mysql_query("select idjne,kecamatan_id,kabupaten_id,provinsi_id,
		                     negara_id,servis_id,hrg_perkilo,servis_nama,_tarif_jne.keterangan from ".$this->tabelnya." INNER JOIN 
							 _servis_jne ON ".$this->tabelnya.".servis_id=_servis_jne.ids where idjne='".$iddata."'") ;
		
		
		if($strsql){
		   $rsa=mysql_fetch_array($strsql);
		   return $rsa;
		} else {
		   return false;
		}
	}
	function totalJne($where){
	    if($where!='') $where = " where ".$where;
		$strsql=mysql_query("select count(*) from ".$this->tabelnya." INNER JOIN _servis_jne ON _tarif_jne.servis_id=_servis_jne.ids INNER JOIN _kecamatan ON _tarif_jne.kecamatan_id=_kecamatan.kecamatan_id INNER JOIN _kabupaten ON _tarif_jne.kabupaten_id=_kabupaten.kabupaten_id INNER JOIN _provinsi ON _kabupaten.provinsi_id=_provinsi.provinsi_id INNER JOIN _negara ON _tarif_jne.negara_id=_negara.negara_id ".$where) or die (mysql_error());
      
		$row = mysql_fetch_row($strsql);
		return $row[0];
	}
	function getServisJne(){
	    $arkab = array();
		$strsql=mysql_query("select * from _servis_jne");
		while ($rsa=mysql_fetch_array($strsql)){
			$arkab[] = array(
				'id' => $rsa['ids'],
				'nm' => $rsa['servis_nama']
			);
		}
		return $arkab;
	}
	function hapusJne($data){
		$check = mysql_query("delete from ".$this->tabelnya." where idjne='$data'");
		if($check) return true;
		else return false;
	}
	function __destruct() {
		$this->db->disconnect();
	}
}
?>