<?php
class controllerOrderStatus {
	private $page;
	private $rows;
	private $offset;
	private $db;
	private $model;
	private $Fungsi;
	private $data=array();
      
	function __construct(){
		$this->model= new modelOrderStatus();
		$this->Fungsi= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		$this->data['status_id'] 	= isset($_POST['iddata']) ? $_POST['iddata']:'';
		$this->data['status_nama']	= isset($_POST['status']) ? $_POST['status']:'';
		$this->data['status_keterangan']	= isset($_POST['keterangan']) ? $_POST['keterangan']:'';
		if ($aksi=='simpan') $hasil = $this->adddata();
		else $hasil = $this->editdata();

		return $hasil;
	} 
   
	public function adddata() {
		$pesan="";
		$modulnya = "input";
		$cek = $this->Fungsi->cekHak(folder,"add",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk menambah data ";
		} else {
			if($this->model->checkDataOrderStatus($this->data['status_nama'])){
				$pesan = "Status Order telah dipergunakan. Silahkan Masukkan Status Order lainnya";	
			} else {
				if(!$this->model->simpanOrderStatus($this->data)) $pesan="SQL Salah";
			}
		}
		return $this->Fungsi->pesandata($pesan,$modulnya);
	}
   
	function editdata(){
		$modulnya = "update";
		$pesan = "";
		
		$cek = $this->Fungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		} else {
			if(!$this->model->checkDataOrderStatusByID($this->data['status_id'])){
				$pesan = " Ada kesalahan data ";
			} else {

				if(!$this->model->editOrderStatus($this->data)) $pesan="SQL Salah";
			}
		}
		return $this->Fungsi->pesandata($pesan,$modulnya);
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

		$result["total"]   = $this->model->totalOrderStatus($data);
		$result["rows"]    = $this->model->getOrderStatusLimit($this->offset,$this->rows,$data);
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
			foreach($dataId as $data){
				if(!$this->model->checkRelasi($data)){
					if(!$this->model->hapusOrderStatus($data)) $pesan="SQL Salah";
				} else {
					$status = $this->model->getOrderStatusByID($data);
					$dataError[] = $status['status_nama'];
				}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
    
	function getOrderStatus(){
		return $this->model->getOrderStatus();
	}

	function dataOrderStatusByID($iddata){
		return $this->model->getOrderStatusByID($iddata);
	}

  
}
?>
