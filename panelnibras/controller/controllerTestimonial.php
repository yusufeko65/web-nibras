<?php
class controllerTestimonial {
	private $page;
	private $rows;
	private $offset;
	private $model;
	private $Fungsi;
	private $data=array();
      
	function __construct(){
		$this->model= new modelTestimonial();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		foreach($_POST as $key => $value)
		{
			$this->data["{$key}"] = $value;
		}
		$this->data['tgl']      = date('Y-m-d H:i');
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
			$simpan = $this->model->simpanTestimonial($this->data);
			if($simpan)
			{
				$pesan = 'Berhasil menyimpan testimonial';
				$status = 'success';
				
			} else {
				
				$pesan = 'Gagal menyimpan testimonial';
				$status = 'error';
				
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
		} else {
			if(!$this->model->checkDataTestimonialByID($this->data['testimid'])){
				$pesan = " Ada kesalahan data ";
				$status = 'error';
			} else {
				$simpan = $this->model->editTestimonial($this->data);
				if($simpan)
				{
					$pesan ='Berhasil menyimpan testimonial';
					$status = 'success';
				} else {
					
					$pesan ='Gagal menyimpan testimonial';
					$status = 'error';
					
				}
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
		$data['approve'] 	= isset($_GET['approve']) ? $_GET['approve']:'all';
		
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalTestimonial($data);
		$result["rows"]    = $this->model->getTestimonialLimit($this->offset,$this->rows,$data);
		$result["page"]    = $this->page; 
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
		return $result;
	}
  
	function hapusdata(){
		$id = isset($_POST['id']) ? $_POST['id']:'';
		$dataid = str_replace(":",",",$id);
		$dataError=array();
		$modul = "hapus";
		$pesan = '';
		$cek = $this->Fungsi->cekHak(folder,"del",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk menghapus ";
			$status = 'error';
		} else {
			$this->model->hapusTestimonial($dataid);
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}

	function dataTestimonialByID($iddata){
		return $this->model->getTestimonialByID($iddata);
	}
  
}
?>
