<?php
class model_Reseller {
	private $db;
	private $tabelnya;
	private $userlogin;
	
	function __construct(){
		$this->tabelnya = '_customer';
		$this->db 		= new Database();
		$this->db->connect();
	}

	function getResellerByID($iddata){
		$sql = "select cust_id,cust_grup_id,cg_nm,cg_total_awal,cg_min_beli,
				cg_min_beli_syarat,cg_min_beli_wajib,cg_deposito,
				cg_diskon,cg_dropship,
				cust_nama,cust_alamat,
				cust_negara,cust_propinsi,provinsi_nama,
				cust_kota,kabupaten_nama,
				cust_kecamatan,kecamatan_nama,
				cust_kelurahan,cust_kdpos, cust_tanpa_biaya_packing,
				cust_telp,cust_email from ".$this->tabelnya." 
				LEFT JOIN _customer_grup ON _customer.cust_grup_id = _customer_grup.cg_id 
				LEFT JOIN _provinsi ON _customer.cust_propinsi = _provinsi.provinsi_id
				left join _kabupaten on _customer.cust_kota = _kabupaten.kabupaten_id
				left join _kecamatan on _customer.cust_kecamatan = _kecamatan.kecamatan_id
				where _customer.cust_id='".$iddata."'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	
	function getResellerCompleteById($iddata){
		$sql = "select cust_id,cust_grup_id,cg_nm,cust_nama,cust_alamat,
				cust_negara,cust_propinsi,provinsi_nama,
				cust_kota,kabupaten_nama,
				cust_kecamatan,kecamatan_nama,
				cust_kelurahan,cust_kdpos,
				cust_telp,cust_email,cg_min_beli,
				cg_min_beli_syarat,cg_deposito,cg_diskon,cg_dropship 
				from ".$this->tabelnya." 
				LEFT JOIN _customer_grup ON _customer.cust_grup_id = _customer_grup.cg_id 
				LEFT JOIN _provinsi ON _customer.cust_propinsi = _provinsi.provinsi_id
				left join _kabupaten on _customer.cust_kota = _kabupaten.kabupaten_id
				left join _kecamatan on _customer.cust_kecamatan = _kecamatan.kecamatan_id
				where _customer.cust_id='".$iddata."'";
		
		
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	
	function getCustByNameHp($data){
		
		$strsql = $this->db->query("select count(ca_cust_id) as total from _customer_address where ca_cust_id='".$data['cust_id']."' and ca_nama='".$this->db->escape(trim($data['nama_pengirim']))."' and ca_hp='".$this->db->escape(trim($data['telp_pengirim']))."'");
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
		
	}
	
	function getGrupCustByID($grupid){
		$strsql= $this->db->query("select * from _customer_grup where cg_id='".$grupid."'");
		return isset($strsql->row) ? $strsql->row : false;
	}
	function getGrupCustMulti($grup) {
		$grup = implode(",",$grup);
		$sql = "select * from _customer_grup where cg_id in (".$grup.")";
		$strsql = $this->db->query($sql);
		if($strsql){
			$data = [];
			foreach($strsql->rows as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}
	function getGrupCusts($grupid){
	    $grupid = "(".implode(",",$grupid).")";
	    
		$strsql = $this->db->query("select * from _customer_grup where cg_id IN $grupid");
		if($strsql){
			$tables = array();
			foreach($strsql->rows as $rs) {
			   $tables[] = array(
				  'id' => $rs['cg_id'],
				  'nm' => $rs['cg_nm']
			   );
			 }
			return $tables;
		} else {
			return false;
		}
	}
	
	public function getGrupAllCusts(){
		$sql = "select * from _customer_grup";
		$strsql = $this->db->query($sql);
		if($strsql) {
			$data = array();
			foreach($strsql->rows as $row){
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}
	
	function getPoin($id){
	    
		$strsql = $this->db->query("select * from _customer_point_history where cph_cust_id='".$id."' ORDER BY cph_tgl DESC");
		if($strsql){
			$tables = array();
			foreach($strsql->rows as $rs) {
				$tables[] = $rs;
			}
		
			return $tables;
		} else {
			return false;
		}
	}
	function getTotalPoin($id) {
	   //$strsql= $this->db->query("select cp_poin from _customer_point where cp_cust_id='".$id."'");
		$sql = "SELECT SUM(CASE WHEN cph_tipe = 'OUT' THEN -cph_poin
					    WHEN cph_tipe = 'IN' THEN cph_poin
					    END) AS totalpoin
				FROM _customer_point_history
				where cph_cust_id='".$id."'";
		
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	function getDeposito($id){
	    
		$strsql = $this->db->query("select * from _customer_deposito_history where cdh_cust_id='".$id."' ORDER BY cdh_tgl DESC");
		if($strsql){
			$tables = array();
			foreach($strsql->rows as $rs) {
				$tables[] = $rs;
			}
			return $tables;
		} else {
			return false;
		}
	    
	}
	function gettotalDeposito($id) {
		$sql = "SELECT SUM(CASE WHEN cdh_tipe = 'OUT' THEN -cdh_deposito
					    WHEN cdh_tipe = 'IN' THEN cdh_deposito
					    END) AS totaldeposito
				FROM _customer_deposito_history
				where cdh_cust_id='".$id."'";
	   $strsql= $this->db->query($sql);
	   return isset($strsql->row) ? $strsql->row : false;
	}
	function getAlamatCustomer($idmember){
		$sql = "select ca_id,ca_cust_id,ca_nama,
		        ca_alamat,ca_propinsi,_provinsi.provinsi_nama,
				ca_kabupaten,_kabupaten.kabupaten_nama,
				ca_kecamatan,_kecamatan.kecamatan_nama,
				ca_kelurahan,ca_kodepos,ca_hp,ca_default
				from _customer_address 
				left join _provinsi on _customer_address.ca_propinsi = _provinsi.provinsi_id 
				left join _kabupaten on _customer_address.ca_kabupaten = _kabupaten.kabupaten_id
				left join _kecamatan on _customer_address.ca_kecamatan = _kecamatan.kecamatan_id 
				where ca_cust_id='".$idmember."' order by ca_default asc, ca_id desc";
		
		$strsql = $this->db->query($sql);
		if($strsql){
			$data=array();
			foreach($strsql->rows as $rs) {
				$data[] = $rs;
			}
			return $data;
		}
		return false;
	}
	public function getAlamatCustomerByID($id){
		$sql = "select ca_id,ca_cust_id,ca_nama,
		        ca_alamat,ca_propinsi,provinsi_nama,
				ca_kabupaten,kabupaten_nama,ca_kecamatan,kecamatan_nama,
				ca_kelurahan,ca_kodepos,ca_hp,ca_default
				from _customer_address 
				left join _provinsi on _customer_address.ca_propinsi = _provinsi.provinsi_id
				left join _kabupaten on _customer_address.ca_kabupaten = _kabupaten.kabupaten_id
				left join _kecamatan on _customer_address.ca_kecamatan = _kecamatan.kecamatan_id
				where ca_id='".$id."'";
		
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
}