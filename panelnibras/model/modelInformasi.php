<?php
class modelInformasi {
	private $db;
	private $tabelnya;
	private $userlogin;
	
	function __construct(){
		$this->tabelnya = '_informasi';
		$this->db 		= new Database();
		$this->db->connect();
		$this->userlogin = isset($_SESSION["userlogin"]) ? $_SESSION["userlogin"]:'';
	}
	
	function checkDataInformasi($judul){
		$check = $this->db->query("select info_judul from _informasi_deskripsi where info_judul='$judul'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataInformasiByID($id){
		$check = $this->db->query("select * from ".$this->tabelnya." where id_info = '$id'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanInformasi($data){
		$tglnow = date('Y-m-d H:i:s');
		$this->db->query("insert into ".$this->tabelnya." values (null,'".$data['aliasurl']."','".$data['sts_info']."','".$tglnow."','".$this->userlogin."','".$tglnow."','".$this->userlogin."')");
		
		$lastid = $this->db->lastid();
		
		$this->db->query("insert into _informasi_deskripsi values (null,'".$lastid."','".$data['info_judul']."','".$this->db->escape($data['info_detail'])."')");
		
		$inisial = 'informasi='.$lastid;
	   
		$this->db->query("insert into _url_alias values ('".$inisial."','".$data['aliasurl']."','informasi')");
	}
	
	function editInformasi($data){
		$this->db->query("update ".$this->tabelnya." 
						 set aliasurl='".$data['aliasurl']."',
						 sts_info='".$data['sts_info']."',
						 dateupdated= '".date('Y-m-d H:i')."',
						 updatedby='".$this->userlogin."'
						 where id_info='".$data['id_info']."'");
						 
		$this->db->query("update _informasi_deskripsi 
						  set info_judul='".$this->db->escape($data['info_judul'])."',
						  info_detail='".$this->db->escape($data['info_detail'])."' 
						  where idinfo='".$data['id_info']."'");
		
		
		
		$inisial = 'informasi='.$data['id_info'];
	   
		$this->db->query("delete from _url_alias WHERE inisial='".$inisial."'");
		$this->db->query("insert into _url_alias values ('".$inisial."','".$data['aliasurl']."','informasi')");
		
	}
	
	function getInformasi(){
		
		$strsql=$this->db->query("select * from ".$this->tabelnya." INNER JOIN _informasi_deskripsi ON _informasi.id_info = _informasi_deskripsi.idinfo order by info_judul asc ");
		if($strsql) {
			$arnegara = array();
			foreach ($strsql->rows as $rsa) {
				$arnegara[] = array(
					'id' => $rsa['id_info'],
					'nm' => $rsa['info_judul']
				);
			}
			return $arnegara;
		}
		return false;
	}
	function getInformasiLimit($batas,$baris,$data){
	    
		
		$where = '';
		$filter = array();
		
		if($data['caridata']!='') $filter[] = " info_judul like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where!='') $where = " where ".$where;
		
		$sql = "SELECT * FROM _informasi INNER JOIN _informasi_deskripsi ON _informasi.id_info = _informasi_deskripsi.idinfo".$where." ORDER BY id_info DESC limit $batas,$baris";
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
	
	function totalInformasi($data){
	    $where = '';
		$filter = [];
		if($data['caridata']!='') $filter[] = " info_judul like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where!='') $where = " where ".$where;
		
		$strsql = $this->db->query("select count(*) as total from ".$this->tabelnya.' INNER JOIN _informasi_deskripsi ON _informasi.id_info = _informasi_deskripsi.idinfo'.$where);
		
		return isset($strsql->row['total']) ? $strsql->row['total'] : false;
	}
	
	function getInformasiByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." INNER JOIN _informasi_deskripsi ON _informasi.id_info=_informasi_deskripsi.idinfo where id_info='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : false;
	}
	
	function checkRelasi($data){
		$check = $this->db->query("select negara_id from _provinsi where negara_id='$data'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	function hapusInformasi($data){
		
		$sql = "DELETE t1,t2 
				FROM _informasi t1 
				LEFT JOIN _informasi_deskripsi t2 ON t1.id_info = t2.idinfo
				WHERE t1.id_info IN (".$data.")";
		$this->db->query($sql);
		$id=explode(",",$data);
		$datains = [];
		foreach($id as $ids)
		{
			$datains[] = "'informasi=".$ids."'";
		}
		$inisial = implode(",",$datains);
		$sql2 = "DELETE FROM _url_alias WHERE inisial IN (".$inisial.")";
		$this->db->query($sql2);
	}
	function hapusDeskripsiInformasi($data){
		$check = $this->db->query("delete from _informasi_deskripsi where idinfo='$data'");
		if($check) return true;
		else return false;
	}
	function __destruct() {
		//$this->db->disconnect();
	}
}
?>