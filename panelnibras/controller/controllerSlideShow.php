<?php
class controllerSlideShow {
	private $page;
	private $rows;
	private $offset;
	private $model;
	private $Fungsi;
	private $data=array();
   
	function __construct(){
		$this->model= new modelSlideShow();
		$this->Fungsi= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		$data = array();
		foreach($_POST as $key => $value)
		{
			$this->data["{$key}"] = $value;
		}
		
		$ext = pathinfo($_FILES['gbr_slide']['name'], PATHINFO_EXTENSION);
		$this->data['slide_image'] = 'slide'.trim(strip_tags(trim(date('YmdHis')))).".".$ext;
		$this->data['urutan'] = $this->data['urutan'] != '' ? $this->data['urutan'] : 0;
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
			$pesan = " Anda tidak mempunyai Akses untuk menambah data ";
			$status = 'error';
		} else {
			if($this->model->checkDataSlideShow($this->data['nama_slide'])){
				$pesan = "Nama SlideShow telah dipergunakan.";	
				$status = 'error';
			} else {
				if(!empty($_FILES['gbr_slide']['name'])){
					if(is_uploaded_file($_FILES['gbr_slide']['tmp_name'])) {
						if($_FILES['gbr_slide']['size'] < 1000000) {
							$upload = $this->Fungsi->UploadImagebyUkuran($_FILES['gbr_slide']['tmp_name'],$_FILES['gbr_slide']['name'],$this->data['slide_image'],$this->data['panjang'],$this->data['lebar']);
						} else {
							$pesan = " Maksimal File 1 MB ";
							$status = 'error';
						}
					}
				}
				
				
			}
			if($pesan == '') {
				$simpan = $this->model->simpanSlideShow($this->data);
				
				if($simpan) {
					$status = 'success';
					$pesan = 'Berhasil Menyimpan Slideshow';
					
				} else {
					$status = 'error';
					$pesan = 'Gagal proses menyimpan Slideshow';
					$this->Fungsi->hapusfilegambar($this->data['slide_image']);
				}
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
			if(!$this->model->checkDataSlideShowByID($this->data['id_slide'])){
				$pesan = " Ada kesalahan data ";
				$status = 'error';
			} else {
				if($this->data['filelama'] != '' && $_FILES['gbr_slide']['name'] == '') $this->data['slide_image'] = $this->data['filelama'];
				
				if(!empty($_FILES['gbr_slide']['name'])){
				    if($_FILES['gbr_slide']['size'] < 1000000) {
					   
						if(is_uploaded_file($_FILES['gbr_slide']['tmp_name'])) {
							$upload = $this->Fungsi->UploadImagebyUkuran($_FILES['gbr_slide']['tmp_name'],$_FILES['gbr_slide']['name'],$this->data['slide_image'],$this->data['panjang'],$this->data['lebar']);
							if(!$upload){
								$pesan = "Upload Tidak berhasil";
								$status = 'error';
							}	
						}
					} else {
						$pesan = " Maksimal File 1 MB ";
						$status = 'error';
					}
				}
				
				if($pesan == '') {
					if ($this->data['nama_slide_lama'] != $this->data['nama_slide']) {
						if($this->model->checkDataSlideShow($this->data['nama_slide'])){
							$pesan = "Nama Slide telah dipergunakan. ";	
							$status = 'error';
						}
					}
					if($pesan == '') {
						
						$simpan = $this->model->editSlideShow($this->data);
						if($simpan) {
							$status = 'success';
							$pesan = 'Berhasil Menyimpan Slideshow';
							$this->Fungsi->hapusfilegambar($this->data['filelama']);
						} else {
							$status = 'error';
							$pesan = 'Gagal proses menyimpan Slideshow';
							
							$this->Fungsi->hapusfilegambar($this->data['slide_image']);
						}
						
					}
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

		$result["total"]   = $this->model->totalSlideShow($data);
		$result["rows"]    = $this->model->getSlideShowLimit($this->offset,$this->rows,$data);
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
			$status = 'error';
		} else {
			foreach($dataId as $data){
				$SlideShow = $this->model->getSlideShowByID($data);
				if(!$this->model->hapusSlideShow($data)) {
					$status = 'error';
				} else {
					$this->Fungsi->hapusfilegambar($SlideShow['gbr_slide'],'other');
					$status = 'success';
				}
			}	
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
    
	function getSlideShow(){
		$SlideShow = $this->model->getSlideShow();
		return $SlideShow;
	}
  
	function dataSlideShowByID($iddata){
		$SlideShow = $this->model->getSlideShowByID($iddata);
		return $SlideShow;
	}
  
  
}
?>
