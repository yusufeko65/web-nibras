<?php
class modelProdusen {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_produsen';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataProdusen($produsen_nama){
		$check = mysql_query("select produsen_nama from ".$this->tabelnya." where produsen_nama='$produsen_nama'");
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataProdusenByID($produsen_id){
		$check = mysql_query("select produsen_nama from ".$this->tabelnya." where produsen_id='$produsen_id'");
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
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
						 '".$data['produsen_ketgrosir']."','1',
						 '".$data['aliasurl']."')");
	   if($sql) return $this->simpanAliasProdusen($data);
	   else return false;
	}
	function simpanDiskonProdusen($value){
	   $sql = mysql_query("insert into _produsen_diskon values $value");
	   if($sql) return true;
	   else return false;
	}
	function editProdusen($data){
	   $sql=mysql_query("update ".$this->tabelnya." 
						 set produsen_nama='".$data['produsen_nama']."',
						 produsen_logo='".$data['produsen_logo']."',
						 produsen_telp='".$data['produsen_telp']."',
						 produsen_alamat='".$data['produsen_alamat']."',
						 produsen_email='".$data['produsen_email']."',
						 produsen_keterangan='".$data['produsen_keterangan']."',
						 produsen_web='".$data['produsen_web']."',
						 produsen_facebook='".$data['produsen_fb']."',
						 produsen_kapasitas = '".$data['produsen_kapasitas']."',
						 produsen_grosir = '".$data['produsen_ketgrosir']."',
						 produsen_approve = '1',
						 produsen_alias = '".$data['aliasurl']."'
						 where produsen_id='".$data['produsen_id']."'");
		
	   if($sql) return $this->simpanAliasProdusen($data);
	   else return false;
	}
	function editDiskonProdusen($data){
	   $sql=mysql_query("update _produsen_diskon set produsen_id='".$data['produsen']."',reseller_grupid='".$data['grup']."',diskon='".$data['diskon']."' where diskon_id='".$data['id']."'");
	   if($sql) return true;
	   else return false;
	}
	function simpanAliasProdusen($data){
	   $inisial = 'produsen='.$data['produsen_id'];
	   
	   $del = mysql_query("delete from _url_alias WHERE inisial='".$inisial."'");
	   $sql = mysql_query("insert into _url_alias values ('".$inisial."','".$data['aliasurl']."','produk')");
	   if($sql) return true;
	   else return false;
	}
	function getProdusen(){
		$arprodusen = array();
		$strsql=mysql_query("select * from ".$this->tabelnya." order by produsen_nama asc ");
		while ($rsa=mysql_fetch_array($strsql)){
			$arprodusen[] = array(
				'ids' => $rsa['produsen_id'],
				'nms' => $rsa['produsen_nama']
			);
		}
		return $arprodusen;
	}
	function getProdusenLimit($batas,$baris,$where){
	    $rows    = "*";
		$orderby = "produsen_id desc limit $batas,$baris";
		$this->db->select($this->tabelnya, $rows, $where, $orderby);
		$hasil = $this->db->getResult();
		return $hasil;
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
	function totalProdusen($where){
	    if($where!='') $where = " where ".$where;
		$strsql=mysql_query("select count(*) from ".$this->tabelnya.$where);
		$row = mysql_fetch_row($strsql);
		return $row[0];
	}
	function checkRelasi($data){
		$check = mysql_query("select produsen from _produk where produsen='$data'");
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	function hapusProdusen($data){
		$check = mysql_query("delete from ".$this->tabelnya." where produsen_id='$data'");
		if($check) return true;
		else return false;
	}
	function hapusDiskonProdusen($data){
		$check = mysql_query("delete from _produsen_diskon where produsen_id='$data'");
		if($check) return true;
		else return false;
	}
	function getResellerGrup() {
	    $arkab = array();
		$strsql=mysql_query("select rs_grupid,rs_grupnama from _reseller_grup");
		while ($rsa=mysql_fetch_array($strsql)){
			$arkab[] = array(
				'id' => $rsa['rs_grupid'],
				'nm' => $rsa['rs_grupnama']
			);
		}
		return $arkab;
	}
	function getGambarProduk($id) {
	    $arkab = array();
		$strsql=mysql_query("select * from _produsen_gambar where produsen_id='".$id."'");
		while ($rsa=mysql_fetch_array($strsql)){
			$arkab[] = array(
				'idgbr' => $rsa['idprodgbr'],
				'gbr' => $rsa['gambar']
			);
		}
		return $arkab;
	}
	function simpanGambarProduk($value){
	   $sql = mysql_query("insert into _produsen_gambar values $value");
	   if($sql) return true;
	   else return false;
	}
	function checkDataGambarProdukByID($id){
		$check = mysql_query("select idprodgbr from _produsen_gambar where idprodgbr='$id'");
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	function hapusGambar($data){
		$check = mysql_query("delete from _produsen_gambar where idprodgbr='$data'");
		if($check) return true;
		else return false;
	}
	function hapusGambars($data){
		$check = mysql_query("delete from _produsen_gambar where ".$data);
		if($check) return true;
		else return false;
	}
	function getProdusenGambarByID($data){
	   $arproduk = array();
		$strsql=mysql_query("select gambar from _produsen_gambar WHERE produsen_id = '$data' ");
		while ($rsa=mysql_fetch_array($strsql)){
			$arproduk[] = array(
				'gbr' => $rsa['gambar']
			);
		}
		return $arproduk;
	}
	function hapusGambarbyProdusen($data){
	    $check = mysql_query("delete from _produsen_gambar where produsen_id='$data'");
		if($check) return true;
		else return false;
	}
	function __destruct() {
		$this->db->disconnect();
	}
}
?>