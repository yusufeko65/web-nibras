<?php
class modelJne {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_tarif';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataJne($kecamatan,$kabupaten,$servis){
		$check = mysql_query("select hrg_perkilo from ".$this->tabelnya." where kecamatan_id='$kecamatan' AND kabupaten_id='$kabupaten' and servis_id='".$servis."'");
		
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataJneDiskonServisByServis($servis){
		$check = mysql_query("select servis_id from _servis_diskon where servis_id='$servis'");
		
		$jml=mysql_num_rows($check);
		if($jml > 0) return true;
		else return false;
	}
	function checkDiskonJneKota($kabupaten){
		$check = mysql_query("select kota from _tarif_diskon_tujuan where kota='$kabupaten'");
		
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	function checkDataJneByID($idjne){
		$check = mysql_query("select idt from ".$this->tabelnya." where idt='$idjne'");
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	function checkDataJneDiskonByID($idjne){
		$check = mysql_query("select idjnedisk from _tarif_diskon where idjnedisk='$idjne'");
		
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	function checkDataServisJneDiskonByID($id){
		$check = mysql_query("select idservisdisk from _servis_diskon where idservisdisk='$id'");
		
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	
	function simpanJne($data){
	   $sql=mysql_query("insert into ".$this->tabelnya." values ".$data);
	   if($sql) return true;
	   else return false;
	}
	function simpanDiskon($data){
	   $sql = "INSERT INTO _tarif_diskon VALUES ('".$data['id']."','".$data['nmdisk']."','".$data['jmldisk']."','".$data['persen']."','".$data['stsdiskon']."','33','".$data['propinsi']."')";
	   
	   $sql = mysql_query($sql);
	   if($sql) return true;
	   else return false;
	}
	function simpanServisDiskon($data){
	   $sql = "INSERT INTO _servis_diskon VALUES ('','".$data['servis']."','".$data['jmldisk']."','".$data['stsdiskon']."')";
	   
	   $sql = mysql_query($sql);
	   if($sql) return true;
	   else return false;
	}
	function simpanDiskonServis($data){
	   $sql=mysql_query("insert into _tarif_diskon_servis values ".$data);
	   if($sql) return true;
	   else return false;
	}
	function simpanDiskonTujuan($data){
	   $sql=mysql_query("insert into _tarif_diskon_tujuan values ".$data);
	   if($sql) return true;
	   else return false;
	}
	function editJne($data){
	   $sql=mysql_query("update ".$this->tabelnya." set kecamatan_id='".$data['kecamatan']."',
	                     kabupaten_id='".$data['kabupaten']."',
						 provinsi_id='".$data['propinsi']."',
						 servis_id='".$data['servis']."',
						 hrg_perkilo='".$data['tarif']."',
						 keterangan='".$data['keterangan']."' where idt='".$data['id']."'");
	   if($sql) return true;
	   else return false;
	}
	function editServisDiskon($data){
	   $sql = "UPDATE _servis_diskon set 
	                  jml_disk='".$data['jmldisk']."',
					  stsdisk='".$data['stsdiskon']."'
			   WHERE idservisdisk='".$data['id']."'";
	   
	   $sql = mysql_query($sql);
	   if($sql) return true;
	   else return false;
	}
	function editDiskon($data){
	   $sql = "UPDATE _tarif_diskon SET 
	              nm_diskon = '".$data['nmdisk']."',
				  jml_disk = '".$data['jmldisk']."',
				  persen = '".$data['persen']."',
				  stsdiskon = '".$data['stsdiskon']."',
				  negara='".$data['negara']."',
				  propinsi='".$data['propinsi']."'
			   WHERE idjnedisk='".$data['id']."'";
	   
	   $sql = mysql_query($sql);
	   if($sql) return true;
	   else return false;
	}
	function getTarifJne(){
		$strsql=mysql_query("select * from ".$this->tabelnya);
		$rsa=mysql_fetch_array($strsql);

		return $rsa;
	}
	function getJneLimit($batas,$baris,$where){
	    $rows    = "idt, _kecamatan.kecamatan_nama, _kabupaten.kabupaten_nama, _provinsi.provinsi_nama,_negara.negara_nama,";
		$rows   .= "_servis.servis_code,hrg_perkilo,_tarif.keterangan,_tarif.kabupaten_id,_tarif.servis_id";
		$orderby = "_tarif.idt desc limit $batas,$baris";
		$tabel = $this->tabelnya.' INNER JOIN _servis ON _tarif.servis_id =_servis.servis_id ';
		$tabel.= 'INNER JOIN _kecamatan ON _tarif.kecamatan_id=_kecamatan.kecamatan_id ';
		$tabel.= 'INNER JOIN _kabupaten ON _tarif.kabupaten_id=_kabupaten.kabupaten_id ';
		$tabel.= 'INNER JOIN _provinsi ON _tarif.provinsi_id=_provinsi.provinsi_id ';
		$tabel.= 'INNER JOIN _negara ON _tarif.negara_id=_negara.negara_id ';
		$this->db->select($tabel, $rows, $where, $orderby);
		$hasil = $this->db->getResult();
		return $hasil;
	}
	function getJneLimitDiskon($batas,$baris,$where){
	    $rows    = "*";
		$orderby = "idjnedisk desc limit $batas,$baris";
		$tabel = "_tarif_diskon";
		$this->db->select($tabel, $rows, $where, $orderby);
		$hasil = $this->db->getResult();
		return $hasil;
	}
	function getJneLimitServisDiskon($batas,$baris,$where){
	    $rows    = "*,servis_nama";
		$orderby = "idservisdisk desc limit $batas,$baris";
		$tabel = "_servis_diskon INNER JOIN _servis ON _servis_diskon.servis_id = _servis.servis_id";
		$this->db->select($tabel, $rows, $where, $orderby);
		$hasil = $this->db->getResult();
		return $hasil;
	}
	function getJneByID($iddata){
		$strsql=mysql_query("select idt,kecamatan_id,kabupaten_id,provinsi_id,
		                     negara_id,servis_id,hrg_perkilo,servis_nama,_tarif.keterangan from ".$this->tabelnya." INNER JOIN 
							 _servis ON ".$this->tabelnya.".servis_id=_servis.servis_id where idt='".$iddata."'") ;
		
		
		if($strsql){
		   $rsa=mysql_fetch_array($strsql);
		   return $rsa;
		} else {
		   return false;
		}
	}
	function getJneDiskonByID($iddata){
		$strsql=mysql_query("SELECT * FROM _tarif_diskon WHERE idjnedisk='".$iddata."'") ;
		
		if($strsql){
		   $rsa=mysql_fetch_array($strsql);
		   return $rsa;
		} else {
		   return false;
		}
	}
	function getJneDiskonServisByID($iddata){
		$arkab = array();
		$strsql=mysql_query("SELECT * FROM _tarif_diskon_servis WHERE iddiskjne='".$iddata."'");
		while ($rsa=mysql_fetch_array($strsql)){
			$arkab[] = $rsa['servis_id'];
		}
		return $arkab;
	}
	function getJneServisDiskonByID($iddata){
		$strsql=mysql_query("SELECT * FROM _servis_diskon WHERE idservisdisk='".$iddata."'");
		if($strsql){
		   $rsa=mysql_fetch_array($strsql);
		   return $rsa;
		} else {
		   return false;
		}
	}
	function getJneDiskonTujuanByID($iddata){
		
		
		$arkab = array();
		
		$strsql=mysql_query("SELECT * FROM _tarif_diskon_tujuan WHERE idjnedisk='".$iddata."'");
		while ($rsa=mysql_fetch_array($strsql)){
			$arkab[] = $rsa['kota'];
		}
		return $arkab;
	}
	function totalJne($where){
	    if($where!='') $where = " where ".$where;
		$strsql=mysql_query("select count(*) from ".$this->tabelnya." INNER JOIN _servis ON _tarif.servis_id=_servis.servis_id INNER JOIN _kecamatan ON _tarif.kecamatan_id=_kecamatan.kecamatan_id INNER JOIN _kabupaten ON _tarif.kabupaten_id=_kabupaten.kabupaten_id INNER JOIN _provinsi ON _kabupaten.provinsi_id=_provinsi.provinsi_id INNER JOIN _negara ON _tarif.negara_id=_negara.negara_id ".$where) or die (mysql_error());
      
		$row = mysql_fetch_row($strsql);
		return $row[0];
	}
	function totalJneDiskon($where){
	    if($where!='') $where = " where ".$where;
		$strsql=mysql_query("select count(*) from _tarif_diskon ".$where) or die (mysql_error());
      
		$row = mysql_fetch_row($strsql);
		return $row[0];
	}
	function totalJneServisDiskon($where){
	    if($where!='') $where = " where ".$where;
		$strsql=mysql_query("select count(*) from _servis_diskon ".$where) or die (mysql_error());
      
		$row = mysql_fetch_row($strsql);
		return $row[0];
	}
	function getServisJne(){
	    $arkab = array();
		$strsql=mysql_query("select * from _servis");
		while ($rsa=mysql_fetch_array($strsql)){
			$arkab[] = array(
				'id' => $rsa['servis_id'],
				'nm' => $rsa['servis_nama']
			);
		}
		return $arkab;
	}
	function hapusJne($data){
		$check = mysql_query("delete from ".$this->tabelnya." where idt='$data'");
		if($check) return true;
		else return false;
	}
	function hapusServisDiskon($data){
		$check = mysql_query("delete from _servis_diskon where idservisdisk='$data'");
		if($check) return true;
		else return false;
	}
	function hapusJneDiskon($data,$jenis){
	    if($jenis=='servis'){
		   $sql = "delete from _tarif_diskon_servis where iddiskjne='$data'";
		} else if($jenis == 'tujuan') {
		   $sql = "delete from _tarif_diskon_tujuan where idjnedisk='$data'";
		} else if($jenis =='diskon'){
		   $sql = "delete from _tarif_diskon where idjnedisk='$data'";
		}
		$check = mysql_query($sql);
		if($check) return true;
		else return false;
	}
	function __destruct() {
		$this->db->disconnect();
	}
}
?>