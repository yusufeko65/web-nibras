<?php
class controllerGrupUser {
   private $data = array();
   private $page;
   private $rows;
   private $offset;
   private $db;
   private $model;
   private $tabelnya;
   private $idmenu;
   private $add;
   private $edit;
   private $del;
   private $view;
   private $jml;   
   function __construct(){
		$this->model= new modelGrupUser();
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
			if($this->model->checkDataGrupUser($this->data['grup'])){
				$pesan = "GrupUser telah dipergunakan. Silahkan Masukkan GrupUser lainnya";	
				$status = 'error';
			} else {
				$this->model->simpanGrupUser($this->data);
				$status = 'success';
				$pesan = 'Berhasil menyimpan grup user';
			}
		}
		
		echo json_encode(array("status"=>$status,"result"=>$pesan));
	}
   
	function editdata(){
		$modulnya = "update";
		$pesan = '';
		
		$cek = $this->Fungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan  = " Anda tidak mempunyai Akses untuk mengubah data ";
			$status = 'error';
		} else {
			if(!$this->model->checkDataGrupUserByID($this->data['iddata'])){
				$pesan  = 'Ada kesalahan data';
				$status = 'error';
			} else {
			    if($this->data['gruplama'] != $this->data['grup']) {
					if($this->model->checkDataGrupUser($this->data['grup'])){
						$pesan  = 'Grup User telah dipergunakan. Silahkan Masukkan GrupUser lainnya';
						$status = 'error';
					} 
				}
				if($pesan == '')
				{
					$this->model->editGrupUser($this->data);
					$status = 'success';
					$pesan  = 'Berhasil menyimpan grup user';
				}
			}
		}
		
		echo json_encode(array("status"=>$status,"result"=>$pesan));
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

		$result["total"]   = $this->model->totalGrupUser($data);
		$result["rows"]    = $this->model->getGrupUserLimit($this->offset,$this->rows,$data);
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
					
					$this->model->hapusGrupUser($data);
				} else {
					$grup = $this->model->getGrupUserByID($data);
					$dataError[] = $grup['lg_nama'];
				}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
    
	function getGrupUser(){
		return $this->model->getGrupUser();
	}
  
	function dataGrupUserByID($iddata){
		return $this->model->getGrupUserByID($iddata);
	}
  
	function getMenu(){
		return $this->model->getMenu();
	}
	function dataHakAkses($iddata){
		return $this->model->getHakAkses($iddata);
	}
}
?>
