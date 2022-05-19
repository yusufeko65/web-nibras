<?php
class controllerUser {
	private $page;
	private $rows;
	private $offset;
	private $Fungsi;
	private $model;
	private $data=array();
      
	function __construct(){
		$this->model= new modelUser();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		foreach($_POST as $key => $value)
		{
			$this->data["{$key}"] = $value;
		}
		$this->data['pass'] = $this->Fungsi->fEnkrip($this->data['pass']);
		
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
			if($this->model->checkDataUser($this->data['user'])){
				$pesan = " User telah dipergunakan. Silahkan Masukkan User lainnya";	
				$status = 'error';
			} else {
				$simpan = $this->model->simpanUser($this->data);
				if($simpan) {
					$status = 'success';
					$pesan = 'Berhasil menyimpan data user';
				} else {
					$status = 'error';
					$pesan = 'Gagal menyimpan data user';
				}
			}
		}
		echo json_encode(array("status"=>$status,"result"=>$pesan));
	}
   
	public function editdata(){
		$modulnya = "update";
		$pesan = "";
		$cek = $this->Fungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
			$status = 'error';
		} else {
			if(!$this->model->checkDataUserByID($this->data['iddata'])){
				$pesan = " Ada kesalahan data ";
				$status = 'error';
			} else {
				$simpan = $this->model->editUser($this->data);
				if($simpan){
					$status = 'success';
					$pesan = 'Berhasil menyimpan data user';
				} else {
					$status = 'error';
					$pesan = 'Gagal menyimpan data user';
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

		$result["total"]   = $this->model->totalUser($data);
		$result["rows"]    = $this->model->getUserLimit($this->offset,$this->rows,$data);
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
					$this->model->hapusUser($data);
				} else {
					$login = $this->model->getUserByID($data);
					$dataError[] = $login['login_nama'];
				}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
		
	}
    
	function getUser(){
		$propinsi = $this->model->getUser();
		return $propinsi;
	}

	function dataUserByID($iddata){
		$propinsi = $this->model->getUserByID($iddata);
		return $propinsi;
	}
  

}
?>
