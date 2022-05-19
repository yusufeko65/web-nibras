<?php
class modelCustomer {
	private $db;
	private $tabelnya;
	private $userlogin;
	function __construct(){
		$this->tabelnya = '_customer';
		$this->db 		= new Database();
		$this->db->connect();
		$this->userlogin = isset($_SESSION["userlogin"]) ? $_SESSION["userlogin"]:'';
	}
	
	function checkDataReseller($email){
		$sql = "select cust_email from ".$this->tabelnya." where cust_email='".$email."'";
		
		$check = $this->db->query($sql);
		$jml   = $check->num_rows;
		 if($jml > 0) return true;
		else return false;
		
	}
	
	function checkNoTelp($notelp){
		
		$check = $this->db->query("select cust_telp from ".$this->tabelnya." where cust_telp='$notelp'");
		$jml=$check->num_rows;
		if($jml > 0) return true;
		else return false;
		
	}
	
	function checkDataResellerByID($rsid){
		$check = $this->db->query("select cust_id from ".$this->tabelnya." where cust_id='$rsid'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanCustomer($data){
		$error = array();
		$status = '';
		$idcust = '';
		$this->db->autocommit(false);
		
		/* simpan ke table _customer */
		$sql = "insert into _customer values 
				(null,'".$data['rtipecust']."','".$data['rnama']."','','','0','0','0','33','0',
				'','".$data['remail']."','".$data['pass']."','0','".$data['rapprove']."','".$data['rstatus']."',
				'".$data['tglupdate']."','".$data['tglupdate']."')";
		$strsql = $this->db->query($sql);
		if(!$strsql) {
			$error[] = "Error di table customer";
		} else {
			$idcust = $this->db->lastid();
		}
		
		
		/* simpan ke table customer aktivitas*/
		$sql = "insert into _customer_aktivitas values 
				(null,'".$idcust."','".$data['tglupdate']."','".$data['aktivitas']."',
				'".$this->db->escape($data['keterangan'])."')";
		$strsql = $this->db->query($sql);
		
		if(!$strsql) $error[] = "Error di table customer aktivitas";
		
		/* update table customer history */
		$sql = "update _customer_history set ch_cust_default=0 where ch_cust_id='".$idcust."'";
		$strsql = $this->db->query($sql);
		
		if(!$strsql) $error[] = "Error di update table customer history";
		
		/* insert table cutomer history */
		$sql = "insert into _customer_history values 
				(null,'".$idcust."','".$data['rtipecust']."',
				'".$data['tglupdate']."','1','".$data['ipaddress']."')";
		$strsql = $this->db->query($sql);
		
		if(!$strsql) $error[] = "Error di insert table customer history";
		
		/* proses penyimpanan */
		if(count($error) > 0) {
			$this->db->rollback();
			$status = "error";
			
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status"=>$status,"idcust"=>$idcust);
		
	}		
	
	function editCustomer($data){
		$error = array();
		$status = '';
		$this->db->autocommit(false);
		
		/* update table _customer */
		$sql = "update _customer set 
				cust_grup_id='".$data['rtipecust']."',
				cust_nama='".$this->db->escape($data['rnama'])."',
				cust_alamat='".$this->db->escape($data['ralamat'])."',
				cust_kelurahan='".$this->db->escape($data['rkelurahan'])."',
				cust_kecamatan='".$this->db->escape($data['rkecamatan'])."',
				cust_kota='".$this->db->escape($data['rkabupaten'])."',
				cust_propinsi='".$this->db->escape($data['rpropinsi'])."',
				cust_negara='33',
				cust_kdpos='".$data['rkodepos']."',
				cust_telp='".$data['rtelp']."',
				cust_email='".$data['remail']."',
				cust_pass='".$data['pass']."',
				cust_newsletter='0',
				cust_approve='".$data['rapprove']."',
				cust_status='".$data['rstatus']."',
				cust_tgl_upd='".$data['tglupdate']."'
				where cust_id='".$data['iddata']."'";
				
		$strsql = $this->db->query($sql);
		if(!$strsql) $error[] = "Error di update table _customer";
		
		/* update table _customer_address */
		$sql = "update _customer_address set
				ca_alamat='".$this->db->escape($data['ralamat'])."',
				ca_propinsi='".$this->db->escape($data['rpropinsi'])."',
				ca_kabupaten='".$this->db->escape($data['rkabupaten'])."',
				ca_kecamatan='".$this->db->escape($data['rkecamatan'])."',
				ca_kelurahan='".$this->db->escape($data['rkelurahan'])."',
				ca_kodepos='".$data['rkodepos']."'
				where ca_cust_id='".$data['iddata']."' and ca_default='1'";
		
		$strsql = $this->db->query($sql);
		if(!$this->db->affected_rows()) { // jika data tidak ada maka diinsert
			
			/* simpan ke table customer address */
			$sql = "insert into _customer_address values
					(null,'".$data['iddata']."','".$this->db->escape($data['ralamat'])."',
					'33','".$data['rpropinsi']."','".$data['rkabupaten']."',
					'".$data['rkecamatan']."','".$data['rkelurahan']."',
					'".$data['rkodepos']."','1')";
			$strsql = $this->db->query($sql);
			
			
		}
		
		if(!$strsql) $error[] = "Error di table customer address";
		
		/* pengecekan update customer history, jika ada perubahan tipe customer */
		if($data['rtipecust'] != $data['rtipecustlama']){
			
			/* update customer history */
			$sql = "update _customer_history set ch_cust_default=0 where ch_cust_id='".$idcust."'";
			$strsql = $this->db->query($sql);
			
			if(!$strsql) $error[] = "Error di update table customer history";
			
			/* insert table cutomer history */
			$sql = "insert into _customer_history values 
					(null,'".$data['iddata']."',
					'".$data['rtipecust']."',
					'".$data['tglupdate']."','1','".$data['ipaddress']."')";
			$strsql = $this->db->query($sql);
			
			if(!$strsql) $error[] = "Error di insert table customer history";
		}
		
		/* simpan ke table customer aktivitas*/
		$sql = "insert into _customer_aktivitas values 
				(null,'".$data['iddata']."','".$data['tglupdate']."','".$data['aktivitas']."',
				'".$this->db->escape($data['keterangan'])."')";
		$strsql = $this->db->query($sql);
		
		if(!$strsql) $error[] = "Error di table customer aktivitas";
		
		/* proses penyimpanan */
		if(count($error) > 0) {
			$this->db->rollback();
			$status = "error";
			
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status"=>$status);
	}
	
	public function cekDeposito($data) {
		$check = $this->db->query("select count(*) as total from _customer_deposito where cd_cust_id='$data'");
		$jml=$check->num_rows;
		if($rs[0] > 0) return true;
		else return false;
	}
	function checkDepositoHistory($data,$jenis) {
		$deposito = -1;
		$str = $this->db->query("SELECT cdh_deposito FROM _customer_deposito_history WHERE cdh_tipe='$jenis' AND cdh_cust_id='".$data['pelangganid']."' AND cdh_order='".$data['nopesan']."'");
		return isset($str->row['cdh_deposito']) ? $str->row['cdh_deposito'] : -1;
		
    }
	
	function updateDepositoHistory($data,$jenis) {
		$sql = "UPDATE _customer_despotiso_history SET cdh_deposito='".$data['totdeposito']."' 
			  WHERE cdh_cust_id='".$data['pelangganid']."' AND cdh_tipe='$jenis' AND cdh_order='".$data['nopesan']."'";
		return $sql;
	}
	
	public function updateDeposito($data) {

		$sql = "UPDATE _customer_deposito set cd_deposito=cd_deposito+'".$data['deposito']."',
						  cd_tglupdate='".$data['tglupdate']."' WHERE cd_cust_id='".$data['iddata']."'";
		return $sql;
	}
	public function DeleteDepositDetail($id,$idorder,$jenis) {
		$sql = "DELETE from _customer_deposito_history where cdh_order='".$idorder."' AND cdh_cust_id='".$id."' AND cdh_tipe='$jenis'";
		return $sql;
	}
	public function simpanInvoice($data) {
	  
		return $this->db->query("INSERT INTO _reseller_invoice values (null,'".$data['id']."','".$data['tipe_reseller']."',
	                      '".$data['tglupdate']."','".$data['biaya_register']."','".$data['stsbayar']."','".$data['keterangan']."')");
	  
	}
	
	function approveReseller($data){
		return $this->db->query("update ".$this->tabelnya." set stsapprove = '1',reseller_kode = '".$data['kode']."' WHERE reseller_id='".$data['id']."' AND stsapprove = '0'");
	  
	}
	
	function approveResellerInvoice($data){
		return $this->db->query("update _reseller_invoice set stsbayar = '1' WHERE idmember='".$data['id']."' AND stsbayar = '0'");
	 
	}
	function editResellerMasaAktif($data=array(),$renew){
		$sql = $this->db->query("select count(*) as total from _reseller_batas_aktif where idreseller='".$data['iddata']."'");
		$total  = isset($sql->row['total']) ? $sql->row['total'] : 0;
		if($total > 0) {
			return $this->db->query("UPDATE _reseller_batas_aktif set
	                       tgl_batas='".$data['tglexpired']."',renew='$renew' where idreseller='".$data['iddata']."'");
		} else {
			return $this->db->query("INSERT INTO _reseller_batas_aktif values ('".$data['iddata']."','".$data['tglexpired']."','$renew')");
		}
	}
	function simpanResellerMasaAktif($data=array(),$renew){
		return $this->db->query("INSERT INTO _reseller_batas_aktif values ('".$data['id']."','".$data['tglexpired']."','".$renew."')");
	   
	}
	
	function editReseller($data=array()){
	  
		$sql = "UPDATE ".$this->tabelnya." set cust_grup_id = '".$data['tipe_reseller']."',cust_nama='".$data['nama']."',cust_alamat = '".$data['alamat']."',
               cust_kelurahan = '".$data['kelurahan']."',cust_kecamatan = '".$data['kecamatan']."',cust_kota = '".$data['kabupaten']."',
							cust_propinsi = '".$data['propinsi']."',cust_negara = '".$data['negara']."',cust_kdpos = '".$data['kodepos']."',
							cust_telp = '".$data['telp']."',cust_email = '".$data['email']."',cust_pass = '".$data['pass']."',
							cust_status = '".$data['status']."',
							cust_tgl_upd ='".$data['tglupdate']."' WHERE cust_id='".$data['iddata']."'";
		return $sql;
	}
	
	function editResellerGrup($data=array()){
		return $this->db->query("UPDATE ".$this->tabelnya." set 
							reseller_tglupdated ='".$data['tglupdate']."',

							reseller_grup = '".$data['tipe_reseller']."'
						    WHERE reseller_id='".$data['id']."'");
		
	}
	
	function getResellerLimit($batas,$baris,$data){
	   	
		$filter				= array();
		$where = '';
		
		if($data['caridata']!='') $filter[] = " cust_telp like '%".trim(strip_tags($this->db->escape($data['caridata'])))."%' OR cust_nama like '%".trim(strip_tags($this->db->escape($data['caridata'])))."%'";
		if($data['grup'] != '' && $data['grup'] != '0') $filter[] = " cust_grup_id = '".trim(strip_tags(urlencode($data['grup'])))."'";
		
		if(!empty($filter))	$where = implode(" and ",$filter);
		if($where != '') $where = " WHERE ".$where;
		$sql = "SELECT * 
				FROM _customer LEFT JOIN  _customer_grup ON _customer.cust_grup_id = _customer_grup.cg_id ".$where." 
				ORDER BY cust_id desc limit $batas,$baris";
		$strsql = $this->db->query($sql);
		if($strsql) {
			$hasil = array();
			foreach ($strsql->rows as $rsa) {
				$hasil[] = $rsa;
			}
			return $hasil;
		}
		return false;
	}
	
	function totalReseller($data){
	    $where = '';
		
		
		if($data['caridata']!='') $filter[] = " cust_telp like '%".trim(strip_tags($this->db->escape($data['caridata'])))."%' OR cust_nama like '%".trim(strip_tags($this->db->escape($data['caridata'])))."%'";
		if($data['grup'] != '' && $data['grup'] != '0') $filter[] = " cust_grup_id = '".trim(strip_tags(urlencode($data['grup'])))."'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		if($where != '') $where = " WHERE ".$where;
		$strsql=$this->db->query("select count(cust_id) as total from ".$this->tabelnya." INNER JOIN _customer_grup ON _customer.cust_grup_id = _customer_grup.cg_id ".$where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
		
	}
	
	function getResellerdepositLimit($batas,$baris,$where) {
	    $rows = "cust_id,cust_kode,cust_grup_id,cust_nama,cd_deposito,cg_nm,cust_email,cust_telp";
		$orderby = "cust_id asc limit $batas,$baris";
		$this->tabelnya .= " LEFT JOIN _customer_deposito ON _customer.cust_id = _customer_deposito.cd_cust_id";
		$this->tabelnya .= " LEFT JOIN _customer_grup ON _customer.cust_grup_id = _customer_grup.cg_id";
		$this->db->select($this->tabelnya, $rows, $where, $orderby);
		$hasil = $this->db->getResult();
		return $hasil;


	}
	function getDataDepositoLimit($batas,$baris,$iddata) {
	    
		$data = array();
		$sql = "SELECT ".$rows. " FROM _customer_deposito_history WHERE ".$where. " ORDER BY ".$orderby;
		$strsql = $this->db->query($sql);
		foreach($strsql->rows as $rsa) {
			$data[] = $rsa;
		}
		return $data;
		
	}
	function getNotifResellerLimit($batas,$baris,$where,$notif_day,$tglskrg){
	    $rows    = "_reseller.reseller_id,reseller_grup,reseller_nama,rs_grupnama as grup,
		            rs_dropship as dropship,reseller_email,stsapprove,reseller_tglregister,
					CONCAT(rs_grupcode,reseller_kode) as kode,tgl_batas,reseller_hp";
		$orderby = "reseller_id desc limit $batas,$baris";
		$this->tabelnya .= " INNER JOIN _reseller_grup ON _reseller.reseller_grup = _reseller_grup.rs_grupid";
		$this->tabelnya .= " LEFT JOIN _reseller_batas_aktif ON _reseller.reseller_id = _reseller_batas_aktif.idreseller";
		$where .= " AND (tgl_batas BETWEEN ('$tglskrg' + INTERVAL 1 DAY) AND ('$tglskrg' + INTERVAL $notif_day DAY))";
		
		$this->db->select($this->tabelnya, $rows, $where, $orderby);
		$hasil = $this->db->getResult();
		return $hasil;
	}
	function getResellerByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." LEFT JOIN _customer_deposito on _customer.cust_id = _customer_deposito.cd_cust_id where cust_id='".$iddata."'");
		return isset($strsql->row) ? $strsql->row : false;
	}
	function getResellerByKode($iddata){
	    $ar = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya." INNER JOIN _reseller_grup ON reseller_grup = rs_grupid where stsapprove='1' AND (CONCAT(rs_grupcode,reseller_kode) like '".$iddata."%' OR reseller_nama like '".$iddata."%' OR reseller_hp like '".$iddata."%')");
		foreach ($strsql->rows as $rsa) {
			$ar[] = $rsa;
		}
		return $ar;
	}
	
	function totalDataDeposito($iddata){
		$strsql = $this->db->query("select count(cdh_id) as total from _customer_deposito_history WHERE cdh_cust_id='".$iddata."'");
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	function totalResellerdeposit($where){
	    if($where!='') $where = " where ".$where;
		$strsql=$this->db->query("select count(cust_id) as total from ".$this->tabelnya." LEFT JOIN _customer_deposito 
		                     ON _customer.cust_id = _customer_deposito.cd_cust_id 
							 LEFT JOIN _customer_grup ON _customer.cust_grup_id = _customer_grup.cg_id ".$where);
		
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	function totalNotifReseller($where,$notif_day,$tglskrg){
	    if($where!='') $where = " where ".$where." AND (tgl_batas BETWEEN ('$tglskrg' + INTERVAL 1 DAY) AND ('$tglskrg' + INTERVAL $notif_day DAY))";
		$strsql=$this->db->query("select count(*) as total from ".$this->tabelnya." INNER JOIN _reseller_grup ON 
		                     _reseller.reseller_grup = _reseller_grup.rs_grupid 
							 LEFT JOIN _reseller_batas_aktif ON _reseller.reseller_id = _reseller_batas_aktif.idreseller ".$where);
		
		
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	function checkRelasi($data){
		$check = $this->db->query("select pelanggan_id from _order where pelanggan_id='$data'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	function hapusReseller($data){
		$check = $this->db->query("delete from ".$this->tabelnya." where cust_id='$data'");
		$check2 = $this->db->query("delete from _customer_history where ch_cust_id='$data'");
		
		if($check) return true;
		else return false;
	}
	public function checkBiayaReseller($grup) {
	    
		$check = $this->db->query("select rs_hrgregister from _reseller_grup where rs_grupid='".$grup."'");
		return isset($check->row['rs_hrgregister']) ? $check->row['rs_hrgregister'] : 0;
		
	}
	public function checkBiayaPerpanjang($grup) {
	    $check = $this->db->query("select rs_hrgperpanjangreg from _reseller_grup where rs_grupid='".$grup."'");
		return isset($check->row['rs_hrgperpanjangreg']) ? $check->row['rs_hrgperpanjangreg'] : 0;
	}
	function getResellerEksekusi($masa_approve,$data){
	   $arkab = array();
	   $strsql=$this->db->query("SELECT reseller_id,reseller_kode,reseller_nama, reseller_grup,rs_hrgregister,
	                        reseller_tglupdated,NOW() AS tglskarang FROM _reseller INNER JOIN 
							_reseller_grup ON _reseller.reseller_grup = _reseller.reseller_grup 
							WHERE stsapprove='0' AND rs_hrgregister > 0 AND 
							(reseller_tglupdated + INTERVAL $masa_approve DAY) < '$data'");
		foreach ($strsql->rows as $rsa) {
			$arkab[] = array(
				'reseller_id' => $rsa['reseller_id'],
				'reseller_kode' => $rsa['reseller_kode']
			);
		}
		return $arkab;
	}
	function getResellerEksekusiMasaAktif($grpResellermasaaktifreseller,$reseller_premium,$tgldata,$tenggang_day) {
	   $arkab = array();
	   $strsql=$this->db->query("SELECT reseller_id,reseller_kode,reseller_nama, reseller_grup,rs_hrgregister,
	                        reseller_tglupdated,NOW() AS tglskarang FROM _reseller INNER JOIN 
							_reseller_grup ON _reseller.reseller_grup = _reseller.reseller_grup 
							left join _reseller_batas_aktif on _reseller.reseller_id = _reseller_batas_aktif.idreseller
							WHERE stsapprove='1' AND rs_hrgregister > 0 AND reseller_grup ='$reseller_premium' and
                            (tgl_batas + INTERVAL $notif_day MONTH) < '$tgldata'");
	    
	   
	   
		foreach ($strsql->rows as $rsa) {
			$arkab[] = array(
				'reseller_id' => $rsa['reseller_id'],
				'reseller_kode' => $rsa['reseller_kode']
			);
		}
		return $arkab;
	}
	function getResellerExport($where){
	   $arkab = array();
	   $strsql=$this->db->query("SELECT reseller_id,reseller_kode,reseller_nama, rs_grupnama,rs_grupcode,reseller_tglupdated,
							reseller_email
							FROM _reseller INNER JOIN _reseller_grup 
							ON _reseller.reseller_grup = _reseller_grup.rs_grupid
							$where ORDER by reseller_kode asc");
	
		foreach ($strsql->rows as $rsa) {
			$arkab[] = array(
				'reseller_kode' => $rsa['rs_grupcode'].$rsa['reseller_kode'],
				'reseller_nama' => $rsa['reseller_nama'],
				'reseller_email' => $rsa['reseller_email'],
				'reseller_grup' => $rsa['rs_grupnama']
				//'tgl_update' => $rsa['reseller_tglupdated'],
				
			);
		}
		
		return $arkab;
	}
	function getTotalCustomer() {
	   $sql = "SELECT count(cust_kode)  as total from _customer WHERE cust_status='1'";
	   $strsql = $this->db->query($sql);
	   return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	public function prosesTransaksi($proses) {
	    $jmlproses = count($proses);
        $this->db->query("SET AUTOCOMMIT=0");
        $this->db->query("START TRANSACTION");
		
		$error = array();
		
		for($i=0; $i < $jmlproses; $i++)
        { 
		    
		  if (!$this->db->query($proses[$i])){
		    
			  $error[] = $i;
			  
		  } 
		
		}
		
		if(count($error) > 0) {
		   $this->db->query("ROLLBACK");
		   return false;
		} else {
		   $this->db->query("COMMIT");
           $this->db->query("SET AUTOCOMMIT=1");
		   return true;
		}
		
	}
	function getAlamatCustomer($id){
		$sql = "select ca_id,ca_cust_id,ca_nama_alamat,ca_nama,
		        ca_alamat,ca_propinsi,_provinsi.provinsi_nama,
				ca_kabupaten,_kabupaten.kabupaten_nama,
				ca_kecamatan,_kecamatan.kecamatan_nama,
				ca_kelurahan,ca_kodepos,ca_hp,ca_default
				from _customer_address 
				left join _provinsi on _customer_address.ca_propinsi = _provinsi.provinsi_id 
				left join _kabupaten on _customer_address.ca_kabupaten = _kabupaten.kabupaten_id
				left join _kecamatan on _customer_address.ca_kecamatan = _kecamatan.kecamatan_id 
				where ca_default='1' and ca_cust_id='".$id."'";
		
		$strsql = $this->db->query($sql);
		if($strsql){
			$data=array();
			foreach($strsql->rows as $rs) {
				$data[] = $rs;
			}
		}
		return false;
	}
}
?>