<?php
class controllerRekening {
	private $page;
	private $rows;
	private $offset;
	private $model;
	private $Fungsi;
	private $data=array();
      
	function __construct(){
		$this->model= new modelRekening();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		$this->data['id']	  = isset($_POST['iddata']) ? $_POST['iddata']:'';
		$this->data['norek']	  = isset($_POST['norek']) ? $_POST['norek']:'';
		$this->data['bank']	  = isset($_POST['bank']) ? $_POST['bank']:'';
		$this->data['atasnama'] = isset($_POST['atasnama']) ? $_POST['atasnama']:'';
		$this->data['cabang']	  = isset($_POST['cabang']) ? $_POST['cabang']:'';
		$this->data['status']	  = isset($_POST['status']) ? $_POST['status']:'';

		if ($aksi=='simpan') $hasil = $this->adddata($this->data);
		else $hasil = $this->editdata($this->data);

		return $hasil;
	} 
   
	public function adddata($data) {
		$pesan="";
		$modulnya = "input";
		$cek = $this->Fungsi->cekHak(folder,"add",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk menambah data ";
		} else {
			if($this->model->checkDataRekening($this->data['norek'])){
				$pesan = "Rekening telah dipergunakan. Silahkan Masukkan Rekening lainnya";	
			} else {
				if(!$this->model->simpanRekening($this->data)) $pesan="SQL Salah";
			}
		}
		return $this->Fungsi->pesandata($pesan,$modulnya);
	}
	
	function editdata($data){
		$modulnya = "update";
		$pesan = "";
		$cek = $this->Fungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		} else {
			if(!$this->model->checkDataRekeningByID($this->data['id'])){
				$pesan = " Ada kesalahan data ";
			} else {
				if(!$this->model->editRekening($this->data)) $pesan="SQL Salah";
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

		$result["total"]   = $this->model->totalRekening($data);
		$result["rows"]    = $this->model->getRekeningLimit($this->offset,$this->rows,$data);
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
					if(!$this->model->hapusRekening($data)) $pesan="SQL Salah";
				} else {
					$rekening = $this->model->getRekeningByID($data);
					$dataError[] = $rekening['rekening_nama'];
				}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
    
	function getRekening(){
		$rekening = $this->model->getRekening();
		return $rekening;
	}
  
	function dataRekeningByID($iddata){
		$rekening = $this->model->getRekeningByID($iddata);
		return $rekening;
	}
  
}
?>
