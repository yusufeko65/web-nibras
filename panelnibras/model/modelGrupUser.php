<?php
class modelGrupUser {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_login_group';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataGrupUser($grup_nama){
		$check = $this->db->query("select lg_nama from ".$this->tabelnya." where lg_nama='$grup_nama'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataGrupUserByID($id){
		$check = $this->db->query("select lg_id from ".$this->tabelnya." where lg_id='$id'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanGrupUser($data){
		
		$sql=$this->db->query("insert into ".$this->tabelnya." values (null,'".$data['grup']."','".$data['keterangan']."')");
		$lastid = $this->db->lastid();
		
		if(count($data['idmenu']) > 0) {
			$value = [];
			foreach($data['idmenu'] as $menu){
				$chkadd = isset($data["chkadd{$menu}"]) ? $data["chkadd{$menu}"] : '0';
				$chkedit = isset($data["chkedit{$menu}"]) ? $data["chkedit{$menu}"] : '0';
				$chkdel = isset($data["chkdel{$menu}"]) ? $data["chkdel{$menu}"] : '0';
				$chkview = isset($data["chkview{$menu}"]) ? $data["chkview{$menu}"] : '0';
				
				if($chkadd == '1') {
					$data['grup_add'] = '1';
				} else {
					$data['grup_add'] = '0';
				}
				if($chkedit == '1') {
					$data['grup_edit'] = '1';
				} else {
					$data['grup_edit'] = '0';
				}
				if($chkdel == '1') {
					$data['grup_del'] = '1';
				} else {
					$data['grup_del'] = '0';
				}
				if($chkview == '1') {
					$data['grup_view'] = '1';
				} else {
					$data['grup_view'] = '0';
				}
				if($chkadd == '1' || $chkedit == '1' || $chkdel == '1' || $chkview == '1') {
				
					$value[] = "('".$menu."','".$lastid."','".$data['grup_add']."','".$data['grup_edit']."','".$data['grup_del']."','".$data['grup_view']."')";
				}
			}
			if(count($value) > 0){
				$datavalue = implode(",",$value);
				$this->db->query("insert into _hak_akses values ".$datavalue);
			} 
			
		}
	   
	}
	
	function simpanMenuAkses($data){
	   $sql=$this->db->query("insert into _hak_akses values ('".$data['grup_menu']."','".$data['grup_id']."','".$data['grup_add']."','".$data['grup_edit']."','".$data['grup_del']."','".$data['grup_view']."')");
	   if($sql) return true;
	   else return false;
	}
	
	function editGrupUser($data){
		$this->db->query("update ".$this->tabelnya." set lg_nama='".$data['grup']."',lg_desc='".$data['keterangan']."'
							  where lg_id='".$data['iddata']."'");
		
		$this->db->query("delete from _hak_akses where lg_id='".$data['iddata']."'");
		
		if(count($data['idmenu']) > 0) {
			$value = [];
			foreach($data['idmenu'] as $menu){
				$chkadd = isset($data["chkadd{$menu}"]) ? $data["chkadd{$menu}"] : '0';
				$chkedit = isset($data["chkedit{$menu}"]) ? $data["chkedit{$menu}"] : '0';
				$chkdel = isset($data["chkdel{$menu}"]) ? $data["chkdel{$menu}"] : '0';
				$chkview = isset($data["chkview{$menu}"]) ? $data["chkview{$menu}"] : '0';
				
				if($chkadd == '1') {
					$data['grup_add'] = '1';
				} else {
					$data['grup_add'] = '0';
				}
				if($chkedit == '1') {
					$data['grup_edit'] = '1';
				} else {
					$data['grup_edit'] = '0';
				}
				if($chkdel == '1') {
					$data['grup_del'] = '1';
				} else {
					$data['grup_del'] = '0';
				}
				if($chkview == '1') {
					$data['grup_view'] = '1';
				} else {
					$data['grup_view'] = '0';
				}
				if($chkadd == '1' || $chkedit == '1' || $chkdel == '1' || $chkview == '1') {
				
					$value[] = "('".$menu."','".$data['iddata']."','".$data['grup_add']."','".$data['grup_edit']."','".$data['grup_del']."','".$data['grup_view']."')";
				}
			}
			if(count($value) > 0){
				$datavalue = implode(",",$value);
				
				$this->db->query("insert into _hak_akses values ".$datavalue);
			} 
			
		}
	   
	}
	
	function getGrupUser(){
		
		$strsql=$this->db->query("select * from ".$this->tabelnya." order by lg_nama asc ");
		if($strsql){
			$ar = array();
			foreach($strsql->rows as $rsa) {
			   $ar[] = array(
				 'lg_id' => $rsa['lg_id'],
				 'lg_nm' => $rsa['lg_nama']
			   );
			}
			return $ar;
		}
		return false;
	}
	function getGrupUserLimit($batas,$baris,$data){
	   
		$where = '';
		$filter = array();
		if($data['caridata']!='') $filter[] = " lg_nama like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		if($where!='') $where = " where ".$where;
		$sql = "SELECT lg_id,lg_nama,lg_desc FROM _login_group ".$where." ORDER by lg_id desc limit $batas,$baris";
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
	function totalGrupUser($data){
		$where = '';
		$filter = array();
		if($data['caridata']!='') $filter[] = " lg_nama like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
	    if($where!='') $where = " where ".$where;
		$strsql=$this->db->query("select count(*) as total from ".$this->tabelnya.$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	function getGrupUserByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where lg_id='".$iddata."'");
		return isset($strsql->row) ?$strsql->row : false;
	}
	
	function checkRelasi($data){
		$check = $this->db->query("select lg_id from _login where lg_id='$data'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	function hapusGrupUser($data){
		return $this->db->query("delete t1,t2 from ".$this->tabelnya." t1 
					      LEFT JOIN _hak_akses t2 ON t1.lg_id = t2.lg_id 
						  where t1.lg_id='".$data."'");
		
		
	}
	function getMenu(){
		
		$strsql=$this->db->query("select * from _menu order by menu_id asc ");
		if($strsql){
			$armenu = array();
			foreach($strsql->rows as $rsa) {
				$armenu[] = array(
					'idm' => $rsa['menu_id'],
					'nmm' => $rsa['menu_name']
				);
			}
			return $armenu;
		}
		return false;
	}
	function getHakAkses($iddata){
		
		$strsql=$this->db->query("select h.ha_menu,m.menu_name,h.ha_add,h.ha_edit,h.ha_delete,h.ha_view from _hak_akses h
							 INNER JOIN _menu m ON h.ha_menu=m.menu_id WHERE h.lg_id=$iddata order by m.menu_id asc ");
		if($strsql){
			$arhak = array();
			foreach($strsql->rows as $rsa) {
				$arhak[] = array(
					'idm' => $rsa['ha_menu'],
					'nmm' => $rsa['menu_name'],
					'add' => $rsa['ha_add'],
					'edit' => $rsa['ha_edit'],
					'del' => $rsa['ha_delete'],
					'view' => $rsa['ha_view']
				);
			}
			return $arhak;
		}
		return false;
	}
	function hapusMenuAkses($data){
		$check = $this->db->query("delete from _hak_akses where lg_id='$data'");
		if($check) return true;
		else return false;
	}
}
?>