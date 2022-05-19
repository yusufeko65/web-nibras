<?php
class modelSlideShow {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_slideshow';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataSlideShow($nama_slide){
		$check = $this->db->query("select nama_slide from ".$this->tabelnya." where nama_slide = '$nama_slide'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataSlideShowByID($id_slide){
		$check = $this->db->query("select nama_slide from ".$this->tabelnya." where id_slide='$id_slide'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanSlideShow($data){
		$sql = "insert into ".$this->tabelnya." values (null,'".$this->db->escape($data['nama_slide'])."','".$data['slide_image']."',
	            '".$data['panjang']."','".$data['lebar']."','".$data['link_slide']."',
				'".$data['sts_slide']."','".$data['urutan']."')";
		$strsql=$this->db->query($sql);
		if($strsql) return true;
		else return false;
	}
	
	function editSlideShow($data){
	   $sql=$this->db->query("update ".$this->tabelnya." set 
							  nama_slide='".$data['nama_slide']."',
							  gbr_slide = '".$data['slide_image']."', 
							  panjang='".$data['panjang']."',
							  lebar = '".$data['lebar']."',
							  link_slide='".$data['link_slide']."',
							  sts_slide = '".$data['sts_slide']."',
							  urutan ='".$data['urutan']."' 
							  where id_slide='".$data['id_slide']."'");
	   
	   if($sql) return true;
	   else return false;
	  
	}
	
	function getSlideShow(){
		$arslide = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya." order by nama_slide asc ");
		if($strsql){
			foreach($strsql->rows as $rsa){
				$arslide[] = array(
					'ids' => $rsa['id_slide'],
					'nms' => $rsa['nama_slide']
				);
			}
		}
		return $arslide;
	}
	function getSlideShowLimit($batas,$baris,$data){
	   
		$hasil = array();
		$where = '';
		$filter = array();
		
		if($data['caridata']!='') $filter[] = " nama_slide like '%".trim(strip_tags($this->db->escape($data['caridata'])))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where) $where = " WHERE ".$where;
		$sql = "SELECT * FROM ".$this->tabelnya.$where." ORDER BY urutan desc limit $batas,$baris";
		$strsql = $this->db->query($sql);
		if($strsql){
			foreach ($strsql->rows as $rsa) {
				$hasil[] = $rsa;
			}
		}
		return $hasil;
	}
	
	function totalSlideShow($data){
		$hasil = array();
		$where = '';
		$filter = array();
		
		if($data['caridata']!='') $filter[] = " nama_slide like '%".trim(strip_tags($this->db->escape($data['caridata'])))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
	    if($where!='') $where = " where ".$where;
		 
		$strsql=$this->db->query("select count(*) as total from ".$this->tabelnya.$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	
	function getSlideShowByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where id_slide='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : false;
	}
	
	
	function hapusSlideShow($data){
		$check = $this->db->query("delete from ".$this->tabelnya." where id_slide = '$data'");
		if($check) return true;
		else return false;
	}
	function __destruct() {
		//$this->db->disconnect();
	}
}
?>