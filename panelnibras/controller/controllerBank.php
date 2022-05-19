<?php
class controllerBank {
	private $page;
	private $rows;
	private $offset;
	private $model;
	private $Fungsi;
	private $data=array();
   
	function __construct(){
		$this->model= new modelBank();
		$this->Fungsi= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$modelsetting = new modelSetting();
		$setting = $modelsetting->getSettingByKeys(array('config_logobank_p','config_logobank_l'));
		foreach($setting as $st){
			$key 	= $st['setting_key'];
			$value 	= $st['setting_value'];
			$this->data["{$key}"] = $value;
		}
		$hasil = '';
		$data = array();
		$this->data['bank_id']     = isset($_POST['iddata']) ? $_POST['iddata']:'';
		$this->data['bank_nama']	    = isset($_POST['bank']) ? $_POST['bank']:'';
		$this->data['logo_tmp']   = $_FILES['filelogo']['tmp_name'];
		$this->data['logo_name']  = isset($_FILES['filelogo']['name']) ? $_FILES['filelogo']['name']:'';
		$this->data['logo_size']  = isset($_FILES['filelogo']['size']) ? $_FILES['filelogo']['size']:0;
		$this->data['logo_lama']  = isset($_POST['filelama']) ? $_POST['filelama']:'';
		$this->data['bank_status'] = isset($_POST['status']) ? $_POST['status']:'';
		
		if ($aksi=='simpan') {
			$hasil = $this->adddata();
		} else {
			$hasil = $this->editdata();
		}
		return $hasil;
	} 
   
	public function adddata() {
		$pesan="";
		$modulnya = "input";
		$cek = $this->Fungsi->cekHak(folder,"add",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk menambah data ";
		} else {
			if($this->model->checkDataBank($this->data['bank_nama'])){
				$pesan = "Bank telah dipergunakan. Silahkan Masukkan Bank lainnya";	
			} else {
				if(is_uploaded_file($this->data['logo_tmp'])) {
				   if($this->data['logo_size'] < 1000000) {
					   $upload = $this->Fungsi->UploadImagebyUkuran($this->data['logo_tmp'],$this->data['logo_name'],'bank'.$this->data['logo_name'],$this->data['config_logobank_p'],$this->data['config_logobank_l']);
					   if(!$upload){
						  $pesan = " Upload Tidak berhasil";
					   } else {
						  $this->data['logo_name'] = 'bank'.$this->data['logo_name'];
					   }
					 
				   } else {
					  $pesan = " Maksimal File 1MB ";
				   }
				}
				if($pesan == ''){
				  if(!$this->model->simpanBank($this->data)) $pesan="SQL Salah";
				}
				
			}
		}
		$pesan = $this->Fungsi->pesandata($pesan,$modulnya);
		$pesan = "<script>parent.suksesdata('$pesan')</script>";
		return $pesan;
	}
   
	function editdata(){
		$modulnya = "update";
		$pesan = "";
		
		$cek = $this->Fungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		} else {
			if(!$this->model->checkDataBankByID($this->data['bank_id'])){
				$pesan = " Ada kesalahan data ";
			} else {
				$this->data['bank_logo'] = 'bank'.$this->data['logo_name'];
				
				if(is_uploaded_file($this->data['logo_tmp'])) {
				    if($this->data['logo_size'] < 1000000) {
					   if(is_uploaded_file($this->data['logo_tmp'])) {
						   if(file_exists(DIR_IMAGE.'_other/other_'.$this->data['logo_lama'])) unlink(DIR_IMAGE.'_other/other_'.$this->data['logo_lama']);
						   
						   $upload = $this->Fungsi->UploadImagebyUkuran($this->data['logo_tmp'],$this->data['logo_name'],$this->data['bank_logo'],$this->data['config_logobank_p'],$this->data['config_logobank_l']);
					       if(!$upload) $pesan = ' Upload Tidak berhasil';
					   }
					}
				}
				
				if($this->data['logo_lama'] != '' && $this->data['logo_name'] == '') $this->data['bank_logo'] = $this->data['logo_lama'];
				if($pesan=='')
				   if(!$this->model->editBank($this->data)) $pesan="SQL Salah";
				
			}
		}
		$pesan = $this->Fungsi->pesandata($pesan,$modulnya);
		$pesan = "<script>parent.suksesdata('$pesan')</script>";
		return $pesan;
	}
    
	public function tampildata(){
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		//$this->rows		= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$this->rows		= 10;
		$result 			= array();
		$filter				= array();
		$where = '';
		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari']:'';
		

		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalBank($data);
		$result["rows"]    = $this->model->getBankLimit($this->offset,$this->rows,$data);
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
		$cek = $this->Fungsi->cekHak("bank","del",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk menghapus ";
		} else {
			foreach($dataId as $data){
			   $bank = $this->model->getBankByID($data);
				if(!$this->model->checkRelasi($data)){
					if(!$this->model->hapusBank($data)) {
						 $pesan="SQL Salah";
					} else {
					   if(file_exists(DIR_IMAGE.'_other/other_'.$bank['bank_logo'])) unlink(DIR_IMAGE.'_other/other_'.$bank['bank_logo']);
					   $pesan = '';
					}
					
				} else {
					$dataError[] = $bank['bank_nama'];
				}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
    
	function getBank(){
		return $this->model->getBank();
	}
  
	function dataBankByID($iddata){
		return $this->model->getBankByID($iddata);
	}
	
}
?>
