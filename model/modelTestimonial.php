<?php
class model_Testimonial {
	private $db;
	private $tabelnya;
	
	public function __construct(){
		$this->tabelnya = '_testimonial';
		$this->db 		= new Database();
		$this->db->connect();
	}
	

	public function Simpan($data){
	    $sql = $this->db->query("INSERT INTO ".$this->tabelnya." values ('',
							'".$data['nama']."',
							'".$data['email']."',
							'".$data['web']."',
							'".$data['komentar']."',
							'".$data['tglkirim']."',
							'0','','')");
		
		return $sql;
		
	}
	public function getTestimonialLimit($batas,$baris,$where){
	    $rows     = "*";
		$orderby  = " testim_tgl desc limit $batas,$baris";
		$where    = 'testim_approve = 1';
		if($where!='') $where = " where ".$where;
		$sql = 'SELECT '.$rows.' FROM '.$this->tabelnya.$where.' ORDER BY '.$orderby;
		
		$strsql = $this->db->query($sql);
		return $strsql->rows;
	}
	
	public function totalTestimonial($where){
	  
		$strsql= $this->db->query("select count(testim_approve) as total from ".$this->tabelnya." WHERE testim_approve=1");
		return $strsql->row['total'];
	}
	
	public function __destruct() {
		$this->db->disconnect();
	}
}
?>