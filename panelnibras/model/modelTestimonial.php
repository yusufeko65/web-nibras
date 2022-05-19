<?php
class modelTestimonial {
	private $db;
	private $tabelnya;
	private $sessionuser;
	
	function __construct(){
		$this->tabelnya = '_testimonial';
		$this->db 		= new Database();
		$this->db->connect();
		$this->sessionuser = isset($_SESSION['userlogin']) ? $_SESSION['userlogin']:'';
	}
	
	function checkDataTestimonial($testimonial_nama){
		$check = $this->db->query("select testimonial_nama from ".$this->tabelnya." where testimonial_nama='$testimonial_nama'");
		$jml = $check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataTestimonialByID($testimonial_id){
		$check = $this->db->query("select testim_nama from ".$this->tabelnya." where testimid='$testimonial_id'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanTestimonial($data){
		$strsql = "insert into ".$this->tabelnya." values (null,'".$data['testim_nama']."',
	               '".$data['testim_email']."','".$data['testim_url']."','".$data['testim_komen']."',
				   '".$data['tgl']."','".$data['testim_status']."','".$data['testim_approve']."','".$this->sessionuser."','".$data['tgl']."')";
	   $sql=$this->db->query($strsql);
	   if($sql) return true;
	   else return false;
	}
	
	function editTestimonial($data){
	   $sql=$this->db->query("update ".$this->tabelnya." set testim_nama='".$data['testim_nama']."',
						testim_email='".$data['testim_email']."',testim_url='".$data['testim_url']."',
						testim_komen='".$data['testim_komen']."',testim_approve='".$data['testim_status']."',
						testim_approveby='".$this->sessionuser."',testim_approvetgl='".$data['tgl']."' 
						where testimid='".$data['testimid']."'");
	   if($sql) return true;
	   else return false;
	  
	}
	
	function getTestimonial(){
		
		$strsql=$this->db->query("select * from ".$this->tabelnya." order by testimonial_nama asc ");
		if($strsql){
			$arTestimonial = array();
			foreach($strsql->rows as $rsa){
				$arTestimonial[] = array(
					'ids' => $rsa['testimonial_id'],
					'nms' => $rsa['testimonial_nama']
				);
			}
			return $arTestimonial;
		}
		return false;
	}
	function getTestimonialLimit($batas,$baris,$data){
	    
		$where = '';
		$filter = array();
		
		if($data['caridata'] !='') $filter[] = " testim_nama like '%".trim($this->db->escape($data['caridata']))."%'";
		if($data['approve']  !='all' && $data['approve'] != '') $filter[] = " testim_approve = '".trim(strip_tags($data['approve']))."'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where!='') $where = " where ".$where;
		
		$sql = "SELECT * FROM _testimonial ".$where." ORDER BY testimid desc limit $batas,$baris";
		$strsql = $this->db->query($sql);
		if($strsql){
			$hasil = array();
			foreach($strsql->rows as $row)
			{
				$hasil[] = $row;
			}
			return $hasil;
		}
		
		return false;
	}
	
	function totalTestimonial($data){
		$where = '';
		$filter = array();
		
		if($data['caridata'] !='') $filter[] = " testim_nama like '%".trim($this->db->escape($data['caridata']))."%'";
		if($data['approve']  !='all' && $data['approve'] != '') $filter[] = " testim_approve = '".trim(strip_tags($data['approve']))."'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		
	    if($where!='') $where = " where ".$where;
		$strsql=$this->db->query("select count(*) as total from ".$this->tabelnya.$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	
	function getTestimonialByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where testimid='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : false;
	}
	
	function checkRelasi($data){
		$check = $this->db->query("select testimonial_id from _testimonial_rekening where testimonial_id='$data'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	function hapusTestimonial($data){
		$check = $this->db->query("delete from ".$this->tabelnya." where testimid IN (".$data.")");
		if($check) return true;
		else return false;
	}
}
?>