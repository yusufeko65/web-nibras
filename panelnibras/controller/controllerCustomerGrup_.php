<?php
class controllerCustomerGrup {
	private $page;
	private $rows;
	private $offset;
	private $model;
	private $data=array();
	private $Fungsi;
      
	function __construct(){
		$this->model= new modelCustomerGrup();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		$data = array();

		$this->data['iddata']	        = isset($_POST['iddata']) ? $_POST['iddata']:'';
		$this->data['grup_nama']	    = isset($_POST['grup']) ? $_POST['grup']:'';
		$this->data['keterangan']    	= isset($_POST['keterangan']) ? $_POST['keterangan']:'';
		$this->data['total_awal']     	= isset($_POST['total_awal']) ? $_POST['total_awal']:0;
		$this->data['min_beli']       	= isset($_POST['min_beli']) ? $_POST['min_beli']:1;
		$this->data['minbeli_syarat'] 	= isset($_POST['minbeli_syarat']) ? $_POST['minbeli_syarat']:1;
		$this->data['minbeli_wjb']    	= isset($_POST['chk_wjb']) ? $_POST['chk_wjb']:'0';
		$this->data['urutan']         	= isset($_POST['urutan']) ? $_POST['urutan']:0;
		$this->data['urutan_lama']    	= isset($_POST['urutan_lama']) ? $_POST['urutan_lama']:0;
		$this->data['deposito']       	= isset($_POST['chk_deposito']) ? $_POST['chk_deposito']:1;
		$this->data['diskon']       	= isset($_POST['diskon']) ? $_POST['diskon']:0;
		$this->data['dropship']       	= isset($_POST['chk_dropship']) ? $_POST['chk_dropship']:0;
		$this->data['biaya_packing']    = isset($_POST['biaya_packing']) ? $_POST['biaya_packing']:0;
		
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
			if($this->model->checkDataResellerGrup($this->data['grup_nama'])){
				$pesan = "Kode Reseller Grup telah dipergunakan. Silahkan Masukkan ResellerGrup lainnya";	
			} else {
				$nourut = $this->Fungsi->fIdAkhir("_customer_grup","cg_urutan");
			    $this->data['urutan'] = isset($nourut) && $nourut != '' ? $nourut : 1; 
				if(!$this->model->simpanResellerGrup($this->data)) $pesan="SQL Salah";
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
			if(!$this->model->checkDataResellerGrupByID($this->data['iddata'])){
				$pesan = " Ada kesalahan data ";
			} else {
			    if($this->data['urutan'] != $this->data['urutan_lama'] && $this->data['urutan'] != '') {
				    $this->model->editUrutanLain($this->data);  
				}
				if(!$this->model->editResellerGrup($this->data)) $pesan="SQL Salah";
				
			}
		}
		return $this->Fungsi->pesandata($pesan,$modulnya);
   }
  
    
	public function tampildata(){
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		//$this->rows		= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$this->rows		= 10;
		$result 			= array();
		$filter				= array();
		$where = '';
		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari']:'';
		
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalResellerGrup($data);
		$result["rows"]    = $this->model->getResellerGrupLimit($this->offset,$this->rows,$data);
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
					if(!$this->model->hapusResellerGrup($data)) $pesan="SQL Salah";
				} else {
					$grup = $this->model->getResellerGrupByID($data);
					$dataError[] = $grup['cg_nm'];
				}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
    
	function getResellerGrup(){
	
		return $this->model->getResellerGrup();
	}
  
	function dataResellerGrupByID($iddata){
		return $this->model->getResellerGrupByID($iddata);
	}
  
}
?>
