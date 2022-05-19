<?php
class controller_Account {
	private $page;
	private $rows;
	private $offset;
	private $dataModel;
	private $Fungsi;
	private $data=array();
	private $error = array();
   
	function __construct(){
		$this->dataModel= new model_Account();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata(){
		$pesan = '';
	  
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			foreach($_POST as $key => $value){
				$this->data["{$key}"] = trim($value);
			}
			$this->data['tglupdate']		= date('Y-m-d H:i:s');
			$this->data['ipaddress']		= $this->Fungsi->get_client_ip();
			
			if($this->validasi()) {
				//proses simpan
				$this->data['id'] = $_SESSION['idmember'];
				
				$_SESSION['usermember']  = $this->data['remail'];
				$_SESSION['namamember']  = $this->data['rnama'];
				
				$simpan = $this->dataModel->Simpan($this->data);
				if($simpan) {
					$pesan = 'Data berhasil diubah';
					$status = 'success';
				} else {
					$pesan = 'Gagal Menyimpan';
					$status = 'error';
				}
				  
			} elseif($this->error) {
				if(count($this->error) == 11) {
					$pesan = 'Harap isi form data akun dibawah ini';
					
				} else {
					$pesan = implode ("<br>",$this->error);
					
				}
				$status = 'error';
			}
		} else {
			$status = 'error';
			$pesan = 'Data tidak valid';
		}
	 
		echo json_encode(array("status"=>$status,"result"=>$pesan));
		  
	} 
   
	private function validasi(){
		
	  
		
		if(strlen($this->data['rnama']) < 4) 
			$this->error['rnama'] = 'Masukkan Nama Anda';
		  
		if(strlen($this->data['rtelp']) < 4)
			$this->error['rtelp'] = 'Masukkan No. Telepon Anda';
	
		if(!filter_var($this->data['remail'], FILTER_VALIDATE_EMAIL)) 
			$this->error['remail'] = 'Masukkan Email dengan Benar';
		   
	  
		if($_SESSION['usermember'] != $this->data['remail']){
			if($this->dataModel->checkDataAccount($this->data['remail']))	$this->error['remail'] = 'Email sudah terdaftar, silahkan gunakan email lainnya';
		}
		
		  
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function simpanalamat($tipe) {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			foreach($_POST as $key => $value){
				$this->data["{$key}"] = trim($value);
			}
			$this->data['tglupdate']= date('Y-m-d H:i:s');
			$this->data['idmember'] = $_SESSION['idmember'];
			if($this->validasialamat()) {
				if($tipe == 'input') {
					$simpan = $this->dataModel->simpanAlamat($this->data);
				} else {
					$simpan = $this->dataModel->updateAlamat($this->data);
				}
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
		} else {
			$status = 'error';
			$pesan = 'Invalid data';
		}
		echo json_encode(array("status"=>$status,"result"=>$pesan));
		
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
	
	function hapusalamat(){
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			foreach($_POST as $key => $value){
				$this->data["{$key}"] = trim($value);
			}
			$hapus = $this->dataModel->hapusAlamat($this->data['id']);
			if($hapus) {
				$pesan = 'Berhasil menghapus data';
				$status = 'success';
			} else {
				$pesan = 'Gagal Menghapus data';
				$status = 'error';
			}
			
		} else {
			$pesan = 'Invalid Data';
			$status = 'error';
		}			
		echo json_encode(array("status"=>$status,"result"=>$pesan));
	}
	
	public function ubahpassword() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			foreach($_POST as $key => $value){
				$this->data["{$key}"] = trim($value);
			}
			$this->data['idmember'] = $_SESSION['idmember'];
			$this->data['newpassword'] = $this->Fungsi->fEnkrip($this->data['newpassword']);
			$hapus = $this->dataModel->ubahpassword($this->data);
			if($hapus) {
				$pesan = 'Berhasil ubah password';
				$status = 'success';
			} else {
				$pesan = 'Gagal ubah password';
				$status = 'error';
			}
			
		} else {
			$pesan = 'Invalid Data';
			$status = 'error';
		}			
		echo json_encode(array("status"=>$status,"result"=>$pesan));
	}
	function listAlamat(){
		$idmember = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : 0;
		return $this->dataModel->listAlamat($idmember);
	}
}
