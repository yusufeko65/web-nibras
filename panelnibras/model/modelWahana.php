<?php
class modelWahana {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_tarif_wahana';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataWahana($kecamatan,$kabupaten,$servis){
		$check = mysql_query("select hrg_perkilo from ".$this->tabelnya." where kecamatan_id='$kecamatan' AND kabupaten_id='$kabupaten' and servis_id='".$servis."'");
		
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataWahanaDiskonServisByServis($servis){
		$check = mysql_query("select servis_id from _servis_wahana_diskon where servis_id='$servis'");
		
		$jml=mysql_num_rows($check);
		if($jml > 0) return true;
		else return false;
	}
	function checkDiskonWahanaKota($kabupaten){
		$check = mysql_query("select kota from _tarif_wahana_diskon_tujuan where kota='$kabupaten'");
		
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	function checkDataWahanaByID($idwahana){
		$check = mysql_query("select idt from ".$this->tabelnya." where idt='$idwahana'");
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	function checkDataWahanaDiskonByID($idwahana){
		$check = mysql_query("select idwahanadisk from _tarif_wahana_diskon where idwahanadisk='$idwahana'");
		
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	function checkDataServisWahanaDiskonByID($id){
		$check = mysql_query("select idservisdisk from _servis_wahana_diskon where idservisdisk='$id'");
		
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	
	function simpanWahana($data){
	   $sql=mysql_query("insert into ".$this->tabelnya." values ".$data);
	   if($sql) return true;
	   else return false;
	}
	
	function editWahana($data){
	   $sql=mysql_query("update ".$this->tabelnya." set kecamatan_id='".$data['kecamatan']."',
	                     kabupaten_id='".$data['kabupaten']."',
						 provinsi_id='".$data['propinsi']."',
						 servis_id='".$data['servis']."',
						 hrg_perkilo='".$data['tarif']."',
						 keterangan='".$data['keterangan']."' where idt='".$data['id']."'");
	   if($sql) return true;
	   else return false;
	}
	
	function getTarifWahana(){
		$strsql=mysql_query("select * from ".$this->tabelnya);
		$rsa=mysql_fetch_array($strsql);

		return $rsa;
	}
	function getWahanaLimit($batas,$baris,$where){
	    $rows    = "idt, _kecamatan.kecamatan_nama, _kabupaten.kabupaten_nama, _provinsi.provinsi_nama,_negara.negara_nama,";
		$rows   .= "_servis_wahana.servis_nama,hrg_perkilo,_tarif_wahana.keterangan,_tarif_wahana.kabupaten_id,_tarif_wahana.servis_id";
		$orderby = "_tarif_wahana.idt desc limit $batas,$baris";
		$tabel = $this->tabelnya.' LEFT JOIN _servis_wahana ON _tarif_wahana.servis_id=_servis_wahana.ids ';
		$tabel.= 'LEFT JOIN _kecamatan ON _tarif_wahana.kecamatan_id=_kecamatan.kecamatan_id ';
		$tabel.= 'LEFT JOIN _kabupaten ON _tarif_wahana.kabupaten_id=_kabupaten.kabupaten_id ';
		$tabel.= 'LEFT JOIN _provinsi ON _tarif_wahana.provinsi_id=_provinsi.provinsi_id ';
		$tabel.= 'LEFT JOIN _negara ON _tarif_wahana.negara_id=_negara.negara_id ';
		
		$this->db->select($tabel, $rows, $where, $orderby);
		$hasil = $this->db->getResult();
		return $hasil;
	}
	
	function getWahanaByID($iddata){
		$strsql=mysql_query("select idt,kecamatan_id,kabupaten_id,provinsi_id,
		                     negara_id,servis_id,hrg_perkilo,servis_nama,_tarif_wahana.keterangan from ".$this->tabelnya." INNER JOIN 
							 _servis_wahana ON ".$this->tabelnya.".servis_id=_servis_wahana.ids where idt='".$iddata."'") ;
		
		
		if($strsql){
		   $rsa=mysql_fetch_array($strsql);
		   return $rsa;
		} else {
		   return false;
		}
	}
	function getWahanaDiskonByID($iddata){
		$strsql=mysql_query("SELECT * FROM _tarif_wahana_diskon WHERE idwahanadisk='".$iddata."'") ;
		
		if($strsql){
		   $rsa=mysql_fetch_array($strsql);
		   return $rsa;
		} else {
		   return false;
		}
	}
	
	function totalWahana($where){
	    if($where!='') $where = " where ".$where;
		$strsql=mysql_query("select count(*) from ".$this->tabelnya." INNER JOIN _servis_wahana ON _tarif_wahana.servis_id=_servis_wahana.ids INNER JOIN _kecamatan ON _tarif_wahana.kecamatan_id=_kecamatan.kecamatan_id INNER JOIN _kabupaten ON _tarif_wahana.kabupaten_id=_kabupaten.kabupaten_id INNER JOIN _provinsi ON _kabupaten.provinsi_id=_provinsi.provinsi_id INNER JOIN _negara ON _tarif_wahana.negara_id=_negara.negara_id ".$where) or die (mysql_error());
      
		$row = mysql_fetch_row($strsql);
		return $row[0];
	}
	
	function getServisWahana(){
	    $arkab = array();
		$strsql=mysql_query("select * from _servis_wahana");
		while ($rsa=mysql_fetch_array($strsql)){
			$arkab[] = array(
				'id' => $rsa['ids'],
				'nm' => $rsa['servis_nama']
			);
		}
		return $arkab;
	}
	function hapusWahana($data){
		$check = mysql_query("delete from ".$this->tabelnya." where idt='$data'");
		if($check) return true;
		else return false;
	}
	
	function __destruct() {
		$this->db->disconnect();
	}
}
?>