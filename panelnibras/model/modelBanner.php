<?php
class modelBanner {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_banner';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataBanner($banner_nama){
		$check = mysql_query("select nama_banner from ".$this->tabelnya." where nama_banner = '$banner_nama'");
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataBannerByID($banner_id){
		$check = mysql_query("select nama_banner from ".$this->tabelnya." where idbanner='$banner_id'");
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	
	function simpanBanner($data){
	   $sql=mysql_query("insert into ".$this->tabelnya." values ('','".$data['banner_nama']."','".$data['logo_name']."',
	                    '".$data['panjang']."','".$data['lebar']."','".$data['urllink']."','".$data['slot']."','".$data['banner_status']."')");
	   if($sql) return true;
	   else return false;
	}
	
	function editBanner($data){
	   $sql=mysql_query("update ".$this->tabelnya." set nama_banner='".$data['banner_nama']."',
	                     gbr_banner = '".$data['logo_name']."', panjang_banner='".$data['panjang']."',
						 lebar_banner = '".$data['lebar']."',link_banner='".$data['urllink']."',
						 tampil = '".$data['banner_status']."' where idbanner='".$data['banner_id']."'");
	   
	   if($sql) return true;
	   else return false;
	  
	}
	
	function getBanner(){
		$arBanner = array();
		$strsql=mysql_query("select * from ".$this->tabelnya." order by Banner_nama asc ");
		while ($rsa=mysql_fetch_array($strsql)){
			$arBanner[] = array(
				'ids' => $rsa['Banner_id'],
				'nms' => $rsa['Banner_nama']
			);
		}
		return $arBanner;
	}
	function getBannerLimit($batas,$baris,$where){
	    $rows    = "*";
		$orderby = "idbanner desc limit $batas,$baris";
		$this->db->select($this->tabelnya, $rows, $where, $orderby);
		$hasil = $this->db->getResult();
		return $hasil;
	}
	function getBannerByID($iddata){
		$strsql=mysql_query("select * from ".$this->tabelnya." where idbanner='".$iddata."'");
		if($strsql){
		   $rsa=mysql_fetch_array($strsql);
		   return $rsa;
		} else {
		   return false;
		}
	}
	function totalBanner($where){
	    if($where!='') $where = " where ".$where;
		$strsql=mysql_query("select count(*) from ".$this->tabelnya.$where);
		$row = mysql_fetch_row($strsql);
		return $row[0];
	}
	function checkRelasi($data){
		$check = mysql_query("select Banner_id from _Banner_rekening where Banner_id='$data'");
		$jml=mysql_num_rows($check);
		if($jml>0) return true;
		else return false;
	}
	function hapusBanner($data){
		$check = mysql_query("delete from ".$this->tabelnya." where idbanner = '$data'");
		if($check) return true;
		else return false;
	}
	function __destruct() {
		$this->db->disconnect();
	}
}
?>