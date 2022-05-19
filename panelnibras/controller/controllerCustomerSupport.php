<?php
class controllerCustomerSupport {
	private $page;
	private $rows;
	private $offset;
	private $model;
	private $Fungsi;
	private $data=array();
      
	function __construct(){
		$this->model= new modelCustomerSupport();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		foreach($_POST as $key => $value)
		{
			$this->data["{$key}"] = $value;
		}
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
			$status = 'error';
		} else {
			if($this->model->checkDataCustomerSupport($this->data['cs_akun'],$this->data['cs_jsupport'])){
				$pesan = ' Customer Support telah dipergunakan. Silahkan Masukkan Customer Support lainnya ';	
				$status = 'error';
			} else {
				$simpan = $this->model->simpanCustomerSupport($this->data);
				if($simpan){
					$pesan = 'Berhasil menyimpan data';
					$status = 'success';
				} else {
					$pesan = 'Gagal menyimpan data';
					$status = 'error';
				}
			}
		}
		echo json_encode(array("status"=>$status,"result"=>$pesan));
	}
   
	function editdata(){
		$modulnya = "update";
		$pesan = '';
		$cek = $this->Fungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		} else {
			if(!$this->model->checkDataCustomerSupportByID($this->data['idsupport'])){
				$pesan = " Ada kesalahan data ";
			} else {
				
				if($this->data['cs_akun_lama'] != $this->data['cs_akun'] && $this->data['cs_jsupport_lama'] != $this->data['cs_jsupport']) {
				   if($this->model->checkDataCustomerSupport($this->data['cs_akun'], $this->data['cs_jsupport'])){
				       $pesan = 'Customer Support telah dipergunakan. Silahkan Masukkan Customer Support lainnya ';	
					   $status = 'error';
			       } 
				}
			}
		}
		if($pesan == ''){
			$simpan = $this->model->editCustomerSupport($this->data);
     		if($simpan){
				$pesan = 'Berhasil menyimpan data';
				$status = 'success';
			} else {
				$pesan = 'Gagal menyimpan data';
				$status = 'error';
			}
		}
	
		echo json_encode(array("status"=>$status,"result"=>$pesan));
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

		$result["total"]   = $this->model->totalCustomerSupport($data);
		$result["rows"]    = $this->model->getCustomerSupportLimit($this->offset,$this->rows,$data);
		$result["page"]    = $this->page; 
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
		return $result;
	}
  
	function hapusdata(){
		$id = isset($_POST['id']) ? $_POST['id']:'';
		//$dataId = explode(":",$id);
		$dataid = str_replace(":",",",$id);
		$dataError = array();
		$modul = "hapus";
		$pesan = '';
		$cek = $this->Fungsi->cekHak(folder,"del",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk menghapus ";
			$status = 'error';
		} else {
			
			if(!$this->model->hapusCustomerSupport($dataid)) $pesan="SQL Salah";
			
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
    
	function getCustomerSupport(){
		$support = $this->model->getCustomerSupport();
		return $support;
	}
  
	function dataCustomerSupportByID($iddata){
		$support = $this->model->getCustomerSupportByID($iddata);
		return $support;
	}

}
?>
