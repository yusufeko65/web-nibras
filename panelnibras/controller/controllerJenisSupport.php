<?php
class controllerJenisSupport {
	private $page;
	private $rows;
	private $offset;
	private $db;
	private $model;
	private $Fungsi;
	private $data=array();
      
	function __construct(){
		$this->model= new modelJenisSupport();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		$this->data['iddata'] 	= isset($_POST['iddata']) ? $_POST['iddata']:'';
		$this->data['jenis_nama']	= isset($_POST['jenis']) ? $_POST['jenis']:'';
		$this->data['link_sumber']= isset($_POST['linksumber']) ? $_POST['linksumber']:'';
		$this->data['tampil']   	= isset($_POST['tampil']) ? $_POST['tampil']:'';

		if ($aksi=='simpan') $hasil = $this->adddata();
		else$hasil = $this->editdata();

		return $hasil;
	} 
   
	public function adddata() {
		$pesan="";
		$modulnya = "input";
		$cek = $this->Fungsi->cekHak(folder,"add",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk menambah data ";
		} else {
			if($this->model->checkDataJenisSupport($this->data['jenis_nama'])){
				$pesan = ", Jenis Support telah dipergunakan. Silahkan Masukkan Jenis Support lainnya";	
			} else {
				if(!$this->model->simpanJenisSupport($this->data)) $pesan="SQL Salah";
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
			if(!$this->model->checkDataJenisSupportByID($this->data['iddata'])){
				$pesan = " Ada kesalahan data ";
			} else {
				if(!$this->model->editJenisSupport($this->data)) $pesan="SQL Salah";
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
		$caridata	= isset($_GET['datacari']) ? $_GET['datacari']:'';
		if($caridata!='') $filter[] = " jenis_support like '%".trim(strip_tags($caridata))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		
		
		$result["total"]   = $this->model->totalJenisSupport($where);
		$result["rows"]    = $this->model->getJenisSupportLimit($this->offset,$this->rows,$where);
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
					if(!$this->model->hapusJenisSupport($data)) $pesan="SQL Salah";
				} else {
					$propinsi = $this->model->getJenisSupportByID($data);
					$dataError[] = $propinsi['provinsi_nama'];
				}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
    
	function getJenisSupport(){
		$propinsi = $this->model->getJenisSupport();
		return $propinsi;
	}
  
	function dataJenisSupportByID($iddata){
		$propinsi = $this->model->getJenisSupportByID($iddata);
		return $propinsi;
	}
  
}
?>
