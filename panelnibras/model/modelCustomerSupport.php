<?php
class modelCustomerSupport {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_customer_support';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataCustomerSupport($cs_akun,$cs_jsupport){
	    $where = " where cs_akun='".$cs_akun."' AND cs_jsupport = '".$cs_jsupport."'";
		$check = $this->db->query("select cs_akun from ".$this->tabelnya.$where);
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataCustomerSupportByID($idsupport){
		$check = $this->db->query("select cs_akun from ".$this->tabelnya." where idsupport='$idsupport'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanCustomerSupport($data){
	   $sql=$this->db->query("insert into ".$this->tabelnya." values (null,'".$data['cs_nama']."','".$data['cs_jsupport']."',
	                    '".$data['cs_akun']."','".$data['cs_status']."')");
	   if($sql) return true;
	   else return false;
	}
	
	function editCustomerSupport($data=array()){
		$sql = "UPDATE ".$this->tabelnya." 
				SET cs_nama='".$data['cs_nama']."',
				cs_jsupport='".$data['cs_jsupport']."',cs_akun='".$data['cs_akun']."',
				cs_status='".$data['cs_status']."' 
				WHERE idsupport='".$data['idsupport']."'";
				
		$sql=$this->db->query($sql);
		if($sql) return true;
		else return false;
	}
	
	function getCustomerSupport(){
		
		$strsql=$this->db->query("select * from ".$this->tabelnya." INNER JOIN 
		                     _jenis_support ON _customer_support.cs_jsupport=_jenis_support.idsupport");
		if($strsql) {
			$data = [];
			foreach($strsql->rows as $rs){
				$data[] = $rs;
			}
			return $data;
		}
		return false;
	}
	function getCustomerSupportLimit($batas,$baris,$data){
	   
		
		$hasil = array();
		$where = '';
		$filter = array();
		
		if($data['caridata']!='') $filter[] = " cs_nama like '%".trim($this->db->escape($data['caridata']))."%' OR cs_akun like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		if($where!='') $where = " where ".$where;
		$sql = "SELECT idsupport,cs_nama,_jenis_support.jenis_support,cs_akun,cs_status 
				FROM ".$this->tabelnya." INNER JOIN _jenis_support ON _customer_support.cs_jsupport=_jenis_support.idjsupport".$where;
		$strsql = $this->db->query($sql);
		if($strsql){
			foreach($strsql->rows as $row)
			{
				$hasil[] = $row;
			}
		}
		return $hasil;
	}
	
	function totalCustomerSupport($data){
		$where = '';
		$filter = array();
		
	    if($data['caridata']!='') $filter[] = " cs_nama like '%".trim(strip_tags($data['caridata']))."%' OR cs_akun like '%".trim(strip_tags($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		if($where!='') $where = " where ".$where;
		
		$strsql=$this->db->query('select count(*) as total from '.$this->tabelnya.' INNER JOIN _jenis_support ON _customer_support.cs_jsupport=_jenis_support.idjsupport'.$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	function getCustomerSupportByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where idsupport='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : false;
	}
	
	
	function hapusCustomerSupport($data){
		$check = $this->db->query("delete from ".$this->tabelnya." where idsupport IN (".$data.")");
		if($check) return true;
		else return false;
	}
}
?>