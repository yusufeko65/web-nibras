<?php
class controllerNegara {
	private $page;
	private $rows;
	private $offset;
	private $db;
	private $model;
	private $Fungsi;
	private $data=array();
      
	function __construct(){
		$this->model= new modelNegara();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		$data = array();
		$this->data['negara_id'] 	= isset($_POST['iddata']) ? $_POST['iddata']:'';
		$this->data['negara_nama']	= isset($_POST['negara']) ? $_POST['negara']:'';

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
			if($this->model->checkDataNegara($this->data['negara_nama'])){
				$pesan = "Negara telah dipergunakan. Silahkan Masukkan Negara lainnya";	
			} else {
				if(!$this->model->simpanNegara($this->data)) $pesan="SQL Salah";
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
			if(!$this->model->checkDataNegaraByID($this->data['negara_id'])){
				$pesan = " Ada kesalahan data ";
			} else {
				if(!$this->model->editNegara($this->data)) $pesan="SQL Salah";
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

		$result["total"]   = $this->model->totalNegara($data);
		$result["rows"]    = $this->model->getNegaraLimit($this->offset,$this->rows,$data);
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
				if(!$this->model->hapusNegara($data)) $pesan="SQL Salah";
			} else {
				$negara = $this->model->getNegaraByID($data);
				$dataError[] = $negara['negara_nama'];
			}
		}
	}
	if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
	if($pesan!='') $pesan="gagal|".$pesan;
	return $pesan;
	
  }
    
  function getNegara(){
	$negara = $this->model->getNegara();
	return $negara;
  }
  
  function dataNegaraByID($iddata){
	$negara = $this->model->getNegaraByID($iddata);
	return $negara;
  }
  
  function cetakcomboboxnegara($idnegara=0,$idobject){
	$anegara = $this->model->getNegara();
	$createcombonegara= "<select id=\"$idobject\" class=\"selectbox\" style=\"width:200px\">";
	$createcombonegara.= "<option value=\"0\">- Pilih Negara -</option>";
	foreach ($anegara as $key => $dneg) {
		if($idnegara==$dneg['idn']) $selected=" selected ";
		else $selected=" ";
		$createcombonegara.="<option value=\"".$dneg['idn']."\" ".$selected.">".$dneg['nmn']."</option>";
	}
	$createcombonegara.="</select>";
	return $createcombonegara;
  }
 
}
?>
