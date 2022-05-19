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
		
		$cek = $this->Fungsi->cekHak(folder,"add",1);
		if($cek) {
			$pesan 	= " Anda tidak mempunyai Akses untuk menambah data ";
			$status = 'error';
		} else {
			if($this->validasi()) {
				if($this->data['rpass'] != '') {
					$this->data['pass'] = $this->Fungsi->fEnkrip($this->data['rpass']);
				}
				
				$this->data['aktivitas'] = 'Register '.$this->data['namagrup'];
				$this->data['keterangan'] = 'Register '.$this->data['namagrup'].' oleh Admin '.$_SESSION["namalogin"];
				
				if((int)$this->data['rdeposit'] > 0){
				    $this->data['depositohistory'] = ' Deposito saat register awal oleh Admin '.$_SESSION["namalogin"];
					
				}
				
				
				$simpan = $this->model->simpanCustomer($this->data);
				if($simpan) {
					$pesan = 'Berhasil menyimpan data';
					$status = 'success';
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
		
		echo json_encode(array("status"=>$status,"result"=>$pesan));
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
		
		
		if(trim($this->data['remail']) != '') {
	  
			if(!filter_var(trim($this->data['remail']), FILTER_VALIDATE_EMAIL)) 
	           $this->error['email'] = ' Masukkan Email dengan Benar';
	  
		}
		if(trim($this->data['remail']) == '') {
			$this->error['remail'] = ' Masukkan Email';
		}
		if($this->data['aksi'] == 'ubah') {
			if(trim($this->data['remaillama']) != trim($this->data['remail'])){
				if($this->model->checkDataReseller($this->data['remail']))	$this->error['email'] = 'Email sudah terdaftar, silahkan gunakan email lainnya ';
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
	  
		if(strlen($this->data['rnama']) < 3) 
			$this->error['rnama'] = 'Masukkan Nama Customer';
		  
		if(strlen($this->data['rtelp']) < 4)
			$this->error['rtelp'] = 'Masukkan No. Telepon Pelanggan';
	  
		if($this->data['aksi'] != 'ubah') {
			if($this->model->checkNoTelp($this->data['rtelp']))
				$this->error['rtelp'] = 'No. Telepon sudah terdaftar';
		}
	  
		if(strlen($this->data['ralamat']) < 8) 
			$this->error['ralamat'] = 'Masukkan Alamat Anda';
	  
		  
		if($this->data['rpropinsi'] == '0' || $this->data['rpropinsi'] == '')
			$this->error['rpropinsi'] = 'Harap Pilih Propinsi';
	  
		if($this->data['rkabupaten'] == '0' || $this->data['rkabupaten'] == '')
			$this->error['rkabupaten'] = 'Harap Pilih Kabupaten';
	  
		if($this->data['rkecamatan'] == '0' || $this->data['rkecamatan'] == '')
			$this->error['rkecamatan'] = 'Harap Pilih Kecamatan';
	  
		if (!$this->error) {
      		return true;
		} else {
      		return false;
		}
	}
	public function simpandatadeposito($aksi) {
		$this->data['iddata'] = isset($_POST['iddata']) ? $_POST['iddata']:'';

		$this->data['deposito'] 		= isset($_POST['indeposito']) ? $_POST['indeposito'] :'0';
		$this->data['keterangan']		= isset($_POST['keterangan']) ? $_POST['keterangan'] :'';
		$this->data['ipaddress']		= $this->Fungsi->get_client_ip();
		$this->data['tglupdate']		= date('Y-m-d H:i:s');
		if ($aksi=='simpan') {
			$hasil = $this->adddatadeposito();
		} else {
			$hasil = $this->editdatadeposito();
		}
		//return $hasil;
	 
	}
	public function adddatadeposito() {
		$ext = pathinfo($_FILES['file']['name'][0], PATHINFO_EXTENSION);
		$this->data['bukti_transfer'] = $_FILES['file']['name'][0];

		$status = '';
		if(!empty($_FILES['file']['name'][0])){
			if(is_uploaded_file($_FILES['file']['tmp_name'][0])) {
				if($_FILES['file']['size'][0] < 2000000) {
							
					$upload = $this->Fungsi->UploadImagebyUkuran($_FILES['file']['tmp_name'][0],$_FILES['file']['name'][0],$this->data['bukti_transfer'],600);
					if(!$upload){
						$pesan = "Upload Tidak berhasil";
						$status = 'error';
					}
							
				} else {
					$pesan = " Maksimal File 2 MB ";
					$status = 'error';
				}
			}
		} else {
			$pesan = "Upload Bukti Pembayaran";
			$status = 'error';
		}

		if($status!='error'){
			$pesan = '';
			$modulnya = 'adddeposito';
			$proses = array();
			
			$this->data['aktivitas'] = ' Tambah Deposito ';
			if($this->data['keterangan'] == '') {
				$this->data['keterangan'] = $this->data['aktivitas'] . ' oleh Admin '.$_SESSION["namalogin"].' sebesar '.$this->data['deposito'];
			} else {
				$this->data['keterangan'] = $this->data['keterangan'] . ' oleh Admin '.$_SESSION["namalogin"];
			}
			
			
			$save = $this->model->simpanDeposito($this->data);
			
			if($save) {
				$status = 'success';
				$pesan = 'Berhasil menambah saldo';
			} else {
				$status = 'error';
				$pesan = 'Error menambah saldo';
			}
		}

		echo json_encode(array("status"=>$status,"result"=>$pesan));
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
		$data['caridata'] 	= isset($_GET['datacari']) ? $_GET['datacari'] : '';
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
		//$filter				= array();
		//$where = '';
		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari']:'';
		$data['grup'] = $this->Fungsi->fcaridata3("_customer_grup","cg_id","cg_deposito='1'");
		
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalCustomerDeposito($data);
		$result["rows"]    = $this->model->getCustomerDepositoLimit($this->offset,$this->rows,$data);
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
	function getAlamatCustomerByID($id) {
		return $this->model->getAlamatCustomerByID($id);
	}
	public function simpanalamat($aksi){
		$hasil = '';
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			foreach($_POST as $key => $value){
				$this->data["{$key}"] = trim($value);
			}
			
			$this->data['tglupdate']		= date('Y-m-d H:i:s');
			if ($aksi=='input') {
				$this->addalamat();
			} else {
				$this->editalamat();
			}
		}

	} 
	
	public function addalamat(){
		$pesan='';
		
		$cek = $this->Fungsi->cekHak(folder,"add",1);
		if($cek) {
			$pesan 	= " Anda tidak mempunyai Akses untuk menambah data ";
			$status = 'error';
		} else {
			if($this->validasialamat()) {
				$simpan = $this->model->simpanAlamat($this->data);
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
		echo json_encode(array("status"=>$status,"result"=>$pesan,"idcust"=>$this->data['idcust']));
	}
	
	public function editalamat(){
		$pesan='';
		
		$cek = $this->Fungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan 	= " Anda tidak mempunyai Akses untuk mengubah data ";
			$status = 'error';
		} else {
			if($this->validasialamat()) {
				$simpan = $this->model->editAlamat($this->data);
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
		echo json_encode(array("status"=>$status,"result"=>$pesan,"idcust"=>$this->data['idcust']));
	}
	public function hapusalamat(){
		$cek = $this->Fungsi->cekHak(folder,"del",1);
		if($cek) {
			$pesan 	= " Anda tidak mempunyai Akses untuk mengubah data ";
			$status = 'error';
		} else {
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				foreach($_POST as $key => $value){
					$this->data["{$key}"] = trim($value);
				}
				$hapus = $this->model->hapusAlamat($this->data['id']);
				if($hapus) {
					$pesan = 'Berhasil menghapus data';
					$status = 'success';
				} else {
					$pesan = 'Gagal Menghapus data';
					$status = 'error';
				}
				
			} 
		}
		echo json_encode(array("status"=>$status,"result"=>$pesan,"idcust"=>$this->data['idcust']));
	}
	private function validasialamat(){
		
	  
		if(strlen($this->data['add_nama']) < 3) 
			$this->error['add_nama'] = 'Masukkan Nama';
		  
		if(strlen($this->data['add_telp']) < 4)
			$this->error['add_telp'] = 'Masukkan No. Telepon Pelanggan';
	  
		
		if(strlen($this->data['add_alamat']) < 8) 
			$this->error['ralamat'] = 'Masukkan Alamat Anda';
	  
		  
		if($this->data['add_propinsi'] == '0' || $this->data['add_propinsi'] == '')
			$this->error['add_propinsi'] = 'Harap Pilih Propinsi';
	  
		if($this->data['add_kabupaten'] == '0' || $this->data['add_kabupaten'] == '')
			$this->error['add_kabupaten'] = 'Harap Pilih Kabupaten';
	  
		if($this->data['add_kecamatan'] == '0' || $this->data['add_kecamatan'] == '')
			$this->error['add_kecamatan'] = 'Harap Pilih Kecamatan';
	  
		if (!$this->error) {
      		return true;
		} else {
      		return false;
		}
	}
	
	function customerAutocomplete(){
		//$json 	= array();
		$data = [];
		$data['cari']		= isset($_GET['caripelanggan']) ? $_GET['caripelanggan']:'';
		$data['stsdeposito']	= isset($_GET['stsdeposito']) ? $_GET['stsdeposito'] : '';
		$datacustomer = $this->model->getCustomersBy($data);
		echo json_encode($datacustomer);
	}
	
	function tampildatapoin(){
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows		= 10;
		
		$result 			= array();
		
		$data = [];
		
		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari']:'';
		
		
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalCustomerPoin($data);
		$result["rows"]    = $this->model->getCustomerPoinList($this->offset,$this->rows,$data);
		$result["page"]    = $this->page; 
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
		return $result;
	}
	
	function dataPoin($iddata) {
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows		= 4;
		
		$result 			= array();
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalPagePoinById($iddata);
		$result["rows"]    = $this->model->getPoinListById($this->offset,$this->rows,$iddata);
		$result["page"]    = $this->page; 
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
		return $result;
		
	}
	function totalPoinById($id){
		return $this->model->totalPoinById($id);
	}
	function totalDepositoById($id){
		return $this->model->totalDepositoById($id);
	}
	
}
?>
