<?php
class controllerKabupaten {
	private $page;
	private $rows;
	private $offset;
	private $db;
	private $model;
	private $Fungsi;
	private $data=array();
      
	function __construct(){
		$this->model= new modelKabupaten();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		$this->data['kabupaten_id'] 	= isset($_POST['iddata']) ? $_POST['iddata']:'';
		$this->data['kabupaten_nama']	= isset($_POST['kabupaten']) ? $_POST['kabupaten']:'';
		$this->data['kabupaten_propinsi']	= isset($_POST['idpropinsi']) ? $_POST['idpropinsi']:'';

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
			if($this->model->checkDataKabupaten($this->data['kabupaten_nama'])){
				$pesan = ", Kabupaten telah dipergunakan. Silahkan Masukkan Kabupaten lainnya";	
			} else {
				if(!$this->model->simpanKabupaten($this->data)) $pesan="SQL Salah";
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
			if(!$this->model->checkDataKabupatenByID($this->data['kabupaten_id'])){
				$pesan = " Ada kesalahan data ";
			} else {
				
				if(!$this->model->editKabupaten($this->data)) $pesan="SQL Salah";
			}
		}
		return $this->Fungsi->pesandata($pesan,$modulnya);
	}
  
  
	public function tampildata(){
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows		= 10;
		$result 			= array();
		$filter				= array();
		$where = '';
		$data['propinsi']	= isset($_GET['propinsi']) ? $_GET['propinsi']:'';
		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari']:'';
		
		
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalKabupaten($data);
		$result["rows"]    = $this->model->getKabupatenLimit($this->offset,$this->rows,$data);
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
					if(!$this->model->hapusKabupaten($data)) $pesan="SQL Salah";
				} else {
					$kabupaten = $this->model->getKabupatenByID($data);
					$dataError[] = $kabupaten['kabupaten_nama'];
				}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
    
	function getKabupaten(){
		$kabupaten = $this->model->getKabupaten();
		return $kabupaten;
	}
	function getKabupatenByPropinsi($propinsi){
		return $this->model->getKabupaten($propinsi);
	}
	function dataKabupatenByID($iddata){
		$kabupaten = $this->model->getKabupatenByID($iddata);
		return $kabupaten;
	}
  
}
?>
