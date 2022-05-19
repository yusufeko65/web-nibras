<?php
class controllerKecamatan {
	private $page;
	private $rows;
	private $offset;
	private $db;
	private $model;
	private $Fungsi;
	private $data=array();
      
	function __construct(){
		$this->model = new modelKecamatan();
		$this->Fungsi = new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		$data = array();
		$this->data['kecamatan_id'] 	= isset($_POST['iddata']) ? $_POST['iddata']:'';
		$this->data['kecamatan_nama']	= isset($_POST['kecamatan']) ? $_POST['kecamatan']:'';
		$this->data['kecamatan_kabupaten'] = isset($_POST['idkabupaten']) ? $_POST['idkabupaten']:'';

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
			if($this->model->checkDataKecamatan($this->data['kecamatan_nama'],$this->data['kecamatan_kabupaten'])){
				$pesan = ", Kecamatan telah dipergunakan. Silahkan Masukkan Kecamatan lainnya";	
			} else {
				if(!$this->model->simpanKecamatan($this->data)) $pesan="SQL Salah";
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
			if(!$this->model->checkDataKecamatanByID($this->data['kecamatan_id'])){
				$pesan = " Ada kesalahan data ";
			} else {
				if(!$this->model->editKecamatan($this->data)) $pesan="SQL Salah";
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
		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari']:'';
		$data['propinsi']	= isset($_GET['propinsi']) ? $_GET['propinsi']:'';
		$data['kabupaten']	= isset($_GET['kabupaten']) ? $_GET['kabupaten']:'';
		
		
		
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalKecamatan($data);
		$result["rows"]    = $this->model->getKecamatanLimit($this->offset,$this->rows,$data);
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
		//$pesan = count($dataId);
		$cek = $this->Fungsi->cekHak(folder,"del",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk menghapus ";
		} else {
			foreach($dataId as $data){
				if(!$this->model->checkRelasi($data)){
					if(!$this->model->hapusKecamatan($data)) $pesan="SQL Salah";
				} else {
					$kecamatan = $this->model->getKecamatanByID($data);
					$dataError[] = $kecamatan['kecamatan_nama'];
				}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan != '') $pesan='gagal|'.$pesan;
		else $pesan = 'sukses';
		return $pesan;
	
	}
    
	function getKecamatan(){
		$kecamatan = $this->model->getKecamatan();
		return $kecamatan;
	}
  
	function dataKecamatanByID($iddata){
		$kecamatan = $this->model->getKecamatanByID($iddata);
		return $kecamatan;
	}
	function dataKecamatanByKabupaten($iddata){
		return $this->model->getKecamatanByKabupaten($iddata);
	}
 
}
?>
