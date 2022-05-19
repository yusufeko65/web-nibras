<?php
class controllerCustomer {
	private $data = array();
	private $page;
	private $rows;
	private $offset;
	private $model;
	private $Fungsi;
	private $error = array();
	  
	function __construct(){
		$this->model= new modelCustomer();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			foreach($_POST as $key => $value){
				$this->data["{$key}"] = trim($value);
			}
			$this->data['ipaddress']		= $this->Fungsi->get_client_ip();
			$this->data['tglupdate']		= date('Y-m-d H:i:s');
			$this->data['aksi']				= $aksi;
			$this->data['namagrup']       	= $this->Fungsi->fcaridata('_customer_grup','cg_nm','cg_id',$this->data['rtipecust']);

			if ($aksi=='simpan') {
				$this->adddata();
			} else {
				$this->editdata();
			}
		}

	} 
   
	public function adddata() {
		$pesan='';
		$modulnya = "input";
		$idcust = '';
		$cek = $this->Fungsi->cekHak(folder,"add",1);
		if($cek) {
			$pesan 	= " Anda tidak mempunyai Akses untuk menambah data ";
			$status = 'error';
		} else {
			if($this->validasi()) {
				if($this->data['rpass'] != '') {
					$this->data['pass'] = $this->Fungsi->fEnkrip($this->data['rpass']);
				}
				
				$this->data['aktivitas'] 	= 'Register '.$this->data['namagrup'];
				$this->data['keterangan'] 	= 'Register '.$this->data['namagrup'].' oleh Admin '.$_SESSION["namalogin"];
				
				$simpan = $this->model->simpanCustomer($this->data);
				
				if($simpan) {
					$pesan = 'Berhasil menyimpan data';
					$status = 'success';
					$idcust = $simpan['idcust'];
		        } else {
					$pesan = 'Gagal Menyimpan data';
					$status = 'error';
		        }
				
			} elseif($this->error) {
				if(count($this->error) == 9) {
					$pesan = 'Harap isi form pendaftaran dibawah ini';
				} else {
					$pesan = implode ("<br>",$this->error);
				}
				$status = 'error';
			}
			 
		}
		
		echo json_encode(array("status"=>$status,"result"=>$pesan,"idcust"=>$idcust));
	}
   
	function editdata(){
		$modulnya = "update";
		$pesan = "";
		$status = '';
		$cek = $this->Fungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
			$status = 'error';
		} else {
			if(!$this->model->checkDataResellerByID($this->data['iddata'])){
				$pesan = " Ada kesalahan data ";
				$status = 'error';
			} else {
			    if($this->validasi()) {
					//proses update
					
					$this->data['pass'] = $this->Fungsi->fEnkrip($this->data['rpass']);
					
					$this->data['aktivitas'] = '';
				    
					//$proses[]=$this->model->editReseller($this->data);
					if($this->data['rtipecust'] != $this->data['rtipecustlama']){
					    $this->data['aktivitas'] = ' Upgrade ke '.$this->data['namagrup'];
						$this->data['keterangan'] = 'Upgrade ke '.$this->data['namagrup'];
						//$proses[] = $this->model->updateHistory($this->data);
						//$proses[] = $this->model->simpanHistory($this->data);
					}
					
					if($this->data['rapprove'] == '1' && $this->data['rapprove'] != $this->data['rapprovelama']) {
					   $this->Data['aktivitas'] .= ' Approve '.$this->data['namagrup'];
					   $this->data['keterangan'] .= ' Approve '.$this->data['namagrup'];
					}
					
					if($this->data['aktivitas'] == '') {
					   $this->data['aktivitas'] = 'Update Data';
					   $this->data['keterangan'] = 'Update Data';
					}
					
					$this->data['keterangan'] .= ' Oleh '.$_SESSION["namalogin"];
					//$proses[]=$this->model->simpanAktivitas($this->data);
					
					$simpan = $this->model->editCustomer($this->data);
					if($simpan) {
						$pesan = 'Berhasil menyimpan data';
						$status = 'success';
					} else {
						$pesan = 'Gagal Menyimpan data';
						$status = 'error';
					}
				} elseif($this->error) {
					$pesan = implode ("<br>",$this->error);
					$pesan = '<br>'.$pesan;
					$status = 'error';
				}
				
			}
		}
		echo json_encode(array("status"=>$status,"result"=>$pesan));
	}
	
	private function validasi(){
		//print_r($this->data);
		
		if(trim($this->data['remail']) != '') {
	  
			if(!filter_var(trim($this->data['remail']), FILTER_VALIDATE_EMAIL)) 
	           $this->error['email'] = ' Masukkan Email dengan Benar';
	  
		}
		if(trim($this->data['remail']) == '') {
			$this->error['remail'] = ' Masukkan Email';
		}
		if($this->data['aksi'] == 'ubah') {
			if(trim($this->data['remaillama']) != trim($this->data['remail'])){
				if($this->model->checkDataReseller($this->data['remail']))	$this->error['email'] = 'Email sudah terdaftar, silahkan gunakan email lainnyasss '.$this->data['remaillama'].' :: '.$this->data['remail'];
			}
		} else {
			if($this->data['remail'] != ''){
				$check = $this->model->checkDataReseller($this->data['remail']);
				
				if($check) {
					$this->error['remail'] = 'Email telah terdaftar, silahkan menggunakan email lainnya';
				}
			}
		}
  
	  
		if($this->data['rpass'] != '') {
			if(strlen($this->data['rpass']) < 6 || strlen($this->data['rpass']) > 20) 
				$this->error['rpass'] = 'Masukkan Password, 5 - 20 karakter';
		  
			if($this->data['rrepass'] != $this->data['rpass']) 
				$this->error['rrepass'] = 'Ulangi Password dan Password tidak sama';
		}
   	  
		if($this->data['rpass'] == '') {  
			$this->error['rpass'] = 'Masukkan Password';
		}
	  
		if($this->data['rrepass'] == '') {  
			$this->error['rrepass'] = 'Masukkan Ulangi Password';
		}
		
		if(strlen($this->data['rnama']) < 3) $this->error['rnama'] = 'Masukkan Nama Customer';
		
		if (!$this->error) {
      		return true;
		} else {
      		return false;
		}
	}
	public function simpandatadeposito($aksi) {
		$this->data['iddata'] = isset($_POST['iddata']) ? $_POST['iddata']:'';

		$this->data['deposito'] = isset($_POST['indeposito']) ? $_POST['indeposito'] :'0';
		$this->data['ipaddress']		= $this->Fungsi->get_client_ip();
		$this->data['tglupdate']		= date('Y-m-d H:i:s');
		if ($aksi=='simpan') {
			$hasil = $this->adddatadeposito();
		} else {
			$hasil = $this->editdatadeposito();
		}
		return $hasil;
	 
	}
	public function adddatadeposito() {
		$pesan = '';
		$modulnya = 'adddeposito';
		$proses = array();
		$this->data['keterangan'] = 'Tambah Deposito oleh Admin '.$_SESSION["namalogin"].' sebesar '.$this->data['deposito'];
		$this->data['aktivitas'] = ' Tambah Deposito ';
		$cekdeposito = $this->model->cekDeposito($this->data['iddata']);
		
		$this->data['depositohistory'] = ' Tambah Deposito oleh Admin '.$_SESSION["namalogin"];
		$proses[] = $this->model->simpanAktivitas($this->data);
		
		if(!$cekdeposito ) {
			$proses[] = $this->model->simpanDeposito($this->data);
		} else {
			$proses[] = $this->model->updateDeposito($this->data);
		}
		
		$proses[] = $this->model->simpanDepositoHistory($this->data);
		if($this->model->prosesTransaksi($proses)) {
		   $pesan = '';
		} else {
		   $pesan = 'Jaringan terputus';
		}
		
		return $this->Fungsi->pesandata($pesan,$modulnya);		   
	}
 
	public function updateDeposito($data) {
		return $this->model->updateDeposito($data);
	}
	public function DeleteDepositDetail($id,$idorder,$jenis) {
		return $this->model->DeleteDepositDetail($id,$idorder,$jenis);
	}
	public function tampildata(){
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows		= 10;
		
		$result 			= array();
		$data['caridata'] 	= isset($_GET['caridata']) ? $_GET['caridata'] : '';
		$data['grup'] 		= isset($_GET['grup']) ? $_GET['grup'] : '';
		$result["total"] 	= 0;
		$result["rows"] 	= '';
		$this->offset 		= ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalReseller($data);
		$result["rows"]    = $this->model->getResellerLimit($this->offset,$this->rows,$data);
		$result["page"]    = $this->page; 
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
		return $result;
	}
	public function tampildatadeposito(){
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows		= 10;
		
		$result 			= array();
		$filter				= array();
		$where = '';
		$caridata	= isset($_GET['datacari']) ? $_GET['datacari']:'';
		$datagrup = $this->Fungsi->fcaridata3("_customer_grup","cg_id","cg_deposito='1'");
		$grup = array();
		foreach($datagrup as $dg) {
		  $grup[] = " cust_grup_id = '".$dg['cg_id']."'";
		}
		if(count($datagrup) > 0) {
		   $filter[] = "(".implode(" OR ",$grup)." )";
		}
		
		if($caridata!='') $filter[] = " ( cust_telp like '%".trim(strip_tags($caridata))."%' OR cust_nama like '%".trim(strip_tags($caridata))."%' )";
		//if($grup!='' && $grup!='0') $filter[] = " cust_grup_id = '".trim(strip_tags(urlencode($grup)))."'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalResellerDeposito($where);
		$result["rows"]    = $this->model->getResellerDepositoLimit($this->offset,$this->rows,$where);
		$result["page"]    = $this->page; 
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
		return $result;
	}
	function dataDeposito($iddata) {
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows		= 4;
		
		$result 			= array();
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalDataDeposito($iddata);
		$result["rows"]    = $this->model->getDataDepositoLimit($this->offset,$this->rows,$iddata);
		$result["page"]    = $this->page; 
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
		return $result;
	
	}
	function hapusdata(){
		$id = isset($_POST['id']) ? $_POST['id']:'';
		$dataId = explode(":",$id);
		$dataError=array();
		$modul = "hapus";
		$pesan = '';
		$cek = $this->Fungsi->cekHak(folder,"del",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk menghapus ";
		} else {
			$i = 0;
			foreach($dataId as $data){
				if(!$this->model->checkRelasi($data)){
					if(!$this->model->hapusReseller($data)) $pesan="SQL Salah";
				} else {
					$i++;
					$res = $this->model->getResellerByID($data);
					$dataError[] = $i.'. '.$res['cust_nama'];
				}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan."\n";
		return $pesan;
	
	}
	
	function getReseller(){
		return $this->model->getReseller();
	}
  
	function getResellerExport(){
		$approve = isset($_GET['rapprove']) ? $_GET['rapprove']:'1';
		$grup	= isset($_GET['grup']) ? $_GET['grup']:'';
		if($approve == 'all') $approve = 1;
		$where	= '';
		$filter = array();
		if($grup!='' && $grup!='0') $filter[] = " reseller_grup = '".trim(strip_tags(urlencode($grup)))."'";
		if($approve!='') $filter[] = " stsapprove = '".trim(strip_tags(urlencode($approve)))."'";
		
		if(!empty($filter))	$where = " WHERE ".implode(" AND ",$filter);
		
		
		$reseller = $this->model->getResellerExport($where);
		return $reseller;
	}
  
	function ResellerEksekusi($masa_approve){
		$data = array();
		$data['tglupdate']		= date('Y-m-d H:i:s');
		$reseller = $this->model->getResellerEksekusi($masa_approve,$data['tglupdate']);
		
		//proses update
		$kodeakhir = $this->Fungsi->fIdAkhir('_reseller','CONVERT(reseller_kode,SIGNED)');
		//$kodenext = sprintf('%04s', $kodeakhir+1);
		$data['rtipecust'] = $this->Fungsi->fcaridata('_reseller_grup','rs_grupid','rs_hrgregister','0');
		$pesan = array();
		$pesans = 'tidak ada error';
		$kodeakhir = $kodeakhir+1;
		foreach($reseller as $r){
			
			$kodenext = sprintf('%04s', $kodeakhir);
			if($r['reseller_kode'] == '' || $r['reseller_kode'] == '-') {
				$data['kode'] = $kodenext;
				$kodeakhir++;
			} else {
				$data['kode'] = $r['reseller_kode'];
			}
			$data['id'] = $r['reseller_id'];
			$data['stsbayar']='1';
			
			$data['biaya_register'] = '0';
			$data['keterangan'] = 'Register Free, karena melebihi batas pembayaran saat register premium ';
			if(!$this->model->approveReseller($data)) $pesan[] = 'Approve Reseller Error';
			if(!$this->model->simpanHistory($data)) $pesan[] = 'Simpan History Error';
			if(!$this->model->simpanInvoice($data)) $pesan[] = 'Simpan Invoice Error';
			if(!$this->model->editResellerGrup($data)) $pesan[] = 'Edit Reseller Grup Error';
			
			$pesan [] = $r['reseller_id'];
		}
		if(count($pesan) > 0){
		   $pesans = implode("<br>",$pesan);
		}
		
		return $pesans;
	}
  
	function ResellerEksekusiMasaAktif($grpResellermasaaktifreseller,$reseller_premium){
		$data = array();
		$pesan = array();
		$pesans = 'tidak ada error';
		$data['tglupdate']		= date('Y-m-d H:i:s');
		$datagrup = $this->Fungsi->fcaridata('_setting_toko','reseller_bayar','1','1');
		$tenggang_day = $this->Fungsi->fcaridata('_reseller_grup','rs_masatengganganggota','rs_grupid',$datagrup);
		$reseller = $this->model->getResellerEksekusiMasaAktif($grpResellermasaaktifreseller,$reseller_premium,$data['tglupdate'],$tenggang_day);
		$data['rtipecust'] = $this->Fungsi->fcaridata('_reseller_grup','rs_grupid','rs_hrgregister','0');
		foreach($reseller as $r){
			$data['kode'] = $r['reseller_kode'];
			$data['id'] = $r['reseller_id'];
			$data['stsbayar']='1';
			$data['keterangan'] = 'Register Free, karena melebihi batas masa aktif reseller premium ';
			if(!$this->model->simpanHistory($data)) $pesan[] = 'Simpan History Error';
			if(!$this->model->simpanInvoice($data)) $pesan[] = 'Simpan Invoice Error';
			if(!$this->model->editResellerGrup($data)) $pesan[] = 'Edit Reseller Grup Error';
			$pesan [] = $r['reseller_id'];
		}
		if(count($pesan) > 0){
			$pesans = implode("<br>",$pesan);
		}

		return $pesans;
	
	}
	function dataResellerByID($iddata){
		return $this->model->getResellerByID($iddata);
	}
	function dataResellerByKode($iddata){
		return $this->model->getResellerByKode($iddata);
	}
	function getTotalCustomer(){
		return $this->model->getTotalCustomer();
	}
	function getAlamatCustomer($id){
		return $this->model->getAlamatCustomer($id);
	}
}
?>
