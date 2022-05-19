<?php
class modelShipping {
	private $db;
	private $tabelnya;
	private $userlogin;
	
	function __construct(){
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function getShippingLimit($batas,$baris,$data){
		
		$where = '';
		$filter = array();
		
		if($data['caridata'] !='') $filter[] = " kecamatan_nama like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where!='') $where = " where ".$where;
		
		$sql = "select * from _shipping ".$where." order by shipping_id desc limit $batas,$baris";
		
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
	function totalShipping($data){
		$where = '';
		$filter = array();
		
		if($data['caridata'] !='') $filter[] = " kecamatan_nama like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
	    if($where!='') $where = " where ".$where;
		$strsql=$this->db->query("select count(*) as total from _shipping ".$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	function getShippingByIdServ($data){
		$sql = "select shipping_id,shipping_kode,
					   shipping_bataskoma,
					   servis_code,
					   shipping_konfirmadmin
				from _shipping left join _servis
				on _shipping.shipping_id = _servis.servis_shipping
				where _servis.servis_id='".$data['serviskurir']."'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	function getServisByIdserv($data){
		$sql = "select _servis.servis_id,servis_code,servis_nama,servis_shipping,
				shipping_kode,shipping_nama,shipping_logo,tampil,
				detek_kdpos,shipping_keterangan,shipping_bataskoma,
				shipping_cod,shipping_konfirmadmin,hrg_perkilo,_tarif.keterangan
				from _servis 
				left join _shipping on _servis.servis_shipping = _shipping.shipping_id
				left join _tarif on _tarif.servis_id = _servis.servis_id
				where _servis.servis_id='".$data['serviskurir']."' 
				and tampil = '1' and shipping_publik='1'
				and kecamatan_id='".$data['kecamatan_penerima']."'
				and kabupaten_id='".$data['kabupaten_penerima']."'
				and provinsi_id='".$data['propinsi_penerima']."'";
		
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	function getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan){
		$sql = "select _servis.servis_id,servis_code,
				servis_nama,servis_shipping,
				shipping_kode,shipping_nama,
				shipping_keterangan,shipping_bataskoma,
				shipping_konfirmadmin,hrg_perkilo,_tarif.keterangan,shipping_cod
				from _tarif 
				left join _servis on _tarif.servis_id = _servis.servis_id
				left join _shipping on _servis.servis_shipping = _shipping.shipping_id
				where tampil='1' and shipping_publik='1' and shipping_konfirmadmin = '0' 
				and kecamatan_id='".$kecamatan."'
				and kabupaten_id='".$kabupaten."'
				and provinsi_id='".$propinsi."'
				union
				select _servis.servis_id,servis_code,
				servis_nama,servis_shipping,
				shipping_kode,shipping_nama,
				shipping_keterangan,shipping_bataskoma,
				shipping_konfirmadmin,'Konfirmasi Admin' as hrg_perkilo,'Konfirmasi Admin' as keterangan,shipping_cod
				from _servis
				left join _shipping on _servis.servis_shipping = _shipping.shipping_id
				where tampil='1' and shipping_publik='1' and shipping_konfirmadmin = '1'
				order by shipping_konfirmadmin asc, servis_code asc";
		
		$strsql = $this->db->query($sql);
		if($strsql) {
			$data = [];
			foreach($strsql->rows as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}
	function tarifkurir($data){
		$totberat = (int)$data['totberat'] / 1000;
		if($totberat < 1) $totberat = 1;
		$jarakkoma = 0;
		if($totberat > 1) {
			$berat = floor($totberat);
			$jarakkoma = $totberat - $berat;
		}
		$idservis = $data['serviskurir'];
		
		$sql = "select _servis.servis_id,servis_code,
				servis_nama,servis_shipping,
				shipping_kode,shipping_nama,
				shipping_keterangan,shipping_bataskoma,
				shipping_konfirmadmin,hrg_perkilo,_tarif.keterangan
				from _tarif 
				left join _servis on _tarif.servis_id = _servis.servis_id
				left join _shipping on _servis.servis_shipping = _shipping.shipping_id
				where tampil='1' and shipping_publik='1' 
				and _tarif.servis_id='".$idservis."' 
				and kecamatan_id='".$data['kecamatan_penerima']."'
				and kabupaten_id='".$data['kabupaten_penerima']."'
				and provinsi_id='".$data['propinsi_penerima']."'";
				
				
		$strsql = $this->db->query($sql);
		$row = isset($strsql->row) ? $strsql->row : false;
		if($row) {
			//print_r($row);
			if($row['shipping_konfirmadmin'] == '0') {
				$batas = $row['shipping_bataskoma'];
				$hargaperkilo = $row['hrg_perkilo'];
				
				if($jarakkoma > $batas) $totberat = ceil($totberat);
				else $totberat = floor($totberat);
				
				$tarif = $totberat * $hargaperkilo;
			} else {
				$tarif = 'Konfirmasi Admin';
			}
		} else {
			$tarif = 'Konfirmasi Admin';
		}
		return $tarif;
		
	}
	function getShippingByID($id){
		$sql = "select * from _shipping where shipping_id='".$id."'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	
	function checkDataShipping($kode_shipping){
		$check = $this->db->query("select shipping_kode from _shipping where shipping_kode='".$kode_shipping."'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function editShipping($data){
		$sql = "update _shipping
				set 
				shipping_kode='".$data['shipping_kode']."',
				shipping_kdrajaongkir='".$data['shipping_kdrajaongkir']."',
				shipping_nama='".$data['shipping_nama']."',
				shipping_logo='".$data['shipping_logo_nama']."',
				shipping_bataskoma='".$data['shipping_bataskoma']."',
				shipping_publik='".$data['shipping_publik']."',
				shipping_cod='".$data['shipping_cod']."',
				shipping_konfirmadmin='".$data['shipping_konfirmadmin']."',
				shipping_rajaongkir='".$data['shipping_rajaongkir']."',
				tampil='".$data['tampil']."'
				where shipping_id='".$data['shipping_id']."'";
		$strsql = $this->db->query($sql);
		if($strsql){
			return array("status"=>"success");
		} else {
			return array("status"=>"error");
		}
				
	}
	
	function simpanShipping($data){
		
		$sql = "insert into _shipping 
				values
				(null,'".$data['shipping_kode']."','".$data['shipping_kdrajaongkir']."','".$data['shipping_nama']."',
				'".$data['shipping_logo_nama']."',".$data['shipping_bataskoma'].",
				'".$data['shipping_publik']."','".$data['shipping_cod']."',
				'".$data['shipping_konfirmadmin']."','".$data['shipping_rajaongkir']."',
				'".$data['tampil']."')";
		$strsql = $this->db->query($sql);
		if($strsql){
			return array("status"=>"success");
		} else {
			return array("status"=>"error");
		}
		
	}
	
	function getServisAllByKurir($batas,$baris,$data){
		$where = '';
		$filter = array();
		
		if($data['idkurir'] != '' && $data['idkurir'] != '0') $filter[] = " servis_shipping='".$data['idkurir']."'";
		if($data['caridata'] !='') $filter[] = " servis_nama like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where!='') $where = " where ".$where;
		
		$sql = "select * from _servis ".$where." order by servis_id desc limit $batas,$baris";
		
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
	
	function totalServisAll($data){
		$where = '';
		$filter = array();
		
		if($data['idkurir'] != '' && $data['idkurir'] != '0') $filter[] = " servis_shipping='".$data['idkurir']."'";
		if($data['caridata'] !='') $filter[] = " servis_nama like '%".trim($this->db->escape($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
	    if($where!='') $where = " where ".$where;
		$strsql=$this->db->query("select count(*) as total from _servis ".$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	
	function getServisByID($idservis){
		$sql = "select 
				servis_id,servis_code,
				servis_nama,servis_shipping,shipping_nama
				from _servis left join _shipping on _servis.servis_shipping = _shipping.shipping_id
				where servis_id='".$idservis."'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	
	function getServisByKurir($shipping_id){
		$sql = "select * from _servis where servis_shipping='".$shipping_id."'";
		$strsql = $this->db->query($sql);
		if($strsql){
			$data = [];
			foreach($strsql->rows as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	function editServis($data){
		$sql = "update _servis set
				servis_code='".$data['servis_code']."',
				servis_nama='".$data['servis_nama']."'
				where servis_id='".$data['servis_id']."'";
		$strsql = $this->db->query($sql);
		if($strsql){
			return array("status"=>"success");
		} else {
			return array("status"=>"error");
		}
	}
	
	function simpanServis($data){
		$sql = "insert into _servis 
				values (null,'".$data['servis_code']."','".$data['servis_nama']."','".$data['shipping_id']."')";
		$strsql = $this->db->query($sql);
		if($strsql){
			return array("status"=>"success");
		} else {
			return array("status"=>"error");
		}
	}
	
	function checkDataServis($data){
		$check = $this->db->query("select servis_code from _servis where servis_code='".$data['servis_code']."' and servis_shipping='".$data['shipping_id']."'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkRelasiServis($idservis){
		$sql = "select pesanan_no from _order where servis_kurir='".$idservis."'";
		$check = $this->db->query($sql);
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function hapusServis($pid){
		$dataid = implode(",",$pid);
		$sql = 'delete from _servis where servis_id in ('.$dataid.')';
		
		$strsql = $this->db->query($sql);
		if($strsql){
			return array("status"=>"success");
		} else {
			return array("status"=>"error");
		}
	}
	
	function importservis($data){
		$sql = "insert ignore into _servis values ".$data;
		$strsql = $this->db->query($sql);
		if($strsql){
			return array("status"=>"success");
		} else {
			return array("status"=>"error");
		}
	}
	
	function getAllServisKonfirmAdmin(){
		/*
		$sql = "select _servis.servis_id,servis_code,
				servis_nama,servis_shipping,
				shipping_kode,shipping_nama,
				shipping_bataskoma,
				shipping_konfirmadmin,'Konfirm Admin' as hrg_perkilo,shipping_cod
				from _servis 
				left join _shipping on _servis.servis_shipping = _shipping.shipping_id
				where tampil='1' and shipping_publik='1' and shipping_konfirmadmin = '1'";
		*/
		$sql = "select _servis.servis_id,servis_code,
				servis_nama,servis_shipping,
				shipping_kode,shipping_nama,
				shipping_bataskoma,
				shipping_konfirmadmin,'Konfirm Admin' as hrg_perkilo,shipping_cod,'0' as shipping_rajaongkir
				from _servis 
				left join _shipping on _servis.servis_shipping = _shipping.shipping_id
				where tampil='1' and shipping_publik='1' and shipping_rajaongkir <> '1'";
		
		$strsql = $this->db->query($sql);
		if($strsql) {
			$data = [];
			foreach($strsql->rows as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}
	
	function getShippingRajaOngkir(){
		$data = [];
		$sql = "select shipping_id,shipping_kode,shipping_kdrajaongkir,servis_id,servis_code,servis_nama,shipping_logo		
				from _shipping inner join _servis
				on _shipping.shipping_id = _servis.servis_shipping				
				where shipping_rajaongkir='1' and tampil='1' order by shipping_nama,servis_nama";
		$strsql = $this->db->query($sql);
		foreach($strsql->rows as $row){
			$data[] = $row;
		}
		return $data;
	}
}
