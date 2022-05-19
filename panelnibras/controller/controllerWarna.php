<?php
class controllerWarna {
	private $iddata;
	private $nmwarna;
	private $warnalama;
	private $alias;
	private $page;
	private $rows;
	private $offset;
	private $db;
	private $model;
	private $tabelnya;
	private $Fungsi;
      
	function __construct(){
		$this->model= new modelWarna();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		$data = array();
		$this->iddata 	= isset($_POST['iddata']) ? $_POST['iddata']:'';
		$this->nmwarna	= isset($_POST['warna']) ? trim($_POST['warna']):'';
		$this->warnalama	= isset($_POST['warnalama']) ? $_POST['warnalama']:'';
		$this->alias   	= isset($_POST['alias']) ? $_POST['alias']:'';

		if($this->alias == '' ) $this->alias = $this->Fungsi->friendlyURL($this->nmwarna);
		else $this->alias = $this->Fungsi->friendlyURL($this->alias);
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
		} else {
			if($this->model->checkDataWarna($this->nmwarna)){
				$pesan = "Warna telah dipergunakan. Silahkan Masukkan Warna lainnya";	
			} else {
				$data['warna_id'] = $this->iddata;
				$data['warna_nama'] = $this->nmwarna;
				$data['warna_alias'] = $this->alias;
				if(!$this->model->simpanWarna($data)) $pesan="SQL Salah";
			}
		}
		return $this->pesandata($pesan,$modulnya);
	}
   
	function editdata(){
		$modulnya = "update";
		$pesan = '';
		
		$cek = $this->Fungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		} else {
			if(!$this->model->checkDataWarnaByID($this->iddata)){
				$pesan = ' <br>Ada kesalahan data ';
			} else {
				if($this->warnalama != $this->nmwarna) {
					if($this->model->checkDataWarna($this->nmwarna)){
					   $pesan = '<br>Warna telah dipergunakan. Silahkan Masukkan Warna lainnya';	
					}
				}
			}
		}
		if($pesan=='') {
		   $data['warna_id'] = $this->iddata;
		   $data['warna_nama'] = $this->nmwarna;
		   $data['warna_alias'] = $this->alias;
		   if(!$this->model->editWarna($data)) $pesan="SQL Salah";
		}
		return $this->pesandata($pesan,$modulnya);
	}
  
	private function pesandata($pesan,$modulnya){
		if($pesan!=""){
			$pesans="gagal|".$modulnya."|".DATA_SIMPAN_GAGAL.$pesan."</div>";
		} else {
			$pesans="sukses|".$modulnya."|".DATA_SIMPAN_SUKSES."</div>";
		}
		return $pesans;
	}
  
	public function tampildata(){
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		//$this->rows		= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$this->rows		= 10;
		$result 			= array();
		$filter				= array();
		$where = '';
		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari']:'';
		
		
		if($data['caridata'] != '') $filter[] = " warna like '%".trim(strip_tags($data['caridata']))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);

		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalWarna($data);
		$result["rows"]    = $this->model->getWarnaLimit($this->offset,$this->rows,$data);
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
					if(!$this->model->hapusWarna($data)) $pesan="SQL Salah";
				} else {
					$warna = $this->model->getWarnaByID($data);
					$dataError[] = $warna['warna_nama'];
				}
			}
			}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
    
	function getWarna(){
		$warna = $this->model->getWarna();
		return $warna;
	}
  
	function dataWarnaByID($iddata){
		$warna = $this->model->getWarnaByID($iddata);
		return $warna;
	}
  
 
}
?>
