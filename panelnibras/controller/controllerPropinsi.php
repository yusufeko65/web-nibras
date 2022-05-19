<?php
class controllerPropinsi {
	private $page;
	private $rows;
	private $offset;
	private $Fungsi;
	private $model;
	private $data=array();
      
	function __construct(){
		$this->model= new modelPropinsi();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		$this->data['propinsi_id'] 	 = isset($_POST['iddata']) ? $_POST['iddata']:'';
		$this->data['propinsi_nama']	 = isset($_POST['propinsi']) ? $_POST['propinsi']:'';
		$this->data['propinsi_negara'] = isset($_POST['idnegara']) ? $_POST['idnegara']:'';

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
			if($this->model->checkDataPropinsi($this->data['propinsi_nama'])){
				$pesan = ", Propinsi telah dipergunakan. Silahkan Masukkan Propinsi lainnya";	
			} else {
				if(!$this->model->simpanPropinsi($this->data)) $pesan="SQL Salah";
			}
		}
		return $this->Fungsi->pesandata($pesan,$modulnya);
	}
   
	public function editdata(){
		$modulnya = "update";
		$pesan = "";
		$cek = $this->Fungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		} else {
			if(!$this->model->checkDataPropinsiByID($this->data['propinsi_id'])){
				$pesan = " Ada kesalahan data ";
			} else {
				if(!$this->model->editPropinsi($this->data)) $pesan="SQL Salah";
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
		

		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;



		$result["total"]   = $this->model->totalPropinsi($data);
		$result["rows"]    = $this->model->getPropinsiLimit($this->offset,$this->rows,$data);
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
					if(!$this->model->hapusPropinsi($data)) $pesan="SQL Salah";
				} else {
					$propinsi = $this->model->getPropinsiByID($data);
					$dataError[] = $propinsi['provinsi_nama'];
				}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;

	}
    
	function getPropinsi(){
		return $this->model->getPropinsi();
	}

	function dataPropinsiByID($iddata){
		$propinsi = $this->model->getPropinsiByID($iddata);
		return $propinsi;
	}

  
}
?>
