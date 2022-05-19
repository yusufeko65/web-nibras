<?php
class controllerInformasi {
	private $page;
	private $rows;
	private $offset;
	private $db;
	private $model;
	private $Fungsi;
	private $data=array();
      
	function __construct(){
		$this->model= new modelInformasi();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		
		foreach($_POST as $key => $value)
		{
			$this->data["{$key}"] = $value;
		}
		
		if($this->data['aliasurl'] == '' ) $this->data['aliasurl'] = $this->Fungsi->friendlyURL($this->data['info_judul']);
		else $this->data['aliasurl'] = $this->Fungsi->friendlyURL($this->data['aliasurl']);

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
			$status = 'error';
		} else {
			if($this->model->checkDataInformasi($this->data['info_judul'])){
				$pesan = "Judul Informasi telah dipergunakan. Silahkan Masukkan Judul Informasi lainnya";
				$status = 'error';
			} else {
				$this->model->simpanInformasi($this->data) ;
				$status = 'success';
				$pesan = 'Berhasil menyimpan informasi';
				
			}
		}
		echo json_encode(array("status"=>$status,"result"=>$pesan));
	}
   
	function editdata(){
		$modulnya = "update";
		$pesan = "";
		$cek = $this->Fungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
			$status = 'error';
		} else {
			if(!$this->model->checkDataInformasiByID($this->data['id_info'])){
				$pesan = " Ada kesalahan data ";
				$status = 'error';
			} else {
				$this->model->editInformasi($this->data);
				$status = 'success';
				$pesan = 'Berhasil menyimpan informasi';
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

		
		
		$result["total"]   = $this->model->totalInformasi($data);
		$result["rows"]    = $this->model->getInformasiLimit($this->offset,$this->rows,$data);
		$result["page"]    = $this->page; 
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
		return $result;
	}
  
	function hapusdata(){
		$id = isset($_POST['id']) ? $_POST['id']:'';
		$dataid = str_replace(":",",",$id);
		$modul = "hapus";
		$pesan = '';
		$cek = $this->Fungsi->cekHak(folder,"del",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk menghapus ";
		} else {
			$this->model->hapusInformasi($dataid);
		}
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
    
	function getInformasi(){
		$negara = $this->model->getInformasi();
		return $negara;
	}
  
	function dataInformasiByID($iddata){
		$negara = $this->model->getInformasiByID($iddata);
		return $negara;
	}
  
}
?>
