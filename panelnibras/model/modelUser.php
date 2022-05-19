<?php
class modelUser {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_login';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataUser($login){
		$check = $this->db->query("select login_nama from ".$this->tabelnya." where login_username='$login'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataUserByID($login_id){
		$check = $this->db->query("select login_nama from ".$this->tabelnya." where login_id='$login_id'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanUser($data){
	   $sql=$this->db->query("insert into ".$this->tabelnya." 
	                     values (null,'".$this->db->escape($data['nama'])."',
						 '".$data['user']."',
						 '".$data['pass']."',
						 '".$data['grup']."',
						 '".$data['status']."','0000-00-00')");
	   if($sql) return true;
	   else return false;
	}
	
	function editUser($data){
	   $sql=$this->db->query("update ".$this->tabelnya." set login_nama='".$this->db->escape($data['nama'])."',
						login_pwd = '".$data['pass']."',
						lg_id = '".$data['grup']."',
						login_status='".$data['status']."' where login_id='".$data['iddata']."'");
	   if($sql) return true;
	   else return false;
	}
	
	function getUserLimit($batas,$baris,$data){
	    
		$where = '';
		$filter = array();
		
		if($data['caridata']!='') $filter[] = " login_nama like '%".trim($this->db->escape($data['caridata']))."%' OR login_username LIKE '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where!='') $where = " where ".$where;
		
		$sql = "select login_id, login_nama,login_username, _login_group.lg_nama 
		        from _login inner join _login_group ON _login.lg_id=_login_group.lg_id".$where." order by _login.login_id desc limit $batas,$baris";
		$strsql = $this->db->query($sql);
		if($strsql){
			$hasil = [];
			foreach($strsql->rows as $rs)
			{
				$hasil[] = $rs;
			}
			return $hasil;
		}
		return false;
	}
	function totalUser($data){
		$where = '';
		$filter = array();
		
		if($data['caridata']!='') $filter[] = " login_nama like '%".trim($this->db->escape($data['caridata']))."%' OR login_username LIKE '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
	    if($where!='') $where = " where ".$where;
		$strsql=$this->db->query("select count(*) as total from _login INNER JOIN _login_group ON _login.lg_id=_login_group.lg_id ".$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	function getUserByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where login_id='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : false;
	}
	
	function checkRelasi($data){
		$check = $this->db->query("select addedby from _informasi where addedby='$data'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	function hapusUser($data){
		$check = $this->db->query("delete from ".$this->tabelnya." where login_id='$data'");
		if($check) return true;
		else return false;
	}
}
?>